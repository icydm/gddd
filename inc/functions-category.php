<?php
function gd_get_current_category_id() {
  $current_category = single_cat_title('', false);//获得当前分类目录名称
  return get_cat_ID($current_category);//获得当前分类目录 ID
}

function gd_post_get_category($a){
	$a = get_the_category($a);
  	$a = $a[0];
  	return '<a href="'.get_category_link($a->term_id).'" target="_blank" >'.$a->name.'</a>';
}
















?>