<?php
if(isset($_GET['page'])){
  $page=intval($_GET['page']);
  require( '../../../../wp-load.php' );

  $args = array( 
      'post_type'=>'post',
      'post_status'=>'publish',
      'paged'=>$page,
      'orderby'=>'date',
      'order'=>'DESC',
  ); 
  if(isset($_GET['cat'])){
  	$cat=intval($_GET['cat']);
    if($cat!== 0){
      	if(gd_is_collection($cat)){
          	$cat = array($cat);
        	$ccc['relation']='AND';
          	$ccc[]=array(
              	'taxonomy' => 'collection',
            	'terms' =>  $cat
            );
            $args['tax_query']  = $ccc;
        }else{
        	$args['cat']=$cat;
        }
    }else{
      $notcat = array();
      foreach( (of_get_option('index_no_category','none')) as $key => $val){
          if($val == '1'){
              $notcat[] = $key;
          }
      }
      $args['category__not_in'] = $notcat;
    }
  }
  if(isset($_GET['tag'])){
  	$tag=intval($_GET['tag']);
    if($tag!=='0'){
    	$args['tag_id']=$tag;
    }
  }
  if(isset($_GET['s'])){
  	$s=intval($_GET['s']);
    if($s!==''){
    	$args['s']=$s;
    }
  }
  query_posts($args); 
  while ( have_posts() ) : the_post();
      include( GD_THEME_DIR.'/formats/default.php');
  endwhile;
  die();
}else{
	die();
}



?>