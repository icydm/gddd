<?php
/**
 *Template Name: 筛选页面
 *
 */
if($_GET){
	$paged = isset($_GET['pp']) ? intval($_GET['pp']) : '1';
	$args = array(
	  'post_type'=>'post',
	  'post_status'=>'publish',
	  'paged'=>$paged,
	  'orderby'=>'date',
	  'order'=>'DESC',
	);
  	if(isset($_GET['num'])){
    	$get = sanitize_user($_GET['sx']);
        $get = explode(',', $get );
        $num = 0;
      	$i=1;
        foreach($get as $a){
          	if($a!=='0'){
             if($i==1){
                  $ls=xifenlei($a)['num'];
                  $num = $ls;
                  $i++;
              }else{
                  $ls=xifenlei($a)['num'];
                  if((int)$ls<(int)$num){
                      $num = $ls;
                     
                  }
               }
            }            	
        } 
      	$nub = get_option('posts_per_page',18);
      	if($num==''){
        	$num = 0;
        }
		$pages_num = ceil($num / $nub);
      	echo $pages_num;
      die();
    }
  	if(isset($_GET['sx'])){
        $get = sanitize_user($_GET['sx']);
        $get = explode(',', $get );
        $are_t = array();
        $are_c = array();
        $are_z = array();
        foreach($get as $a){
            if(strpos($a,"a")!== false){
                $are_t[]=xifenlei($a)['id'];
            }
            if(strpos($a,"b")!== false){
                $are_c[]=xifenlei($a)['id'];
            }
            if(strpos($a,"c")!== false){
                $are_z[]=xifenlei($a)['id'];
            }
        }
        if(count($are_t)>0){
            $args['tag__in'] = $are_t;
        }
        if(count($are_c)>0){
            $args['cat'] = $are_c;
        }
        if(count($are_z)>0){
          	$ccc['relation']='AND';
          	$ccc[]=array(
              	'taxonomy' => 'collection',
            	'terms' =>  $are_z
            );
          	
            $args['tax_query']  = $ccc;
        }
        query_posts($args);
        while (have_posts()) : the_post();
             include( GD_THEME_DIR.'/formats/default.php');
        endwhile;
        wp_reset_query(); 
    }
die();
}
get_header();

$nub = get_option('posts_per_page',18);
$count_posts = wp_count_posts('post'); 
$publish_posts = $count_posts->publish;
$pages_num = ceil($publish_posts / $nub);

$shaixuan=of_get_option('shaixuan_textarea', 'none');
$shaixuan = explode(PHP_EOL, $shaixuan );
function xifenlei($a){
  	if($a=='0'){
    	return '';
    }
	if(strpos($a,"a")!== false){
		//标签
		$a = substr($a,1);
		$a = get_tag($a);
		$id = $a -> term_id;
		$name = $a -> name;
		return array(
			'id' => $id,
			'name' => $name,
          	'num'=> $a -> count
		);
	}
	if(strpos($a,"b")!== false){
		//分类
		$a = substr($a,1);
		$a = get_category($a);
		$id = $a -> term_id;
		$name = $a -> name;
		return array(
			'id' => $id,
			'name' => $name,
          	'num'=> $a -> count
          
		);
	}
	if(strpos($a,"c")!== false){
		//专题
		$a = substr($a,1);
		$a = get_term($a);
		$id = $a -> term_id;
		$name = $a -> name;
		return array(
			'id' => $id,
			'name' => $name,
          	'num'=> $a -> count
		);
	}
}
?>

<div class="content">
<ul class="gd_sx">
<?php
foreach($shaixuan as $a){
	$leibie = explode('|', $a );
	$xuanxiang = explode(',', $leibie[1] );
?>
	<li><span class="dalei"><?php echo $leibie[0]; ?>：</span>
      			<span onclick="gd_sx($(this))" class="xz bingo" id="0">全部</span>
		<?php foreach($xuanxiang as $b){ ?>
     		<span onclick="gd_sx($(this))" class="xz" id="<?php echo trim($b);?>"><?php echo xifenlei(trim($b))['name'];?></span>
		<?php } ?>
		
	</li>
<?php	
	}
