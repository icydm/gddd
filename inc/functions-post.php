<?php
/*
* 清除字符串中的标签
*/
function gd_clear_code($string){
    $string = trim($string);
    if(!$string)
        return '';
    $string = preg_replace('/[#][1-9]\d*/','',$string);//清除图片索引（#n）
    $string = str_replace("\r\n",' ',$string);//清除换行符
    $string = str_replace("\n",' ',$string);//清除换行符
    $string = str_replace("\t",' ',$string);//清除制表符
    $pattern = array("/> *([^ ]*) *</","/[\s]+/","/<!--[^!]*-->/","/\" /","/ \"/","'/\*[^*]*\*/'","/\[(.*)\]/");
    $replace = array(">\\1<"," ","","\"","\"","","");
    return preg_replace($pattern,$replace,$string);
}
/*
* 获取文章摘要
* $content 需要截断的字符串 (string)
* $size 截断的长度 (int)
*/
function gd_get_content_ex($content = '',$size = 130){

    if(!$content){
        global $post;
        $excerpt = $post->post_excerpt;
        $content = $excerpt ? $excerpt : $post->post_content;
    }

    return mb_strimwidth(gd_clear_code(strip_tags(strip_shortcodes($content))), 0, $size,'...');

}
function gd_get_post_des($post_id){
	if(!$post_id){
		global $post;
		$post_id = $post->ID;
	}

	$post_meta = gd_seo_get_post_meta($post_id, 'gd_seo_description');
	$post_excpert = get_post_field('post_excerpt',$post_id);
	$post_content = gd_get_content_ex(get_post_field('post_content',$post_id),150);

	//如果存在SEO描述输出，否则输出文章摘要，否则输出文章内容截断
	$description = $post_meta ? $post_meta : ($post_excpert ? $post_excpert : $post_content);

	return trim(strip_tags($description));
}
/**
 * @param $content
 * @return null
 *  从HTML文本中提取所有图片
 */
function get_images_from_html($content){
    $pattern="/<img.*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/";
    preg_match_all($pattern,htmlspecialchars_decode($content),$match);
    if(!empty($match[1])){
        return $match[1];
    }
    return null;
}
function gd_get_post_img_count($post_id){
    if(!$post_id) {
        global $post;
        $post_id = $post->ID;
        $content = $post->post_content;
    }else{
		$content = get_post_field('post_content', $post_id);
    }
	
	$num = get_images_from_html($content);
	if(is_array($num)){
		$num = count($num);	
	}else{
return;
	}
		

  	if($num > 1){
    	return '<div class="amount-content">'.$num.'P</div>';
    }else{
    	return;
    }
}
/*
* 获取content第 N 张图片
* $content 通常为文章内容,也可以是其他任意字符串(string)
* $i 返回第几章图片 (int)
*/
function gd_get_first_img($content,$i = 0) {
    preg_match_all('~<img[^>]*src\s?=\s?([\'"])((?:(?!\1).)*)[^>]*>~i', $content, $match,PREG_PATTERN_ORDER);

    if(is_numeric($i)){
        return isset($match[2][$i]) ? esc_url($match[2][$i]) : '';
    }elseif($i == 'all'){
        return $match[2];
    }else{
        return isset($match[2][0]) ? esc_url($match[2][0]) : '';
    }
}
/*
* 获取文章缩略图
*/
function gd_get_post_thumb($post_id = 0,$type = false){

    if(!$post_id) {
        global $post;
        $post_id = $post->ID;
        $content = $post->post_content;
    }else{
        $content = get_post_field('post_content', $post_id);
    }
    $post_thumbnail_url = get_the_post_thumbnail_url($post_id);

    //如果存在特色图，则返回特色图
    if($post_thumbnail_url){
        return esc_url($post_thumbnail_url);
    }

    //如果没有特色图则返回文章第一张图片
    if(!$type){
        $img = gd_get_first_img($content,0);
      	if(!$img){
        	$img = of_get_option('default_thumb', 'none');
        }
      	return $img;
    }else{
        return '';
    }
}
/*
*获取分类文章数量 
*/
function gd_get_category_count($input = '') {
	global $wpdb;

	if($input == '') {
		$category = get_the_category();
		return $category[0]->category_count;
	}
	elseif(is_numeric($input)) {
		$SQL = "SELECT $wpdb->term_taxonomy.count FROM $wpdb->terms, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id=$wpdb->term_taxonomy.term_id AND $wpdb->term_taxonomy.term_id=$input";
		return $wpdb->get_var($SQL);
	}
	else {
		$SQL = "SELECT $wpdb->term_taxonomy.count FROM $wpdb->terms, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id=$wpdb->term_taxonomy.term_id AND $wpdb->terms.slug='$input'";
		return $wpdb->get_var($SQL);
	}
}
/*
*喜欢
*/
function get_ta_like($id){
  	$talike = false;
	if(is_user_logged_in()){
		$current_user = wp_get_current_user();
        $user_id = $current_user->ID;
      	$talikek =  get_user_meta( $user_id,'i_like',true);
      	if($talikek){
          	$talikek = explode(",",$talikek);
          	if(in_array($id,$talikek)){
            	$talike = true;
            }
      	}
    }
  	return $talike;
}
function get_post_like_num_all($post_id){
	$key = 'i_like';
    if(!get_post_meta($post_id, $key, true )){
        update_post_meta( $post_id, $key, '' );
      	return '0';
    };
  	$likenum =  count(explode(",",get_post_meta($post_id, $key, true )));
	return $likenum;
}
function update_post_like_ren($post_id,$user_id){
	$key = 'i_like';
	if(get_post_meta($post_id, $key, true )){
		$like = explode(",",get_post_meta($post_id, $key, true ));
		if(in_array($user_id,$like)){
			$a=array($user_id);
			$like=array_diff($like,$a);
		}else{
			$like[]=$user_id;
		}
		$like = implode(',',$like);
	}else{
		$like=$user_id;
	}
	update_post_meta( $post_id, $key, $like);
}

