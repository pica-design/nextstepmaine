<?php
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Masthead Navigation', 'nextstepmaine' ),
		'secondary' => __( 'Next Step', 'nextstepmaine' ),
		'tertiary' => __( 'Footer Navigation', 'nextstepmaine' )
	) );