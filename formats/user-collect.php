<?php
global $wp_query;
$nub = get_option('posts_per_page');
$ipages = ceil( $wp_query->found_posts / $nub);
$user_id =  get_query_var('author');
$post_id =  get_query_var('ID');
$current_user = get_current_user_id();
//var_dump( $wp_query);
$key = 'i_collect';
$value = get_user_meta( $user_id, $key,true);
if($value){
    $value = explode(",",$value);
    foreach($value as $postid){

            if ( have_posts() ) :

                while ( have_posts() ) : the_post();
                    if(get_the_ID() == $postid){
                        get_template_part( 'formats/cms','none');
                    }
                endwhile;


            else :

                echo '<p class="mar10-t">没有文章</p>';

            endif;

    }
}else{
	echo '<p class="mar10-t">没有文章</p>';
}

?>