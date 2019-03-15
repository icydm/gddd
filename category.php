<?php
get_header(); ?>

<div class="content">

<ul class="gd_category">
	<li>
		<span><?php single_cat_title(); ?></span>
	</li>
</ul>

<div class="js-masonryy">

<?php
  
	if (have_posts()) : while (have_posts()) : the_post();

  		get_template_part( 'formats/default','none'); 

	endwhile;
	endif;
?>


</div>
  
  <button onclick="f_ajaxindex()" class="btn col-mx-auto in_load_more">加载更多</button>

</div>

<?php get_footer(); ?>