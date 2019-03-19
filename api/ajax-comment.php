<?php
if(isset($_POST)){
  	require( '../../../../wp-load.php' );
	if(!is_user_logged_in()){
            	$response = array(
                  "status" => 500,
				  "msg" =>'请先登录',
                );
                print json_encode($response);
                die();		
	}
	if(checkheiwu(get_current_user_id())){
          print json_encode( array('status'=>500,'msg'=>'您已被关入小黑屋') );
          die();
    }
	
	$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
    if ( is_wp_error( $comment ) ) {
        $data = $comment->get_error_data();
        if ( ! empty( $data ) ) {
            	$response = array(
                  "status" => 500,
                  "msg" =>$comment->errors,
                );
                print json_encode($response);
                die();
        } else {
           die();
        }
    }
  	
  	$post_id = $_POST['comment_post_ID'];
  	$user = wp_get_current_user();
  	$include_unapproved = $user ->ID;
 	$comments = get_comments('post_id='.$post_id.'&comment__in='.$comment->comment_ID.'&include_unapproved='.$include_unapproved);
    $msg = wp_list_comments( array(
          'callback' => 'gd_comment',
          'echo'=>false
          ), $comments);
    $response = array(
      "status" => 200,
      "msg" =>$msg,
    );
    print json_encode($response);
    die();
}
?>