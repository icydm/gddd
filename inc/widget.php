<?php
class likecollectshare extends WP_Widget {
	function __construct() {
		parent::__construct( 
			'gd_lcs',
			"GD_点赞收藏分享",
			array (
				'description' => "点赞收藏分享"
			)
		);
	}
	
	function form( $instance ) {
		$default = array(
            'float' => '0',
            'mobile_hide'=>'0'
		);
		$float = isset($instance[ 'float' ]) ? $instance[ 'float' ] : $default['float'];
		$mobile_hide = isset($instance[ 'mobile_hide' ]) ? $instance[ 'mobile_hide' ] : $default[ 'mobile_hide' ];

		//后台选项
	?>
		   <p>
			   <label>是否浮动?</label>
			   <select name="<?php echo $this->get_field_name( 'float' ); ?>" id="<?php echo $this->get_field_id('float'); ?>">
				   <option value="1" <?php echo ($float == 1 ? 'selected="selected"' : ''); ?>>浮动</option>
				   <option value="0" <?php echo ($float == 0 ? 'selected="selected"' : ''); ?>>不浮动</option>
			   </select>
		   </p>
			
		   <p>
			   <label>移动端显示吗？</label>
			   <select name="<?php echo $this->get_field_name( 'mobile_hide' ); ?>" id="<?php echo $this->get_field_id('mobile_hide'); ?>">
				   <option value="1" <?php echo ($mobile_hide == 1 ? 'selected="selected"' : ''); ?>>显示</option>
				   <option value="0" <?php echo ($mobile_hide == 0 ? 'selected="selected"' : ''); ?>>不显示</option>
			   </select>
		   </p>
 
	<?php
	}
	
	function update( $new_instance, $old_instance ) {
			// 保存小工具选项
			$instance = $old_instance;
			$instance[ 'float' ] = strip_tags( $new_instance[ 'float' ] );
			$instance[ 'mobile_hide' ] = strip_tags( $new_instance[ 'mobile_hide' ] );
			return $instance;
	}
	
	function widget( $args, $instance ) {
		$default = array(
            'float' => '0',
            'mobile_hide'=>'0'
		);
		$float = isset($instance[ 'float' ]) ? $instance[ 'float' ] : $default['float'];
		$mobile_hide = isset($instance[ 'mobile_hide' ]) ? $instance[ 'mobile_hide' ] : $default[ 'mobile_hide' ];      
    
      	if(wp_is_mobile()&&$mobile_hide=='0' ){
      		return;
      	}

			extract( $args );

			echo $before_widget;
?>

<?php 
	$id = get_the_ID();

?>
<div class="dashboard">
	<ul class="columns">
		<li class="column col-6 like">
			<dl>
				<dt>
					<i id="wg-lcs-like" class="fa fa-thumbs-o-up fa-lg gd-color-hover <?php if(get_ta_like($id)){echo 'gd-color';} ?>" onclick="gd_post_like($(this))" data-post-id="<?php echo $id;?>"></i>
					<span><?php echo get_post_like_num_all($id) ?></span>
				</dt>
				<dd>
                  	<?php if(get_ta_like($id)){ ?> 
					<div class="text">已点赞</div>
                  	<?php }else{ ?>
                  	<div class="text">点赞</div>
                  	<?php } ?>
				</dd>
			</dl>
		</li>
		<li class="column col-3 collection">
			<dl>
				<dt>
					<i id="wg-lcs-collect" class="iconfont icon-gd_shoucang fa-lg gd-color-hover <?php if(get_ta_collect($id)){echo 'gd-color';} ?>" onclick="gd_post_collect($(this))" data-post-id="<?php echo $id;?>"></i>
				</dt>
				<dd>
                  	<?php if(get_ta_collect($id)){ ?> 
					<div class="text">已收藏</div>
                  	<?php }else{ ?>
                  	<div class="text">收藏</div>
                  	<?php } ?>
				</dd>
			</dl>
		</li>
		<li class="column col-3 share">
			<dl>
				<dt>
					<i onclick="openfx()" id="wg-lcs-share" class="fa fa-share-square-o fa-lg gd-color-hover"></i>
				</dt>
				<dd>
					<div class="text">分享</div>
				</dd>
			</dl>			
		</li>
	</ul>
</div>
<?php if($float=='1' ){ ?>
<script>
  $(document).ready(function(){
    $(".widget_gd_lcs").sticky({topSpacing:10});
  });
</script>
<?php } ?>

<?php	
			echo $after_widget;
	}

}
class gd_auther_info extends WP_Widget {
	function __construct() {
		parent::__construct( 
			'gd_auther_info',
			"GD_作者信息",
			array (
				'description' => "文章页使用"
			)
		);
	}
	
