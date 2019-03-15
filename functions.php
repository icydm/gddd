<?php
/////////////////////////////////////////////////////////////////设置框架
define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/options/' );
require_once dirname( __FILE__ ) . '/options/options-framework.php';
$optionsfile = locate_template( 'options.php' );
load_template( $optionsfile );
add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );
function optionsframework_custom_scripts() { ?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#example_showhidden').click(function() {
  		jQuery('#section-example_text_hidden').fadeToggle(400);
	});
	if (jQuery('#example_showhidden:checked').val() !== undefined) {
		jQuery('#section-example_text_hidden').show();
	}
});
</script>
<?php
}
function prefix_options_menu_filter( $menu ) {
	$menu['mode'] = 'menu';
	$menu['page_title'] = __( '主题设置', 'textdomain');
	$menu['menu_title'] = __( '主题设置', 'textdomain');
	$menu['menu_slug'] = 'gddd-options';
	return $menu;
}
add_filter( 'optionsframework_menu', 'prefix_options_menu_filter' );
//////////////////////////////////////////////////////////////////开始自己的
function exclude_category_home( $query ){
    if ( $query->is_home ) {
    $notcat = array();
    foreach( (of_get_option('index_no_category','none')) as $key => $val){
        if($val == '1'){
            $notcat[] = -$key;
        }
    }
   	$query->set( 'cat', implode(",",$notcat));
    } 
    return $query; 
    } 
add_filter( 'pre_get_posts', 'exclude_category_home' );
function gd_get_theme_version(){
    $theme = wp_get_theme();
    return $theme->get( 'Version' );
}
function gd_get_page_num(){
	global $wp_query;
  	$nub = get_option('posts_per_page',18);
  	$ipages = ceil( $wp_query->found_posts / $nub);
  	return $ipages;
}
function gd_ajaxpost_cat(){
	if(is_category()){
    	return gd_get_current_category_id();
    }elseif(gd_is_custom_tax('collection')){
		return get_queried_object_id();
    }else{
      return '0';
    }
}
function gd_is_open_wuxian(){
	if(of_get_option('index_ajax_gd','no entry')){
      return '1';
    }else{
      return '0';
    }
}
function gd_is_user_loggedin(){
	if(is_user_logged_in()){
      return '1';
    }else{
      return '0';
    }
}
//////////////////////////////////////////////////////////////////////require一大堆
define( 'GD_THEME_DIR', get_template_directory() );
define(	'GD_THEME_VERSION',gd_get_theme_version());
require_once GD_THEME_DIR . '/inc/functions-default.php';
require_once GD_THEME_DIR . '/inc/functions-disable.php';
require_once GD_THEME_DIR . '/inc/functions-collection.php';
require_once GD_THEME_DIR . '/inc/functions-cache.php';
require_once GD_THEME_DIR . '/inc/functions-post.php';
require_once GD_THEME_DIR . '/inc/functions-index.php';
require_once GD_THEME_DIR . '/inc/functions-comment.php';
require_once GD_THEME_DIR . '/inc/functions-category.php';
require_once GD_THEME_DIR . '/inc/functions-rewrite.php';
require_once GD_THEME_DIR . '/inc/functions-gold.php';
require_once GD_THEME_DIR . '/inc/functions-user.php';
require_once GD_THEME_DIR . '/inc/functions-seo.php';
require_once GD_THEME_DIR . '/inc/functions-message.php';
require_once GD_THEME_DIR . '/inc/functions-announcement.php';
require_once GD_THEME_DIR . '/inc/widget.php';
require_once GD_THEME_DIR . '/inc/sdk/ali_sms.php';


///////////////////////////////////////////////////////////////////
//链接支持
add_filter( 'pre_option_link_manager_enabled', '__return_true' );
//特色图支持
add_theme_support( 'post-thumbnails' );

