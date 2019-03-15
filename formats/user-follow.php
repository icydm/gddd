<?php
$user_id =  get_query_var('author');
$follow = gd_get_user_meta_message($user_id,'follow');
if(count($follow)>=1){
  foreach($follow as $user_id){ ?>
    <div class="user-cent-user">
      <a href="<?php echo gd_get_user_page_url($user_id,$type = ''); ?>" target="_blank">
        <img class="img-lazy" data-original="<?php echo gd_get_user_meta_message($user_id,'avatar');?>" id="h-avatar" />
        <div class="cms_content_title"><?php echo gd_get_user_message($user_id,'display_name');?></div>
      </a>
    </div>
  <?php } ?>
<script type="text/javascript" charset="utf-8">
  $(function() {
      $(".img-lazy").lazyload({placeholder : "https://wx4.sinaimg.cn/large/0060lm7Tly1fyl8da9nkcg307407440b.gif", effect: "show"});
  });
</script>
<?php }else{
	echo '<p class="mar10-t">谁也没有关注</p>';
}

?>