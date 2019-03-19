<?php
if(isset($_GET['id'])){
  	require( '../../../../wp-load.php' );
  	$post_id=intval($_GET['id']);
  	if(is_user_logged_in()){
		
	if(checkheiwu(get_current_user_id())){
          print json_encode( array('status'=>500,'msg'=>'您已被关入小黑屋') );
          die();
    }
		
		$current_user = wp_get_current_user();
        $user_id = $current_user->ID;
      	$key = 'i_like';
      	$value = get_user_meta( $user_id, $key,true);
      	if($value){
          	$value = explode(",",$value);
          	if(in_array($post_id,$value)){
            	$a=array($post_id);
              	$value=array_diff($value,$a);
              	addcredit($user_id,'postlike',-(int)of_get_option('postdianzan','none'));
            }else{
            	$value[]=$post_id;
              	addcredit($user_id,'postlike',(int)of_get_option('postdianzan','none'));
            }
          	$value = implode(',',$value);
        }else{
        	$value=$post_id;
        }
      	update_user_meta($user_id, $key, $value, $prev_value='');
      	update_post_like_ren($post_id,$user_id);
    }
  	die();
}else{
	die();
}
?>