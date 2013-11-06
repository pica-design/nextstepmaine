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
		/*
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
		*/
	}//register_import_dol_excel_file_submenu_page
	
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
			$file_path = WP_CONTENT_DIR . "/uploads/";
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
		endif; ?>
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
	
	
	/*
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
			//Build the newly uploaded file's path
			$file_path = WP_CONTENT_DIR . "/uploads/";
			$file_name = $_FILES['file-upload']['name'];
			//Move the uploaded file to it's final resting place
			move_uploaded_file($_FILES['file-upload']['tmp_name'], $file_path . $file_name);
			//Open the uploaded csv	
			if (($uploaded_csv = fopen($file_path . $file_name, "r")) !== false) :
				//Loop through each row in the csv and parse the contents by the comma delimiter and double-quote quantifier
				//$row_count = 0;
				while (($row = fgetcsv($uploaded_csv, 0, ',', '"')) !== false) :
					//if ($row_count > 1) :
						//DEBUGGING
						//echo "<pre>" . print_r($row, true) . "</pre>";
						*/
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
						/*
						$user_id = wp_insert_user(array(
							'user_login' => $row[0],
							'user_url' => $row[2],
							'user_pass' => $row[1], //by default we'll use the Institution's Title IV Code as the password
							'description' => $row[9]
						));

						if (is_wp_error($user_id)) : 
							//print_r($user_id);
						else :
							update_user_meta($user_id, 'title_iv_code', $row[1]);
							update_user_meta($user_id, 'address', $row[4]);
							update_user_meta($user_id, 'phone', $row[5]);
							update_user_meta($user_id, 'finaid_contact', $row[6]);
							update_user_meta($user_id, 'admission_contact', $row[7]);
							update_user_meta($user_id, 'type', $row[8]);
								
							//Assign the user the 'Author' role
							wp_update_user(array('ID' => $user_id, 'role' => 'author')) ;
						endif;

					//endif;
					$row_count++;
				endwhile;
				//Close the opened file
				fclose($uploaded_csv);
				//Delete the uploaded CSV file
				unlink($file_path . $file_name);
				//Set an update notice
				//$nsm_update_notice = "Inserted " . ($row_count - 1) ." Institutions";
			endif;
		endif ?>
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
	}//import_insitutions_excel_file_page_contents
	*/
	
	function import_programs_excel_file_page_contents () {
		$nsm_update_notice = "";
		//A form has been submitted
		if ($_POST) :
			global $post ;
			//First off we need to remove the previous program posts authored by the current user
			$nsm_programs = new WP_Query(array(
				'post_type' => 'nsm_program',
				'post_author' => get_current_user_id(),
				'posts_per_page' => -1
			));
			while ($nsm_programs->have_posts()) : $nsm_programs->the_post();
				wp_delete_post($post->ID, true);
			endwhile;

			$file_path = WP_CONTENT_DIR . "/uploads/";
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
					//if ($row_count > 2) :
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
						/*
						$institution = get_users(
							array(
								'meta_key' => 'title_iv_code',
								'meta_value' => $row[1]
							)
						);
						*/

						//The author institution should automatically pull from the currently logged in user whom executed the import
						//Create a custom post for each row
						$post_id = wp_insert_post(array(
							'post_type'	  => 'nsm_program',
							'post_status' => 'publish',
							'post_title'  => ucwords(strtolower($row[3])),
							'post_content' => $row[13],
							'post_author' => get_current_user_id()
							//'post_author' => $institution[0]->ID
						));	

						//Add the category terms to the post
						if (strpos($row[12], ',')) :
							//If there are multiple categories seperated by a comma insert them one by one
							$terms = explode(',', $row[12]);
							foreach ($terms as $term) :
								//echo ucwords(strtolower($term));
								wp_set_object_terms($post_id, ucwords(strtolower($term)), 'nsm_program_category');
							endforeach;
						else :
							//Else insert the singular category
							//echo ucwords(strtolower($row[11]));
							wp_set_object_terms($post_id, ucwords(strtolower($row[12])), 'nsm_program_category');
						endif;
						//Add the remaining data as post meta
						update_post_meta($post_id, '_nsm_program_insitution_title_iv_code', $row[1]);
						update_post_meta($post_id, '_nsm_program_cip', $row[2]);
						update_post_meta($post_id, '_nsm_program_type', $row[4]);
						update_post_meta($post_id, '_nsm_program_level', $row[5]);
						update_post_meta($post_id, '_nsm_program_format', $row[6]);
						update_post_meta($post_id, '_nsm_program_location', $row[7]);
						update_post_meta($post_id, '_nsm_program_schedule', $row[8]);
						update_post_meta($post_id, '_nsm_program_url', $row[9]);
						update_post_meta($post_id, '_nsm_program_timeframe', $row[10]);
						update_post_meta($post_id, '_nsm_program_cost', $row[11]);
					//endif;
					$row_count++;	
				endwhile;
				//Close the opened file
				fclose($uploaded_csv);
				//Delete the uploaded CSV file
				unlink($file_path . $file_name);
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
	}//import_programs_excel_file_page_contents
?>