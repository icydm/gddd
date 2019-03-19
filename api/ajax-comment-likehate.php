<?php
if(isset($_POST['coid'])){
  	require( '../../../../wp-load.php' );
  	$typeid=htmlspecialchars($_POST['coid']);
  	if(is_user_logged_in()){
        $user_id = wp_get_current_user()->ID;
		
	if(checkheiwu($user_id)){
          print json_encode( array('status'=>500,'msg'=>'您已被关入小黑屋') );
          die();
    }
	
      	if(strpos($typeid,'like') !== false){
          	$key = 'like';
          	$Word = '顶';
        }else{
        	$key = 'hate';
          	$Word = '踩';
        }
      	$commentid = number($typeid);
      	$commentauther = get_comment($commentid)->user_id;
      
      	if($commentauther == $user_id){
          print json_encode( array('status'=>500,'msg'=>'不能'.$Word.'自己') );
          die();      	
        }
      	
      	$value =get_comment_meta($commentid, $key,true);
      	if(!$value){
          	$value = array();
        	update_comment_meta( $commentid, $key, $value, $prev_value = '' );
        }
       	if(in_array($user_id,$value)){
            $a=array($user_id);
            $value=array_diff($value,$a);
        }else{
          	$value[]=$user_id;
        }
      	
      	update_comment_meta( $commentid, $key, $value, $prev_value = '' );
      	

      	if($value==array() ){
        	$msg = '0';
        }else{
        	$msg = count_commentlikehate_num($commentid,$key);
        }
      
      	print json_encode( array('status'=>200,'msg'=>$msg) );
      	die();
      
    }
  
  	print json_encode( array('status'=>500,'msg'=>'请先登录') );
  	die();
}else{
	die();
}
?>