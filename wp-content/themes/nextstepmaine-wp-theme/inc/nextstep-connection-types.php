<?php
	function my_connection_types() {
		// Make sure the Posts 2 Posts plugin is active.
		if ( !function_exists( 'p2p_register_connection_type' ) )
			return;
	
		p2p_register_connection_type( array(
			'name' => 'Program Institution',
			'from' => 'nsm_program',
			'to' => 'nsm_institution'
		) );
	}
	add_action( 'wp_loaded', 'my_connection_types' );
?>