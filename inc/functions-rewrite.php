<?php
/////////////////////////////////////////////url重写
add_action('generate_rewrite_rules', 'gd_login_rewrite_rules' );
function gd_login_rewrite_rules( $wp_rewrite ){
    $new_rules = array(    
        'login/?$' => 'index.php?gd_page=login',
      	'sign/?$' => 'index.php?gd_page=sign',
      	'forget/?$' => 'index.php?gd_page=forget',
      	'write/?$' => 'index.php?gd_page=write',
      	'notification/?$' => 'index.php?gd_page=notification',
     	'collections/?$' => 'index.php?gd_page=collections',
      	'setting/?$' => 'index.php?gd_page=setting',
      	'open/?$' => 'index.php?gd_page=open',
        'gold/?$' => 'index.php?gd_page=gold',
        'vip/?$' => 'index.php?gd_page=vip',
      	'links/?$' => 'index.php?gd_page=links',
      	'blacklist/?$' => 'index.php?gd_page=blacklist',
    ); 
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

add_action('query_vars', 'gd_login_add_query_vars');
function gd_login_add_query_vars($public_query_vars){
    $public_query_vars[] = 'gd_page';
    return $public_query_vars;
}

add_action("template_redirect", 'gd_login_template_redirect');
function gd_login_template_redirect(){
    global $wp;
    global $wp_query, $wp_rewrite;
    $reditect_page =  isset($wp_query->query_vars['gd_page']) ? $wp_query->query_vars['gd_page'] : '';
    if ($reditect_page == "login"){
        include(GD_THEME_DIR.'/temple-page/login.php');
        die();
    }
  	if ($reditect_page == "sign"){
        include(GD_THEME_DIR.'/temple-page/sign.php');
        die();
    }
  	if ($reditect_page == "forget"){
        include(GD_THEME_DIR.'/temple-page/findpass.php');
        die();
    }
  	if ($reditect_page == "write"){
        include(GD_THEME_DIR.'/temple-page/write.php');
        die();
    }
  	if ($reditect_page == "notification"){
        include(GD_THEME_DIR.'/temple-page/notification.php');
        die();
    }
  	if ($reditect_page == "collections"){
        include(GD_THEME_DIR.'/temple-page/collections.php');
        die();
    }
  	if ($reditect_page == "setting"){
        include(GD_THEME_DIR.'/temple-page/setting.php');
        die();
    }
  	if ($reditect_page == "open"){
        include(GD_THEME_DIR.'/temple-page/open.php');
        die();
    }
  	if ($reditect_page == "gold"){
        include(GD_THEME_DIR.'/temple-page/gold.php');
        die();
    }
  	if ($reditect_page == "vip"){
        include(GD_THEME_DIR.'/temple-page/vip.php');
        die();
    }
  	if ($reditect_page == "links"){
        include(GD_THEME_DIR.'/temple-page/links.php');
        die();
    }
  	if ($reditect_page == "blacklist"){
        include(GD_THEME_DIR.'/temple-page/blacklist.php');
        die();
    }
}

add_action( 'load-themes.php', 'gd_flush_rewrite_rules' );
function gd_flush_rewrite_rules() {
    global $pagenow, $wp_rewrite;
    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) )
        $wp_rewrite->flush_rules();
}

add_filter('redirect_canonical', 'x_page_cancel_redirect_canonical');
function x_page_cancel_redirect_canonical($redirect_url){
    if( get_query_var('gd_page')) return false;
    return $redirect_url;
}
/////////////////////////////////////////////url重写end
/////////////////////////////////////////////name
function gd_custom_page_document_title($title_parts){
     $type = get_query_var('gd_page');

     if ($type){
         $gd_page_name = apply_filters( 'gd_page_name',array(
			'login' => '登录',
			'sign' => '注册',
			'forget' => '找回密码',
			'write' => '写作',
			'notification' => '通知',
			'collections' => '专题中心',
			'setting' => '设置',
			'open' => '社交登录',
           	'gold' => '财富',
           	'vip' => 'vip',
           	'links' => '友情链接',
           	'blacklist' => '小黑屋',
         ));
         $title_parts['tagline'] = $title_parts['title'];
         foreach ($gd_page_name as $key => $value) {
             if ( $type ==  $key) {
                 $title_parts['title'] = $value;
                 break;
             }
         }
     }
    unset($gd_page_name);
    return $title_parts;
 }
 add_filter( 'document_title_parts', 'gd_custom_page_document_title' );
?>