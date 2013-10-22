<?php
	/*********************************************************
	NEXT STEP MAINE INSTITUTIONS CPT
	*********************************************************/
	register_post_type( 'nsm_institution',
		array(
			'labels' => array(
				'name' => __( 'Institutions' ),
				'singular_name' => __( 'Institution' ),
				'human_friendly' => __('Higher Education Institution(s) in Maine'),
				'add_new_item' => 'Add New Institution',
				'edit_item' => 'Edit Institutions',
				'new_item' => 'New Institution',
				'search_items' => 'Search Institutions',
				'not_found' => 'No institutions found',
				'not_found_in_trash' => 'No institutions found in trash',
		   ),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title','editor','thumbnail','gallery'),
			'rewrite' => array('slug' => 'institution', 'with_front' => true)
		)	
	);
				
	//Create the 'Type' taxonomy for the 'Work' post type
	register_taxonomy('nsm_institution_category', 'nsm_institution',
		array(
			'hierarchical' => true,
			'label' => 'Categories',	// the human-readable taxonomy name
			'query_var' => true,	// enable taxonomy-specific querying
			'rewrite' => array( 'slug' => 'institution-categories', 'with_front' => false),	// pretty permalinks for your taxonomy?
		)
	);
	
	//Create a new column 'Type' on the admin 'Work' page to display the types of each work item
	add_filter('manage_edit-nsm_institution_columns', 'manage_nsm_institution_admin_columns');
	//Populate the contents of the new columns we just created
	add_action('manage_nsm_institution_posts_custom_column', 'manage_nsm_institution_admin_columns_content');
	//Tell WordPress those new columns can be sortable within the admin
	add_filter('manage_edit-nsm_institution_sortable_columns', 'nsm_institution_category_column_register_sortable' );
	//Allow taxonomy sorting for the pica work cpt
	add_action( 'restrict_manage_posts', 'nsm_institution_cpt_taxonomy_filters' );
	
	//We want to tap in and add a column for the location ID and a column for the location region taxonomy term
	function manage_nsm_institution_admin_columns ($columns) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['nsm_institution_logo'] = _x('Institution Logo', 'column name');	
		$new_columns['title'] = _x('Institution Name', 'column name');	
		
		$new_columns['nsm_institution_phone'] = _x('Phone', 'column name');		
		$new_columns['nsm_institution_address'] = _x('Address', 'column name');		
		$new_columns['nsm_institution_category'] = __('Category');
		$new_columns['author'] = __('Author');
		$new_columns['date'] = _x('Date', 'column name');
		return $new_columns;
	}//end function manage_nsm_institution_admin_columns
	
	// Register the new 'Location' columns as sortable
	function nsm_institution_category_column_register_sortable( $columns ) {
		$columns['nsm_institution_category'] = 'nsm_institution_category';
		$columns['nsm_institution_phone'] = 'nsm_institution_phone';
		$columns['nsm_institution_address'] = 'nsm_institution_phone';
		return $columns;
	}//end function nsm_institution_category_column_register_sortable
	
	//Create the contents of our new 'Location' columns
	function manage_nsm_institution_admin_columns_content ($column) {
		global $post;
		switch ($column) :
			case 'nsm_institution_logo' :
                $image_url = get_post_meta($post->ID, '_nsm_institution_logo', true) ;
                if ($image_url != "") :
					?><img class="institution-image" src="<?php echo $image_url ?>" alt="Institution Image" width="60px" /><?php
                endif; 
			break;
			case 'nsm_institution_category':
				$terms = get_the_terms( $post->ID, 'nsm_institution_category');
				if ($terms) :
					$count = 0;
					foreach ($terms as $term) :
						echo '<a href="'.get_bloginfo('url').'/wp-admin/edit-tags.php?action=edit&taxonomy=nsm_institution_category&post_type=nsm_institution&tag_ID='.$term->term_id.'">'.$term->name.'</a>';
						if ($count != (count($terms) - 1)) :
							echo ", ";
						endif;
						$count++;
					endforeach;
				endif;
			break;
			case 'nsm_institution_phone':
				echo get_post_meta($post->ID, '_nsm_institution_phone', true);
			break;
			case 'nsm_institution_address':
				echo get_post_meta($post->ID, '_nsm_institution_address', true);
			break;
		endswitch;
	}//end function manage_nsm_institution_admin_columns_content
	
	function nsm_institution_cpt_taxonomy_filters() {
		generate_html_taxonomy_filter ('nsm_institution', 'nsm_institution_category');
	}//end function nsm_institution_cpt_taxonomy_filters
	
	/* END NEXT STEP INSTITUTIONS CPT
	*********************************************************/