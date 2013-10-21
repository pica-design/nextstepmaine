<?php
	/*
		 ____                            ____                                          
		/\  _`\   __                    /\  _`\                  __                    
		\ \ \L\ \/\_\    ___     __     \ \ \/\ \     __    ____/\_\     __     ___    
		 \ \ ,__/\/\ \  /'___\ /'__`\    \ \ \ \ \  /'__`\ /',__\/\ \  /'_ `\ /' _ `\  
		  \ \ \/  \ \ \/\ \__//\ \L\.\_   \ \ \_\ \/\  __//\__, `\ \ \/\ \L\ \/\ \/\ \ 
		   \ \_\   \ \_\ \____\ \__/.\_\   \ \____/\ \____\/\____/\ \_\ \____ \ \_\ \_\
			\/_/    \/_/\/____/\/__/\/_/    \/___/  \/____/\/___/  \/_/\/___L\ \/_/\/_/
																		 /\____/       
																		 \_/__/
																																					 
		Graphic Design & Marketing | www.picadesign.com
	*/
	
	//Theme Setup
	add_action( 'init', 'nextstepmaine_theme_setup' );
	function nextstepmaine_theme_setup() {
		//The theme namespace is used for menu of the operations below. 
		//The namespace NEEDS to mirror the name of the theme folder
		//And the primary js script file should also use this same namespace
		global $theme_namespace; $theme_namespace = 'nextstepmaine';

		//Load our psuedo-cdn/subdomain url generation
		include('inc/cdn-url-generation.php');
		//Instantiate our CDN url class
		//This variable with be used throughout the theme to point to website assets
		global $cdn;
		$cdn = new CDN(0) ;

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style('stylesheets/editor.css');		
	}//nextstepmaine_theme_setup

	//Helper Functions
	include('inc/helper-functions.php');
	//Load our WordPress Core Overrides
	include('inc/overrides.php');
	//wp_head() and wp_footer() scripts and styles
	include('inc/styles-scripts.php');
	//Menus
	include('inc/menus.php');
	//Classes
	include('inc/classes.php');
	//Custom Post Types
	include('inc/post-types.php');
	//Posts 2 Posts
	include('inc/connection-types.php');
	//WP-Admin Modifications
	include('inc/admin-pages.php');
	//Meta Boxes
	include('inc/meta-boxes.php');
	//Shortcodes
	include('inc/shortcodes.php');
	//Sidebars
	include('inc/sidebars.php');
	//Widgets
	include('inc/widgets.php');