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

	//Maine Employers Initiative Sidebar
	register_sidebar(array(
		'name' => __('MEI'),
		'id' => 'mei-sidebar',
		'description' => __('Widgets in this sidebar will display in the right-hand sidebar on the mei page.'),
		'before_title' => '',
		'after_title' => ''
	));