<?php
if(isset($_GET['id'])){
  	require( '../../../../wp-load.php' );
  	$id=intval($_GET['id']);
  	if(is_user_logged_in()){
		$current_user = wp_get_current_user();
        $user_id = $current_user->ID;
      	if($id == $user_id){
            print json_encode( array('status'=>500,'msg'=>'无法关注这么优秀的自己') );
            die();        	
        }
        if($id=='0'){
            print json_encode( array('status'=>500,'msg'=>'未注册，无法关注') );
            die();            
        }
    	if(!get_the_author_meta('display_name',$id)){
            print json_encode( array('status'=>500,'msg'=>'此用户已注销') );
            die();         	
        }
      	$follow = gd_get_user_meta_message($user_id,'follow');//自己的关注者
      	$fans = gd_get_user_meta_message($id,'fans');//对方的粉丝
      	
        if(in_array($id,$follow)){
            $a=array($id);
          	$b= array($user_id);
            $follow=array_diff($follow,$a);
          	$fans=array_diff($fans,$b);
          	$msg = '取消关注成功';
        }else{
          	$fans[]=$user_id;
        	$follow[]=$id;
          	$msg = '关注成功';
        }
		update_user_meta($id,'fans',$fans, $prev_value='');
      	update_user_meta($user_id, 'follow', $follow, $prev_value='');
      	print json_encode( array('status'=>200,'msg'=>$msg) );
      	die();
    }
  	print json_encode( array('status'=>500,'msg'=>'请先登录') );
  	die();
}else{
	die();
}
?>