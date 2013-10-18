<?php
	//Homepage Sidebar
	register_sidebar(array(
		'name' => __('Homepage'),
		'id' => 'homepage-sidebar',
		'description' => __('Widgets in this sidebar will display on the website homepage'),
		'before_title' => '',
		'after_title' => ''
	));
	
	//Page Sidebar
	register_sidebar(array(
		'name' => __('Page'),
		'id' => 'page-sidebar',
		'description' => __('Widgets in this sidebar will display in the right-hand sidebar on pages'),
		'before_title' => '',
		'after_title' => ''
	));