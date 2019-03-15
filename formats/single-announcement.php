<div class="container content-article">
  <div class="columns">
    <div class="column col-9 content-article-content">
    
<?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
      
<?php 
$post_id=get_the_id();
?>

     <article>
       <header>
       <h1 class="title"><?php the_title(); ?></h1>
       <div class="info mt-2">
         <time>发表时间：<?php the_time('Y-m-d G:i:s'); ?></time>
         <span style="display: block;" <?php if(!wp_is_mobile()){?>class="float-right"<?php } ?>>
           <span>浏览：<?php echo gd_get_post_view($post_id); ?></span>
           <span>&nbsp&nbsp收藏：<span id="post-collect"><?php echo get_post_collect_num_all($post_id) ?></span></span>
           <span>&nbsp&nbsp支持：<span id="post-like"><?php echo get_post_like_num_all($post_id) ?></span></span>
         </span>
         </div>
       </header>
       <hr style="margin: 10px 0;">
       <div class="article-content">
			<?php the_content(); ?>        	
       </div>
    </article>
 
<?php else : ?>
<article>
	没有公告！
</article>
<?php endif; ?>      
    
    </div>
    
    <div class="column col-3 gd_aside"><?php get_sidebar(); ?></div>
  </div>
</div>

<?php 

  get_footer();
?>