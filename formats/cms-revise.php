<?php 
$postlink = get_the_permalink();
$postid = get_the_ID();
$post_title = get_the_title();
?>
<div class="cms_mukuai col-4">
  
    <div class="back cms_mukuai_back" style="background-image: url('<?php echo gd_get_post_thumb(); ?>');">
      <!--<span class="user_cent_revise"><a href="#" target="_blank">修改</a></span>-->
      <a href="<?php echo $postlink; ?>"  target="_blank"><div class="cms_content_title"><?php echo $post_title;?></div></a>
    </div>
  
</div>