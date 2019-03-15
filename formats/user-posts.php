<?php
global $wp_query;
$nub = get_option('posts_per_page');
$ipages = ceil( $wp_query->found_posts / $nub);
$user_id =  get_query_var('author');
$current_user = get_current_user_id();
if ( have_posts() ) :

    while ( have_posts() ) : the_post();
        if($user_id == $current_user || current_user_can('delete_users')){
            get_template_part( 'formats/cms','revise');
        }else{
            get_template_part( 'formats/cms','none');
        }
    endwhile;



else :

    echo '<p class="mar10-t">没有文章</p>';

endif;
?>
