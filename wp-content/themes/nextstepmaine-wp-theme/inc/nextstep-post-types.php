<?php
	
	// Define icon styles for the custom post type
	function post_type_icons() {
	?>
	<style type="text/css" media="screen">
			#menu-posts-nsm_institution .wp-menu-image {
				background: url(<?php bloginfo('template_url') ?>/images/admin-dashboard/nsm_institution-icon-16x64.jpg) no-repeat 6px -32px !important;
			}
			#menu-posts-nsm_institution:hover .wp-menu-image, #menu-posts-casestudy.wp-has-current-submenu .wp-menu-image {
				background-position:6px 0px !important;
			}
			#icon-edit.icon32-posts-nsm_institution {
				background: url(<?php bloginfo('template_url') ?>/images/admin-dashboard/nsm_institution-icon-32x32.jpg) no-repeat;
			}
			
		</style>
	
	<?php }
	add_action( 'admin_head', 'post_type_icons' );
	

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
	
	
	
	
	/*********************************************************
	NEXT STEP MAINE INSTITUTIONS CPT
	*********************************************************/
	register_post_type( 'nsm_institution',
		array(
			'labels' => array(
				'name' => __( 'Institutions' ),
				'singular_name' => __( 'Institution' ),
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
		$new_columns['title'] = _x('Institution Name', 'column name');	
		$new_columns['nsm_institution_phone'] = _x('Phone', 'column name');		
		$new_columns['nsm_institution_address'] = _x('Phone', 'column name');		
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
		global $typenow;
		// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
		$taxonomies = array('nsm_institution_category');
		// must set this to the post type you want the filter(s) displayed on
		if($typenow == 'nsm_institution') :
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
	}//end function nsm_institution_cpt_taxonomy_filters
	
	/* END NEXT STEP INSTITUTIONS CPT
	*********************************************************/
	
	
	
	/*********************************************************
	NEXT STEP MAINE PROGRAMS CPT
	*********************************************************/
	register_post_type( 'nsm_program',
		array(
			'labels' => array(
				'name' => __( 'Programs' ),
				'singular_name' => __( 'Program' ),
				'add_new_item' => 'Add New Program',
				'edit_item' => 'Edit Programs',
				'new_item' => 'New Program',
				'search_items' => 'Search Programs',
				'not_found' => 'No programs found',
				'not_found_in_trash' => 'No programs found in trash',
		   ),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title','editor','thumbnail','gallery'),
			'rewrite' => array('slug' => 'program', 'with_front' => true)
		)	
	);
				
	//Create the 'Type' taxonomy for the 'Work' post type
	register_taxonomy('nsm_program_category', 'nsm_program',
		array(
			'hierarchical' => true,
			'label' => 'Categories',	// the human-readable taxonomy name
			'query_var' => true,	// enable taxonomy-specific querying
			'rewrite' => array( 'slug' => 'program-categories', 'with_front' => false),	// pretty permalinks for your taxonomy?
		)
	);
	
	/*
	//Create the 'Type' taxonomy for the 'Work' post type
	register_taxonomy('nsm_program_institution', 'nsm_program',
		array(
			'hierarchical' => true,
			'label' => 'Institutions',	// the human-readable taxonomy name
			'query_var' => true,	// enable taxonomy-specific querying
			'rewrite' => array( 'slug' => 'program-institutions', 'with_front' => false),	// pretty permalinks for your taxonomy?
		)
	);
	*/
	
	//Create a new column 'Type' on the admin 'Work' page to display the types of each work item
	add_filter('manage_edit-nsm_program_columns', 'manage_nsm_program_admin_columns');
	//Populate the contents of the new columns we just created
	add_action('manage_nsm_program_posts_custom_column', 'manage_nsm_program_admin_columns_content');
	//Tell WordPress those new columns can be sortable within the admin
	add_filter('manage_edit-nsm_program_sortable_columns', 'nsm_program_category_column_register_sortable' );
	//Allow taxonomy sorting for the pica work cpt
	add_action( 'restrict_manage_posts', 'nsm_program_cpt_taxonomy_filters' );
	
	//We want to tap in and add a column for the location ID and a column for the location region taxonomy term
	function manage_nsm_program_admin_columns ($columns) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = _x('Program Name', 'column name');			
		//$new_columns['nsm_program_institution'] = __('Institution');
		$new_columns['nsm_program_category'] = __('Category');
		$new_columns['author'] = __('Author');
		$new_columns['date'] = _x('Date', 'column name');
		return $new_columns;
	}//end function manage_nsm_program_admin_columns
	
	// Register the new 'Location' columns as sortable
	function nsm_program_category_column_register_sortable( $columns ) {
		$columns['nsm_program_category'] = 'nsm_program_category';
		//$columns['nsm_program_institution'] = 'nsm_program_institution';
		return $columns;
	}//end function nsm_program_category_column_register_sortable
	
	//Create the contents of our new 'Location' columns
	function manage_nsm_program_admin_columns_content ($column) {
		global $post;
		switch ($column) :
			case 'nsm_program_category':
				$terms = get_the_terms( $post->ID, 'nsm_program_category');
				if ($terms) :
					$count = 0;
					foreach ($terms as $term) :
						echo '<a href="'.get_bloginfo('url').'/wp-admin/edit-tags.php?action=edit&taxonomy=nsm_program_category&post_type=nsm_program&tag_ID='.$term->term_id.'">'.$term->name.'</a>';
						if ($count != (count($terms) - 1)) :
							echo ", ";
						endif;
						$count++;
					endforeach;
				endif;
			break;/*
			case 'nsm_program_institution':
				
				$terms = get_the_terms( $post->ID, 'nsm_program_institution');
				if ($terms) :
					$count = 0;
					foreach ($terms as $term) :
						echo '<a href="'.get_bloginfo('url').'/wp-admin/edit-tags.php?action=edit&taxonomy=nsm_program_institution&post_type=nsm_program&tag_ID='.$term->term_id.'">'.$term->name.'</a>';
						if ($count != (count($terms) - 1)) :
							echo ", ";
						endif;
						$count++;
					endforeach;
				endif;
				
			break;
			*/
		endswitch;
	}//end function manage_nsm_program_admin_columns_content
	
	function nsm_program_cpt_taxonomy_filters() {
		global $typenow;
		// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
		$taxonomies = array('nsm_program_category');
		// must set this to the post type you want the filter(s) displayed on
		if($typenow == 'nsm_program') :
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
	}//end function nsm_program_cpt_taxonomy_filters
	
	/* END NEXT STEP PROGRAMS CPT
	*********************************************************/
?>