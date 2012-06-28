<?php
	/********************************************
	WORDPRESS GALLERY ADDITIONS CLASS
	********************************************/
	//The Post Gallery class issues objects containing an post's attachment 'gallery' and returns an array of that data
	class Post_Gallery {
		public function __construct ($post_id) {
			//If no post was passed in grab the current post
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
	
	
	/********************************************
	BREADCRUMB GENERATION CLASS
	********************************************/
	class Breadcrumbs {
		public function __construct () {
			global $post ; 
			?>
            <ul class="breadcrumbs">
            <?php
				//Generate the hierarchical page breadcrumbs
				if ($post->post_type == "page") :
					?>
						<li class="first"><a href="<?php bloginfo('url') ?>" title="Next Step Maine Homepage">Home</a></li>
						<?php foreach ($post->ancestors as $ancestor) : ?>
						<li><a href="<?php echo get_permalink($ancestor) ?>" title="<?php echo get_the_title($ancestor) ?>"><?php echo get_the_title($ancestor) ?></a></li>
						<?php endforeach ?>
						<?php 
							//The program pages use a query_var for page display, so we want to incorporate that into the breadcrumbs
							$program_type = get_query_var('program_type');
							//If we're on the programs page AND the query var has been set
								//If no query var is set the default page breadcrumb will suffice
							if (is_page('programs') && isset($program_type) && !empty($program_type)) : ?>
								<li><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo get_the_title($post->ID) ?>"><?php echo get_the_title($post->ID) ?></a></li>
								<li class="last"><span class="current-post"><?php echo get_query_var('program_type') ?></span></li>
							<?php else : /* DEFAULT */ ?>
								<li class="last"><span class="current-post"><?php echo $post->post_title ?></span></li>
						<?php endif ?>
					<?php
				endif;
				
				//Generate the single post type breadcrumbs
				if ($post->post_type == "nsm_job") : 
					if (is_single()) : 
						?>
						<li class="first"><a href="<?php bloginfo('url') ?>" title="Next Step Maine Homepage">Home</a></li>
						<li><a href="<?php bloginfo('url') ?>/jobs" title="Jobs in Demand in Maine">Jobs</a></li>
						<li class="last"><span class="current-post"><?php echo $post->post_title ?></span></li>
						<?
					endif;
				endif;
				
				//Generate the single post type breadcrumbs
				if ($post->post_type == "nsm_institution") : 
					if (is_single()) : 
						?>
						<li class="first"><a href="<?php bloginfo('url') ?>" title="Next Step Maine Homepage">Home</a></li>
						<li><a href="<?php bloginfo('url') ?>/jobs" title="Institutions in Maine">Institutions</a></li>
						<li class="last"><span class="current-post"><?php echo $post->post_title ?></span></li>
						<?
					endif;
				endif;
			?>
            </ul>
            <div class="clear"></div>
            <?php
		}
	}
?>