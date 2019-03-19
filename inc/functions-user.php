<?php
/*
gd_get_user_message($user_id,$type)
gd_get_user_meta_message($user_id,$type)

gd_get_user_dname() current displayname
gd_get_user_email()

gd_get_user_followbutton($user_id) 关注按钮
gd_get_user_msgbutton($user_id)	私信按钮

gd_get_user_page_url($user_id,$type = '') 链接
gd_get_user_page_link($user_id,$target) <a 

updatelv($user_id) 更新等级


*/
function gd_is_user_fans($user_id){
	$fans = gd_get_user_meta_message($user_id,'fans');
  	$myid = wp_get_current_user()->ID;
  	if(($user_id!=='0') && in_array($myid,$fans)){
    	return true;
    }else{
    	return false;
    }
}
function gd_get_user_dname(){
    $current_user = wp_get_current_user();
     return $current_user->display_name;    
}
function gd_get_user_email(){
	$current_user = wp_get_current_user();
    return $current_user->user_email;
}
function gd_get_user_followbutton($user_id){
  $class ='';
  $msg = '关注';
  if(is_user_logged_in()){
    if(gd_is_user_fans($user_id)==true){
		$class ="yiguanzhu";
      	$msg = '已关注';   
    }
  }
  return '<button class="btn gd-color '.$class.'" data-id="'.$user_id.'" onclick="gd_follow($(this))">'.$msg.'</button>';
}
function gd_get_user_msgbutton($user_id){
  return '<button class="btn gd-color" data-id="'.$user_id.'" onclick="gd_sendsixin($(this))">发信息</button>';
}
function gd_get_user_page_url($user_id,$type = ''){
    if($user_id=='0') $user_id = wp_get_current_user()->ID;
    $url = home_url('/user/'.$user_id);
    if(!$type){
        return esc_url($url);
    }else{
        return esc_url($url.'/'.$type);
    }
}

function gd_get_user_page_link($user_id,$target=true){
    if($user_id=='0') $user_id = wp_get_current_user()->ID;
    $display_name = get_the_author_meta('display_name',$user_id);
    $url = gd_get_user_page_url($user_id);
  	if($target==true){
  		$target = 'taget="_blank"';
    }else{
    	$target = '';
    }
    if($display_name){
        $link = '<a id="user-'.$user_id.'" class="users" href="'.$url.'" '.$target.'>'.esc_html($display_name).'</a>';
    }else{
        $link = '<span class="gray">'.esc_html__('已注销','ziranzhi2').'</span>';
    }
    return $link;
}

function gd_get_user_message($user_id,$type){
  $user_obj = get_user_by('id',$user_id);
  $a = $user_obj->$type;
  return $a;
}

function gd_get_user_meta_message($user_id,$type){
	switch ($type) {
		case 'credit':
		      $credit = get_user_meta($user_id,$type,true);
		      if(!$credit){
		        update_user_meta($user_id, $type, '0', $prev_value='');
		        $credit = get_user_meta( $user_id, $type , true );
		      }
		      return $credit;
			break;
		case 'rmb':
		      $rmb = get_user_meta( $user_id , $type ,true);
		      if(!$rmb){
		        update_user_meta($user_id, $type, '0', $prev_value='');
		        $rmb = get_user_meta( $user_id , $type , true );
		      }
		      return $rmb;
			break;
		case 'follow':
		      $follow = get_user_meta( $user_id , $type ,true);
		      if(!$follow){
		        update_user_meta($user_id, $type,array(), $prev_value='');
		        $follow = get_user_meta( $user_id , $type , true );
		      }
		      return $follow;
			break;
		case 'fans':
		      $fans = get_user_meta($user_id,$type,true);
		      if(!$fans){
		        update_user_meta($user_id, $type,array(), $prev_value='');
		        $fans = get_user_meta( $user_id , $type , true );
		      }
		      return $fans;
			break;
		case 'lv':
		      $lv= get_user_meta( $user_id , $type , true );
		      if(!$lv){
		        update_user_meta($user_id, $type, '1', $prev_value='');
		        $lv = get_user_meta( $user_id , $type , true );
		      }
		      return $lv;
			break;
		case 'qianming':
		      $qianming = get_user_meta( $user_id , $type , true );
		      if(!$qianming){
		        update_user_meta($user_id, $type, '编辑个性签名', $prev_value='');
		        $qianming = get_user_meta( $user_id , $type , true );
		      }
		      return $qianming;
			break;
        case 'avatar':
		      $avatar = get_user_meta( $user_id , $type , true );
		      if(!$avatar){
		        update_user_meta($user_id, $type, of_get_option('default_ava',''), $prev_value='');
		        $avatar = get_user_meta( $user_id , $type , true );
		      }
        	  if(!strpos($avatar,'thirdqq.qlogo.cn')){
              	$avatar = home_url('/wp-content/themes/gddd/inc/sdk/thumb/index.php?src='.$avatar.'&w=200&h=200&zc=1');
              }
		      return $avatar;
			break;
         case 'back':
		      $back = get_user_meta( $user_id , $type , true );
		      if(!$back){
		        update_user_meta($user_id, $type,of_get_option('default_user_back',''), $prev_value='');
		        $back = get_user_meta( $user_id , $type , true );
		      }
        	  $back = home_url('/wp-content/themes/gddd/inc/sdk/thumb/index.php?src='.$back.'&w=1144&h=200&zc=1');
		      return $back;
			break;
         case 'dashang':
		      $dashang = get_user_meta( $user_id , $type , true );
		      if(!$dashang){
		        $dashang = of_get_option('default_dashang','');
		      }
		      return home_url('/wp-content/themes/gddd/inc/sdk/thumb/index.php?src='.$dashang.'&w=400&h=400&zc=1');
			break;
		default:
			break;
	}
}










