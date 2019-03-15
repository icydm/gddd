<?php if ( comments_open() ) : ?>
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php printf(__('你需要先 <a href="%s">登录</a> 才能发表评论.'), get_option('siteurl')."/login");?></p>
<?php else : ?>
  <div class="comment-send ">
    <div class="user-face">
      <img class="user-head" src="<?php echo gd_get_user_meta_message($user_ID,'avatar');?>">
    </div>
    <div class="textarea-container">
      <textarea cols="80" name="msg" rows="5" placeholder="请自觉遵守互联网相关的政策法规，严禁发布色情、暴力、反动的言论。" class="ipt-txt"></textarea>
      <button class="comment-submit" onclick="postcomment($(this))">发表评论</button>
    </div>
<?php gd_comment_emoji(); ?>
  </div>
<?php endif;
else :  ?>
<p><?php _e('对不起评论已经关闭.'); ?></p>
<?php endif; ?>
<?php
   if (!empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {
   ?>
   <li class="decmt-box">
       <p><a href="#addcomment">请输入密码再查看评论内容.</a></p>
   </li>
   <?php
       } else if ( !comments_open() ) {
   ?>
   <li class="decmt-box">
       <p><a href="#addcomment">评论功能已经关闭!</a></p>
   </li>
   <?php
       } else if ( !have_comments() ) {
   ?>
       <p style="margin-top: 30px;">还没有任何评论，快来说两句吧</p>
   <?php
       } else {
           wp_list_comments('type=comment&callback=gd_comment');
       }
?>