/*
*标签
*/
function gd_get_post_tags($a){
	$a = wp_get_post_tags($a);
  	if(count($a)>=1){
      $c ='丨';
      foreach($a as $b){
           $c .= ' <span class="label label-rounded label-primary mx-1 a-tag"><a href="'.get_tag_link($b->term_id).'" target="_blank">'.$b -> name.'</a></span>';
      }
      return $c;    
    }
	return;
}
/*
*浏览
*/
function gd_get_post_view($post_id){
  	$a = get_post_meta($post_id, 'views', true );
  	if($a){  	
    	return $a;
    }else{
      	return '1';
    }
}
/*
*图片结构
*/
if(of_get_option('single_hl', 'none')=='1'){
	add_filter( 'the_content', 'gd_image_alt');
}
function gd_image_alt($c) {
	global $post;
	$title = strip_tags($post->post_title);
	$s = array('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png))\"?.+>/i'=> '<a href="$1" data-fancybox="gallery" data-caption="'.$title.'"><img src="$1" alt="'.$title.'" title="'.$title.'" /></a>');
	foreach($s as $p => $r){
		$c = preg_replace($p,$r,$c);
	}
	return $c;
}

/*
*收藏
*/
function get_ta_collect($id){
  	$talike = false;
	if(is_user_logged_in()){
		$current_user = wp_get_current_user();
        $user_id = $current_user->ID;
      	$talikek =  get_user_meta( $user_id,'i_collect',true);
      	if($talikek){
          	$talikek = explode(",",$talikek);
          	if(in_array($id,$talikek)){
            	$talike = true;
            }
      	}
    }
  	return $talike;
}
function get_post_collect_num_all($post_id){
	$key = 'i_collect';
    if(!get_post_meta($post_id, $key, true )){
        update_post_meta( $post_id, $key, '' );
      	return '0';
    };
  	$likenum =  count(explode(",",get_post_meta($post_id, $key, true )));
	return $likenum;
}
function update_post_collect_ren($post_id,$user_id){
	$key = 'i_collect';
	if(get_post_meta($post_id, $key, true )){
		$like = explode(",",get_post_meta($post_id, $key, true ));
		if(in_array($user_id,$like)){
			$a=array($user_id);
			$like=array_diff($like,$a);
		}else{
			$like[]=$user_id;
		}
		$like = implode(',',$like);
	}else{
		$like=$user_id;
	}
	update_post_meta( $post_id, $key, $like);
}

//下载按钮短代码
add_shortcode( 'gd_file', 'gd_file_down' );
function gd_file_down($atts, $content = null ){
	if(empty($atts)) return;
	$r = wp_parse_args($atts,array(
        'link'=>'',
        'name'=>'',
        'pass'=>'无',
        'code'=>'无'
    ));

	return '<div class="card">
    <div class="card-header"><a class="btn btn-primary float-right" href="'.$r['link'].'" target="_blank">下载</a>
        <div class="card-title h5">'.$r['name'].'</div>
        <div class="card-subtitle text-gray">提取码：'.$r['pass'].'，解压码：'.$r['code'].'</div>
    </div>
</div>';
}


