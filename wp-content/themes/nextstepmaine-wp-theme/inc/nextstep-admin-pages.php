<?php 
	/*********************************************************
	NEXT STEP MAINE REGISTER IMPORT PAGES
	*********************************************************/
	add_action('admin_menu', 'register_subpage_menus');
	
	function register_subpage_menus () {
		add_submenu_page(
			'edit.php?post_type=nsm_job',
			'Import DOL Excel File',
			'Import',
			'manage_options',
			'import-dol-excel-file',
			'import_dol_excel_file_page_contents'
		);
		add_submenu_page(
			'edit.php?post_type=nsm_institution',
			'Import Institutions Excel File',
			'Import',
			'manage_options',
			'import-insitutions-excel-file',
			'import_insitutions_excel_file_page_contents'
		);
		add_submenu_page(
			'edit.php?post_type=nsm_program',
			'Import Programs Excel File',
			'Import',
			'manage_options',
			'import-programs-excel-file',
			'import_programs_excel_file_page_contents'
		);
	} /* END register_import_dol_excel_file_submenu_page */
	
	
	/*********************************************************
	NEXT STEP MAINE DOL JOBS IN DEMAND - IMPORT PAGE
	*********************************************************/
	function import_dol_excel_file_page_contents () {
		$nsm_update_notice = "";
		//A form has been submitted
		if ($_POST) :
			global $post ;
			
			//Connect to our O*NET database
			$onet = new wpdb(DB_USER, DB_PASSWORD, 'onet', DB_HOST);

			//First off we need to remove the previous institution posts
			$nsm_jobs = new WP_Query('post_type=nsm_job&posts_per_page=-1');
			while ($nsm_jobs->have_posts()) : $nsm_jobs->the_post(); wp_delete_post($post->ID, true); endwhile;
			
			$file_path = WP_CONTENT_DIR . "/uploads/dol-data/";
			$file_name = $_FILES['file-upload']['name'];
			//Move the uploaded file to it's final resting place
			move_uploaded_file($_FILES['file-upload']['tmp_name'], $file_path . $file_name);
			//Open the uploaded csv	
			if (($uploaded_csv = fopen($file_path . $file_name, "r")) !== false) :
				//Loop through each row in the csv and parse the contents by the comma delimiter and double-quote quantifier
				$row_count = 0;

				while (($row = fgetcsv($uploaded_csv, 0, ',', '"')) !== false) :
					
					//DEBUGGING
					//echo "<pre>" . print_r($row, true) . "</pre>";

					/*
					Array
					(
					    [0] => High Growth
					    [1] => SOC Code
					    [2] => SOCTitle
					    [3] => Base Empl
					    [4] => Proj Empl
					    [5] => Percent Change
					    [6] => Total Openings
					    [7] => 2011 Entry Wage
					    [8] => 2011 Median Wage
					    [9] => Education/ Training Required
					    [10] => URL
					    [11] => O*NET Summary Report URL
					)
					*/
					
					//Ignore the header row
					if ($row_count > 0) : 
					
						//Select some information about the current occupation
						$occupation_description = "";
						$occupation = $onet->get_results("SELECT description FROM occupation_data WHERE onetsoc_code LIKE '%{$row[1]}%'");
						//print_r($occupation);
						if (is_array($occupation) && isset($occupation[0])) : 
							$occupation_description = $occupation[0]->description;
						endif;

						//Create a custom post for each row
						$post_id = wp_insert_post(array(
							'post_type'	  => 'nsm_job',
							'post_status' => 'publish',
							'post_title'  => $row[2],
							'post_content' => $occupation_description
						));	
						
						//Add the category term to the post
						wp_set_object_terms($post_id, $row[9], 'nsm_job_education_requirement');
							
						//Add the remaining data as post meta
						update_post_meta($post_id, '_nsm_job_soc_code', $row[1]);
						update_post_meta($post_id, '_nsm_job_base_employment', $row[3]);
						update_post_meta($post_id, '_nsm_job_projected_employment', $row[4]);
						update_post_meta($post_id, '_nsm_job_growth_rate', $row[5]);
						update_post_meta($post_id, '_nsm_job_annual_openings', $row[6]);
						update_post_meta($post_id, '_nsm_job_entry_wage', $row[7]);
						update_post_meta($post_id, '_nsm_job_median_wage', $row[8]);
						update_post_meta($post_id, '_nsm_job_onet_link', $row[10]);
					endif;
					$row_count++;
				endwhile;
				//Close the opened file
				fclose($uploaded_csv);
				//Set an update notice
				$nsm_update_notice = "Inserted $row_count Jobs";
			endif;
		endif;
	
		?>
        	<div id="wpbody-content">
            	<div class="wrap">
	            	<div id="icon-edit" class="icon32 icon32-posts-nsm_job"></div>
	            	<h2>Jobs in Demand in Maine</h2>
                    <?php if (!empty($nsm_update_notice)) : ?>
                    <div class="updated"><p><?php echo $nsm_update_notice ?></p></div>
                    <?php endif ?>
                    <h3>Import a list of jobs via the 'Download Excel' link on Maine DOL</h3>
                    <p><em>NOTE: All previous jobs will be removed before importing the new ones.</em></p>
                    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI'] ?>" enctype="multipart/form-data">
                    	<ul>
                        	<li>
                            	<label for="file-upload">Select a comma-separated values (.CSV) version of your Excel file</label>
                            	<input type="file" name="file-upload" />
                            </li>
                            <li>
                            	<input class="button-primary" type="submit" name="upload" value="<?php _e('Upload') ?>" id="submitbutton" />
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        <?php
	} /* END import_dol_excel_file_page_contents */
	
	
	
	function import_insitutions_excel_file_page_contents () {
		$nsm_update_notice = "";
		//A form has been submitted
		if ($_POST) :		
			global $post ;
			//First off we need to remove the previous institution posts
			$nsm_institutions = new WP_Query('post_type=nsm_institution&posts_per_page=-1');
			while ($nsm_institutions->have_posts()) : $nsm_institutions->the_post();
				wp_delete_post($post->ID, true);
			endwhile;
			
			$file_path = WP_CONTENT_DIR . "/uploads/dol-data/";
			$file_name = $_FILES['file-upload']['name'];
			
			//Move the uploaded file to it's final resting place
			move_uploaded_file($_FILES['file-upload']['tmp_name'], $file_path . $file_name);
		
			//Open the uploaded csv	
			if (($uploaded_csv = fopen($file_path . $file_name, "r")) !== false) :
				
				//Loop through each row in the csv and parse the contents by the comma delimiter and double-quote quantifier
				$row_count = 0;
				while (($row = fgetcsv($uploaded_csv, 0, ',', '"')) !== false) :
					
					if ($row_count > 1) :
					
						//DEBUGGING
						//echo "<pre>" . print_r($row, true) . "</pre>";
						
						/*
						Array
						(
						    [0] => Institution Name
						    [1] => Title IV School Code
						    [2] => Institution Website URL
						    [3] => Institution Logo URL
						    [4] => Institution Physical Address
						    [5] => Institution Phone Number
						    [6] => Financial Aid Contact
						    [7] => Admissions Contact
						    [8] => Category
						    [9] => Description
						)
						*/
						
						//Create a custom post for each row
						$post_id = wp_insert_post(array(
							'post_type'	  => 'nsm_institution',
							'post_status' => 'publish',
							'post_title'  => $row[0],
							'post_content' => $row[9]
						));	
					
						
						//Add the category term to the post
						wp_set_object_terms($post_id, $row[8], 'nsm_institution_category');
							
						//Add the remaining data as post meta
						update_post_meta($post_id, '_nsm_institution_title_iv_code', $row[1]);
						update_post_meta($post_id, '_nsm_institution_website_url', $row[2]);
						update_post_meta($post_id, '_nsm_institution_logo', $row[3]);
						update_post_meta($post_id, '_nsm_institution_address', $row[4]);
						update_post_meta($post_id, '_nsm_institution_phone', $row[5]);
						update_post_meta($post_id, '_nsm_institution_finaid_contact', $row[6]);
						update_post_meta($post_id, '_nsm_institution_admission_contact', $row[7]);
						

						/*
							REMOVE!! - we used these for a period but ultimately decided not to use them
							_nsm_institution_finaid_phone
							_nsm_institution_finaid_email
							_nsm_institution_finaid_website
							_nsm_institution_admission_phone
							_nsm_institution_admission_email
						*/
					endif;
					
					$row_count++;
				endwhile;
				//Close the opened file
				fclose($uploaded_csv);
				//Set an update notice
				$nsm_update_notice = "Inserted " . ($row_count - 1) ." Institutions";
			endif;
			
		endif;
	
		?>
        <div id="wpbody-content">
            <div class="wrap">
                <div id="icon-edit" class="icon32 icon32-posts-nsm_institution"></div>
                <h2>Maine Institutions</h2>
                <?php if (!empty($nsm_update_notice)) : ?>
                <div class="updated"><p><?php echo $nsm_update_notice ?></p></div>
                <?php endif ?>
                <h3>Import a list of insititutions</h3>
                <p><em>NOTE: All previous institutions will be removed before importing the new ones.</em></p>
                <form method="POST" action="<?php echo $_SERVER['REQUEST_URI'] ?>" enctype="multipart/form-data">
                    <ul>
                        <li>
                            <label for="file-upload">Select a comma-separated values (.CSV) version of your Excel file</label>
                            <input type="file" name="file-upload" />
                        </li>
                        <li>
                            <input class="button-primary" type="submit" name="upload" value="<?php _e('Upload') ?>" id="submitbutton" />
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <?php
	}
	
	
	function import_programs_excel_file_page_contents () {
		$nsm_update_notice = "";
		//A form has been submitted
		if ($_POST) :
			global $post ;
			//First off we need to remove the previous program posts
			$nsm_programs = new WP_Query('post_type=nsm_program&posts_per_page=-1');
			$program_count = 0;
			while ($nsm_programs->have_posts()) : $nsm_programs->the_post();
				wp_delete_post($post->ID, true);
				$program_count++;
			endwhile;

			$file_path = WP_CONTENT_DIR . "/uploads/dol-data/";
			$file_name = $_FILES['file-upload']['name'];
			
			//Move the uploaded file to it's final resting place
			move_uploaded_file($_FILES['file-upload']['tmp_name'], $file_path . $file_name);
			
			//Open the uploaded csv	
			if (($uploaded_csv = fopen($file_path . $file_name, "r")) !== false) :
				
				ini_set("auto_detect_line_endings", true);
				
				//Loop through each row in the csv and parse the contents by the comma delimiter and double-quote quantifier
				$row_count = 0;
				while (($row = fgetcsv($uploaded_csv, 0, ',', '"')) !== false) :
					
					
					
					//Omit the first 'headings' row	
					if ($row_count > 2) :
						
						//DEBUGGING
						//echo "<pre>" . print_r($row, true) . "</pre>";
						
						/*
						Array
						(
						    [0] => Institution Name
						    [1] => Title IV School Code
						    [2] => Program Title
						    [3] => Program Type
						    [4] => Program Level
						    [5] => Program Format 
						    [6] => Location
						    [7] => Schedule (if possible)
						    [8] => Program URL
						    [9] => Avg Timeframe to Complete in Hours
						    [10] => Avg Cost Per Credit Hour
						    [11] => Program Category 
						    [12] => Program Description
						)
						*/
						
						
						//Create a custom post for each row
						$post_id = wp_insert_post(array(
							'post_type'	  => 'nsm_program',
							'post_status' => 'publish',
							'post_title'  => ucwords(strtolower($row[2])),
							'post_content' => $row[12]
						));	
					
						/*
							THIS IS NOT PROPERLY LOWERCASING THE CATEGORY NAMES
								* ODD - this works fine on the live server
						*/

						//Add the category terms to the post
						if (strpos($row[11], ',')) :
							//If there are multiple categories seperated by a comma insert them one by one
							$terms = explode(',', $row[11]);
							foreach ($terms as $term) :
								//echo ucwords(strtolower($term));
								wp_set_object_terms($post_id, ucwords(strtolower($term)), 'nsm_program_category');
							endforeach;
						else :
							//Else insert the singular category
							//echo ucwords(strtolower($row[11]));
							wp_set_object_terms($post_id, ucwords(strtolower($row[11])), 'nsm_program_category');
						endif;

						
						//Add the remaining data as post meta
						update_post_meta($post_id, '_nsm_program_insitution_title_iv_code', $row[1]);
						update_post_meta($post_id, '_nsm_program_type', $row[3]);
						update_post_meta($post_id, '_nsm_program_level', $row[4]);
						update_post_meta($post_id, '_nsm_program_format', $row[5]);
						update_post_meta($post_id, '_nsm_program_location', $row[6]);
						update_post_meta($post_id, '_nsm_program_schedule', $row[7]);
						update_post_meta($post_id, '_nsm_program_url', $row[8]);
						update_post_meta($post_id, '_nsm_program_timeframe', $row[9]);
						update_post_meta($post_id, '_nsm_program_cost', $row[10]);
						
						//Select the institution that this program belongs to based on the Title IV Code
						$institution = new WP_Query(array(
							'post_type'	 => 'nsm_institution',						
							'meta_key' 	 => '_nsm_institution_title_iv_code',
							'meta_value' => $row[1]
						));
						
						//Connect this program with the parent institution 
						p2p_type('Program Institution')->connect($post_id, $institution->posts[0]->ID, array(
							'date' => current_time('mysql')
						));
						
					endif;
					
					$row_count++;	
				endwhile;
				//Close the opened file
				fclose($uploaded_csv);
				//Set an update notice
				$nsm_update_notice = "Inserted " . ($row_count - 1) ." programs";
			endif;
		endif;
		?>
        <div id="wpbody-content">
            <div class="wrap">
                <div id="icon-edit" class="icon32 icon32-posts-nsm_program"></div>
                <h2>Maine Institution Programs</h2>
                <?php if (!empty($nsm_update_notice)) : ?>
                <div class="updated"><p><?php echo $nsm_update_notice ?></p></div>
                <?php endif ?>
                <h3>Import a list of Programs</h3>
                <p><em>NOTE: All previous programs will be removed before importing the new ones.</em></p>
                <form method="POST" action="<?php echo $_SERVER['REQUEST_URI'] ?>" enctype="multipart/form-data">
                    <ul>
                        <li>
                            <label for="file-upload">Select a comma-separated values (.CSV) version of your Excel file</label>
                            <input type="file" name="file-upload" />
                        </li>
                        <li>
                            <input class="button-primary" type="submit" name="upload" value="<?php _e('Upload') ?>" id="submitbutton" />
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <?php
	}
?>