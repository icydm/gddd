<?php

add_action('index_content','gd_index_banner');//首页banner
function gd_index_banner(){
	$banner = of_get_option('banner_textarea', 'none');
    if($banner){
    	?><div class="carousel gd-banner"><?php
    	$banner = explode(PHP_EOL, $banner );
    	foreach($banner as $a){
			$b = explode('|', $a );
          	?><div class="carousel-cell"><a href="<?php echo $b[0]; ?>" target="_blank"><div class="carousel-image" style="background-image:url(<?php echo $b[1]; ?>);"></div></a></div><?php
      	}
     	echo '</div> ';        
    }
}


add_action( 'index_content','gd_index_zhaunti');//首页显示专题
function gd_index_zhaunti(){
  $zhaunti = of_get_option('zhuanti_textarea', 'none');
  if($zhaunti){ ?>
  	<div class="cen-hot"><div class="hot-zhuanti"><h4 class="cen-hot-title"><a href="<?php echo home_url('/collections'); ?> " target="_blank" >热门专题</a></h4><ul class="cen-hot-card" data-flickity='{ "freeScroll": true, "contain": true,"wrapAround": true, "pageDots": false ,"prevNextButtons": false}'>
      
      <?php
    $zhaunti = explode(PHP_EOL, $zhaunti );
    	foreach($zhaunti as $a){
			$b = explode('|', $a );
          	?>
				<li >
					<a href="<?php echo get_category_link($b[0]);?>" target="_blank">
						<div class="cen-hot-card-img">
							<div class="image" style="background-image: url('<?php echo $b[1];?>');"></div>
						<div class="status">
							<div class="word"><?php echo $b[2];?></div>
						</div>
						</div>
					</a>
				</li>
			<?php
      	}
    echo '</ul></div>';                                  
                                      
    echo '</div>';
  }
}

if(of_get_option('index_cms_open', 'none')=='1'){
	add_action( 'index_content' ,'gd_index_cms');//cms
}
function gd_index_cms(){
  if(of_get_option('index_cms_open', 'none')=='1'){
      add_filter( 'the_content', 'gd_image_alt');
  }

  $cmsoption = of_get_option('index_cms_option', 'none');
  if($cmsoption!==''){
      $cmsoption = explode(PHP_EOL, $cmsoption );
  }else{
      return;
  }
  echo '<div class="cms_content">';
  foreach($cmsoption as $a ){
    $a = explode("|",$a);
    ?>
      <div class="cms_ul">
        <h4 class="cms-title">
          <a href="<?php echo $a[2]; ?>" target="_blank"><?php echo $a[3]; ?></a>
        </h4>
        <?php
      $args = array( 
              'post_type'=>'post',
              'post_status'=>'publish',
              'orderby'=>'date',
              'order'=>'DESC'
          );
      if($a[0]=='1'){
          $args['cat'] = $a[1];
          $args['posts_per_page'] = '6';
          $args[ 'paged'] = '1';
	 
      }elseif($a[0]=='2'){
          $postarray = explode(",",$a[1]);
       	  $args['post__in'] = $postarray;
	
      }
    ?><div class="cms_li columns"><?php
   	$cmsPosts = new WP_Query();
    $cmsPosts->query($args);

	while ($cmsPosts->have_posts()) : $cmsPosts->the_post();
            get_template_part( 'formats/cms','none');
      endwhile;
      wp_reset_postdata();    

    
    echo '</div></div>';
  }
  echo '</div>';
}

if(of_get_option('index_cms_open', 'none')!=='1'){
	add_action( 'index_content' ,'gd_index_cagegory');//菜单
}
function gd_index_cagegory(){
	$category = of_get_option('index_ajax_category', 'none');
	echo '<div class="select-dapubua"><ul><li class="activated" onclick="window.location.href=gd_array.site_url"><span>推荐</span></li>';
	if ( is_array( $category ) ) {
		foreach ( $category as $key => $value ) {
			if($value=='1'){
             	$a=get_the_category_by_ID($key);
		    	echo '<li onclick="f_index_cat($(this))" cat="'.$key.'"><span>'.$a.'</span></li>';
		    }
		}
	}
	echo '</ul></div>';
}



