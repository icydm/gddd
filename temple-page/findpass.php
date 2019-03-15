<?php   
global $wpdb,$user_ID;
if (!$user_ID) {
	if($_POST){
      	if (!isset($_SESSION)) {
            session_start();
        }
      	$start_yzm = isset($_POST['yzms']) ? sanitize_user($_POST['yzms']) : '';
      	$username = isset($_POST['username']) ? sanitize_user($_POST['username']) : '';
		$password = isset($_POST['password']) ? sanitize_user($_POST['password']) : '';
		$yzm = isset($_POST['yzm']) ? sanitize_user($_POST['yzm']) : '';

/////////////////////////////////////////////////////////////////////////////////////////////////////////账号格式验证
      	$zhanghaotype = gd_check_phone_email($username);
      	if($zhanghaotype == false){
            ?>
            <script type='text/javascript'>swal("账号格式错误", "","error");</script>
            <?php
            exit;
        }
////////////////////////////////////////////////////////////////////////////////////////////////////////验证码
      	if($start_yzm == '1'){
          	if(isset($_SESSION['forget_code_time'])){
            	if((time()-$_SESSION['forget_code_time'])<50){
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
          	if($zhanghaotype=='email'){
              	if(email_exists($username)){
              		$send = wp_mail( $username, $subject, $body, $headers );//邮箱发送
			    }else{
			    	?>
			        <script type='text/javascript'>swal("邮箱未注册", "","error");</script>
			        <?php
			        exit;
			    }
              	if(!is_wp_error($send)){
                    $_SESSION['forget_code_time'] = time();
                    $_SESSION['forget_code'] = $code_yzm;
                    $_SESSION['forget_zhanghao'] = $username;
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
                    if(username_exists($username)){
                    	$send = gd_ali_sendSms($username,$code_yzm);
                    }else{
                        ?>
                        <script type='text/javascript'>swal("手机号未注册", "","error");</script>
                        <?php
                        exit;
                    }
                    	if($send->Message == 'OK'){
                            $_SESSION['forget_code_time'] = time();
                            $_SESSION['forget_code'] = $code_yzm;
                            $_SESSION['forget_zhanghao'] = $username;
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
            }

        
      


///////////////////////////////////一堆判断验证
      	$username = isset($_SESSION['forget_zhanghao']) ? sanitize_user($_SESSION['forget_zhanghao']) : '';//获取发送验证码的账号
      	
	    if(!$password || !$yzm){
	        ?>
	        <script type='text/javascript'>swal("请输入完整信息", "","error");</script>
	        <?php
	        exit;
	    }

	    if(strlen($password)<6 || strlen($password)>15){
			?>
	        <script type='text/javascript'>swal("密码长度错误。", "","error");</script>
	        <?php
	        exit;
	    }
		if((time()-$_SESSION['forget_code_time'])>300){
			?>
	        <script type='text/javascript'>swal("验证码已失效。", "","error");</script>
	        <?php
	        exit;        	
        }
      	if((int)$yzm !== $_SESSION['forget_code']){
			?>
	        <script type='text/javascript'>swal("验证码错误。", "","error");</script>
	        <?php
	        exit;           		
      	}
/////////////////////////////////重置密码
		if($zhanghaotype=='email'){//邮箱
		    $user = get_user_by( 'email', $username );
		}else{//手机
	    	$user = get_user_by( 'login', $username );
        }
        $arr = array(
            'user_pass'=>$password,
            'ID'=>$user->ID
        );
        $user_id = wp_update_user($arr);
        if(is_numeric($user_id)){
	        ?>
	        <script type='text/javascript'>swal("找回密码成功", "","success");setTimeout(function(){window.location=gd_array["site_url"];},1500);</script>
	        <?php
			wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
			unset($_SESSION['forget_code_time']);
            unset($_SESSION['forget_code']);
            unset($_SESSION['forget_zhanghao']);	        
		}else {
	        ?>
	        <script type='text/javascript'>swal("找回密码失败。", "","error");</script>
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
          <h2>找回密码 <a style="text-decoration: none;" href="<?php echo home_url(); ?>"><?php bloginfo( 'name' );?></a></h2>   
		<div id="result"></div>
        <div class="wp_login_form form-group">
            <div class="form-group">
              <label class="form-label" for="username">账号</label>
              <input class="form-input" type="text" id="username" placeholder="手机 邮箱">
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
            <button class="btn gd-dl" style="margin-top: 10px;width: 100px;" onclick="gd_zhuce()">找回密码</button>  
        </div>
		<script type="text/javascript"> 
        function gd_zhuce(){
            $(".gd-dl").addClass("loading");
          	if($('#username').val()==''|| $('#password').val()=='' || $('#verificationcode').val()==''){
            	swal("有信息未填写", "","error");
              	$(".gd-dl").removeClass("loading");
              	return;            	
            }
          	if($('#password').val() !== $('#passwordt').val()){
            	swal("两次密码不一致", "","error");
              	$(".gd-dl").removeClass("loading");
              	return;
            }
            var input_data = 'username='+$('#username').val()+'&password='+$('#password').val()+'&yzm='+$('#verificationcode').val();
            $.ajax({
                type: "POST",   
                url:  gd_array["site_url"]+"/forget",   
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
                url:  gd_array["site_url"]+"/forget",   
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
}else{
    echo "<script type='text/javascript'>window.location='".home_url()."';</script>";   
}   
?>  