<?php
$user_id =  get_query_var('author');
$fans = gd_get_user_meta_message($user_id,'fans');
if(count($fans)>=1){
  foreach($fans as $user_id){ ?>
    <div class="user-cent-user">
      <a href="<?php echo gd_get_user_page_url($user_id,$type = ''); ?>" target="_blank">
        <img src="<?php echo gd_get_user_meta_message($user_id,'avatar');?>" id="h-avatar" />
        <div class="cms_content_title"><?php echo gd_get_user_message($user_id,'display_name');?></div>
      </a>
    </div>
  <?php }
}else{
	echo '<p class="mar10-t">还没有粉丝</p>';
}

?>