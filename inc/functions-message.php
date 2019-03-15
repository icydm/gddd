<?php
//关于私信的

function gd_send_message($who,$to,$value){
	$who = (int)$who;
	$to = (int)$to;
	$value = htmlspecialchars((string)$value);

	global $wpdb;  
	$data['user_id'] = $who;
	$data['msg_users'] = $to;
	$data['msg_read'] = 0;
	$data['msg_key'] = '';
	$data['msg_value'] = $value;
	$result = $wpdb->insert( $wpdb->prefix.'gd_message', $data);
  	if($result == 1){
    	return true;
    }else{
    	return false;
    }
}
function gd_check_uread_message($user_id,$who=''){
	global $wpdb;
	global $table_prefix;
	$message_table_name = $table_prefix.'gd_message';    //_message
  	if($who){
      	$message = $wpdb->get_results("SELECT count(*) FROM $message_table_name WHERE msg_read=0 and msg_users = $user_id and user_id = $who ORDER BY msg_date DESC",ARRAY_A);
    	return $message[0]['count(*)'];
    }
  	$message = $wpdb->get_results("SELECT count(*) FROM $message_table_name WHERE msg_read=0 and msg_users = $user_id ORDER BY msg_date DESC",ARRAY_A);
	return $message[0]['count(*)'];
}
function gd_get_user_all_message($user_id){
	global $wpdb;
	global $table_prefix;
	$message_table_name = $table_prefix.'gd_message';    //_message
  	$message = $wpdb->get_results("SELECT * FROM $message_table_name WHERE user_id = $user_id or msg_users = $user_id ORDER BY msg_date DESC",ARRAY_A);
  	$message_group = array();
  	foreach($message as $a){
      	$person = $a['msg_users'];
      	$person_to = $a['user_id'];
      	if((int)$person !== $user_id){
    		$message_group[$person][] = $a;
        }else{
        	$message_group[$person_to][] = $a;
        }
    }
  	return $message_group;
}
function gd_get_contact_message($user_id,$who){
	global $wpdb;
	global $table_prefix;
	$message_table_name = $table_prefix.'gd_message';    //_message
  	$message = $wpdb->get_results("SELECT * FROM $message_table_name WHERE (user_id = $user_id and  msg_users = $who) or (user_id = $who and  msg_users = $user_id) ORDER BY msg_date ASC",ARRAY_A); //私信
  	$wpdb->update( $message_table_name, array( 'msg_read' => '1' ), array( 'msg_users' => $user_id,'user_id' => $who ) );  
  	return $message;	
}


//关于通知的

function gd_send_noti($msg_user,$msg_type,$msg_who,$msg_value,$msg_text){
	$msg_user = esc_sql((int)$msg_user);
	$msg_type = esc_sql((int)$msg_type);
	$msg_who = esc_sql((int)$msg_who);
    $msg_value = esc_sql((int)$msg_value);
    $msg_text = esc_sql(esc_attr($msg_text));
    $msg_read = esc_sql((int)$msg_read);

	global $wpdb;  					 //               购买视频；申请链接
	$data['msg_user'] = $msg_user;   //谁的通知               ；
	$data['msg_type'] = $msg_type;   //通知类型   		  1   ；   2
	$data['msg_who'] = $msg_who;     //谁发过来的     官方id  ； 申请人
	$data['msg_value'] = $msg_value; //关键值        post_id  ；       
	$data['msg_text'] = $msg_text;   //通知信息               ；
	$data['msg_read'] = '0'; 		 //读了没           

	$result = $wpdb->insert( $wpdb->prefix.'gd_notification', $data);
  	if($result == 1){
    	return true;
    }else{
    	return false;
    }
}

function gd_check_uread_noti($user_id){
	global $wpdb;
	global $table_prefix;
	$message_table_name = $table_prefix.'gd_notification';    //_gd_notification

  	$message = $wpdb->get_results("SELECT count(*) FROM $message_table_name WHERE msg_read=0 and msg_user = $user_id ORDER BY msg_date DESC",ARRAY_A);
	return $message[0]['count(*)'];
}

function gd_get_user_all_noti($user_id){
	global $wpdb;
	global $table_prefix;
	$message_table_name = $table_prefix.'gd_notification';    //_gd_notification
  	$message = $wpdb->get_results("SELECT * FROM $message_table_name WHERE msg_user = $user_id ORDER BY msg_date DESC",ARRAY_A);
  	$wpdb->update( $message_table_name, array( 'msg_read' => '1' ), array( 'msg_user' => $user_id) );
  	return $message;
}
?>