<div class="container content-article">
  <div class="columns">
    <div class="column col-9 content-article-content">
    
<?php while ( have_posts() ) : the_post(); ?>
      
<?php 
$post_id=get_the_id();
?>

     <article>
       <header>
       <h1 class="title"><?php the_title(); ?></h1>
       <i id="accusation" class="iconfont icon-jinggao" onclick="gd_post_accusation($(this))" data-post-id="<?php echo $post_id;?>"></i>
         <div class="tags mt-1">
           <span class="cat"><?php echo gd_post_get_category($post_id); ?></span>
           <?php echo gd_get_post_tags($post_id); ?>
         </div>
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
       <div class="article-state">
			<button class="btn gd-color posthaibao" onclick="openhaibao()">海报</button>
			<button class="btn gd-color posthaibao" onclick="opendashang()">打赏</button>
			<button class="btn gd-color posthaibao" onclick="openfx()">分享</button>	   
       </div>
    </article>

<?php if(of_get_option('postadopen','none')=='1'){ ?>
      <div class="postad">
        <?php echo of_get_option('postad','none');?>
      </div>
<?php } ?>

<?php
if ( comments_open() || get_comments_number() ) :
  echo '<div class="post_comments">';
  comments_template();
  echo '</div>';
endif;
?>    

<?php endwhile; ?>      
    
    </div>
    
    <div class="column col-3 gd_aside"><?php get_sidebar(); ?></div>
  </div>
</div>

<?php 

  get_footer();
?>