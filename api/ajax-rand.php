<?php
require( '../../../../wp-load.php' );
$rand_post=get_posts('numberposts=1&orderby=rand');
$id = $rand_post[0]->ID;
$title = $rand_post[0]->post_title;
$link = get_the_permalink($id);
$thumb = gd_get_post_thumb($id);
$html = '<a href="'.$link.'"><img src="'.$thumb.'" style="width: 100%;"><h2 style="font-size: 15px;color: #666666;padding: 10px;">'.$title.'</h2></a>';
print json_encode( array('status'=>200,'msg'=>$html) );
die();
?>