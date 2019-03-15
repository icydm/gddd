<?php
require( '../../../../wp-load.php' );
$emoji = of_get_option('emoji_textarea', 'none');
$emoji = explode(',', $emoji );
print json_encode( array('status'=>200,'emoji'=>$emoji) );
die();

?>