//视频短代码
add_shortcode( 'gd_video', 'gd_video_play' );
function gd_video_play($atts, $content = null ){
	if(empty($atts)) return;
	$video = apply_filters('gd_video_play_arg',wp_parse_args($atts,array(
        'link'=>'',
        'credit'=>'0',
        'rmb'=>'0'
    )));
  	
	$id = get_the_id();
  
  	$msg = '';
      
  	$is_pay = false;
    global $wpdb,$user_ID;
    if ($user_ID) {
		$user_id = $user_ID;
      	global $table_prefix;
        $table_name = $table_prefix.'gd_order';
      	$is_pay = $wpdb->get_results("SELECT order_state FROM $table_name WHERE user_id=$user_id and post_id= $id and order_type = 'v'");
      	$is_pay = empty($is_pay) ? false : true;
      	if($is_pay){
        	$msg = '<div class="toast">您已购买</div>';
        }
      
      	if(of_get_option('isvipshow','none')=='1'){
            if(gd_is_vip($user_id)){
            	$is_pay = true;
              	$msg = '<div class="toast">VIP特权免费查看</div>';
            }
        }
    }
  
  	$video['credit'] = ($video['credit'] !== '' ) ? $video['credit'] : 0;
  	$video['rmb'] = ($video['rmb'] !== '' ) ? $video['rmb'] : 0;
  		
  	if(($video['credit']==0) && ($video['rmb']==0) && !$is_pay){
    	$is_pay = true;
    }

  
	ob_start();
  	?>
	<link href="https://cdn.bootcss.com/dplayer/1.25.0/DPlayer.min.css" rel="stylesheet">
	<script src="https://cdn.bootcss.com/hls.js/8.0.0-beta.3/hls.js"></script>
	<script src="https://cdn.bootcss.com/dplayer/1.25.0/DPlayer.min.js"></script>
	<?php
		echo $msg;
	?>
	
	<div style="position: relative;min-height: 170px;background: black;">
      <?php if(!$is_pay){ ?>
      <div class="videonoti" id="notice<?php echo $id;?>">【付费视频】您可以免费试看20秒，购买后可观看全部。</div>
      <div id="videonotibuy<?php echo $id;?>"></div>
      <?php } ?>
      <div id="gddplayer<?php echo $id;?>"></div>
    </div>
	<script>
		const gddplayer<?php echo $id;?> = new DPlayer({
		    container: document.getElementById('gddplayer<?php echo $id;?>'),
		    screenshot: false,
		    video: {
		        url: '<?php echo $video['link']; ?>'
		    }
		});
	</script>
	<?php if(!$is_pay){ ?>
	<script type="text/javascript">
    gddplayer<?php echo $id;?>.on('play', function () {
       $('#notice<?php echo $id;?>').html('');
    });
    gddplayer<?php echo $id;?>.on("timeupdate", function(){
      if(gddplayer<?php echo $id;?>.video.currentTime>=20){
        gddplayer<?php echo $id;?>.pause();
        $('#gddplayer<?php echo $id;?>').html('');
        gddplayer<?php echo $id;?>.fullScreen.cancel();
        $('.dplayer-video-wrap video').attr('src','');
        $('#videonotibuy<?php echo $id;?>').addClass('videonotibuy');
        $('#videonotibuy<?php echo $id;?>').html('\
            <div class="gd_video_buy_content">\
            该视频需要<span>付费购买</span>才可以观看完整版</br>\
			售价：<?php echo $video['rmb']; ?>RMB、<?php echo $video['credit']; ?>积分</br>\
            <span class="gd_video_buy_btn btn" onclick="gd_video_buy(<?php echo $id;?>)">购买</span>\
            </div>'
       	);
      }
    });
    </script>
	<?php } 
	$html = ob_get_clean();
	return $html;
}

//隐藏内容短代码
add_shortcode( 'gd_content', 'gd_content_fun' );
function gd_content_fun($atts, $content = null ){
  if(empty($atts)) return;
  $access = isset($atts['access']) ? $atts['access'] :'1';
  $key = isset($atts['key']) ? $atts['key'] :'0';
  $id = get_the_id();
  $msg = '';
  $is_pay = false;

  switch ($access) {
    case '1':
      //登录可见
      break;
    case '2':
      //积分阅读
      break;
    case '3':
      //rmb阅读
      break;
    case '4':
      //用户组
      break;
    default:
      break;
  }
  
  /*
    global $wpdb,$user_ID;
    if ($user_ID) {
    $user_id = $user_ID;
        global $table_prefix;
        $table_name = $table_prefix.'gd_order';
        $is_pay = $wpdb->get_results("SELECT order_state FROM $table_name WHERE user_id=$user_id and post_id= $id and order_type = 'v'");
        $is_pay = empty($is_pay) ? false : true;
        if($is_pay){
          $msg = '<div class="toast">您已购买</div>';
        }
      
        if(of_get_option('isvipshow','none')=='1'){
            if(gd_is_vip($user_id)){
              $is_pay = true;
                $msg = '<div class="toast">VIP特权免费查看</div>';
            }
        }
    }
  
      
    if($key == '0' && !$is_pay){
      $is_pay = true;
    }
*/
  
  ob_start();
?>



<?php 
  $html = ob_get_clean();
  return $html;
}

?>