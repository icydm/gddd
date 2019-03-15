<footer class="footer mt-2">
	<div class="foot-box">
		<!---->
        <div class="foot-nav">
			<?php wp_nav_menu( array( 'theme_location'=>'footer-menu', 'depth' =>1, 'container_class' => 'foot_menu_parent','menu_class'   => 'foot_menu_menu',) ); ?>      
        </div>
		<!---->
        <i class="cl"></i>
      	<div class="friend-links">
      <?php
          if ( is_front_page() || is_home() ){
          	 $footlinkid = of_get_option('foot_link_id','');
          	 if($footlinkid!==''){
               	$bookmarks = get_bookmarks(array(
                'category'=>$footlinkid,
                'orderby'=>'link_rating',
                'order'=>'DESC'
            	));
                if ( !empty($bookmarks) ) {
                  	$linkcount = count($bookmarks)-1;
                  	$countkey = 0;
                    ?><div class="footer-links"  data-flickity='{ "prevNextButtons": false,"pageDots": false,"cellAlign": "left", "contain": true }'><?php
                        foreach ($bookmarks as $bookmark) {
                            echo '<a target="_blank" href="' . $bookmark->link_url . '">' . $bookmark->link_name . '</a>';
                          	    if ($countkey !== $linkcount){
                                    echo "<span>·</span>";
                                }
                          	$countkey++;
                        }
                    echo '</div>';
                }
             }
          }
      ?>
      
      	</div>
        <div class="foot-copyright">
            <p><?php echo of_get_option('foot_msg','none');?></p>
         	<p>Build with <b class="gd-color">♥</b> by <a href="#" target="_blank" style="color:black;">摸鱼 </a></p>
          	<p><?php echo get_num_queries().' queries in '.timer_stop(0,3).' seconds ';?></p>
        </div>
          
    <div id="asidebutton" class="asidebutton">
      <?php if(is_user_logged_in()){ ?>
      <a href="javascript:;" @click="qiandan" is-dot><i class="iconfont icon-Sign icon-lg"></i></a>
      <?php } ?>
      <a href="javascript:;" @click="backtop"><i class="iconfont icon-top icon-lg"></i></a>
    </div>
          
    </div>
	

</footer>


  
<?php wp_footer(); ?>

</body>
</html>
