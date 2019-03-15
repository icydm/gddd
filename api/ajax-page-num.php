<?php

if(isset($_GET['cat'])){
  	require( '../../../../wp-load.php' );
  	$cat=intval($_GET['cat']);
  	$nub = get_option('posts_per_page',18);
  	if($cat==0){
     	$count_posts = wp_count_posts('post'); 
      	$publish_posts = $count_posts->publish;
		$pages_num = ceil($publish_posts / $nub);
		print json_encode( array('status'=>200,'num'=>$pages_num) );
		die();
    }
	$pages_num = ceil( gd_get_category_count($cat) / $nub);
	print json_encode( array('status'=>200,'num'=>$pages_num) );
	die();
}else{
	die();
}



?>