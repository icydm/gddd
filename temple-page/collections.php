<?php
get_header(); ?>

<div class="content">
			<?php
  			if(wp_is_mobile()){
            	$flex = 'col-12';
            }else{
            	$flex = 'col-6';
            }
            $taxonomies = get_terms( array(
            'taxonomy' => 'collection',
			'hide_empty' => false,
			'order'=>'DESC',
			'orderby'=>'count'
            ) );
			if ( !empty($taxonomies) ) :
            $html = '<ul class="collection-content columns">';
                foreach ($taxonomies as $key => $val) {
                    $html .= '
<li class="collection-list column '.$flex.'">
	<dl class="collection-list-back">
		<a href="'.esc_url(get_term_link($val->term_id)).'" target="_blank">
			<dt>
				<div class="collection-list-img" data-ratio="2:1" style="background-image: url('.get_term_meta($val->term_id,'back',true).');">
				</div>
			</dt>
		</a>
		<dd status-onging="已更新'.$val->count.'篇" class="collection-list-state">
			<h4>
				<a href="'.esc_url(get_term_link($val->term_id)).'" target="_blank">'.$val->name.'</a>
			</h4>
			<span>'.($val->description ? $val->description : '没有说明').'</span>
		</dd>
	</dl>
</li>
';
                }
            echo $html.'</ul>';
			else :
				echo '<div>
					<p>没有专栏，请创建一个！</p>
				</div>';
			endif;
            ?>
</div>
<?php
get_footer();
