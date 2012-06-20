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
	
	
	/************************
			OVERRIDES
	************************/
	remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
	remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
	remove_action( 'wp_head', 'index_rel_link' ); // index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // Display relational links for the posts adjacent to the current post.
	remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
	remove_action( 'wp_head', 'rel_canonical'); //Remove the wp canonical url
	add_filter( 'next_post_rel_link', 'disable_stuff' );
	function disable_stuff( $data ) { return false; }
	
	
	//THIS SHOULD ONLY BE ON THE STEP PAGES?!
		//Would rather sort out why autop is messing up my shortcodes
	remove_filter( 'the_content', 'wpautop' );
	
	
	/************************
			SETUP
	************************/
	add_action( 'init', 'nextstepmaine_theme_setup' );
		
		//Adding thumbnail images into Posts
		add_theme_support( 'post-thumbnails', array('post', 'page'));
		
		//Additional attachment dimensions
		add_image_size('slideshow', 800, 287, true);
		
		/************************
   		 CUSTOM QUERY VARIABLES
		************************/
		//Register our custom $_GET variable (aka query var, aka rewrite tag) ?prog_edu_lvl=foo
		add_rewrite_tag('%prog_edu_lvl%', '([^&]+)');
		//Create the rewrite write rule to convert site.com/programs/foo to site.com/programs/?prog_edu_lvl=foo 
		add_rewrite_rule('^programs/([^/]*)/?', 'index.php?pagename=programs&prog_edu_lvl=$matches[1]', 'top');
		
		function nextstepmaine_theme_setup() {
			// This theme styles the visual editor with editor-style.css to match the theme style.
			add_editor_style('styles/global-styles.css');		
			// This theme uses wp_nav_menu() in one location.
			register_nav_menus( array(
				'primary' => __( 'Masthead Navigation', 'nextstepmaine' ),
				'secondary' => __( 'Next Step', 'nextstepmaine' ),
				'tertiary' => __( 'Footer Navigation', 'nextstepmaine' )
			) );
			
			/************************
				CUSTOM POST TYPES
			************************/
			include('inc/nextstep-post-types.php') ;
			
			/************************
				Posts 2 Posts Connection Types
			************************/
			include('inc/nextstep-connection-types.php') ;
			
			/************************
				CUSTOM ADMIN PAGES
			************************/
			include('inc/nextstep-admin-pages.php') ;
			
			/************************
				CUSTOM META BOXES
			************************/
			include('inc/nextstep-meta-boxes.php') ;
		}
		
		
	/************************
			CLASSES
	************************/
	include('inc/nextstep-classes.php') ;
	
	
	/************************
			GALLERY
	************************/
	/* Adding custom attachment fields */
	add_filter("attachment_fields_to_edit", "post_attachment_new_fields", null, 2);
	/* Save custom attachment fields on update */
	add_filter("attachment_fields_to_save", "update_post_attachment_new_fields", null , 2);
	
	/* Adding custom attachment fields */
	function post_attachment_new_fields ($form_fields, $post) {
		$form_fields["attachment-exclude-from-gallery"] = array(
			"label" => __("Exclude"),
			"input" => "html",
			"html"  => "<input type='checkbox' name='attachments[$post->ID][attachment-exclude-from-gallery]' " . checked( get_post_meta($post->ID, "_attachment-exclude-from-gallery", true), 'on', 0 ) . " /> &nbsp;" . __("Don't show this attachment in the gallery.")
		);
	   return $form_fields;
	}
	
	/* Save custom attachment fields */ 
	function update_post_attachment_new_fields ($post, $attachment) {
		if (isset($attachment['attachment-exclude-from-gallery'])) : 
			update_post_meta($post['ID'], '_attachment-exclude-from-gallery', $attachment['attachment-exclude-from-gallery']);
		else :
			update_post_meta($post['ID'], '_attachment-exclude-from-gallery', 'off');
		endif;
		return $post;
	}
	
	
	/************************
			SIDEBARS
	************************/
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
	
	
	/************************
			WIDGETS
	************************/
	//Include our widgets
	include('inc/nextstep-widgets.php') ;
	
	//Register our widgets
	add_action( 'widgets_init', 'register_widgets');
	
	function register_widgets () {
		register_widget( "NEXTSTEP_Financial_Aid_Widget" );
		register_widget( "NEXTSTEP_Jobs_in_Demand_Widget" );
		register_widget( "NEXTSTEP_Institutions_Widget" );
	};
	
	
	/************************
			SHORTCODES
	************************/
	add_shortcode('accordion', 'generate_accordion_content');
	function generate_accordion_content ($atts, $content) {
		$html_str  = "<div class='accordion closed'>";
		$html_str .= "<div class='title'>{$atts['title']}</div>";
		$html_str .= "<div class='content'>$content</div>";
		$html_str .= "</div>";
		return $html_str;
	}
	
?>