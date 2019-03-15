<?php
if(isset($_POST)){
  require( '../../../../wp-load.php' );
    if(is_user_logged_in()){
      
        print json_encode( array('status'=>200,'msg'=>'举报成功') ); //测试站使用，正常使用需删除
        die();//测试站使用，正常使用需删除
      
      
        $id = intval($_POST['id'])-1;
      	$value = htmlspecialchars($_POST['value']);
        $who = wp_get_current_user()->ID;

      	if(checkheiwu($who)){
          print json_encode( array('status'=>500,'msg'=>'您已被关入小黑屋') );
          die();
        }
      
		$reason = array('侵犯版权','话题不相关','垃圾广告','色情','引战','违法信息','人身攻击','不顺眼');
      
        $username = of_get_option('jubaoemail','none');
        $subject = get_bloginfo( 'name' ).'有文章遭受举报';
        $body = '文章链接：'.$value.'<br>原因：'.$reason[$id].'<br>举报者id:'.$who;
        $headers = array('Content-Type: text/html; charset=UTF-8');

        $send = wp_mail( $username, $subject, $body, $headers );//邮箱发送

        if(!is_wp_error($send)){
          print json_encode( array('status'=>200,'msg'=>'举报成功') );
          die();
        }
        print json_encode( array('status'=>500,'msg'=>'举报失败') );
        die();

    }
  	print json_encode( array('status'=>500,'msg'=>'请先登录') );
}
die();

?>