add_action( 'gd_body_header' ,'gd_body_header_menu');//顶部
function gd_body_header_menu(){
  $logo_url = of_get_option('web_logo', 'none');
?>
<header class="gd-header">
	<div class="navbar gd-header-logo">
      
		<div class="am-topbar-brand">
			<h1 class="head-logo"><?php if($logo_url){echo '<a  style="display:block;" href="'.home_url().'"><img src="'.$logo_url.'"></a>';}?></h1>
          
          	<?php wp_nav_menu( array( 'theme_location'=>'PrimaryMenu', 'depth' =>2, 'container_class' => 'head_menu_parent','menu_class'   => 'head_menu_menu',) ); ?>
		</div>
      
      	<div class="am-topbar-brand head-search navbar-section">

          <?php if(!wp_is_mobile()){?>
          
          <span style="margin-right: 20px;"><i class="fa fa-volume-down fa-lg gonggaoi" onclick="$('.gonggao').toggle()"></i>
          <div class="timeline gonggao" hidden>
<?php
            $args = array( 
              'post_type'=>'announcement',
              'post_status'=>'publish',
              'orderby'=>'date',
              'order'=>'DESC',
              'posts_per_page'=>'6',
            );
			$the_query = new WP_Query($args);
            while ( $the_query->have_posts() ){
            $the_query->the_post();
?>
            <div class="timeline-item">
              <div class="timeline-left">
                <i class="timeline-icon "></i>
              </div>
				<div class="tile-content">
                  <span class="tile-subtitle tooltip" data-tooltip="<?php the_time('Y 年 n 月 j 日'); ?>"><a href="<?php echo get_permalink(); ?>" target="_blank"><?php the_title(); ?></a></span>
                </div>
            </div>
<?php
            }wp_reset_postdata(); 
?>
		
          </div>
          </span>
          
          <span style="margin-right: 10px;"><i class="fa fa-search fa-lg" onclick="openmodal('search-modal')"></i></span>
          <div class="modal modal-sm" id="search-modal">
            <a class="modal-overlay" onclick="openmodal('search-modal')"></a>
            <div class="modal-container">
                   <form action="<?php echo home_url();?>" method="get" target="_blank" class="cse-form">
                      <input type="text" name="s" value="" class="input-search" autocomplete="off">
                  </form>
            </div>
          </div>
          <?php } ?>
          <?php if(wp_is_mobile()){?>
          <span><div style="border-radius: 50%;background: #fb7299;border-color: #fb7299;" class="off-canvas-toggle btn btn-primary btn-action"><i class="fa fa-align-justify fa-lg" onclick="openmodal('sidebar-id')"></i></div></span>
          <div class="off-canvas" style="width:0;">
          <div id="sidebar-id" class="off-canvas-sidebar">
              <form action="<?php echo home_url();?>" method="get" target="_blank" class="cse-form">
              		<input style="height: 46px;border-radius: 0;" type="text" name="s" value="" class="input-search" placeholder="Search" autocomplete="off">
              </form>
              <?php wp_nav_menu( array( 'theme_location'=>'PrimaryMenu', 'depth' =>2, 'container_class' => 'head_menu_parent_mobile','menu_class'   => 'head_menu_menu_mobile',) ); ?>
          </div>
          <a class="off-canvas-overlay" onclick="openmodal('sidebar-id')"></a>
		  </div>
          <?php } ?>
          
           <?php if(is_user_logged_in()){ ?>
    	  <span class="head-person">
            <figure <?php $message_unread = gd_check_uread_message(wp_get_current_user()->ID)+gd_check_uread_noti(wp_get_current_user()->ID);if($message_unread >0){echo 'class="avatar badge" data-badge="'.$message_unread.'"';}else{echo 'class="avatar"';} ?> onclick="$('.menu').toggle()">
              <img src="<?php echo gd_get_user_meta_message(wp_get_current_user()->ID,'avatar');?>">
            </figure>
            <ul class="menu" hidden>
                  <li class="menu-item">
                    <div class="tile tile-centered">
                      
                        <div class="tile-icon"><img class="avatar" src="<?php echo gd_get_user_meta_message(wp_get_current_user()->ID,'avatar');?>" alt="Avatar"></div>
                        <div class="tile-content"> <a href="<?php echo gd_get_user_page_url(0); ?>"> <?php echo gd_get_user_dname();?></a></div>
                    
                    </div>
                  </li>
                  <li class="divider"></li>
                  <li class="menu-item">
                    <div class="menu-badge">
                      <?php if($message_unread >0){echo '<label class="label label-primary">'.$message_unread.'</label>';} ?>
                    </div>
                    <a href="<?php echo home_url('/notification');?>">消息</a>
                  </li>
              	  <li class="menu-item"><a href="<?php echo home_url('/setting');?>">设置</a></li>
              	  <li class="menu-item"><a href="<?php echo home_url('/gold');?>">财富</a></li>
              	  <li class="menu-item"><a href="<?php echo home_url('/vip');?>">VIP</a></li>
              	  <?php if(is_super_admin()){ ?>
              	  <li class="menu-item"><a href="<?php echo home_url('/wp-admin/index.php');?>">进入后台</a></li>
              	  <?php } ?>
                  <li class="menu-item"><a href="<?php echo wp_logout_url(home_url()); ?>" title="Logout">登出</a></li>
              </ul>
          </span>
  				
  		  <?php }else{
    		echo '<span><a class="head-login" href="'.home_url('/login').'">登录</a></span>';
			 if(get_option('users_can_register')){//是否允许注册
			 	echo '<span><a class="head-login" href="'.home_url('/sign').'">注册</a></span>';
			 }
  		  }?> 
          
      	</div>   
	</div>
</header>
<?php
}



























?>