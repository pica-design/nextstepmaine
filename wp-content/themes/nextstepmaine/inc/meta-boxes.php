<?php
	add_action( 'add_meta_boxes', 'nsm_create_meta_boxes' );
	function nsm_create_meta_boxes() {
        //Remove the WordPress SEO Meta box
        if (!current_user_can('manage_options')) remove_meta_box('wpseo_meta', 'nsm_program', 'normal');


		//Edit Institution Data
		add_meta_box( 
			'nsm_program_meta_information',
			__( 'Program Information', 'nextstepmaine' ),
			'nsm_render_program_information_meta_box',
			'nsm_program',
			'side',
			'high'
		);	
	}

	function nsm_render_program_information_meta_box ($post) { 
		$program = array(
			'type' => get_post_meta($post->ID, '_nsm_program_type', true),
			'level' => get_post_meta($post->ID, '_nsm_program_level', true),
			'format' => get_post_meta($post->ID, '_nsm_program_format', true),
			'schedule' => get_post_meta($post->ID, '_nsm_program_schedule', true),
			'timeframe' => get_post_meta($post->ID, '_nsm_program_timeframe', true),
			'cost' => get_post_meta($post->ID, '_nsm_program_cost', true),
			'location' => get_post_meta($post->ID, '_nsm_program_location', true),
			'url' => get_post_meta($post->ID, '_nsm_program_url', true),
			'cip' => get_post_meta($post->ID, '_nsm_program_cip', true),
            'uc' => get_post_meta($post->ID, '_nsm_program_uc', true),
		);
		?>
      	<table>
      		<tr>
        		<td>
                	<label for="nsm_program_type" title="Program types are used to sort programs on the website. Valid values are 'bachelor', 'associate', 'certificate', and 'master'.">Type</label>
            	</td><td>
                	<select name="nsm_program_type">
                		<option value="">Select One</option>
                		<option value="certificate" <?php selected($program['type'], 'certificate') ?>>Certificate</option>
                		<option value="associate" <?php selected($program['type'], 'associate') ?>>Associate</option>
                		<option value="bachelor" <?php selected($program['type'], 'bachelor') ?>>Bachelor</option>
                		<option value="master" <?php selected($program['type'], 'master') ?>>Master</option>
                	</select>
                </td>
        	</tr><tr>
        		<td>
                	<label for="nsm_program_level" title="Valid values for program level include, but are not limited to: 'graduate', 'undergraduate', and 'non-credit'.">Level</label>
            	</td><td>
                	<select name="nsm_program_level">
                		<option value="">Select One</option>
                		<option value="non-credit" <?php selected($program['level'], 'non-credit') ?>>Non-Credit</option>
                		<option value="undergraduate" <?php selected($program['level'], 'undergraduate') ?>>Undergraduate</option>
                		<option value="graduate" <?php selected($program['level'], 'graduate') ?>>Graduate</option>
                	</select>
                </td>
        	</tr><tr>
        		<td>
                	<label for="nsm_program_format" title="Program format is used by users to filter & sort programs in the programs list. Valid values include 'online', 'classroom', and 'hybrid' (meaning the course meets both online and in the classroom).">Format</label>
            	</td><td>
                	<select name="nsm_program_format">
                		<option value="">Select One</option>
                		<option value="classroom" <?php selected($program['format'], 'classroom') ?>>Classroom</option>
                		<option value="online" <?php selected($program['format'], 'online') ?>>Online</option>
                		<option value="hybrid" <?php selected($program['format'], 'hybrid') ?>>Hybrid</option>
                	</select>
                </td>
        	</tr><tr>
        		<td>
                	<label for="nsm_program_schedule" title="Program schedule only has two values, 'flexible' or 'fixed'. For example, a stay-at-home dad might only be able to take online courses at night, and is looking for a program with a 'flexible' schedule.">Schedule</label>
            	</td><td>
                	<select name="nsm_program_schedule">
                		<option value="">Select One</option>
                		<option value="fixed" <?php selected($program['schedule'], 'fixed') ?>>Fixed</option>
                		<option value="flexible" <?php selected($program['schedule'], 'flexible') ?>>Flexible</option>
                	</select>
                </td>
        	</tr><tr>
        		<td>
                	<label for="nsm_program_timeframe" title="This field is used to give users a good idea how long the program will take them to complete. If the program has a 'flexible' schedule a value of 'variable' is acceptable. Otherwise please specify the time length in hours, e.g. '34 hours'.">Timeframe</label>
            	</td><td>
                	<input type="text" name="nsm_program_timeframe" value="<?php echo $program['timeframe'] ?>" />
                </td>
        	</tr><tr>
        		<td>
                	<label for="nsm_program_cost" title="This is the average cost per credit. An acceptable value would be '$200 per credit'. Users will sort by this data to view the most affordable programs for their budget.">Cost</label>
            	</td><td>
                	<input type="text" name="nsm_program_cost" value="<?php echo $program['cost'] ?>" />
                </td>
        	</tr><tr>
        		<td>
                	<label for="nsm_program_location" title="Location is used to describe the physical location students meet for classroom and hybrid classes. Example values include, but are not limited to: 'bangor' or 'augusta'. A value of 'statewide' indicates to users that the program is online and that location is not a factor.">Location</label>
            	</td><td>
                	<input type="text" name="nsm_program_location" value="<?php echo $program['location'] ?>" />
                </td>
        	</tr><tr>
        		<td>
                	<label for="nsm_program_url" title="Enter the web page address for the program, if one exists. This web page should include all additional program data not listed on Next Step Maine, and the page should have clear instructions on the user's next step, whether it's to call Admissions or do another action. When a user clicks on the Next Step Maine website to view additional program details they'll be taken to this page. NOTE: This value must include http:// at the beginning of the url, i.e. http://www.nextstepmaine.org/">URL</label>
            	</td><td>
                	<input type="text" name="nsm_program_url" value="<?php echo $program['url'] ?>" />
                </td>
        	</tr><tr>
        		<td>
                	<label for="nsm_program_cip" title="The Classification of Instructional Programs (CIP) provides a taxonomic scheme that supports the accurate tracking and reporting of fields of study and program completions activity. Valid CIP codes are ##. or ##.## or ##.####  http://nces.ed.gov/ipeds/cipcode/browse.aspx?y=55">CIP</label>
            	</td><td>
                	<input type="text" name="nsm_program_cip" value="<?php echo $program['cip'] ?>" />
                </td>
        	</tr><tr>
                <td align="right">
                    <input type="checkbox" name="nsm_program_uc" <?php checked('on', $program['uc']) ?> />
                </td><td>
                    <label for="nsm_program_uc" title="University College supported program. As the University of Maine System’s distance education organization, University College offers access to courses and programs from the seven universities at dozens of locations and online.">University College Program</label>
                </td>
            </tr>
        </table>
        <br />
        <em><strong>Note:</strong> Be sure to update your program spreadsheet if making edits above</em><?php
	}//nsm_render_program_information_meta_box
	
	add_action( 'save_post', 'nsm_save_meta_box_data' );
	function nsm_save_meta_box_data ($post_id) {
		global $post ;

        if (isset($post->post_type)) : 
            if ($post->post_type == 'nsm_program') : 

        		$author_institution_title_iv_code = get_user_meta($post->post_author, 'title_iv_code', true);
                update_post_meta($post_id, '_nsm_program_insitution_title_iv_code', $author_institution_title_iv_code);

        		if (isset($_POST['nsm_program_type'])) update_post_meta($post_id, '_nsm_program_type', $_POST['nsm_program_type']);
        		if (isset($_POST['nsm_program_level'])) update_post_meta($post_id, '_nsm_program_level', $_POST['nsm_program_level']);
        		if (isset($_POST['nsm_program_format'])) update_post_meta($post_id, '_nsm_program_format', $_POST['nsm_program_format']);
        		if (isset($_POST['nsm_program_schedule'])) update_post_meta($post_id, '_nsm_program_schedule', $_POST['nsm_program_schedule']);
                if (isset($_POST['nsm_program_timeframe'])) update_post_meta($post_id, '_nsm_program_timeframe', $_POST['nsm_program_timeframe']);
                if (isset($_POST['nsm_program_cost'])) update_post_meta($post_id, '_nsm_program_cost', $_POST['nsm_program_cost']);
                if (isset($_POST['nsm_program_location'])) update_post_meta($post_id, '_nsm_program_location', $_POST['nsm_program_location']);
        		if (isset($_POST['nsm_program_url'])) update_post_meta($post_id, '_nsm_program_url', $_POST['nsm_program_url']);
        		if (isset($_POST['nsm_program_cip'])) update_post_meta($post_id, '_nsm_program_cip', $_POST['nsm_program_cip']);
                if (isset($_POST['nsm_program_uc'])) :
                    update_post_meta($post_id, '_nsm_program_uc', $_POST['nsm_program_uc']);
                else : 
                    delete_post_meta($post_id, '_nsm_program_uc');
                endif;
            endif;
        endif;
	}//nsm_save_meta_box_data
