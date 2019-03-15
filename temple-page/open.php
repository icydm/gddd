<?php
//QQ
define('GD_QQ_APPID',of_get_option('open_qq_id','none'));
define('GD_QQ_APPSECRET',of_get_option('open_qq_key','none'));

//微博
//define('ZRZ_WEIBO_APPID',zrz_get_social_settings('open_weibo_key'));
//define('ZRZ_WEIBO_APPSECRET',zrz_get_social_settings('open_weibo_secret'));

$type = (isset($_GET['open_type']) && !empty($_GET['open_type'])) ? $_GET['open_type'] : false;
$code = (isset($_GET['code']) && !empty($_GET['code'])) ? $_GET['code'] : false;
$re_url = (isset($_GET['url']) && !empty($_GET['url'])) ? $_GET['url'] : false;
if($type && $code){
    all_oauth($type,$code,$re_url);
}

function ouath_redirect($userid,$type,$avatar,$re_url){
    echo '<div class="fs14 pos-r" style="width:100%;height:100%">跳转中，请不要关闭此窗口...</div>';

    $has_avatar = get_user_meta( $userid , 'avatar' , true );
  	
    if(empty($has_avatar)){
        update_user_meta($userid , 'avatar',$avatar);
    }
        echo '<body>
        <script>
        if (window.opener) {
        window.opener.location.reload();
        window.close();
        } else {
            window.location.href="'.$re_url.'";
        }</script>
        </body>';

    exit;
}

function get_token($code,$type){

    if($type == 'qq'){
        $url = "https://graph.qq.com/oauth2.0/token";
        $client_id = GD_QQ_APPID;
        $client_secret = GD_QQ_APPSECRET;
    }


    $data = array(
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'grant_type' => 'authorization_code',
        'redirect_uri' => home_url('/'),
        'code' => $code,
    );

    $response = wp_remote_post( $url, array(
            'method' => 'POST',
            'body' => $data,
        )
    );
    $body = $response['body'];
    $output = false;
    if($type == 'qq'){
        $params = array ();
        parse_str( $body, $params );
        if(empty($params ["access_token"])){
            die('服务器响应错误');
        }
        $output = $params ["access_token"];
    }else{
        $output = json_decode($body,true);
    }

    return $output;
}

function all_oauth($type,$code,$re_url){
    $output = get_token($code,$type);

    if(!$output || (isset($output['error']) && !empty($output['error'])) || (isset($output['errcode']) && !empty($output['errcode']))){
        wp_die('社交登录设置有误，请联系管理员！');
    }


    $access_token = $output;


    $unionid = '';

    if($type == 'qq'){
        $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" . $access_token;
        $str = wp_remote_get( $graph_url );
        $str = $str['body'];

        if (strpos ( $str, "callback" ) !== false) {
            $lpos = strpos ( $str, "(" );
            $rpos = strrpos ( $str, ")" );
            $str = substr ( $str, $lpos + 1, $rpos - $lpos - 1 );
        }

        $user = json_decode ( $str,true );
        if (isset ( $user->error )) {
            echo "<h3>错误代码:</h3>" . $user->error;
            echo "<h3>信息  :</h3>" . $user->error_description;
            exit ();
        }

        $qq_openid = $user['openid'];
        if(!$qq_openid){
            wp_redirect(home_url());
            exit;
        }

        $get_user_info_url = "https://graph.qq.com/user/get_user_info?" . "access_token=" . $access_token . "&oauth_consumer_key=" . GD_QQ_APPID . "&openid=" . $qq_openid . "&format=json";
        $uid = $qq_openid;
        $username = 'nickname';
        $avatar = 'figureurl_qq_2';

    }

    $user = wp_remote_get( $get_user_info_url );

    $userinfo  = json_decode($user['body'] , true);
    $username = $userinfo[$username];
    $avatar = $userinfo[$avatar];

    $avatar = str_replace("http","https",$avatar);
    $user_info = array(
        'user_id' => $uid,
        'token' =>$access_token,
        'avatar'=>$avatar,
        'username' =>$username,
        'unionid'=>$unionid
    );
    save_login($type,$user_info,$re_url);
}

function save_login($type,$user_info,$re_url){
    $uid = $user_info['user_id'];
    $access_token = $user_info['token'];
    $avatar = $user_info['avatar'];
    $username = $user_info['username'];

    if($user_info['unionid'] && $user_info['unionid'] != ''){
        $user = get_users(array('meta_key'=>'gd_'.$type.'_unionid','meta_value'=>$user_info['unionid']));
    }else{
        $user = get_users(array('meta_key'=>'gd_'.$type.'_uid','meta_value'=>$uid));
    }
	

        //创建新用户
        if(is_wp_error($user) || empty($user)){
          
            if(!get_option('users_can_register')){
                die('本站已关闭注册');
            }
            $login_name = wp_create_nonce($uid.rand(1,99));
            $random_password = wp_generate_password( $length=12, false );
            $userdata=array(
               'user_login' => $login_name,
               'display_name' => $username,
               'user_pass' => $random_password,
               'nickname' => $username
            );
            $user_id = wp_insert_user( $userdata );
            wp_signon(array("user_login"=>$login_name,"user_password"=>$random_password),false);
            $meta_val = array(
               $type.'_access_token' => $access_token,
               'avatar_set' => $type
            );
            if(is_wp_error($user_id)){
               die('此用户已经存在！');
            }
            if($user_info['unionid']){
                update_user_meta($user_id ,'gd_'.$type.'_unionid',$user_info['unionid']);
            }
            update_user_meta($user_id ,'gd_'.$type.'_uid',$uid);
            update_user_meta($user_id ,'gd_open',$meta_val);
            ouath_redirect($user_id,$type,$avatar,$re_url);
        }else{

            $gd_open = get_user_meta($user[0]->ID,'gd_open',true);
            $gd_open = is_array($gd_open) ? $gd_open : array();
            $gd_open[$type.'_access_token'] = $access_token;
            if($user_info['unionid']){
                update_user_meta($user[0]->ID ,'gd_'.$type.'_unionid',$user_info['unionid']);
            }
            update_user_meta($user[0]->ID,'gd_open',$gd_open);
            wp_set_auth_cookie($user[0]->ID,true);
            ouath_redirect($user[0]->ID,$type,$avatar,$re_url);
          	
        }

}

