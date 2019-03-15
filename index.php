<?php
get_header(); ?>

<div class="content">
  
  <?php do_action( 'index_content' );
  
if(of_get_option('index_cms_open', 'none')!=='1'){   ?>

<div class="js-masonryy">

<?php

   	$indexfPosts = new WP_Query();
  	$args = array();
  	$args['showposts']=get_option('posts_per_page',18);
  	
    $notcat = array();
    foreach( (of_get_option('index_no_category','none')) as $key => $val){
        if($val == '1'){
            $notcat[] = $key;
        }
    }
    $args['category__not_in'] = $notcat;
  
    $indexfPosts->query($args);
	$i = 0;
	while ($indexfPosts->have_posts()) : $indexfPosts->the_post();
  		get_template_part( 'formats/default','none');
	$i++;
	endwhile;

	wp_reset_query();

?>
	
</div>
  
  <button onclick="f_ajaxindex()" class="btn col-mx-auto in_load_more">加载更多</button>
<?php } ?>  
</div>

<?php get_footer(); ?>