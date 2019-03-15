<?php
/*
* 专题页面
*/

//创建一个专题文章类型
function gd_create_series_taxonomies() {
	$labels = array(
		'name'              => '专题',
		'singular_name'     => '专题',
		'search_items'      => '搜索专题',
		'all_items'         => '所有专题',
		'parent_item'       => '父级专题',
		'parent_item_colon' => '父级专题',
		'edit_item'         => '编辑专题',
		'update_item'       => '更新专题',
		'add_new_item'      => '添加专题',
		'new_item_name'     => '专题名称',
		'menu_name'         => '专题',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'collection' ),
	);

	register_taxonomy( 'collection', array( 'post' ), $args );
}
add_action( 'init', 'gd_create_series_taxonomies' );

function gd_is_collection($id){
  if($id!==0){
    global $wpdb;
    global $table_prefix;
    $term_taxonomy_table_name = $table_prefix.'term_taxonomy';
    $taxonomy = $wpdb->get_results("SELECT taxonomy FROM $term_taxonomy_table_name where term_taxonomy_id = $id",ARRAY_A);
    if($taxonomy[0]['taxonomy'] == 'collection'){
      return true;
    }
  }
  	return false;
}


?>