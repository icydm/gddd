<?php
/*获取当前网址*/
 function gd_get_currect_url(){
 	global $wp;
 	return home_url(add_query_arg(array(),$wp->request));
}
function gd_check_phone_email($a){
	if(is_email($a)){
		return 'email';
	}
	if(preg_match('#^13[\d]{9}$|^14[5,6,7,8,9]{1}\d{8}$|^15[^4]{1}\d{8}$|^16[6]{1}\d{8}$|^17[0,1,2,3,4,5,6,7,8]{1}\d{8}$|^18[\d]{9}$|^19[8,9]{1}\d{8}$#',$a)){
		return 'phone';
	}
		return false;
}

/*是否爬虫*/
function gd_checkrobot($useragent=''){
	static $kw_spiders = array('bot', 'crawl', 'spider' ,'slurp', 'sohu-search', 'lycos', 'robozilla');
	static $kw_browsers = array('msie', 'netscape', 'opera', 'konqueror', 'mozilla');

	$useragent = strtolower(empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent);
	if(strpos($useragent, 'http://') === false && gd_dstrpos($useragent, $kw_browsers)) return false;
	if(gd_dstrpos($useragent, $kw_spiders)) return true;
	return false;
}
function gd_dstrpos($string, $arr, $returnvalue = false) {
	if(empty($string)) return false;
	foreach((array)$arr as $v) {
		if(strpos($string, $v) !== false) {
			$return = $returnvalue ? $v : true;
			return $return;
		}
	}
	return false;
}

/*截取数字*/
function number($str){
    return preg_replace('/\D/s', '', $str);
}

/*smtp*/
if(of_get_option('smtp_ok', 'none')=='1'){
	add_action('phpmailer_init', 'gd_mail_smtp');	
}
function gd_mail_smtp( $phpmailer ) {
  $phpmailer->FromName = of_get_option('smtp_name', 'none');
  $phpmailer->Host = of_get_option('smtp_ad', 'none');
  $phpmailer->Port = of_get_option('smtp_port', 'none');
  $phpmailer->Username = of_get_option('smtp_count', 'none');
  $phpmailer->Password = of_get_option('smtp_pass', 'none');
  $phpmailer->From = of_get_option('smtp_count', 'none');
  $phpmailer->SMTPAuth = true;
  $phpmailer->SMTPSecure = of_get_option('smtp_jiami', 'none');
  $phpmailer->IsSMTP();
}

/*分页*/
function gd_pagenavi($range = 5,$page_data = array('pages'=>0,'paged'=>0)){
    global $paged, $wp_query;

    if($page_data['pages']){
        $max_page = $page_data['pages'];
    }else{
        $max_page = $wp_query->max_num_pages;
    }
    if($page_data['paged']){
        $paged = $page_data['paged'];
    }

    $html = '';
    if($max_page > 1){
        $html .= '<div class="btn-group fl">';
        if(!$paged){
            $paged = 1;
        }
        if($max_page > $range){
            if($paged < $range){
                for($i = 1; $i <= ($range + 1); $i++){
                    $html .= '<a class="button empty '.($i==$paged ? 'selected disabled' : '').'" href="'. get_pagenum_link($i) .'">'.$i.'</a>';
                }
            }elseif($paged >= ($max_page - ceil(($range/2)))){
                for($i = $max_page - $range; $i <= $max_page; $i++){
                    $html .= '<a class="button empty '.($i==$paged ? 'selected disabled' : '').'" href="'. get_pagenum_link($i) .'">'.$i.'</a>';
                }
            }elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
                for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){
                    $html .= '<a class="button empty '.($i==$paged ? 'selected disabled' : '').'" href="'. get_pagenum_link($i) .'">'.$i.'</a>';
                }
            }
        }else{
            for($i = 1; $i <= $max_page; $i++){
                $html .= '<a class="button empty '.($i==$paged ? 'selected disabled' : '').'" href="'. get_pagenum_link($i) .'">'.$i.'</a>';
            }
        }

        if($max_page > $range){
            $html .= '<button class="empty bordernone">...</button>';
            $html .= '<a class="button empty" href="' . get_pagenum_link($max_page) . '" class="extend" title="跳转到最后一页">'.$max_page.'</a>';
        }
        $html .= '</div>';
        $html .= '<div class="btn-pager fr fs13">';
        $pre = get_pagenum_link($paged-1);
        $html .= $paged-1 > 0 ? '<button class="button"><a href="'.$pre.'">❮</a></button>' : '<button class="disabled button">❮</button>';
        $next = get_pagenum_link($paged+1);
        if(wp_is_mobile()){
            $html .= '<div class="pager-center">'.$paged.'/'.$max_page.'</div>';
        }
        $html .= $paged+1 <= $max_page ? '<button class="navbtr button"><a href="'.$next.'">❯</a></button>' : '<button class="disabled navbtr button">❯</button>';
        $html .= '</div>';

    }

    if($max_page > 1){
        return '<div class="zrz-pager clearfix pd10 pos-r box custom-search">'.$html.'</div>';
    }else {
        unset($html);
        return '';
    }

}


/*时间戳到*/
function stamptoday($times){
  // 天数
  $day = floor($times/86400);
  // 小时
  $hour = floor(($times-86400 * $day)/3600);
  // 分钟
  $minute = floor(($times-86400 * $day-3600 * $hour)/60);
  return $day.'天'.$hour.'小时'.$minute.'分钟';
}