	function form( $instance ) {
		$default = array(
            'float' => '0',
            'mobile_hide'=>'0'
		);
		$float = isset($instance[ 'float' ]) ? $instance[ 'float' ] : $default['float'];
		$mobile_hide = isset($instance[ 'mobile_hide' ]) ? $instance[ 'mobile_hide' ] : $default[ 'mobile_hide' ];

		//后台选项
	?>
		   <p>
			   <label>是否浮动?</label>
			   <select name="<?php echo $this->get_field_name( 'float' ); ?>" id="<?php echo $this->get_field_id('float'); ?>">
				   <option value="1" <?php echo ($float == 1 ? 'selected="selected"' : ''); ?>>浮动</option>
				   <option value="0" <?php echo ($float == 0 ? 'selected="selected"' : ''); ?>>不浮动</option>
			   </select>
		   </p>
			
		   <p>
			   <label>移动端显示吗？</label>
			   <select name="<?php echo $this->get_field_name( 'mobile_hide' ); ?>" id="<?php echo $this->get_field_id('mobile_hide'); ?>">
				   <option value="1" <?php echo ($mobile_hide == 1 ? 'selected="selected"' : ''); ?>>显示</option>
				   <option value="0" <?php echo ($mobile_hide == 0 ? 'selected="selected"' : ''); ?>>不显示</option>
			   </select>
		   </p>
 
	<?php
	}
	
	function update( $new_instance, $old_instance ) {
			// 保存小工具选项
			$instance = $old_instance;
			$instance[ 'float' ] = strip_tags( $new_instance[ 'float' ] );
			$instance[ 'mobile_hide' ] = strip_tags( $new_instance[ 'mobile_hide' ] );
			return $instance;
	}
	
	function widget( $args, $instance ) {
		$default = array(
            'float' => '0',
            'mobile_hide'=>'0'
		);
		$float = isset($instance[ 'float' ]) ? $instance[ 'float' ] : $default['float'];
		$mobile_hide = isset($instance[ 'mobile_hide' ]) ? $instance[ 'mobile_hide' ] : $default[ 'mobile_hide' ];      
    
      	if(wp_is_mobile()&&$mobile_hide=='0' ){
      		return;
      	}

			extract( $args );

			echo $before_widget;
?>

<?php 
	$id = get_the_ID();
	$auther_id = get_the_author_meta( 'ID' );;
?>
<div class="author-info">
	<a href="<?php echo gd_get_user_page_url($auther_id,$type = '');?>" target="_blank">
		<div class="author-avatar" style="background-image: url('<?php echo gd_get_user_meta_message($auther_id,'avatar');?>');">
		</div>
	</a>
	<div class="info-cent">
		<div class="user-level-ctnr">
			<i style="color: #fb7299;">lv<?php echo gd_get_user_meta_message($auther_id,'lv');?></i>
		</div>
		<a href="<?php echo gd_get_user_page_url($auther_id,$type = '');?>" target="_blank">
			<span class="author-name"><?php echo gd_get_user_message($auther_id,'display_name');?></span>
		</a>
	</div>
	<small class="supporting-info dp-block">
		<a href="<?php echo gd_get_user_page_url($auther_id,'follow'); ?>" target="_blank" class="link-pink">
			<span> 关注 <?php echo count(gd_get_user_meta_message($auther_id,'follow'));?></span>
		</a>
		<span class="auther-divider"></span>
		<a href="<?php echo gd_get_user_page_url($auther_id,'fans'); ?>" target="_blank" class="link-pink">
			<span> 粉丝 <?php echo count(gd_get_user_meta_message($auther_id,'fans'));?></span></a>
	</small>
	<div class="favourite-btn-ctnr">
      <div id="guanzhu"><?php echo gd_get_user_followbutton($auther_id); ?></div>
      <div id="faxinxi"><?php echo gd_get_user_msgbutton($auther_id); ?></div>
	</div>
</div>
<?php if($float=='1' ){ ?>
<script>
  $(document).ready(function(){
    $("#gd_auther_info-2").sticky({topSpacing:10});
  });
</script>
<?php } ?>

<?php	
			echo $after_widget;
	}

}
class gdrandpost extends WP_Widget {
	function __construct() {
		parent::__construct( 
			'gd_randpost',
			"GD_随机文章",
			array (
				'description' => "随机文章"
			)
		);
	}
	
