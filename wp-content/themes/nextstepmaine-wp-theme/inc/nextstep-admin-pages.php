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
			
			//Move the uploaded file to it's final resting place
			move_uploaded_file($_FILES['file-upload']['tmp_name'], WP_CONTENT_DIR . "/uploads/dol-data/" . $_FILES['file-upload']['name']);
		
			//Open the file
			
			//Loop through each line and explode the commas
			
			//!!! how to get explode() to ignore commas in quotes?!?!?!
			
			//ARG
		
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