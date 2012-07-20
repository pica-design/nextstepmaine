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
			$file_path = WP_CONTENT_DIR . "/uploads/dol-data/";
			$file_name = $_FILES['file-upload']['name'];
			//Move the uploaded file to it's final resting place
			move_uploaded_file($_FILES['file-upload']['tmp_name'], $filepath . $file_name);
			//Open the uploaded csv	
			if (($uploaded_csv = fopen($filepath . $file_name, "r")) !== false) :
				//Loop through each row in the csv and parse the contents by the comma delimiter and double-quote quantifier
				$row_count = 0;
				while (($row = fgetcsv($uploaded_csv, 0, ',', '"')) !== false) :
					
					//DEBUGGING
					//echo "<pre>" . print_r($row, true) . "</pre>";
					/*
					Array
					(
						[0] => 49-3021		//_soc_code
						[1] => Automotive Body and Related Repairers	// post title
						[2] => 811 			// _employment_2008
						[3] => 803			// _employment_2018
						[4] => -1.0%		// _growth_rate
						[5] => 21			// _annual_openings
						[6] => $12.26		// _entry_wage
						[7] => $16.94		// _median_wage
						[8] => http://online.onetcenter.org/link/summary/49-3021.00		// _onet_link
						[9] => Automotive Body and Related Repairers	// post title - DUPLICATE - uneeded
 						[10] => High School // nsm_job_category
					)
					
					
					*/
					
					//Connect to our O*NET database
					$onet = new wpdb('root', '1309piCa', 'onet', 'localhost');
					
					//Select some information about the current occupation
					$occupation = $onet->get_results("SELECT description FROM occupation_data WHERE onetsoc_code LIKE '%{$row[0]}%'");
					
					//Create a custom post for each row
					$post_id = wp_insert_post(array(
						'post_type'	  => 'nsm_job',
						'post_status' => 'publish',
						'post_title'  => $row[1],
						'post_content' => $occupation[0]->description
					));	
					
					//Add the category term to the post
					wp_set_object_terms($post_id, $row[10], 'nsm_job_education_requirement');
						
					//Add the remaining data as post meta
					update_post_meta($post_id, '_nsm_job_soc_code', $row[0]);
					update_post_meta($post_id, '_nsm_job_employment_2008', $row[2]);
					update_post_meta($post_id, '_nsm_job_employment_2018', $row[3]);
					update_post_meta($post_id, '_nsm_job_growth_rate', $row[4]);
					update_post_meta($post_id, '_nsm_job_annual_openings', $row[5]);
					update_post_meta($post_id, '_nsm_job_entry_wage', $row[6]);
					update_post_meta($post_id, '_nsm_job_median_wage', $row[7]);
					update_post_meta($post_id, '_nsm_job_onet_link', $row[8]);
					$row_count++;
				endwhile;
				//Close the opened file
				fclose($uploaded_csv);
				//Set an update notice
				$nsm_update_notice = "Inserted $row_count rows";
			endif;
		endif;
	
		?>
        	<div id="wpbody-content">
            	<div class="wrap">
                	<div id="icon-edit-pages" class="icon32"></div>
	            	<h2>Jobs in Demand in Maine</h2>
                    <?php if (!empty($nsm_update_notice)) : ?>
                    <div class="updated"><p><?php echo $nsm_update_notice ?></p></div>
                    <?php endif ?>
                    <h3>Import a list of jobs via the 'Download Excel' link on Maine DOL</h3>
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
			$file_path = WP_CONTENT_DIR . "/uploads/dol-data/";
			$file_name = $_FILES['file-upload']['name'];
			
			//Move the uploaded file to it's final resting place
			move_uploaded_file($_FILES['file-upload']['tmp_name'], $filepath . $file_name);
		
			//Open the uploaded csv	
			if (($uploaded_csv = fopen($filepath . $file_name, "r")) !== false) :
				
				//Loop through each row in the csv and parse the contents by the comma delimiter and double-quote quantifier
				$row_count = 0;
				while (($row = fgetcsv($uploaded_csv, 0, ',', '"')) !== false) :
					
					if ($row > 0) :
					
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
						    [6] => Financial Aid Phone
						    [7] => Financial Aid Email
						    [8] => Financial Aid Website
						    [9] => Admissions Contact Phone
						    [10] => Admissions Contact Email
						    [11] => Category
						    [12] => Description
						)
						*/
						
						//Create a custom post for each row
						$post_id = wp_insert_post(array(
							'post_type'	  => 'nsm_institution',
							'post_status' => 'publish',
							'post_title'  => $row[0],
							'post_content' => $row[12]
						));	
						
						//Add the category term to the post
						wp_set_object_terms($post_id, $row[11], 'nsm_institution_category');
							
						//Add the remaining data as post meta
						update_post_meta($post_id, '_nsm_institution_title_iv_code', $row[1]);
						update_post_meta($post_id, '_nsm_institution_website_url', $row[2]);
						update_post_meta($post_id, '_nsm_institution_logo', $row[3]);
						update_post_meta($post_id, '_nsm_institution_address', $row[4]);
						update_post_meta($post_id, '_nsm_institution_phone', $row[5]);
						update_post_meta($post_id, '_nsm_institution_finaid_phone', $row[6]);
						update_post_meta($post_id, '_nsm_institution_finaid_email', $row[7]);
						update_post_meta($post_id, '_nsm_institution_finaid_website', $row[8]);
						update_post_meta($post_id, '_nsm_institution_admission_phone', $row[9]);
						update_post_meta($post_id, '_nsm_institution_admission_email', $row[10]);
						
					endif;
					
					$row_count++;
				endwhile;
				//Close the opened file
				fclose($uploaded_csv);
				//Set an update notice
				$nsm_update_notice = "Inserted $row_count rows";
			endif;
		endif;
	
		?>
        <div id="wpbody-content">
            <div class="wrap">
                <div id="icon-edit-pages" class="icon32"></div>
                <h2>Maine Institutions</h2>
                <?php if (!empty($nsm_update_notice)) : ?>
                <div class="updated"><p><?php echo $nsm_update_notice ?></p></div>
                <?php endif ?>
                <h3>Import a list of insititutions</h3>
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
			
			$file_path = WP_CONTENT_DIR . "/uploads/dol-data/";
			$file_name = $_FILES['file-upload']['name'];
			
			//Move the uploaded file to it's final resting place
			move_uploaded_file($_FILES['file-upload']['tmp_name'], $filepath . $file_name);
			
			
			
			//Open the uploaded csv	
			if (($uploaded_csv = fopen($filepath . $file_name, "r")) !== false) :
				
				ini_set("auto_detect_line_endings", true);
				
				//Loop through each row in the csv and parse the contents by the comma delimiter and double-quote quantifier
				$row_count = 0;
				while (($row = fgetcsv($uploaded_csv, 0, ',', '"')) !== false) :
					
					//DEBUGGING
					//echo "<pre>" . print_r($row, true) . "</pre>";
					
					/*
					Array
					(
					    [0] => Institution Name
					    [1] => Title IV School Code
					    [2] => Program Title
					    [3] => Program Type
					    [4] => Discipline
					    [5] => Program Level
					    [6] => Program Format 
					    [7] => Location
					    [8] => Schedule (if possible)
					    [9] => Program URL
					    [10] => Avg Timeframe to Complete in Hours
					    [11] => Avg Cost Per Credit Hour
					    [12] => Program Category 
					    [13] => Program Description
					)
					*/
					
					//Omit the first 'headings' row			
					if ($row_count > 0) :
						//if ($row_count < 5) :
							
							//Create a custom post for each row
							$post_id = wp_insert_post(array(
								'post_type'	  => 'nsm_program',
								'post_status' => 'publish',
								'post_title'  => $row[2],
								'post_content' => $row[13]
							));	
							
							//Add the category terms to the post
							$terms = explode(',', $row[12]);
							foreach ($terms as $term) :
								wp_set_object_terms($post_id, $term, 'nsm_program_category');
							endforeach;
								
							//Add the category term to the post
							//wp_set_object_terms($post_id, $row[0], 'nsm_program_institution');
							
							//Add the remaining data as post meta
							update_post_meta($post_id, '_nsm_program_insitution_title_iv_code', $row[1]);
							update_post_meta($post_id, '_nsm_program_type', $row[3]);
							update_post_meta($post_id, '_nsm_program_discipline', $row[4]);
							update_post_meta($post_id, '_nsm_program_level', $row[5]);
							update_post_meta($post_id, '_nsm_program_format', $row[6]);
							update_post_meta($post_id, '_nsm_program_location', $row[7]);
							update_post_meta($post_id, '_nsm_program_schedule', $row[8]);
							update_post_meta($post_id, '_nsm_program_url', $row[9]);
							update_post_meta($post_id, '_nsm_program_timeframe', $row[10]);
							update_post_meta($post_id, '_nsm_program_cost', $row[11]);
							
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
								
						//endif;
					endif;
					$row_count++;	
				endwhile;
				//Close the opened file
				fclose($uploaded_csv);
				//Set an update notice
				$nsm_update_notice = "Inserted $row_count rows";
			endif;
		endif;
		?>
        <div id="wpbody-content">
            <div class="wrap">
                <div id="icon-edit-pages" class="icon32"></div>
                <h2>Maine Institution Programs</h2>
                <?php if (!empty($nsm_update_notice)) : ?>
                <div class="updated"><p><?php echo $nsm_update_notice ?></p></div>
                <?php endif ?>
                <h3>Import a list of Programs</h3>
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