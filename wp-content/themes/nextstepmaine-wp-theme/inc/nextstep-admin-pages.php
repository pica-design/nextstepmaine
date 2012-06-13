<?php 

	/*********************************************************
	NEXT STEP MAINE DOL JOBS IN DEMAND - IMPORT PAGE
	*********************************************************/
	add_action('admin_menu', 'register_import_dol_excel_file_submenu_page');
	
	function register_import_dol_excel_file_submenu_page () {
		add_submenu_page(
			'edit.php?post_type=nsm_job',
			'Import DOL Excel File',
			'Import',
			'manage_options',
			'import-dol-excel-file',
			'import_dol_excel_file_page_contents'
		);
	} /* END register_import_dol_excel_file_submenu_page */
	
	function import_dol_excel_file_page_contents () {
		
		//A form has been submitted
		if ($_POST) :
			
			$file_path = WP_CONTENT_DIR . "/uploads/dol-data/";
			$file_name = $_FILES['file-upload']['name'];
			
			//Move the uploaded file to it's final resting place
			move_uploaded_file($_FILES['file-upload']['tmp_name'], $filepath . $file_name);
		
			//Open the uploaded csv	
			if (($uploaded_csv = fopen($filepath . $file_name, "r")) !== false) :
				
				//Loop through each row in the csv and parse the contents by the comma delimiter and double-quote quantifier
				while (($job_row = fgetcsv($uploaded_csv, 0, ',', '"')) !== false) :
					
					//DEBUGGING
					//echo "<pre>" . print_r($data_row, true) . "</pre>";
					/*
					Array
					(
						[0] => High School   // nsm_job_category
						[1] => 811			 // _employment_2008
						[2] => 803			 // _employment_2018
						[3] => -1.0%		 // _growth_rate
						[4] => 21			 // _annual_openings
						[5] => $12.26		 // _entry_wage
						[6] => $16.94		 // _median_wage
						[7] => http://online.onetcenter.org/link/summary/49-3021.00		// _onet_link
						[8] => Automotive Body and Related Repairers					// post title
					)
					*/
					
					//Create a custom post for each row
					$job_post_id = wp_insert_post(array(
						'post_type'	  => 'nsm_job',
						'post_status' => 'publish',
						'post_title'  => $job_row[8]
					));	
					
					//Add the category term to the post
					wp_set_object_terms($job_post_id, $job_row[0], 'nsm_job_category');
						
					//Add the remaining data as post meta
					update_post_meta($job_post_id, '_employment_2008', $job_row[1]);
					update_post_meta($job_post_id, '_employment_2018', $job_row[2]);
					update_post_meta($job_post_id, '_growth_rate', $job_row[3]);
					update_post_meta($job_post_id, '_annual_openings', $job_row[4]);
					update_post_meta($job_post_id, '_entry_wage', $job_row[5]);
					update_post_meta($job_post_id, '_median_wage', $job_row[6]);
					update_post_meta($job_post_id, '_onet_link', $job_row[7]);
					
				endwhile;
				
				//Close the opened file
				fclose($uploaded_csv);
			endif;
		endif;
	
		?>
        	<div id="wpbody-content">
            	<div class="wrap">
                	<div id="icon-edit-pages" class="icon32"></div>
	            	<h2>Jobs in Demand in Maine</h2>
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
	
?>