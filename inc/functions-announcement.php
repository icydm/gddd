<?php
//添加公告所的文章类型
function gd_create_post_type_announcement() {
	$gg = array(
 		'name' => __('公告','gdtheme'),
    	'singular_name' => __('公告','gdtheme'),
    	'add_new' => __('添加一个公告','gdtheme'),
    	'add_new_item' => __('添加一个公告','gdtheme'),
    	'edit_item' => __('编辑公告','gdtheme'),
    	'new_item' => __('新的公告','gdtheme'),
    	'all_items' => __('所有公告','gdtheme'),
    	'view_item' => __('查看公告','gdtheme'),
    	'search_items' => __('搜索公告','gdtheme'),
    	'not_found' =>  __('没有公告','gdtheme'),
    	'not_found_in_trash' =>__('回收站为空','gdtheme'),
    	'parent_item_colon' => '',
    	'menu_name' => __('公告','gdtheme'),
    );
	register_post_type( 'announcement', array(
		'labels' => $gg,
		'has_archive' => true,
 		'public' => true,
		'supports' => array( 'title', 'editor','thumbnail'),
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'rewrite' => array( 'slug' => 'announcement' ),
		)
	);
}
add_action( 'init', 'gd_create_post_type_announcement' );

add_filter('post_type_link', 'custom_book_link_gg', 1, 3);
function custom_book_link_gg( $link, $post = 0 ){
    if ( $post->post_type == 'announcement' ){
        return home_url( 'announcement/' . $post->ID .'.html' );
    } else {
        return $link;
    }
}

add_action( 'init', 'custom_book_rewrites_gg_init' );
function custom_book_rewrites_gg_init(){
    add_rewrite_rule(
        'announcement/([0-9]+)?.html$',
        'index.php?post_type=announcement&p=$matches[1]',
        'top' );
    add_rewrite_rule(
        'announcement/([0-9]+)?.html/comment-page-([0-9]{1,})$',
        'index.php?post_type=announcement&p=$matches[1]&cpage=$matches[2]',
        'top');
}