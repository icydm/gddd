<?php
if(isset($_GET['id'])){
  	require( '../../../../wp-load.php' );
  	$post_id=intval($_GET['id']);
  	$a = get_post_meta($post_id, 'views', true );
  	if($a){
    	update_post_meta($post_id, 'views', $a+1);
    }else{
    	update_post_meta($post_id, 'views', '1');
    }
  	die();
}else{
	die();
}
?>