//将用户页面的 author 改为 user
function change_author_permalinks() {
    global $wp_rewrite;
    $wp_rewrite->author_base = 'user';
    //$wp_rewrite->flush_rules();
}
add_action('init','change_author_permalinks');

add_filter('query_vars', 'users_query_vars');
function users_query_vars($vars) {
    // add lid to the valid list of variables
    $vars[] = 'user';
    $vars[] = 'gd_user_page';
    return $vars;
}

add_filter('author_link', 'gd_author_url_with_id', 1000, 2);
function gd_author_url_with_id($link, $author_id) {
  $link_base = trailingslashit(get_option('home'));
  $link = "user/".$author_id;
  return $link_base . $link;
}

function user_rewrite_rules( $wp_rewrite ) {
    $new_rules = array();
    $new_rules['user/([0-9]+)/([^&]+)?'] = 'index.php?author=$matches[1]&gd_user_page=$matches[2]';
    $new_rules['user/([0-9]+)?'] = 'index.php?author=$matches[1]';
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    return $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules','user_rewrite_rules');



function updatelv($user_id){
	$lv2 = of_get_option('gdlva','none');
	$lv3 = of_get_option('gdlvb','none');
	$lv4 = of_get_option('gdlvc','none');
	$lv5 = of_get_option('gdlvd','none');
	$lv6 = of_get_option('gdlve','none');
	$lv7 = of_get_option('gdlvf','none');
  	$type = 'credit';
    $credit = get_user_meta($user_id,$type,true);
    if(!$credit){
      update_user_meta($user_id, $type, '0', $prev_value='');
      $credit = get_user_meta( $user_id, $type , true );
    }
  	$type = 'lv';
	$lv= get_user_meta( $user_id , $type , true );
    if(!$lv){
      update_user_meta($user_id, $type, '1', $prev_value='');
      $lv = get_user_meta( $user_id , $type , true );
    }
	$lvv = 1;
	for($i=2;$i<8;$i++){
      	$a = 'lv'.$i;
		if($credit>=$$a){
			$lvv = $i;
		}
	}
	if($lvv > $lv){
		update_user_meta($user_id, 'lv', $lvv, $prev_value='');
	}
}

function addcredit($user_id,$type,$num){
  global $wpdb;
  $table = $wpdb->prefix . 'gd_notification';
  $jiangli = $wpdb->get_results("SELECT * FROM $table where (msg_type=3 or msg_type=4 or msg_type=5 or msg_type=6 or msg_type=7) and to_days(msg_date) = to_days(now())",ARRAY_A);
  
  $has = 0;
  foreach($jiangli as $all){
    $has = $has + $all['msg_value'];
  }
  
  if($has>(int)of_get_option('maxjifenliangji','none')){
  	return;
  }
  
  $credit = gd_get_user_meta_message($user_id,'credit');
  update_user_meta($user_id,'credit',$credit+$num);
  updatelv($user_id);
  
  if((int)$num>0){
    
    if($type=='qiandao'){
      gd_send_noti($user_id,3,of_get_option('guanfangid','none'),$num,'签到成功：奖励'.$num.'积分');  
    }
    if($type=='postlike'){
      gd_send_noti($user_id,4,of_get_option('guanfangid','none'),$num,'文章点赞成功：奖励'.$num.'积分');  
    }
    if($type=='postshoucang'){
      gd_send_noti($user_id,5,of_get_option('guanfangid','none'),$num,'文章收藏成功：奖励'.$num.'积分');  
    }
    if($type=='guanzhujiangli'){
      gd_send_noti($user_id,6,of_get_option('guanfangid','none'),$num,'关注成功：奖励'.$num.'积分');  
    }
    if($type=='beiguanzhujiangli'){
      gd_send_noti($user_id,7,of_get_option('guanfangid','none'),$num,'您被关注：奖励'.$num.'积分');  
    }
  }
   return;
}
?>