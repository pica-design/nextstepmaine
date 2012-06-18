<?php
	
	
	add_action( 'add_meta_boxes', 'nsm_create_meta_boxes' );
	add_action( 'save_post', 'nsm_save_meta_box_data' );
	
	function nsm_create_meta_boxes() {
		
		/* Edit Institution Data */
		add_meta_box( 
			'nsm_institution_meta_information',
			__( 'Institution Information', 'nextstepmaine' ),
			'nsm_render_meta_boxes',
			'nsm_institution',
			'normal',
			'high'
		);	
	}
	
	function nsm_render_meta_boxes ($post) {
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
	
	function nsm_save_meta_box_data ($post_id) {
		
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
	}
	
?>