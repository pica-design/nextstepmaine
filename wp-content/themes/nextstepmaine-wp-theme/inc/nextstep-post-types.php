<?php

	/*********************************************************
	NEXT STEP MAINE DOL JOBS IN DEMAND CPT
	*********************************************************/
	register_post_type( 'nsm_job',
		array(
			'labels' => array(
				'name' => __( 'Jobs' ),
				'singular_name' => __( 'Job' ),
				'add_new_item' => 'Add New Job',
				'edit_item' => 'Edit Jobs',
				'new_item' => 'New Job',
				'search_items' => 'Search Jobs',
				'not_found' => 'No jobs found',
				'not_found_in_trash' => 'No jobs found in trash',
		   ),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title','editor','thumbnail','gallery'),
			'rewrite' => array('slug' => 'resources/jobs', 'with_front' => true)
		)	
	);
				
	//Create the 'Type' taxonomy for the 'Work' post type
	register_taxonomy('nsm_job_category', 'nsm_job',
		array(
			'hierarchical' => true,
			'label' => 'Job Categories',	// the human-readable taxonomy name
			'query_var' => true,	// enable taxonomy-specific querying
			'rewrite' => array( 'slug' => 'job-categories', 'with_front' => false),	// pretty permalinks for your taxonomy?
		)
	);
	
	//Create a new column 'Type' on the admin 'Work' page to display the types of each work item
	add_filter('manage_edit-nsm_job_columns', 'manage_nsm_job_admin_columns');
	//Populate the contents of the new columns we just created
	add_action('manage_nsm_job_posts_custom_column', 'manage_nsm_job_admin_columns_content');
	//Tell WordPress those new columns can be sortable within the admin
	add_filter('manage_edit-nsm_job_sortable_columns', 'nsm_job_category_column_register_sortable' );
	//Allow taxonomy sorting for the pica work cpt
	add_action( 'restrict_manage_posts', 'nsm_job_cpt_taxonomy_filters' );
	
	//We want to tap in and add a column for the location ID and a column for the location region taxonomy term
	function manage_nsm_job_admin_columns ($columns) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = _x('Job Name', 'column name');			
		$new_columns['nsm_job_category'] = __('Category');
		$new_columns['author'] = __('Author');
		$new_columns['date'] = _x('Date', 'column name');
		return $new_columns;
	}//end function manage_nsm_job_admin_columns
	
	// Register the new 'Location' columns as sortable
	function nsm_job_category_column_register_sortable( $columns ) {
		$columns['nsm_job_category'] = 'nsm_job_category';
		return $columns;
	}//end function nsm_job_category_column_register_sortable
	
	//Create the contents of our new 'Location' columns
	function manage_nsm_job_admin_columns_content ($column) {
		global $post;
		switch ($column) :
			case 'nsm_job_category':
				$terms = get_the_terms( $post->ID, 'nsm_job_category');
				if ($terms) :
					$count = 0;
					foreach ($terms as $term) :
						echo '<a href="'.get_bloginfo('url').'/wp-admin/edit-tags.php?action=edit&taxonomy=nsm_job_category&post_type=nsm_job&tag_ID='.$term->term_id.'">'.$term->name.'</a>';
						if ($count != (count($terms) - 1)) :
							echo ", ";
						endif;
						$count++;
					endforeach;
				endif;
			break;
		endswitch;
	}//end function manage_nsm_job_admin_columns_content
	
	function nsm_job_cpt_taxonomy_filters() {
		global $typenow;
		// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
		$taxonomies = array('nsm_job_category');
		// must set this to the post type you want the filter(s) displayed on
		if($typenow == 'nsm_job') :
			foreach ($taxonomies as $tax_slug) :
				$tax_obj = get_taxonomy($tax_slug);
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if(count($terms) > 0) :
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>Show All $tax_name</option>";
					foreach ($terms as $term) :
						echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; 
					endforeach;
					echo "</select>";
				endif;
			endforeach;
		endif;
	}//end function nsm_job_cpt_taxonomy_filters
	
	/* END NEXT STEP MAINE DOL JOBS IN DEMAND CPT
	*********************************************************/
	
?>