//注册菜单
add_theme_support('nav_menus'); 
register_nav_menus(
    array(
      'PrimaryMenu'=>'顶部菜单',
      'footer-menu' => '底部菜单'
    )
);
//有子菜单时添加图标
function gd_add_has_children_to_nav_items( $items ){
    $parents = wp_list_pluck( $items, 'menu_item_parent');
    foreach ( $items as $item ){
        $item->title = '<span>'.$item->title.'</span>';
        in_array( $item->ID, $parents ) && $item->title = $item->title.'<i class="fa fa-angle-down fa-lg"></i>';
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'gd_add_has_children_to_nav_items' );
//标题支持
function gd_add_theme_support_title(){
    add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'gd_add_theme_support_title' );
function gd_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( '文章页'),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( '显示位置：文章页' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s mar16-b">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title l1 box-header">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( '页面'),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( '显示位置：页面' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s mar16-b">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title l1 box-header">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( '个人中心'),
		'id'            => 'sidebar-3',
		'description'   => esc_html__( '显示位置：个人中心' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s mar16-b">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title l1 box-header">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'gd_widgets_init' );
/////////////////////////////////////////////////////////////////////js///css//莫慌，大部分都是用的BootCDN，速度快，还不花你流量
function gd_eq_sc(){
	$url=get_stylesheet_directory_uri();
//框架，正在进行替换
	wp_enqueue_style( 'gd-spectre-m-style', $url.'/assets/css/spectre.css' , array() , null, 'all');
	wp_enqueue_style( 'gd-spectre-e-style', $url.'/assets/css/spectre-exp.css' , array() , null, 'all');
//new  	
  	wp_enqueue_style( 'gd-elecss', 'https://cdn.bootcss.com/element-ui/2.5.4/theme-chalk/index.css' , array() , null, 'all');
//icon
   	wp_enqueue_style( 'gd-iconfont-style', 'https://at.alicdn.com/t/font_971306_appwmqjflz.css' , array() ,null, 'all');//对图标进行补充
	wp_enqueue_style( 'gd-font-awesome-style', 'https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css' , array() ,null, 'all');
//css
  	wp_enqueue_style( 'gd-flickity-style', 'https://cdn.bootcss.com/flickity/2.1.2/flickity.min.css' , array() , null, 'all');
  	wp_enqueue_style( 'gd-fancybox-style', 'https://cdn.bootcss.com/fancybox/3.5.2/jquery.fancybox.min.css' , array() , null, 'all');
	wp_enqueue_style( 'gd-Ow0style', $url.'/assets/OwO/OwO.min.css' , array() , null, 'all');
  	wp_enqueue_style( 'gd-style', $url.'/style.css' , array() , GD_THEME_VERSION, 'all');
//顶部加载	
  	wp_enqueue_script( 'gd-vue-script', 'https://cdn.bootcss.com/vue/2.6.6/vue.min.js' , array() ,null,false);
  	wp_enqueue_script( 'gd-vueele-script', 'https://cdn.bootcss.com/element-ui/2.5.4/index.js' , array() ,null,false);
  	wp_enqueue_script( 'gd-resource-script', 'https://cdn.bootcss.com/vue-resource/1.5.1/vue-resource.min.js' , array() ,null,false);
	wp_enqueue_script( 'gd-jq-script', 'https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js' , array() ,null,false);
//页脚加载  
	wp_enqueue_script( 'gd-masonry-script', 'https://cdn.bootcss.com/masonry/4.2.2/masonry.pkgd.min.js' , array() ,null, 'all' );//瀑布流
	wp_enqueue_script( 'gd-lazyload-script', 'https://cdn.bootcss.com/jquery_lazyload/1.9.7/jquery.lazyload.js' , array() ,null, 'all' );//图片懒加载
	wp_enqueue_script( 'gd-flickity-script', 'https://cdn.bootcss.com/flickity/2.1.2/flickity.pkgd.min.js' , array() ,null, 'all' );//幻灯
  	wp_enqueue_script( 'gd-sweetalert-script', 'https://cdn.bootcss.com/sweetalert/2.1.2/sweetalert.min.js' , array() ,null, 'all' );//弹窗
 	wp_enqueue_script( 'gd-fancybox-script', 'https://cdn.bootcss.com/fancybox/3.5.2/jquery.fancybox.min.js' , array() ,null, 'all' );//画廊
  	wp_enqueue_script( 'gd-stickyjs-script', 'https://cdn.bootcss.com/jquery.sticky/1.0.4/jquery.sticky.min.js' , array() ,null, 'all' );//浮动
  
	wp_enqueue_script( 'gd-global-script', $url.'/assets/gd.js' , array() , GD_THEME_VERSION, 'all' );
  	wp_enqueue_script( 'gd-Ow0', $url.'/assets/OwO/OwO.min.js' , array() , null, 'all' );
  
  
  
	$script_cs = array(
		'site_url' => home_url(),//网址
		'pages_num' => gd_get_page_num(),//多少页
		'ajaxpage' => '1',//目前
		'ajaxpost_cat' => gd_ajaxpost_cat(),//分类id
		'ajax_gd' => gd_is_open_wuxian(),//是否开启滚动加载
		'login_state' => gd_is_user_loggedin(),//是否登陆
		'index_ajax_gd_num' => of_get_option('index_ajax_gd_num','0'),//到多少页停一下
      	'viewadd' => '0',//是否运行增加访问量
      	'ajaxurl' => home_url("/wp-admin/admin-ajax.php?action="),//ajax_url
    );
    if(is_single() || is_page()){
		$post_id = get_the_id();
      	$auther_id = get_post($post_id)->post_author;
		$script_cs['post_id'] = $post_id;
		$script_cs['viewadd'] = '1';
		$script_cs['wp_unfiltered_html_comment'] = wp_create_nonce( 'unfiltered-html-comment_' . $post_id );
      	$script_cs['thumb']	=	gd_get_post_thumb();
      	$script_cs['auther_ava'] = get_user_meta( $auther_id,'avatar' , true );
      	$script_cs['auther_name'] = gd_get_user_message($auther_id,'display_name');
      	$script_cs['postdes'] = gd_get_post_des($post_id);
      	$script_cs['dashang'] = gd_get_user_meta_message($auther_id,'dashang');
    }
	wp_localize_script( 'gd-global-script', 'gd_array', $script_cs );
}
add_action('wp_enqueue_scripts','gd_eq_sc');

function gs_script_cs(){?>
  
    <?php if(is_home() || is_category() || gd_is_custom_tax('collection')){ ?>
        <script type="text/javascript">
              $(function() {
                  $('.carousel-image').height($('.carousel-image').width()/4.3);
                  f_banner();
                  f_masonry();
                  f_lazyload();
                  hide_jz_button();
               });
          		$(window).scroll(function(){
                    if ($(document).height() - $(window).scrollTop() - $(window).height() < 20) {
                      if(gd_array["ajaxpage"] < gd_array["pages_num"] && gd_array["pages_num"]!=='1' && gd_array["ajaxpage"]!==parseInt(gd_array["index_ajax_gd_num"])){
                        if(gd_array["ajax_gd"]=='1'){
                            f_ajaxindex();
                        }
                      }else{
                        //console.log('ajaxpage'+gd_array["ajaxpage"]);
                        //console.log('pages_num'+gd_array["pages_num"]);
                        //console.log('我是有底线滴');
                      }	
                    }
                });
        </script>
    <?php }elseif(is_tag() || is_search()){ ?>
        <script type="text/javascript">
              $(function() {
                  f_masonry();
                  f_lazyload();
                  hide_jz_button();
               });
        </script>
	<?php } ?>

<?php }
add_action('wp_footer','gs_script_cs',21);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*主题启用时数据初始化*/
function gd_message_install_callback(){
     global $wpdb;

     //新建私信表
     $table_name = $wpdb->prefix . 'gd_message';
     if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) :
 		$sql = " CREATE TABLE `$table_name` (
            `msg_id` bigint(20) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY(msg_id),
            `user_id` int,
            `msg_users` int,
            `msg_read` int,
            `msg_date` timestamp not null default current_timestamp,
            `msg_key` text,
            `msg_value` longtext
 		) CHARSET=utf8;";
 			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
 			dbDelta($sql);
     endif;
  
     //新建通知表
     $table_name = $wpdb->prefix . 'gd_notification';
     if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) :
 		$sql = " CREATE TABLE `$table_name` (
            `msg_id` bigint(20) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY(msg_id),
            `msg_user` int,
            `msg_type` int,
            `msg_who` int,
            `msg_value` int,
            `msg_text` longtext,
            `msg_read` int,
            `msg_date` timestamp not null default current_timestamp
 		) CHARSET=utf8;";
 			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
 			dbDelta($sql);
     endif;
  
  	 //新建订单管理表
     $shop_table_name = $wpdb->prefix . 'gd_order';
     if( $wpdb->get_var("SHOW TABLES LIKE '$shop_table_name'") != $shop_table_name ) :
       $sql = " CREATE TABLE `$shop_table_name` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY(id),
            `order_id` text,
            `user_id` int,
            `post_id` int,
            `order_type` text,
            `order_state` text,
            `rmb_use` float,
            `credit_use` int,
            `rmb_get` float,
            `credit_get` int,
            `order_count` int,
            `order_value` longtext,
            `order_date` timestamp not null default current_timestamp
       ) CHARSET=utf8;";
           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           dbDelta($sql);
     endif;
  
  	 //新建卡密表
     $card_table_name = $wpdb->prefix . 'gd_card';
     if( $wpdb->get_var("SHOW TABLES LIKE '$card_table_name'") != $card_table_name ) :
       $sql = " CREATE TABLE `$card_table_name` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY(id),
            `card_key` text,
            `card_value` text,
            `card_rmb` int,
            `card_status` int,
            `card_user` int
       ) CHARSET=utf8;";
           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           dbDelta($sql);
     endif;
 }

function gd_message_install(){
     global $pagenow;
     if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){
         gd_message_install_callback();
    }
 }
add_action( 'load-themes.php', 'gd_message_install' );
//////////////////////////////////////////////////////////////////////////////
?>