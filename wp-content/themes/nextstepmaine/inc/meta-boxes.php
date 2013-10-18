<?php
	
	
	add_action( 'add_meta_boxes', 'nsm_create_meta_boxes' );
	add_action( 'save_post', 'nsm_save_meta_box_data' );
	
	function nsm_create_meta_boxes() {
		
		/* Edit Institution Data */
		add_meta_box( 
			'nsm_institution_meta_information',
			__( 'Institution Information', 'nextstepmaine' ),
			'nsm_render_institution_information_meta_box',
			'nsm_institution',
			'normal',
			'high'
		);	

		/* Edit Institution Data */
		add_meta_box( 
			'nsm_program_meta_information',
			__( 'Program Information', 'nextstepmaine' ),
			'nsm_render_program_information_meta_box',
			'nsm_program',
			'normal',
			'high'
		);	
	}
	
	function nsm_render_institution_information_meta_box ($post) {
		?>
        <em><strong>Note:</strong> Be sure to update the master Excel list if making edits below</em>
      	<ul>
        	<li>
                <label for="nsm_institution_website_url">Websute URL</label>
                <input type="text" name="nsm_institution_website_url" value="<?php echo get_post_meta($post->ID, '_nsm_institution_website_url', true) ?>" />
        	</li><li>        
                <label for="nsm_institution_logo">Logo Image URL</label>
                <input type="text" name="nsm_institution_logo" value="<?php echo get_post_meta($post->ID, '_nsm_institution_logo', true) ?>" />
			</li><li>
                <label for="nsm_institution_address">Address</label>
                <input type="text" name="nsm_institution_address" value="<?php echo get_post_meta($post->ID, '_nsm_institution_address', true) ?>" />
        	</li><li>        
                <label for="nsm_institution_phone">General Contact</label>
                <input type="text" name="nsm_institution_phone" value="<?php echo get_post_meta($post->ID, '_nsm_institution_phone', true) ?>" />
        	</li><li>        
                <label for="nsm_institution_finaid_contact">Financial Aid Contact</label>
                <input type="text" name="nsm_institution_finaid_contact" value="<?php echo get_post_meta($post->ID, '_nsm_institution_finaid_contact', true) ?>" />
        	</li><li>        
                <label for="nsm_institution_admission_contact">Admissions Contact</label>
                <input type="text" name="nsm_institution_admission_contact" value="<?php echo get_post_meta($post->ID, '_nsm_institution_admission_contact', true) ?>" />
			</li>
        </ul>        
        <?php
	}

	function nsm_render_program_information_meta_box ($post) {
		?>
		<em><strong>Note:</strong> Be sure to update the master Excel list if making edits below</em>
      	<ul>
        	<li>
                <label for="nsm_program_insitution_title_iv_code">Title IV Code</label>
                <input type="text" name="nsm_program_insitution_title_iv_code" value="<?php echo get_post_meta($post->ID, '_nsm_program_insitution_title_iv_code', true) ?>" />
        	</li><li>
                <label for="nsm_program_type">Program Type</label>
                <input type="text" name="nsm_program_type" value="<?php echo get_post_meta($post->ID, '_nsm_program_type', true) ?>" />
        	</li><li>
                <label for="nsm_program_discipline">Program Discipline</label>
                <input type="text" name="nsm_program_discipline" value="<?php echo get_post_meta($post->ID, '_nsm_program_discipline', true) ?>" />
        	</li><li>
                <label for="nsm_program_level">Program Level</label>
                <input type="text" name="nsm_program_level" value="<?php echo get_post_meta($post->ID, '_nsm_program_level', true) ?>" />
        	</li><li>
                <label for="nsm_program_format">Program Format</label>
                <input type="text" name="nsm_program_format" value="<?php echo get_post_meta($post->ID, '_nsm_program_format', true) ?>" />
        	</li><li>
                <label for="nsm_program_location">Program Location</label>
                <input type="text" name="nsm_program_location" value="<?php echo get_post_meta($post->ID, '_nsm_program_location', true) ?>" />
        	</li><li>
                <label for="nsm_program_schedule">Program Schedule</label>
                <input type="text" name="nsm_program_schedule" value="<?php echo get_post_meta($post->ID, '_nsm_program_schedule', true) ?>" />
        	</li><li>
                <label for="nsm_program_url">Program URL</label>
                <input type="text" name="nsm_program_url" value="<?php echo get_post_meta($post->ID, '_nsm_program_url', true) ?>" />
        	</li><li>
                <label for="nsm_program_timeframe">Program Timeframe</label>
                <input type="text" name="nsm_program_timeframe" value="<?php echo get_post_meta($post->ID, '_nsm_program_timeframe', true) ?>" />
        	</li><li>
                <label for="nsm_program_cost">Program Cost</label>
                <input type="text" name="nsm_program_cost" value="<?php echo get_post_meta($post->ID, '_nsm_program_cost', true) ?>" />
        	</li>
        </ul>
		<?php
	}
	
	function nsm_save_meta_box_data ($post_id) {
		/* INSTITUTION UPDATES */
		if (isset($_POST['nsm_institution_website_url'])) :
			update_post_meta($post_id, '_nsm_institution_website_url', $_POST['nsm_institution_website_url']);
		endif;
		if (isset($_POST['nsm_institution_logo'])) :
			update_post_meta($post_id, '_nsm_institution_logo', $_POST['nsm_institution_logo']);
		endif;
		if (isset($_POST['nsm_institution_address'])) :
			update_post_meta($post_id, '_nsm_institution_address', $_POST['nsm_institution_address']);
		endif;
		if (isset($_POST['nsm_institution_phone'])) :
			update_post_meta($post_id, '_nsm_institution_phone', $_POST['nsm_institution_phone']);
		endif;
		if (isset($_POST['nsm_institution_finaid_contact'])) :
			update_post_meta($post_id, '_nsm_institution_finaid_contact', $_POST['nsm_institution_finaid_contact']);
		endif;
		if (isset($_POST['nsm_institution_admission_contact'])) :
			update_post_meta($post_id, '_nsm_institution_admission_contact', $_POST['nsm_institution_admission_contact']);
		endif;

		/* PROGRAM UPDATES */
		if (isset($_POST['nsm_program_insitution_title_iv_code'])) :
			update_post_meta($post_id, '_nsm_program_insitution_title_iv_code', $_POST['nsm_program_insitution_title_iv_code']);
		endif;
		if (isset($_POST['nsm_program_type'])) :
			update_post_meta($post_id, '_nsm_program_type', $_POST['nsm_program_type']);
		endif;
		if (isset($_POST['nsm_program_discipline'])) :
			update_post_meta($post_id, '_nsm_program_discipline', $_POST['nsm_program_discipline']);
		endif;
		if (isset($_POST['nsm_program_level'])) :
			update_post_meta($post_id, '_nsm_program_level', $_POST['nsm_program_level']);
		endif;
		if (isset($_POST['nsm_program_format'])) :
			update_post_meta($post_id, '_nsm_program_format', $_POST['nsm_program_format']);
		endif;
		if (isset($_POST['nsm_program_location'])) :
			update_post_meta($post_id, '_nsm_program_location', $_POST['nsm_program_location']);
		endif;
		if (isset($_POST['nsm_program_schedule'])) :
			update_post_meta($post_id, '_nsm_program_schedule', $_POST['nsm_program_schedule']);
		endif;
		if (isset($_POST['nsm_program_url'])) :
			update_post_meta($post_id, '_nsm_program_url', $_POST['nsm_program_url']);
		endif;
		if (isset($_POST['nsm_program_timeframe'])) :
			update_post_meta($post_id, '_nsm_program_timeframe', $_POST['nsm_program_timeframe']);
		endif;
		if (isset($_POST['nsm_program_cost'])) :
			update_post_meta($post_id, '_nsm_program_cost', $_POST['nsm_program_cost']);
		endif;
	}
	
?>