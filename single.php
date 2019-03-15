<?php
get_header(); ?>

<?php
	switch (get_post_type()) {
		case 'announcement':
			get_template_part( 'formats/single', 'announcement');
			break;
		default:
			get_template_part( 'formats/single', 'default');
			break;
	}

?>