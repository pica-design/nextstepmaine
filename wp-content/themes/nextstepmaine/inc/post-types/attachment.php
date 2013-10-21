<?php
	/***********************************************************
		
		WORDPRESS ATTACHMENT CUSTOMIZATIONS
		
	***********************************************************/
	//Adding thumbnail images into Posts
	add_theme_support( 'post-thumbnails', array('nsm_institution'));
	//Additional attachment dimensions
	add_image_size('slideshow', 800, 287, true);

	//Assign new attachments the proper 'Attachment_Use' taxonomy term
	add_action('add_attachment', array('Post_Attachments', 'add_attachment'), 10, 1);
	/* Adding custom attachment fields */
	add_filter("attachment_fields_to_edit", array('Post_Attachments', 'edit_attachment_fields'), 10, 2);
	//Save our custom attachment fields in the post editor - Each filter below serves a purpose
	//Attachment Fields to Save, when editing an attachment post in the media library
	add_filter("attachment_fields_to_save", array('Post_Attachments', 'save_attachment_fields'), 10, 2);
	//Attachment Fields to Save, when editing an attachment post in the compat editor (via AJAX)
	add_action('edit_attachment', array('Post_Attachments', 'save_attachment_fields'));

	//The Post Gallery class issues objects containing a post's attachment 'gallery' and returns an array of that data
	class Post_Attachments {
		/* Adding custom attachment fields */
		static function edit_attachment_fields ($form_fields, $post) {
			$form_fields["attachment-copyright"] = array(
				"label" => __("Copyright"),
				"input" => "text", // this is default if "input" is omitted
				"value" => get_post_meta($post->ID, "_photo_credit", true),
				"helps" => __("Set your copyright information.")
			);
			$form_fields["attachment-exclude-from-gallery"] = array(
				"label" => __("Exclude"),
				"input" => "html",
				"html"  => "<div style='height:10px;'>&nbsp;</div><input type='checkbox' name='attachments[" . $post->ID . "][attachment-exclude-from-gallery]' " . checked( get_post_meta($post->ID, "_attachment-exclude-from-gallery", true), 'on', 0 ) . " /> " . __("<em>Don't show this attachment in any galleries.</em>")
			);
			$form_fields["attachment-slide-link"] = array(
				"label" => __("Slide Link"),
				"input" => "text",
				"value" => get_post_meta($post->ID, '_attachment-slide-link', true)
			);
		   return $form_fields;
		}//edit_attachment_fields

		//Save attachment fields in the attachment post editor
		static function save_attachment_fields ($attachment, $attachment_meta) {
			//ATTACHMENT COPYRIGHT
			if (isset($_POST['attachments'][$attachment['ID']]['attachment-copyright'])) : 
				update_post_meta($attachment['ID'], '_photo_credit', $_POST['attachments'][$attachment['ID']]['attachment-copyright']);
			endif;
			//ATTACHMENT GALLERY EXCLUSION
			if (isset($_POST['attachments'][$attachment['ID']]['attachment-exclude-from-gallery'])) : 
				update_post_meta($attachment['ID'], '_attachment-exclude-from-gallery', $_POST['attachments'][$attachment['ID']]['attachment-exclude-from-gallery']);
			else :
				delete_post_meta($attachment['ID'], '_attachment-exclude-from-gallery');
			endif;
			//ATTACHMENT COPYRIGHT
			if (isset($_POST['attachments'][$attachment['ID']]['attachment-slide-link'])) : 
				update_post_meta($attachment['ID'], '_attachment-slide-link', $_POST['attachments'][$attachment['ID']]['attachment-slide-link']);
			endif;

			return $attachment;
		}//update_post_attachment_new_fields

		/* Fetch Post attachments */
		public static function fetch ($post_id, $orderby = 'menu_order', $order = 'ASC', $post_mime_type = 'image/jpeg,image/gif,image/jpg,image/png', $additional_meta_query = array(), $posts_per_page = -1) {
			global $post, $cdn;

			//Create the query meta_query
			$meta_query = array(
				array(				
					'key' => '_attachment-exclude-from-gallery',
					'compare' => 'NOT EXISTS'
				)
			);

			//Add in any passed in meta query arguments
			if (!empty($additional_meta_query)) $meta_query[] = $additional_meta_query ;

			//Select the attachments requested
			$args = array (
				'post_parent' => $post_id, 
				'post_status' => 'inherit', 
				'post_type' => 'attachment',
				'order' => $order, 
				'orderby' => $orderby,
				'post_mime_type' => $post_mime_type,
				'posts_per_page' => $posts_per_page,
				'meta_query' => $meta_query
			);

			$attachments = new WP_Query($args, ARRAY_A);

			//Make the attachments object a little cleaner by only using the data we want, the posts.
			//This also ensures our ->attachments variable holds and array (so we can easily display a random attachment on the website homepage
			$attachments = $attachments->posts;

			////Merge some additional attachment data into our main object
			foreach ($attachments as &$attachment) :
				//By doing both str_replace's below we can change the url when the website is running on the live server or local dev
				
				//Remove www. if we're running on the live website
				$attachment->guid = str_replace('www.', '', $attachment->guid);
				//Remove the ht protocol 
				$attachment->guid = str_replace('http://', '', $attachment->guid);
				//Replace the site url with the protocolless cdn url
				$attachment->guid = str_replace($cdn->site_url, $cdn->images_url, $attachment->guid);

				//Grab the attachment's meta data
				$attachment->meta_data = get_post_custom($attachment->ID);
				//Some of our meta data needs to be unserialized for use to user it
				$attachment->meta_data['_wp_attachment_metadata'] = @unserialize($attachment->meta_data['_wp_attachment_metadata'][0]);
			endforeach;

			//Remove the array indecies (they do not help us)
			return array_values($attachments);
		}//fetch	
	}//Post_Attachments
?>