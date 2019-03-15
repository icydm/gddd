<?php 
$postlink = get_the_permalink();
$postid = get_the_ID();
$post_title = get_the_title();
if(!get_ta_like($postid)){
	$xihuan = 'style="display: none;"';
}else{
	$xihuan ='';
}
$auther_id = get_the_author_meta( 'ID' );
?>
<div class="dapubua">
  <dl>
        <?php if(of_get_option('index_ajax_height','no entry')){ ?>
            <dt class="denggaoaaa">
                <a href="<?php echo $postlink; ?>" target="_blank">
                <div class="dapubu-img" style="background-image: url('<?php echo gd_get_post_thumb(); ?>');" ></div>
                <?php echo gd_get_post_img_count($postid); ?>
                </a>
            </dt>
        <?php }else{?>
            <dt>
                <a href="<?php echo $postlink; ?>" target="_blank">
                <img class="dapubu-img img-lazy" data-original="<?php echo gd_get_post_thumb(); ?>" alt="<?php echo $post_title; ?>"/>
                <?php echo gd_get_post_img_count($postid); ?>
                </a>
            </dt>
        <?php } ?>
		<dd>
			<h3>
				<a href="<?php echo $postlink; ?>" target="_blank"><?php echo $post_title; ?></a>
			</h3>
			<h4>
				<div>
					<a href="<?php echo gd_get_user_page_url($auther_id,$type = '');?>" target="_blank">
						<i style="background-image: url('<?php echo gd_get_user_meta_message($auther_id,'avatar');?>');"></i>
					</a>
					<a href="<?php echo gd_get_user_page_url($auther_id,$type = '');?>" target="_blank">
						<span><?php echo gd_get_user_message($auther_id,'display_name');?></span>
					</a>
				</div>
			</h4>
			<div class="good dapubua-aha" onclick="gd_postlike($(this))" data-post-id="<?php echo $postid; ?>">
				<div class="dapubua-word">支持一下</div>
				<i class="fa fa-thumbs-o-up fa-lg"></i>
			</div>
			<div class="good-a aha" onclick="gd_postnotlike($(this))" <?php echo $xihuan;?> data-post-id="<?php echo $postid; ?>">
				<i class="fa fa-thumbs-o-up fa-lg"></i>
			</div>
		</dd>
	</dl>
</div>