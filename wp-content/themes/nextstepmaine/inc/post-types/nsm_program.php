<?php
		/*********************************************************
	NEXT STEP MAINE PROGRAMS CPT
	*********************************************************/
	/*
		The following created a query_var called 'program_type', so..
		site.com/programs/foo  ==  site.com/programs?program_type=foo

		NOTE: Your 'programs' page can not have any child
	*/
	//Register our custom $_GET variable (aka query var, aka rewrite tag) ?program_type=foo
	add_rewrite_tag('%program_type%', '([^&]+)');
	//Create the rewrite write rule to convert site.com/programs/foo to site.com/programs/?program_type=foo 
	add_rewrite_rule('^programs/([^/]*)/?', 'index.php?pagename=programs&program_type=$matches[1]', 'top');

	register_post_type( 'nsm_program',
		array(
			'labels' => array(
				'name' => __( 'Programs' ),
				'singular_name' => __( 'Program' ),
				'add_new_item' => 'Add New Program',
				'human_friendly' => __('Maine Educational Program(s)'),
				'edit_item' => 'Edit Program',
				'new_item' => 'New Program',
				'search_items' => 'Search Programs',
				'not_found' => 'No programs found',
				'not_found_in_trash' => 'No programs found in trash',
		   ),
			'public' => true,
			'hierarchical' => true,
			'capability_type' => array('program', 'programs'),
			'map_meta_cap' => true,
			/*
				The above creates the following capabilities
				read_private_programs
				publish_programs
				edit_programs
				edit_published_programs
				edit_others_programs
				delete_programs
				delete_others_programs

				We then used the 'Members' plugin to create a custom Role 'Conifer Editor'
				And manually added each of the above caps to that role
			*/
			'supports' => array('title','editor','thumbnail','gallery','author'),
			'rewrite' => array('slug' => 'program', 'with_front' => true)
		)	
	);
				
	//Create the 'Type' taxonomy for the 'Work' post type
	register_taxonomy('nsm_program_category', 'nsm_program',
		array(
			'hierarchical' => true,
			'label' => 'Categories',	// the human-readable taxonomy name
			'query_var' => true,	// enable taxonomy-specific querying
			'capabilities' => array(
				'manage_terms' => 'manage_nsm_program_category_terms',
				'edit_terms' => 'manage_nsm_program_category_terms',
				'delete_terms' => 'manage_nsm_program_category_terms',
				'assign_terms' => 'edit_programs'
			),
			'rewrite' => array( 'slug' => 'program-categories', 'with_front' => false),	// pretty permalinks for your taxonomy?
		)
	);	
	
	add_action('admin_head', 'column_widths');
	function column_widths() { 
		if (get_current_post_type() == 'nsm_program') : ?>
			<style type="text/css">
				th.column-title {
					width: 25% !important ;
				}
				th.column-author {
					width: 25% !important ;
				}
				th.column-nsm_program_category {
					width: 25% !important ;
				}
			</style><?php
		endif;
	}//column_widths

	//We want to tap in and add a column for the location ID and a column for the location region taxonomy term
	//Create a new column 'Type' on the admin 'Work' page to display the types of each work item
	add_filter('manage_edit-nsm_program_columns', 'manage_nsm_program_admin_columns');
	function manage_nsm_program_admin_columns ($columns) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = _x('Program Name', 'column name');			
		$new_columns['author'] = __('Institution');
		$new_columns['nsm_program_category'] = __('Category');
		$new_columns['date'] = _x('Date', 'column name');
		return $new_columns;
	}//end function manage_nsm_program_admin_columns
	
	//Tell WordPress those new columns can be sortable within the admin
	add_filter('manage_edit-nsm_program_sortable_columns', 'nsm_program_category_column_register_sortable' );
	function nsm_program_category_column_register_sortable( $columns ) {
		$columns['author'] = 'author';
		$columns['nsm_program_category'] = 'nsm_program_category';
		return $columns;
	}//end function nsm_program_category_column_register_sortable
	
	//Populate the contents of the new columns we just created
	add_action('manage_nsm_program_posts_custom_column', 'manage_nsm_program_admin_columns_content');
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
			break;
		endswitch;
	}//end function manage_nsm_program_admin_columns_content
	
	//Allow taxonomy sorting for the pica work cpt
	add_action( 'restrict_manage_posts', 'nsm_program_cpt_taxonomy_filters' );
	function nsm_program_cpt_taxonomy_filters() {
		generate_html_taxonomy_filter ('nsm_program', 'nsm_program_category');
		//generate_html_author_filter ('nsm_program');
	}//end function nsm_program_cpt_taxonomy_filters

	add_action('restrict_manage_posts', 'restrict_manage_authors');
	function restrict_manage_authors() {
		if (isset($_GET['post_type']) && post_type_exists($_GET['post_type']) && in_array(strtolower($_GET['post_type']), array('nsm_program'))) {
			wp_dropdown_users(array(
				'show_option_all'	=> 'Show all Authors',
				'show_option_none'	=> false,
				'name'				=> 'author',
				'selected'			=> !empty($_GET['author']) ? $_GET['author'] : 0,
				'include_selected'	=> false,
				'exclude'			=> array(2)
			));
		}
	}//restrict_manage_authors
	
