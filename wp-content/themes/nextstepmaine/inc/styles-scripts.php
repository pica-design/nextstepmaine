<?php
	//Load the theme styles and scripts scripts
	add_action('wp_enqueue_scripts', 'load_theme_styles_and_scripts');
	function load_theme_styles_and_scripts() {
		if (!is_admin()) : 
	    	global $post, $is_IE, $theme_namespace, $cdn ;
		    /* SCRIPTS */
		    //Use the hosted version of jquery - we need jquery in the header, hence the use of false for the last argument
		    wp_deregister_script( 'jquery' );
		    //1.8.3. is needed for Redactor .. anything above that causes issues
		    wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', '', '', true);
		    wp_enqueue_script( 'jquery' );

		    //Load the theme jquery file - LAST, so it can use the above scripts
			//wp_register_script("jquery-cycle", $cdn->template_scripts_url . "jquery.cycle.all.min.js", array('jquery'), '', true);
			//wp_enqueue_script("jquery-cycle");

			//Load the theme jquery file - LAST, so it can use the above scripts
			wp_register_script("jquery-$theme_namespace", $cdn->template_scripts_url . "jquery.$theme_namespace.js", array("jquery"), '', true);
			wp_enqueue_script("jquery-$theme_namespace");

			/* STYLES */
			//Load our stylesheet
		    wp_register_style("style-$theme_namespace", $cdn->template_styles_url . "style.css", '', null);
		    wp_enqueue_style("style-$theme_namespace");

			//Add our < IE9 scripts and styles
		    if ($is_IE) : 
		    	// Include the file, if needed
			    if ( ! function_exists( 'wp_check_browser_version' ) )
			        include_once( ABSPATH . 'wp-admin/includes/dashboard.php' );
			    
        		// IE version conditional enqueue
			    $response = wp_check_browser_version();
			    //print_r($response);
			    $major_version = explode('.', $response['version']);
			    $major_version = $major_version[0];
			    $minor_version = $minor_version[1];
			    if ($major_version >= 9) :
			        //Include the HTML5 for IE version 9 and lower
				    wp_register_script ('html5shim', "http://html5shim.googlecode.com/svn/trunk/html5.js");
				    wp_enqueue_script ('html5shim');

				    //<!--[if gte IE 9]><style type="text/css">.gradient { filter: none; }</style><![endif]-->
				endif;
				if ($major_version < 9) :
			        //Load our stylesheet
				    wp_register_style("style-$theme_namespace-lt-ie8", $cdn->template_styles_url . "lt-ie8.css", '', null);
				    wp_enqueue_style("style-$theme_namespace-lt-ie8");
				endif;
			endif;//END IE
		endif;
	}    
?>