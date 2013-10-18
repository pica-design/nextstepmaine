<?php
	/* END NEXT STEP PROGRAMS CPT
	*********************************************************/

	/*********************************************************
	NEXT STEP MAINE FAQs
	*********************************************************/
	register_post_type( 'nsm_faq',
		array(
			'labels' => array(
				'name' => __( 'FAQs' ),
				'singular_name' => __( 'FAQ' ),
				'human_friendly' => __('Frequently Asked Question(s)'),
				'add_new_item' => 'Add New FAQ',
				'edit_item' => 'Edit FAQs',
				'new_item' => 'New FAQ',
				'search_items' => 'Search FAQs',
				'not_found' => 'No faqs found',
				'not_found_in_trash' => 'No faqs found in trash',
		   ),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title','editor','thumbnail','gallery'),
			'rewrite' => array('slug' => 'faq', 'with_front' => true)
		)	
	);
				
	//Create the 'Type' taxonomy for the 'Work' post type
	register_taxonomy('nsm_faq_category', 'nsm_faq',
		array(
			'hierarchical' => true,
			'label' => 'Categories',	// the human-readable taxonomy name
			'query_var' => true	// enable taxonomy-specific querying
		)
	);
	
	//Create a new column 'Type' on the admin 'Work' page to display the types of each work item
	add_filter('manage_edit-nsm_faq_columns', 'manage_nsm_faq_admin_columns');
	//Populate the contents of the new columns we just created
	add_action('manage_nsm_faq_posts_custom_column', 'manage_nsm_faq_admin_columns_content');
	//Tell WordPress those new columns can be sortable within the admin
	add_filter('manage_edit-nsm_faq_sortable_columns', 'nsm_faq_category_column_register_sortable' );
	//Allow taxonomy sorting for the pica work cpt
	add_action( 'restrict_manage_posts', 'nsm_faq_cpt_taxonomy_filters' );
	
	//We want to tap in and add a column for the location ID and a column for the location region taxonomy term
	function manage_nsm_faq_admin_columns ($columns) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = _x('FAQ Name', 'column name');			
		$new_columns['nsm_faq_category'] = __('Categories');
		$new_columns['author'] = __('Author');
		$new_columns['date'] = _x('Date', 'column name');
		return $new_columns;
	}//end function manage_nsm_faq_admin_columns
	
	// Register the new 'Location' columns as sortable
	function nsm_faq_category_column_register_sortable( $columns ) {
		$columns['nsm_faq_category'] = 'nsm_faq_category';
		return $columns;
	}//end function nsm_faq_category_column_register_sortable
	
	//Create the contents of our new 'Location' columns
	function manage_nsm_faq_admin_columns_content ($column) {
		global $post;
		switch ($column) :
			case 'nsm_faq_category':
				$terms = get_the_terms( $post->ID, 'nsm_faq_category');
				if ($terms) :
					$count = 0;
					foreach ($terms as $term) :
						echo '<a href="'.get_bloginfo('url').'/wp-admin/edit-tags.php?action=edit&taxonomy=nsm_faq_category&post_type=nsm_faq&tag_ID='.$term->term_id.'">'.$term->name.'</a>';
						if ($count != (count($terms) - 1)) :
							echo ", ";
						endif;
						$count++;
					endforeach;
				endif;
			break;
		endswitch;
	}//end function manage_nsm_faq_admin_columns_content
	
	function nsm_faq_cpt_taxonomy_filters() {
		generate_html_taxonomy_filter('nsm_faq', 'nsm_faq_category');
	}//end function nsm_faq_cpt_taxonomy_filters
	
	/* END NEXT STEP MAINE DOL JOBS IN DEMAND CPT
	*********************************************************/