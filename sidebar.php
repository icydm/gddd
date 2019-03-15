<aside id="secondary" class=" widget-area fd mobile-full-width">
	<div class="widget-area-in">
<?php
      if(is_singular('post')){
  		dynamic_sidebar( 'sidebar-1' );
      }
      if(is_page()){
  		dynamic_sidebar( 'sidebar-2' );
      }
      if(is_author()){
      	dynamic_sidebar( 'sidebar-3' );
      }
?>
	</div>
</aside><!-- #secondary -->