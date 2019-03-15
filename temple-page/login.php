<?php   
global $wpdb,$user_ID;
if (!$user_ID) {
	if($_POST){
	    $username = sanitize_user($_REQUEST['username']); 
	    $password = sanitize_user($_REQUEST['password'],true);
	    $remember = sanitize_user($_REQUEST['rememberme'],true);
      	//需要消毒
	    $login_data = array();   
	    $login_data['user_login'] = $username;   
	    $login_data['user_password'] = $password;   
	    $login_data['remember'] = $remember;   
	    $user_verify = wp_signon( $login_data);

	    if ( is_wp_error($user_verify) ) {    
	        ?><script type='text/javascript'>swal("用户名或密码错误，请重试！", "","error");</script><?php
	        exit();   
	    } else {
	        ?><script type='text/javascript'>swal("登陆成功", "","success");setTimeout(function(){window.location=gd_array["site_url"];},1500);</script><?php
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
          top: 20%;
          left: 0;
          width: 100%;
          overflow: hidden;
          height: 72%;
		}
		#content{
			max-width: 400px;
		    margin: 0 auto;
		    padding: 15px;
		}
       .input-group {
            margin-bottom: 27px;
        }
        .input-group-addon{
          background-color: #f5f7fa;
          color: #909399;
        }
        .sjdl:before{
          position: absolute;
          border-top: 1px solid #ccc;
          content: '';
          top: 20px;
          width: 100%;
          left: 0;
          z-index: -1;
        }
		</style>
		<div id="container">      
		<div id="content">   
		<h2>登入 <a style="text-decoration: none;" href="<?php echo home_url(); ?>"><?php bloginfo( 'name' );?></a></h2>   
		<div id="result"></div>

        <div class="wp_login_form form-group">
            <div class="input-group">
                <input class="form-input zhanghao" name="username" type="text" autocomplete="off" placeholder="Email丨Phone" >
                <span class="input-group-addon">账号</span>
            </div>
            <div class="input-group">
                <input class="form-input mima" name="password" type="password" autocomplete="off" placeholder="Password">
                <span class="input-group-addon">密码</span>
            </div>
            <div class="input-group" style="display: block;">
                <label class="form-checkbox" style="margin: 0;display: inline-block;">
                    <input type="checkbox" class="jizhuwo" name="rememberme">
                    <i class="form-icon"></i> 记住我
                </label>
                <a href="<?php echo home_url('/forget'); ?>" style="float: right;">忘记密码？</a>
            </div>
            <?php if(get_option('users_can_register')){//是否允许注册 ?>
            	<button class="btn" style="width: 100px;" onclick="javascrtpt:window.location.href=gd_array['site_url']+'/sign'">注册</button> 
            <?php }else{ ?>
          		<button class="btn  disabled" style="width: 100px;">禁止注册</button> 
            <?php } ?>
            <button class="btn gd-dl" style="width: 100px;float:right;" onclick="gd_denglu()"> 登入</button>  
        </div>
        <p class="sjdl" style="text-align: center;position: relative;"><span style="background: #f2f3f5;padding: 13px;">快捷登录</span></p>
        <p>
          <?php if(of_get_option('open_qq_state','')=='1'){
          	$qq_oauth_url = "https://graph.qq.com/oauth2.0/authorize?client_id=" .of_get_option('open_qq_id',''). "&state=".md5 ( uniqid ( rand (), true ) )."&response_type=code&redirect_uri=" . urlencode (home_url('/open?open_type=qq&url='.gd_get_currect_url()));
          ?>
          <span style="box-shadow: 0 1px 3px 0 rgba(80,80,80,.11);border-radius: 4px;padding: 9px;background-color: rgb(23, 164, 255);font-size: 22px;color: #fff;cursor: pointer;display: inline-block;width: 50px;height: 50px;text-align: center;" onclick='window.open("<?php echo $qq_oauth_url; ?>","TencentLogin","width=450,height=320,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1")'><i class="fa fa-qq fa-lg" style="vertical-align: 0;"></i></span>
          <?php } ?>
        </p>
		<script type="text/javascript"> 
        function gd_denglu(){
            $(".gd-dl").addClass("loading");
            var input_data = 'username='+$('.zhanghao').val()+'&password='+$('.mima').val()+'&rememberme='+$('.jizhuwo').prop('checked');
            $.ajax({
                type: "POST",   
                url:  gd_array["site_url"]+"/login",   
                data: input_data,   
                success: function(msg){   
                    $(".gd-dl").removeClass("loading");
                    $('<div>').html(msg).appendTo('div#result').hide().fadeIn('slow');   
                }   
            });   
            return false;   
        }
		</script>   
		</div>   
		</div>
	<?php	get_footer();
	}
}else {
    echo "<script type='text/javascript'>window.location='".home_url()."';</script>";   
}   
?>  