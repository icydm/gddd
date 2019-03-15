<?php
if ($_FILES) {
  
    require( '../../../../wp-load.php' );
    if(!is_user_logged_in()){
    	die();
    }
  	$user_id = wp_get_current_user()->ID;
	$file = $_FILES['file'];
  	$type = $file['type']; //文件类型
  	$action = isset($_POST["type"]) ? esc_sql(esc_attr($_POST["type"])) : ''; //上传类别
  
  	if($action==''){
		print json_encode( array('status'=>500,'msg'=>'未知行为') );
	  	die();
    }
	if(!in_array($file['type'],array('image/jpg','image/png','image/jpeg','image/gif','image/JPG','image/PNG','image/JPEG','image/GIF'))){
		print json_encode( array('status'=>500,'msg'=>'文件格式不支持') );
	  	die();
	}
  	if($file["size"] >= (5*1024*1024)){
		print json_encode( array('status'=>500,'msg'=>'文件大小超过限制') );
	  	die();
    }
	/*
	array (size=5)
	  'name' => string '14249903_1.jpg' (length=14)
	  'type' => string 'image/jpeg' (length=10)
	  'tmp_name' => string 'D:\upupw\temp\phpDE28.tmp' (length=25)
	  'error' => int 0
	  'size' => int 106422
	 */
    // 获取上传目录信息
    $wp_upload_dir = wp_upload_dir();
    /*
    array (size=6)
	  'path' => string 'D:\app\mysite/wp-content/uploads/2015/05' (length=39)
	  'url' => string 'http://mysite/wp-content/uploads/2015/05' (length=39)
	  'subdir' => string '/2015/05' (length=8)
	  'basedir' => string 'D:\app\mysite/wp-content/uploads' (length=31)
	  'baseurl' => string 'http://mysite/wp-content/uploads' (length=31)
	  'error' => boolean false
     */
    // 将上传的图片文件移动到上传目录
    $basename = esc_sql(esc_attr($file['name']));
  	if(strlen($basename)<=7){
      $basename	= time().'-'.$basename;
    }
    $filename = $wp_upload_dir['path'].'/'.$basename;
    rename($file['tmp_name'], $filename);

	// Prepare an array of post data for the attachment.
	$attachment = array(
	  'guid'           => $wp_upload_dir['url'] . '/' . $basename,//外部链接的 url
	  'post_mime_type' => $file['type'],
	  'post_title'     => preg_replace( '/\.[^.]+$/', '', $basename ),//去除扩展名之后的文件名
	  'post_content'   => '',
	  'post_status'    => 'inherit'
	);

	$attach_id = wp_insert_attachment( $attachment, $filename );

	// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// Generate the metadata for the attachment, and update the database record.
	$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
	wp_update_attachment_metadata( $attach_id, $attach_data );

  	
    $url = wp_get_attachment_url(  $attach_id);
  
  	if($action=='ava'){
    	update_user_meta($user_id, 'avatar', $url, $prev_value='');
      	$url = gd_get_user_meta_message($user_id,'avatar');
    }

    if($action=='back'){
    	update_user_meta($user_id, 'back', $url, $prev_value='');
      	$url = gd_get_user_meta_message($user_id,'back');
    }
  
    if($action=='dashang'){
    	update_user_meta($user_id, 'dashang', $url, $prev_value='');
      	$url = gd_get_user_meta_message($user_id,'dashang');
    }      
	print json_encode( array('status'=>200,'msg'=>$url) );
  	die();
}
?>