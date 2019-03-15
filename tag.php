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

<button onclick="f_ajaxindex_cat()" class="btn col-mx-auto in_load_more">加载更多</button>
<script type="text/javascript">
function f_ajaxindex_cat(){
	gd_array["ajaxpage"]++;
  	$(".in_load_more").addClass("loading");
	$.get(gd_array["site_url"] + '/wp-content/themes/gddd/api/ajax-page-post.php?page='+gd_array["ajaxpage"]+'&tag=<?php echo get_queried_object_id(); ?>', function( content ) {
		var $content = $( content );
		ajcontainer.append( $content ).masonry( 'appended', $content );
      	f_masonry();
		f_lazyload();
      	$(".in_load_more").removeClass("loading"); 
      	if(gd_array["ajaxpage"] == gd_array["pages_num"]){
           $(".in_load_more").hide(); 	
        }
    });
}
</script>
</div>

<?php get_footer(); ?>