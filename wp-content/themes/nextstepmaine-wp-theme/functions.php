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
	
	
	/***********************************************************
		
		WORDPRESS CORE CUSTOMIZATIONS
		
	***********************************************************/
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
	
	
	
	/***********************************************************
		
		THEME SETUP
		
	***********************************************************/
	add_action( 'init', 'nextstepmaine_theme_setup' );
		
		//Adding thumbnail images into Posts
		add_theme_support( 'post-thumbnails', array('post', 'page'));
		
		//Additional attachment dimensions
		add_image_size('slideshow', 800, 287, true);
		
		function nextstepmaine_theme_setup() {
			// This theme styles the visual editor with editor-style.css to match the theme style.
			add_editor_style('styles/global-styles.css');		
			// This theme uses wp_nav_menu() in one location.
			register_nav_menus( array(
				'primary' => __( 'Masthead Navigation', 'nextstepmaine' ),
				'secondary' => __( 'Next Step', 'nextstepmaine' ),
				'tertiary' => __( 'Footer Navigation', 'nextstepmaine' )
			) );
		}
	
	
	
	/***********************************************************
		
		WORDPRESS GALLERY CUSTOMIZATIONS
		
	***********************************************************/
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
	
	//The Post Gallery class issues objects containing an post's attachment 'gallery' and returns an array of that data
	class Post_Gallery {
		public function __construct ($post_id) {
			//Make the current post global - !!!! This should be passed in, or at least the option to do so !!!!
			if (empty($post_id)) : global $post; $post_id = $post->ID ; endif;
			
			$this->attachments = new WP_Query( 
				array (
					'post_parent' => $post_id, 
					'post_status' => 'inherit', 
					'post_type' => 'attachment',
					'order' => 'ASC', 
					'orderby' => 'menu_order',
					'posts_per_page' => -1,
					'meta_query' => array(
						array(				
							'key' => '_attachment-exclude-from-gallery',
							'value' => 'off',
							'compare' => '='
						)
					)
				), ARRAY_A
			);

			//Make the attachments object a little cleaner by only using the data we want, the posts.
			//This also ensures our ->attachments variable holds and array (so we can easily display a random attachment on the website homepage
			$this->attachments = $this->attachments->posts;
			
			////Merge some additional attachment data into our main object
			foreach ($this->attachments as &$attachment) :
				//Grab the attachment's meta data
				$attachment->meta_data = get_post_custom($attachment->ID);
				//Some of our meta data needs to be unserialized to use it
				$attachment->meta_data['_wp_attachment_metadata'] = @unserialize($attachment->meta_data['_wp_attachment_metadata'][0]);
			endforeach;
			
			//Remove the array indecies (they do not help us)
			$this->attachments = array_values($this->attachments);
		}//End __construct
		
		
		public function has_attachments () {
			if (empty($this->attachments)) :
				return false;
			else :
				return true;
			endif;
		}
	}
		
?>