?>


</ul>

<div class="js-masonryy">

<?php
	$args = array(
	  'post_type'=>'post',
	  'post_status'=>'publish',
	  'paged'=>'1',
	  'orderby'=>'date',
	  'order'=>'DESC',
	  'showposts='.get_option('posts_per_page',18),
	); 

   	$indexfPosts = new WP_Query();
    $indexfPosts->query($args);

	while ($indexfPosts->have_posts()) : $indexfPosts->the_post();

  		 include( GD_THEME_DIR.'/formats/default.php');

	endwhile;

	wp_reset_query();

?>

</div>
  
  <button onclick="f_ajaxindexx()" class="btn col-mx-auto iin_load_more">加载更多</button>
  <style>
    .gd_sx{
        list-style: none;
        width: 100%;
        background: white;
        padding: 18px;
        margin: 0 0 15px 0;
        box-shadow: 0 1px 3px 0 rgba(80,80,80,.11);
        border-radius: 8px;
    }
    .gd_sx li{
    	padding:5px;
    }
    .gd_sx li .xz{
    	padding:5px 10px;
        cursor: pointer;
      	display: inline-block;
    }
    .bingo{
    	background: #fb7299;
   		color: white;
    }
    .iin_load_more{
    padding: 10px;
    background: white;
    border: 0;
    color: #999999;
    border-radius: 8px;
    box-shadow: 0 1px 3px 0 rgba(80,80,80,.11);
  	outline:none;
  	min-width: 84px;
  	display: block;
 	margin-bottom: 15px;
    line-height: 0.8rem;
  	transition: all .2s;
}
.iin_load_more:focus,.iin_load_more:hover,.iin_load_more:active {
    box-shadow:none;
    background: white; 
    border: 0; 
 	color: #999999;
}
  </style>
  <script type="text/javascript"> 
var gd_sx_page='1';
var gd_sx_c='0';
var gd_sx_pn='<?php echo $pages_num;?>';
      $(function() {
          f_masonry();
          f_lazyload();
          if(gd_sx_pn==1){
    		$(".iin_load_more").hide();}
      });
    
function gd_sx(obj){
      var gd_sx_id = new Array();
      obj.parent().children().removeClass("bingo");
      obj.addClass("bingo");
      obj.addClass("loading");
      $(".gd_sx li span").each(function(){
        if($(this).hasClass("bingo")){
                gd_sx_id.push($(this).attr('id'));
            }
      });
      $a = gd_sx_id.join();
      gd_sx_c = $a;
      $.get( window.location.protocol+'//'+window.location.host+window.location.pathname+'?sx='+gd_sx_c+'&pp='+gd_sx_page, function( content ) {
        var $content = $( content );
        ajcontainer.html( $content ).masonry('destroy').masonry();
        f_masonry();
        f_lazyload();
      });
  	gd_array["ajaxpage"]='1';
  	  $.get( window.location.protocol+'//'+window.location.host+window.location.pathname+'?sx='+gd_sx_c+'&pp='+gd_sx_page+'&num=1', function( content ) {
		gd_sx_pn = content;
        if(gd_sx_pn == '1' || gd_sx_pn == '0'){
           $(".iin_load_more").hide();
        }else{
        	$(".iin_load_more").show();
        }
	   });
      obj.removeClass("loading");
      console.log($a);
}
    
function f_ajaxindexx(){
	gd_array["ajaxpage"]++;
  	$(".iin_load_more").addClass("loading");
	$.get( window.location.protocol+'//'+window.location.host+window.location.pathname+'?sx='+gd_sx_c+'&pp='+gd_array["ajaxpage"], function( content ) {
		var $content = $( content );
		ajcontainer.append( $content ).masonry( 'appended', $content );
      	f_masonry();
		f_lazyload();
      	$(".iin_load_more").removeClass("loading"); 
      	if(gd_array["ajaxpage"] == gd_sx_pn){
           $(".iin_load_more").hide(); 	
        }
    });
}
    
  </script>	
</div>

<?php get_footer(); ?>
