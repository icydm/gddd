<?php
if(isset($_POST)){
  require( '../../../../wp-load.php' );
    if(is_user_logged_in()){
        $to = $_POST['to'];
      	$value = $_POST['value'];
        $who = wp_get_current_user()->ID;
      	
      	if(checkheiwu($who)){
          print json_encode( array('status'=>500,'msg'=>'您已被关入小黑屋') );
          die();
        }
      
      	if($who == $to){
        	print json_encode( array('status'=>500,'msg'=>'无法向自己发送私信') );
          	die();
        }
		if($value=='' || $value=='null'){
        	print json_encode( array('status'=>500,'msg'=>'请输入内容') );
          	die();
        }
      	$result = gd_send_message($who,$to,$value);
      	if($result){
        	print json_encode( array('status'=>200,'msg'=>'发送成功') );
        }else{
        	print json_encode( array('status'=>500,'msg'=>'请先失败') );
        }
      	die();
    }
  	print json_encode( array('status'=>500,'msg'=>'请先登录') );
}
die();

?>