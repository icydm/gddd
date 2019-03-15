<?php
//////////////////评论回调
function gd_comment($comment, $args, $depth){
   $GLOBALS['comment'] = $comment;
    $comment_id = get_comment_ID();
    $commentcount_html='';
    $commentcount = 5;
    if($comment->comment_parent==0){
        $comments = commentlouceng($comment,$comment_id);
        $commentcount =$comments+1;//获取主评论总数量
        $commentcount_html = '<span class="floor">#'.$commentcount.'</span>';
    }
?>
<div class="list-item reply-wrap" id="comment<?php echo $comment_id; ?>" comment-id="<?php echo $comment_id; ?>" depth="<?php echo $depth; ?>">
	<div class="user-ava">
		<a href="<?php echo gd_get_user_page_url($comment->user_id,$type = '');?>" target="_blank">
          	<img src="<?php echo gd_get_user_meta_message($comment->user_id,'avatar');?>" />
		</a>
      	<?php if($commentcount<4){?>
		<div class="user-follow">
			<?php echo gd_get_user_followbutton($comment->user_id); ?>
		</div>
      	<?php } ?>
	</div>
	<div class="comment-zhuti">
		<div class="user">
			<a href="<?php echo gd_get_user_page_url($comment->user_id,$type = '');?>" target="_blank" class="name "><?php echo gd_get_user_message($comment->user_id,'display_name');?></a>
			<a href="#" target="_blank">
				<i class="gd-color">level <?php echo gd_get_user_meta_message($comment->user_id,'lv');?></i>
			</a>
		</div>
		<div class="text">
			<?php if ($comment->comment_approved == '0') : ?><em>你的评论正在审核，稍后会显示出来！</em><br /><?php endif; ?>
			<?php comment_text(); ?>
		</div>
		<div class="info">
			<?php echo $commentcount_html; ?>
			<!-- <span class="plad">来自<a href="" target="_blank">安卓客户端</a></span>-->
			<span class="time"><?php echo get_comment_time('Y-m-d H:i'); ?></span>
			<span class="like " onclick="commentlikehate($(this))" coid="like<?php echo $comment_id; ?>"><i class="fa fa-thumbs-o-up fa-lg <?php echo if_commentlikehate($comment_id,'like',wp_get_current_user()->ID); ?>"></i><span class="mr-l5"><?php echo count_commentlikehate_num($comment_id,'like'); ?></span></span>
			<span class="hate " onclick="commentlikehate($(this))" coid="hate<?php echo $comment_id; ?>"><i class="fa fa-thumbs-o-down fa-lg <?php echo if_commentlikehate($comment_id,'hate',wp_get_current_user()->ID); ?>"></i><span class="mr-l5"><?php echo count_commentlikehate_num($comment_id,'hate'); ?></span></span>
			<span class="reply" onclick="commentformadd($(this))" comment-id="<?php echo $comment_id; ?>">
				回复
			</span>
			<div class="tianchong">
				<div class="reply"></div>
			</div>
		</div>
		<div class="reply-box">
		</div>
		<div class="paging-box">
		</div>
	</div>
</div>
<?php } ?>
<?php
///////////回复加@
function gd_comment_add_at( $comment_text, $comment = '') {
  if( $comment->comment_parent > 0) {  
    $comment_text = '<a class="commentat">@'.( get_comments($comment->comment_parent))[0]->comment_author . '</a> ' . $comment_text;
  }
  return $comment_text;
}
add_filter( 'comment_text' , 'gd_comment_add_at', 20, 2);
function gd_recover_comment_fields($comment_fields){
    $comment = array_shift($comment_fields);
    $comment_fields =  array_merge($comment_fields ,array('comment' => $comment));
    return $comment_fields;
}
add_filter('comment_form_fields','gd_recover_comment_fields');
//////////////评论楼层
function commentlouceng($comment,$comment_id){
	$key = 'comment_louceng';
	$a = get_comment_meta( $comment_id, $key, true );
	if($a==null){
		global $wpdb;
    	$meta_value = $wpdb->get_var("SELECT COUNT( * ) FROM $wpdb->comments WHERE comment_post_ID = $comment->comment_post_ID AND comment_type = '' AND comment_approved = '1' AND comment_parent ='0' AND comment_ID < $comment_id");
		update_comment_meta( $comment_id,$key,$meta_value);
		$a = get_comment_meta( $comment_id, $key, true );
	}
	return $a;
}
/////////////////评论表情
function gd_comment_emoji(){
?>
    <div class="comment-emoji">
      <i class="face"></i>
      <div class="OwO"></div>
      <script>
        window.onload = function () {
      var OwO_demo = new OwO({
    logo: 'OωO',
    container: document.getElementsByClassName('OwO')[0],
    target: document.getElementsByClassName('ipt-txt')[0],
    api: gd_array['site_url']+'/wp-content/themes/gddd/assets/OwO/OwO.json',
    position: 'down',
    width: '100%',
    maxHeight: '250px'
});}
      </script>
    </div>
<?php
}
/////////////////////评论顶踩

function count_commentlikehate_num($commentid,$key){
	$value = get_comment_meta($commentid, $key,true);
  	if(empty($value)){
    	return '0';
    }
  	if($value[0]==array()){
    	return '0';
    }else{
    	return count($value);
    }
}
function if_commentlikehate($commentid,$key,$userid){
  	if(!is_user_logged_in()){
    	return '';
    }
  	
	$value = get_comment_meta($commentid, $key,true);
  	if(empty($value)){
    	return '';
    }
  	
  	if($value[0]==array()){
    	return '';
    }
  	
    if(in_array($userid,$value)){
		return 'gd-color';
    }
  	return '';
}


?>