//申请链接
add_action('wp_ajax_gd_insert_link','gd_insert_link');
function gd_insert_link(){
    $link_url = isset($_POST['link_url']) ? trim(sanitize_text_field(htmlspecialchars($_POST['link_url'], ENT_QUOTES))) : '';
    $link_name = isset($_POST['link_name']) ? trim(sanitize_text_field(htmlspecialchars($_POST['link_name'], ENT_QUOTES))) : '';
    $link_image = isset($_POST['link_image']) ? esc_url($_POST['link_image']) : '';
    $link_category = isset($_POST['link_category']) ? trim(sanitize_text_field(htmlspecialchars($_POST['link_category'], ENT_QUOTES))) : '';
    $link_description = isset($_POST['link_description']) ? trim(sanitize_text_field(htmlspecialchars($_POST['link_description'], ENT_QUOTES))) : '';
    $current_user = get_current_user_id();

    if($link_url && $link_name && $link_category && $link_description){
        $linkdata = array(
            'link_url'=>$link_url,
            'link_name'=>$link_name,
            'link_image'=>$link_image,
            'link_category'=>$link_category,
            'link_owner'=>$current_user,
            'link_description'=>$link_description,
            'link_visible'=> 'N'
        );
        $link_id = wp_insert_link( $linkdata );
        if($link_id){
            gd_send_noti(1,2,$current_user,$link_id,'申请友链：<a href="'.$link_url.'" target="_blank">'.$link_name.'</a>，请在后台进行 <a href="'.home_url('/wp-admin/link-manager.php').'">操作</a> ');
          
            print json_encode(array('status'=>200,'msg'=>'申请成功'));
            exit;
        }else{
            print json_encode(array('status'=>500,'msg'=>$link_id));
            exit;
        }
    }
    print json_encode(array('status'=>500,'msg'=>'申请失败，请完善资料'));
    exit;
}

//加入或者移除小黑屋
add_action('wp_ajax_blacklisttoggle','blacklisttoggle');
function blacklisttoggle(){
  	if(!is_super_admin()){
      print json_encode(array('status'=>500,'msg'=>'失败，没有权限进行操作'));
      exit;
    }
  	$reason = isset($_POST['reason']) ? trim(sanitize_text_field(htmlspecialchars($_POST['reason'], ENT_QUOTES))) : '';
  	$user_id = intval($_POST['id']) ? intval($_POST['id']) : '';
  	$day = intval($_POST['day']) ? intval($_POST['day']) : 1;
  	
  	if($user_id == ''){
      print json_encode(array('status'=>500,'msg'=>'用户ID未填写'));
      exit;
    }
  	
	$now =time();
  	$time = $now + $day * 24 * 60 * 60;
  
	$blacklist = (array)get_option('blacklist');
  	if(!in_array($user_id,$blacklist)){
    	$blacklist[] = $user_id;
      	$heiwu = array(
        	'state' => '1',
          	'reason' => $reason,
          	'time'=> $time
        );
      	update_user_meta($user_id,'heiwu',$heiwu);
      	$msg = '已加入小黑屋';
    }else{
        $a = array($user_id);
        $blacklist = array_diff($blacklist,$a);
      	$heiwu = array(
        	'state' => '0',
          	'reason' => '',
          	'time'=>''
        );
      	update_user_meta($user_id,'heiwu',$heiwu);
      	$msg = '已从小黑屋移除';
    }
  	update_option('blacklist',$blacklist);

    print json_encode(array('status'=>200,'msg'=>$msg));
    exit;
}
//是否关在小黑屋

function checkheiwu($user_id){
  $heiwu = get_user_meta($user_id,'heiwu',true);
  if((int)$heiwu['time']<=time()){
      $a = (int)$user_id;
      $blacklist = (array)get_option('blacklist');
      foreach($blacklist as $key => $val){
      	if($val==$a){
        	unset ($blacklist[$key]);
        }
      }
      update_option('blacklist',$blacklist);
      $heiwu = array(
          'state' => '0',
          'reason' => '',
          'time'=>''
      );
      update_user_meta($user_id,'heiwu',$heiwu);
  }
  if($heiwu['state']=='1'){
	return true;
  }
  return false;
}

//get_ava替换
add_filter( 'get_avatar' , 'my_custom_avatar' , 1 , 5 );
function my_custom_avatar( $avatar, $id_or_email, $size, $default, $alt) {
	if (filter_var($id_or_email, FILTER_VALIDATE_EMAIL)) {//判断是否为邮箱
		$email = $id_or_email;//用户邮箱
		$user = get_user_by( 'email', $email );//通过邮箱查询用户信息
      	$avatar = "https://i.leiue.com/avatar.php?email=".$email;
    }else{
      	if ( ! empty( $id_or_email->user_id ) ) {
        	$uid = (int) $id_or_email->user_id;//获取用户 ID
        }else{
        	$uid = (int) $id_or_email;//获取用户 ID
        }
    	
        $user = get_user_by( 'id', $uid );//通过 ID 查询用户信息
      	$avatar = gd_get_user_meta_message($uid,'avatar');
    }
	$alt = $user->user_nicename;//用户昵称
  
    $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
 
    return $avatar;
}


?>