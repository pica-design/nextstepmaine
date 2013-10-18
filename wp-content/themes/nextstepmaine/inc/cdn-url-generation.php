<?php
	class CDN {
		//Define our variables

		#General URL's
		public $domain ;
		public $mainsite_domain ;
		public $path ;
		public $site_url ;
		public $template_directory ;

		#CDN Subdomains
		public $images_subdomain ;
		public $styles_subdomain ;
		public $scripts_subdomain ;
		public $fonts_subdomain ;
		public $downloads_subdomain ;

		#CDN URL's
		public $images_url ;
		public $styles_url ;
		public $scripts_url ;
		public $fonts_url ;
		public $downloads_url ;

		#CDN TEMPLATE URL's
		public $template_images_url ;
		public $template_styles_url ;
		public $template_scripts_url ;
		public $template_fonts_url ;
		public $template_downloads_url ;

		#MULTISITE URL'S
		public $mainsite_images_url ;
		public $mainsite_styles_url ;
		public $mainsite_scripts_url ;
		public $mainsite_fonts_url ;
		public $mainsite_downloads_url ;

		#MULTISITE TEMPLATE URL'S
		public $mainsite_template_images_url ;
		public $mainsite_template_styles_url ;
		public $mainsite_template_scripts_url ;
		public $mainsite_template_fonts_url ;
		public $mainsite_template_downloads_url ;

		//Construct the class instance
		public function __construct ($use_subdomains = 1) {
			//The theme_namespace is defined in functions.php in the theme setup function
			//It simply contains the theme folder name, i.e. 'pica'
			global $theme_namespace;
			//$domain will be domain.com
			$this->domain = str_replace('www.', '', $_SERVER['HTTP_HOST']); 
			//$mainsite_domain will be domain.com or domain.com/foo (depending on the root folder)
			$this->mainsite_url = str_replace('www.', '', str_replace('http://', '', get_site_url(1)));
			//$path will either be / or /subdirectory
			$this->path = str_replace('index.php', '', $_SERVER['PHP_SELF']); 
			//$site_url will either be domain.com/ or domain.com/subdirectory
			$this->site_url = $this->domain . $this->path; 
			//$template_directory will either be domain.com/wp-content/themes/themefolder or domain.com/subdirectory/wp-content/themes/themefolder
			$this->template_directory = $this->domain . $this->path . 'wp-content/themes/' . $theme_namespace . '/' ;
			//Remove wp-signup.php from the url if it is present
			$this->template_directory = str_replace('wp-signup.php', '', $this->template_directory);
			//Remove wp-activate.php from the url if it is present
			$this->template_directory = str_replace('wp-activate.php', '', $this->template_directory);

			//Acertain the current template directory
			//NOTE: This will be the parent theme if there is a child theme in use 
			$template_directory = get_template_directory();
			$template_directory = substr($template_directory, strrpos($template_directory, '/') + 1);
			//$mainsite_template_directory will be domain.com/wp-content/themes/
			$this->mainsite_template_directory = $this->mainsite_url . '/wp-content/themes/' . $template_directory . '/' ;

			//Define the CDN subdomains
			if ($use_subdomains) : 
				$this->images_subdomain 	= '//images.';
				$this->styles_subdomain 	= '//scripts.';
				$this->scripts_subdomain 	= '//styles.';
				$this->fonts_subdomain 		= '//fonts.';
				$this->downloads_subdomain 	= '//downloads.';
			else :
				//Local when www. does not exist
				$this->images_subdomain 	= '//';
				$this->styles_subdomain 	= '//';
				$this->scripts_subdomain 	= '//';
				$this->fonts_subdomain 		= '//';
				$this->downloads_subdomain 	= '//';
			endif;

			#CDN URL's
			$this->images_url 	 = $this->images_subdomain 	  . $this->site_url ;
			$this->styles_url 	 = $this->styles_subdomain 	  . $this->site_url ;
			$this->scripts_url 	 = $this->scripts_subdomain   . $this->site_url ;
			$this->fonts_url 	 = $this->fonts_subdomain 	  . $this->site_url ;
			$this->downloads_url = $this->downloads_subdomain . $this->site_url ;

			#CDN TEMPLATE URL's
			$this->template_images_url 	  = $this->images_subdomain    . $this->template_directory  . 'images/' ;
			$this->template_styles_url 	  = $this->styles_subdomain    . $this->template_directory  . 'stylesheets/' ;
			$this->template_scripts_url   = $this->scripts_subdomain   . $this->template_directory  . 'scripts/'  ;
			$this->template_fonts_url 	  = $this->fonts_subdomain     . $this->template_directory  . 'fonts/'  ;
			$this->template_downloads_url = $this->downloads_subdomain . $this->template_directory ;

			#MULTISITE URL's
			$this->mainsite_images_url 	 	 = $this->images_subdomain 	  . $this->mainsite_url ;
			$this->mainsite_styles_url 	 	 = $this->styles_subdomain 	  . $this->mainsite_url ;
			$this->mainsite_scripts_url 	 = $this->scripts_subdomain   . $this->mainsite_url ;
			$this->mainsite_fonts_url 	 	 = $this->fonts_subdomain 	  . $this->mainsite_url ;
			$this->mainsite_downloads_url 	 = $this->downloads_subdomain . $this->mainsite_url ;

			#MULTISITE TEMPLATE URL's
			$this->mainsite_template_images_url 	  = $this->images_subdomain    . $this->mainsite_template_directory  . 'images/' ;
			$this->mainsite_template_styles_url 	  = $this->styles_subdomain    . $this->mainsite_template_directory  . 'stylesheets/' ;
			$this->mainsite_template_scripts_url      = $this->scripts_subdomain   . $this->mainsite_template_directory  . 'scripts/'  ;
			$this->mainsite_template_fonts_url 	  	  = $this->fonts_subdomain     . $this->mainsite_template_directory  . 'fonts/'  ;
			$this->mainsite_template_downloads_url    = $this->downloads_subdomain . $this->mainsite_template_directory ;
		}//__construct
	}//class CDN
?>