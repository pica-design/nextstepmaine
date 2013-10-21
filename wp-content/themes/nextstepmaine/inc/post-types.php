<?php
	include('post-types/nsm_job.php');
	include('post-types/nsm_faq.php');
	include('post-types/nsm_institution.php');
	include('post-types/nsm_program.php');
	include('post-types/attachment.php');
	// Define icon styles for the custom post types
	add_action( 'admin_enqueue_scripts', 'post_type_icons' );
	function post_type_icons() {
		wp_register_style('nextstep-admin-icons', $cdn->template_styles_url . "admin.css");
		wp_enqueue_style('nextstep-admin-icons');
	}