	function form( $instance ) {
		$default = array(
            'float' => '0',
            'mobile_hide'=>'0'
		);
		$float = isset($instance[ 'float' ]) ? $instance[ 'float' ] : $default['float'];
		$mobile_hide = isset($instance[ 'mobile_hide' ]) ? $instance[ 'mobile_hide' ] : $default[ 'mobile_hide' ];

		//后台选项
	?>
		   <p>
			   <label>是否浮动?</label>
			   <select name="<?php echo $this->get_field_name( 'float' ); ?>" id="<?php echo $this->get_field_id('float'); ?>">
				   <option value="1" <?php echo ($float == 1 ? 'selected="selected"' : ''); ?>>浮动</option>
				   <option value="0" <?php echo ($float == 0 ? 'selected="selected"' : ''); ?>>不浮动</option>
			   </select>
		   </p>
			
		   <p>
			   <label>移动端显示吗？</label>
			   <select name="<?php echo $this->get_field_name( 'mobile_hide' ); ?>" id="<?php echo $this->get_field_id('mobile_hide'); ?>">
				   <option value="1" <?php echo ($mobile_hide == 1 ? 'selected="selected"' : ''); ?>>显示</option>
				   <option value="0" <?php echo ($mobile_hide == 0 ? 'selected="selected"' : ''); ?>>不显示</option>
			   </select>
		   </p>
 
	<?php
	}
	
	function update( $new_instance, $old_instance ) {
			// 保存小工具选项
			$instance = $old_instance;
			$instance[ 'float' ] = strip_tags( $new_instance[ 'float' ] );
			$instance[ 'mobile_hide' ] = strip_tags( $new_instance[ 'mobile_hide' ] );
			return $instance;
	}
	
	function widget( $args, $instance ) {
		$default = array(
            'float' => '0',
            'mobile_hide'=>'0'
		);
		$float = isset($instance[ 'float' ]) ? $instance[ 'float' ] : $default['float'];
		$mobile_hide = isset($instance[ 'mobile_hide' ]) ? $instance[ 'mobile_hide' ] : $default[ 'mobile_hide' ];      
    
      	if(wp_is_mobile()&&$mobile_hide=='0' ){
      		return;
      	}

			extract( $args );

			echo $before_widget;
?>

<div style="position: relative;">
  <small style="margin-left: 8px;display: inline-block;;padding: 5px 0;">随机文章</small>
  <i onclick="randpostadd()" class="fa fa-refresh" style="position: absolute;right: 0;padding: 5px;cursor: pointer;"></i>
  <div class="randpost-content">
  
  </div>
<script>
$(document).ready(function(){
	randpostadd();
});
function randpostadd(){
  $link = gd_array["site_url"]+'/wp-content/themes/gddd/api/ajax-rand.php';
  $.get($link, function(result){
      $a =JSON.parse(result);
      $('.randpost-content').html($a['msg']).hide().fadeIn('slow');
  });  
}
</script>
</div>
<?php if($float=='1' ){ ?>
<script>
  $(document).ready(function(){
    $(".widget_gd_randpost").sticky({topSpacing:10});
  });
</script>
<?php } ?>

<?php	
			echo $after_widget;
	}

}

