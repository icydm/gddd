<?php
if(isset($_GET['id'])){
  	require( '../../../../wp-load.php' );
  	$post_id=intval($_GET['id']);
  	if(is_user_logged_in()){
		$current_user = wp_get_current_user();
        $user_id = $current_user->ID;
      	$key = 'i_collect';
      	$value = get_user_meta( $user_id, $key,true);
      	if($value){
          	$value = explode(",",$value);
          	if(in_array($post_id,$value)){
            	$a=array($post_id);
              	$value=array_diff($value,$a);
            }else{
            	$value[]=$post_id;
            }
          	$value = implode(',',$value);
        }else{
        	$value=$post_id;
        }
      	update_user_meta($user_id, $key, $value, $prev_value='');
      	update_post_collect_ren($post_id,$user_id);
    }
  	die();
}else{
	die();
}
?>