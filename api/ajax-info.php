<?php
if(isset($_POST)){
  require( '../../../../wp-load.php' );
    if(is_user_logged_in()){
        $user_id = intval($_POST['to']);
      	$value = htmlspecialchars($_POST['value']);
      	$type = htmlspecialchars($_POST['type']);

        $can = current_user_can('delete_users');
        $current_user_id =wp_get_current_user()->ID;
        $self = ($user_id == $current_user_id || $can) ? true : false;
      	if(!$self){
        	print json_encode( array('status'=>500,'msg'=>'没有修改的权限') );
          	die();
        }
		if($value=='' || $value=='null'){
        	print json_encode( array('status'=>500,'msg'=>'请输入内容') );
          	die();
        }
      
        if($type == 'disname'){
          $value = wp_trim_words( $value, 10 ,'');
          $arr = array(
            'display_name'=>$value,
            'ID'=>$user_id
          );
          wp_update_user($arr); 
        }

        print json_encode( array('status'=>200,'msg'=>'修改成功') );
      	die();
    }
  	print json_encode( array('status'=>500,'msg'=>'请先登录') );
}
die();

?>