class gddown extends WP_Widget {
    function __construct() {
        parent::__construct( 
            'gd_down',
            "GD_下载",
            array (
                'description' => "下载小部件"
            )
        );
    }
    
    function form( $instance ) {
        $default = array(
            'float' => '0',
            'mobile_hide'=>'0'
        );
        $float = isset($instance[ 'float' ]) ? $instance[ 'float' ] : $default['float'];
        $mobile_hide = isset($instance[ 'mobile_hide' ]) ? $instance[ 'mobile_hide' ] : $default[ 'mobile_hide' ];

        //后台选项
    ?>
           <p>
               <label>是否浮动?</label>
               <select name="<?php echo $this->get_field_name( 'float' ); ?>" id="<?php echo $this->get_field_id('float'); ?>">
                   <option value="1" <?php echo ($float == 1 ? 'selected="selected"' : ''); ?>>浮动</option>
                   <option value="0" <?php echo ($float == 0 ? 'selected="selected"' : ''); ?>>不浮动</option>
               </select>
           </p>
            
           <p>
               <label>移动端显示吗？</label>
               <select name="<?php echo $this->get_field_name( 'mobile_hide' ); ?>" id="<?php echo $this->get_field_id('mobile_hide'); ?>">
                   <option value="1" <?php echo ($mobile_hide == 1 ? 'selected="selected"' : ''); ?>>显示</option>
                   <option value="0" <?php echo ($mobile_hide == 0 ? 'selected="selected"' : ''); ?>>不显示</option>
               </select>
           </p>
 
    <?php
    }
    
    function update( $new_instance, $old_instance ) {
            // 保存小工具选项
            $instance = $old_instance;
            $instance[ 'float' ] = strip_tags( $new_instance[ 'float' ] );
            $instance[ 'mobile_hide' ] = strip_tags( $new_instance[ 'mobile_hide' ] );
            return $instance;
    }
    
    function widget( $args, $instance ) {
        $default = array(
            'float' => '0',
            'mobile_hide'=>'0'
        );
        $float = isset($instance[ 'float' ]) ? $instance[ 'float' ] : $default['float'];
        $mobile_hide = isset($instance[ 'mobile_hide' ]) ? $instance[ 'mobile_hide' ] : $default[ 'mobile_hide' ];      
    
        if(wp_is_mobile()&&$mobile_hide=='0' ){
            return;
        }

            extract( $args );

            echo $before_widget;
?>

<div style="position: relative;">
  	<h6 style="padding: 10px 0;text-align: center;">文章资源信息</h6>
    <button class="btn gd-color" style="width:100%;">立刻下载</button>
  	<ul>
      <li>资源名称：</li>
      <li>提取码：</li>
      <li>解压码：</li>
  	</ul>
</div>
<?php if($float=='1' ){ ?>
<script>
  $(document).ready(function(){
    $(".widget_gd_down").sticky({topSpacing:10});
  });
</script>
<?php } ?>

<?php   
    echo $after_widget;
    }

}

function likecollectshare_register_widgets() {
    register_widget( 'likecollectshare' );
    register_widget( 'gd_auther_info' );
    register_widget( 'gdrandpost' );
  	//register_widget( 'gddown' );
}
add_action( 'widgets_init', 'likecollectshare_register_widgets' );

?>