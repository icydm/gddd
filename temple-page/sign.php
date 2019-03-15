<?php   
global $wpdb,$user_ID;
if (!$user_ID) {
  	if(!get_option('users_can_register')){//是否允许注册
    	echo "<script type='text/javascript'>window.location='".home_url()."';</script>";       
    }
	if($_POST){
      	if (!isset($_SESSION)) {
            session_start();
        }
      	$start_yzm = isset($_POST['yzms']) ? sanitize_user($_POST['yzms']) : '';
      	$username = isset($_POST['username']) ? sanitize_user($_POST['username']) : '';
		$password = isset($_POST['password']) ? sanitize_user($_POST['password']) : '';
		$displayname = isset($_POST['displayname']) ? sanitize_user($_POST['displayname']): '';
		$yzm = isset($_POST['yzm']) ? sanitize_user($_POST['yzm']) : '';

/////////////////////////////////////////////////////////////////////////////////////////////////////////手机邮箱注册方式选择
      	$zhanghaotype = gd_check_phone_email($username);
      	if($zhanghaotype !== false){
        	$keyiwande = of_get_option('sign_way', 'none');
          	if($keyiwande[$zhanghaotype]==false){
              if($zhanghaotype=='email'){
                ?>
                <script type='text/javascript'>swal("禁止使用邮箱注册", "","error");</script>
                <?php
                exit;
              }else{
                ?>
                <script type='text/javascript'>swal("禁止使用手机注册", "","error");</script>
                <?php
                exit;              
              }
            }
        }else{
            ?>
            <script type='text/javascript'>swal("账号格式错误", "","error");</script>
            <?php
            exit;           	
        }
//////////////////////////////////////////////////////////////////////////////////////////////////////// 注册验证码
      	if($start_yzm == '1'){
          	if(!$username){
              ?>
              <script type='text/javascript'>swal("请输入账号", "","error");</script>
              <?php
              exit;
            }
          	if(isset($_SESSION['sign_code_time'])){
            	if((time()-$_SESSION['sign_code_time'])<50){
		        	?>
			        <script type='text/javascript'>swal("表着急，待会才能再发个", "","error");</script>
			        <?php
			        exit;                	
                }
            }
          	$code_yzm = rand(1000,9999);
          	$subject = get_bloginfo( 'name' ).'注册验证码';
            $body = $code_yzm;//这里是邮件发送的内容
            $headers = array('Content-Type: text/html; charset=UTF-8');
          	if(is_email($username)){
              	if(email_exists($username)){
		        	?>
			        <script type='text/javascript'>swal("邮箱已注册", "","error");</script>
			        <?php
			        exit;
			    }
            	$send = wp_mail( $username, $subject, $body, $headers );//邮箱发送

              	if(!is_wp_error($send)){
                    $_SESSION['sign_code_time'] = time();
                    $_SESSION['sign_code'] = $code_yzm;
                    $_SESSION['sign_zhanghao'] = $username;
                    ?>
                    <script type='text/javascript'>
                      swal("发送成功", "","success");
                      $(".yzmfs").addClass("disabled");
                      gd_dj_six_Time();
                     </script>
                    <?php
                  	exit;
                }else{
                    ?>
                    <script type='text/javascript'>swal("发送失败", "","error");</script>
                    <?php
                  	exit;
                }
            }else{
	    		if(preg_match('#^13[\d]{9}$|^14[5,6,7,8,9]{1}\d{8}$|^15[^4]{1}\d{8}$|^16[6]{1}\d{8}$|^17[0,1,2,3,4,5,6,7,8]{1}\d{8}$|^18[\d]{9}$|^19[8,9]{1}\d{8}$#',$username)){//手机
                    if(username_exists($username)){
                        ?>
                        <script type='text/javascript'>swal("手机号已注册", "","error");</script>
                        <?php
                        exit;
                    }else{
                    	$send = gd_ali_sendSms($username,$code_yzm);
                    	if($send->Message == 'OK'){
                            $_SESSION['sign_code_time'] = time();
                            $_SESSION['sign_code'] = $code_yzm;
                            $_SESSION['sign_zhanghao'] = $username;
                            ?>
                            <script type='text/javascript'>
                              swal("发送成功", "","success");
                              $(".yzmfs").addClass("disabled");
                              gd_dj_six_Time();
                             </script>
                            <?php
                            exit;
                        }else{
                            ?>
                            <script type='text/javascript'>swal("发送失败", "","error");</script>
                            <?php
                            exit;
                       }
                    }   
                }else{
                    ?>
                    <script type='text/javascript'>swal("账号格式错误", "","error");</script>
                    <?php
                  	exit;                
                }
            }
          exit;
        }
      


///////////////////////////////////一堆判断验证
      	$username = isset($_SESSION['sign_zhanghao']) ? sanitize_user($_SESSION['sign_zhanghao']) : '';//获取发送验证码的账号
      	
	    if(!$username || !$password || !$displayname || !$yzm){
	        ?>
	        <script type='text/javascript'>swal("请输入完整信息", "","error");</script>
	        <?php
	        exit;
	    }

	    if(preg_match('#^13[\d]{9}$|^14[5,6,7,8,9]{1}\d{8}$|^15[^4]{1}\d{8}$|^16[6]{1}\d{8}$|^17[0,1,2,3,4,5,6,7,8]{1}\d{8}$|^18[\d]{9}$|^19[8,9]{1}\d{8}$#',$username)){//手机
			if(username_exists($username)){
		        ?>
		        <script type='text/javascript'>swal("手机号已注册", "","error");</script>
		        <?php
		        exit;
			}
		}else{//邮箱
		    if(!is_email($username)){
		        ?>
		        <script type='text/javascript'>swal("账号格式错误", "","error");</script>
		        <?php
		        exit;
	        }else{
	        	if(email_exists($username)){
		        	?>
			        <script type='text/javascript'>swal("邮箱已注册", "","error");</script>
			        <?php
			        exit;
			    }
	        }
		}

	    if(strlen($password)<6 || strlen($password)>15){
			?>
	        <script type='text/javascript'>swal("密码长度错误。", "","error");</script>
	        <?php
	        exit;
	    }
		if((time()-$_SESSION['sign_code_time'])>300){
			?>
	        <script type='text/javascript'>swal("验证码已失效。", "","error");</script>
	        <?php
	        exit;        	
        }
      	if((int)$yzm !== $_SESSION['sign_code']){
			?>
	        <script type='text/javascript'>swal("验证码错误。", "","error");</script>
	        <?php
	        exit;           		
      	}
/////////////////////////////////开始注册
		if(is_email($username)){//邮箱
		    $user_id = wp_create_user( wp_create_nonce($username.rand(1,999)), $password, $username );
		}else{//手机
	    	$user_id = wp_create_user($username, $password);
        }

		if (isset($user_id['errors'])) {
	        ?>
	        <script type='text/javascript'>swal("注册失败", "请检查注册信息","error");</script>
	        <?php
		}else {
			//更新用户昵称
            $arr = array(
                'display_name'=>$displayname,
                'ID'=>$user_id
            );
            wp_update_user($arr);

			//自动登录
			wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
			unset($_SESSION['sign_code_time']);
            unset($_SESSION['sign_code']);
            unset($_SESSION['sign_zhanghao']);
	        ?>
	        <script type='text/javascript'>swal("注册成功", "","success");setTimeout(function(){window.location=gd_array["site_url"];},1500);</script>
	        <?php
	        exit();
	    }  
	} else {
	    get_header();//载入头部文件   
		?>

	    <style>
	    .gd-body{
           	overflow: hidden;
	    }
	    .gd-header,.footer{
	    	display:none;
	    }
	    #container{
      	  position: absolute;
          top: 5%;
          left: 0;
          width: 100%;
          overflow: hidden;
          height: 100%;
		}
		#content{
			max-width: 400px;
		    margin: 0 auto;
		    padding: 15px;
		}
        .input-group-addon{
          background-color: #f5f7fa;
          color: #909399;
        }
        .form-input-hint {
            margin-bottom: 0;
        }
		</style>
		<div id="container">      
		<div id="content">   
          <h2>注册 <a style="text-decoration: none;" href="<?php echo home_url(); ?>"><?php bloginfo( 'name' );?></a></h2>   
		<div id="result"></div>
		<?php
      $fstext = of_get_option('sign_way', 'none');
      $keyidefangshi = '';
      if($fstext['email']=='1'){
      	$keyidefangshi = '邮箱';
      }
      if($fstext['phone']=='1'){
      	$keyidefangshi .= ' 手机';
      }
      if($keyidefangshi == ''){
	  ?>
	  <script type='text/javascript'>window.location='<?php echo home_url(); ?>';</script>
	  <?php
      }
      ?>
        <div class="wp_login_form form-group">
            <div class="form-group">
              <label class="form-label" for="username">账号</label>
              <input class="form-input" type="text" id="username" placeholder="<?php echo $keyidefangshi; ?>">
            </div>
          	<div class="form-group">
              <label class="form-label" for="verificationcode">验证码</label>
              <input class="form-input" type="text" id="verificationcode" placeholder="Verification Code" style="width: 69%;float: left;">
              <button class="btn yzmfs" style="width: 30%;margin-left: 1%;height: 100%;" onclick="gd_fsyzm()">发送</button>
            </div>
            <div class="form-group">
              <label class="form-label" for="password">密码</label>
              <input class="form-input" id="password" type="password" autocomplete="off" placeholder="6-15字符 数字 字母 空格 _  . -  @ ">
            </div>
            <div class="form-group">
              <label class="form-label" for="passwordt">密码确认</label>
              <input class="form-input" id="passwordt" type="password" autocomplete="off" placeholder="Password Again">
            </div>
            <div class="form-group">
              <label class="form-label" for="nickname">昵称</label>
              <input class="form-input" type="text" id="nickname" placeholder="昵称">
            </div>
          	<div class="input-group" style="display: block;">
                <label class="form-checkbox" style="margin: 5px 0;;display: inline-block;">
                    <input type="checkbox" class="jizhuwo" name="rememberme">
                  <i class="form-icon"></i> 阅读并接受 <a target="_blank" href="<?php echo of_get_option('sign_xieyi', 'none'); ?>" style="text-decoration: none;">《用户协议》</a>
                </label>
            </div>
            <button class="btn" style="width: 100px;margin-top: 10px;" onclick="javascrtpt:window.location.href=gd_array['site_url']+'/gd-login'">已有账号</button>  
            <button class="btn gd-dl" style="margin-top: 10px;width: 100px;float:right;" onclick="gd_zhuce()"> 注册</button>  
        </div>
		<script type="text/javascript"> 
        function gd_zhuce(){
            $(".gd-dl").addClass("loading");
          	if($('#username').val()==''|| $('#password').val()=='' || $('#nickname').val()=='' || $('#verificationcode').val()==''){
            	swal("有信息未填写", "","error");
              	$(".gd-dl").removeClass("loading");
              	return;            	
            }
          	if($('#password').val() !== $('#passwordt').val()){
            	swal("两次密码不一致", "","error");
              	$(".gd-dl").removeClass("loading");
              	return;
            }
          	if($('.jizhuwo').prop('checked') == false){
            	swal("未接受用户协议", "","error");
              	$(".gd-dl").removeClass("loading");
              	return;	
            }
            var input_data = 'username='+$('#username').val()+'&password='+$('#password').val()+'&displayname='+$('#nickname').val()+'&yzm='+$('#verificationcode').val();
            $.ajax({
                type: "POST",   
                url:  gd_array["site_url"]+"/sign",   
                data: input_data,   
                success: function(msg){   
                    $('<div>').html(msg).appendTo('div#result').hide().fadeIn('slow');
                  	$(".gd-dl").removeClass("loading");
                }   
            });   
            return false;   
        }
        function gd_fsyzm(){
          	if($('#username').val()==''){
            	swal("账号未填写", "","error");
              	$("..yzmfs").removeClass("loading");
              	return;            	
            }
          	$(".yzmfs").addClass("loading");
           	var input_data = 'username='+$('#username').val()+'&yzms=1';
            $.ajax({
                type: "POST",   
                url:  gd_array["site_url"]+"/sign",   
                data: input_data,   
                success: function(msg){   
                    $('<div>').html(msg).appendTo('div#result').hide().fadeIn('slow');
                  	$(".yzmfs").removeClass("loading");
                }   
            });
        }
        var countDown=60;
        function gd_dj_six_Time() {
            if (countDown == 0) {
                $(".yzmfs").removeClass("disabled");
                $(".yzmfs").text("发送");
                countDown = 60;
                return;
            } else {
                $(".yzmfs").text(countDown);
                countDown--;
            }
            setTimeout(function() {
                gd_dj_six_Time()
            },1000)
        };        
		</script>   
		</div>   
		</div>
	<?php	get_footer();
	}
}else {
    echo "<script type='text/javascript'>window.location='".home_url()."';</script>";   
}   
?>  