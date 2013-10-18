<?php
	/*********************************************************
	NEXT STEP MAINE DOL JOBS IN DEMAND CPT
	*********************************************************/
	/*
		The following created a query_var called 'program_type', so..
		site.com/programs/foo  ==  site.com/programs?program_type=foo

		NOTE: Your 'programs' page can not have any child
	*/
	//Register our custom $_GET variable (aka query var, aka rewrite tag) ?prog_edu_lvl=foo
	add_rewrite_tag('%education_requirement%', '([^&]+)');
	//Create the rewrite write rule to convert site.com/programs/foo to site.com/programs/?prog_edu_lvl=foo 
	add_rewrite_rule('^jobs/([^/]*)/?', 'index.php?pagename=jobs&education_requirement=$matches[1]', 'top');
	
	register_post_type( 'nsm_job',
		array(
			'labels' => array(
				'name' => __( 'Jobs' ),
				'singular_name' => __( 'Job' ),
				'human_friendly' => __('High-Demand Job(s) in Maine'),
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
			'rewrite' => array('slug' => 'job', 'with_front' => true)
		)	
	);
				
	//Create the 'Type' taxonomy for the 'Work' post type
	register_taxonomy('nsm_job_education_requirement', 'nsm_job',
		array(
			'hierarchical' => true,
			'label' => 'Education Requirements',	// the human-readable taxonomy name
			'query_var' => true,	// enable taxonomy-specific querying
			'rewrite' => array( 'slug' => 'job-education-requirement', 'with_front' => false),	// pretty permalinks for your taxonomy?
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
		$new_columns['nsm_job_education_requirement'] = __('Education Requierment');
		$new_columns['author'] = __('Author');
		$new_columns['date'] = _x('Date', 'column name');
		return $new_columns;
	}//end function manage_nsm_job_admin_columns
	
	// Register the new 'Location' columns as sortable
	function nsm_job_category_column_register_sortable( $columns ) {
		$columns['nsm_job_education_requirement'] = 'nsm_job_education_requirement';
		return $columns;
	}//end function nsm_job_category_column_register_sortable
	
	//Create the contents of our new 'Location' columns
	function manage_nsm_job_admin_columns_content ($column) {
		global $post;
		switch ($column) :
			case 'nsm_job_education_requirement':
				$terms = get_the_terms( $post->ID, 'nsm_job_education_requirement');
				if ($terms) :
					$count = 0;
					foreach ($terms as $term) :
						echo '<a href="'.get_bloginfo('url').'/wp-admin/edit-tags.php?action=edit&taxonomy=nsm_job_education_requirement&post_type=nsm_job&tag_ID='.$term->term_id.'">'.$term->name.'</a>';
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
		generate_html_taxonomy_filter('nsm_job', 'nsm_job_education_requirement');
	}//end function nsm_job_cpt_taxonomy_filters
	
	/* END NEXT STEP MAINE DOL JOBS IN DEMAND CPT
	*********************************************************/