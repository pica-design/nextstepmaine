<?php 
	//Pull our post gallery if one exists
	$gallery = new Post_Gallery;
	
	//Grab the page seo settings
	$metadesc = get_post_meta($post->ID, '_yoast_wpseo_metadesc', true);
	$metakeywords = get_post_meta($post->ID, '_yoast_wpseo_metakeywords', true);
?>
<!DOCTYPE HTML>
<html>
    <head>
    	<!--
             ____                            ____                                          
            /\  _`\   __                    /\  _`\                  __                    
            \ \ \L\ \/\_\    ___     __     \ \ \/\ \     __    ____/\_\     __     ___    
             \ \ ,__/\/\ \  /'___\ /'__`\    \ \ \ \ \  /'__`\ /',__\/\ \  /'_ `\ /' _ `\  
              \ \ \/  \ \ \/\ \__//\ \L\.\_   \ \ \_\ \/\  __//\__, `\ \ \/\ \L\ \/\ \/\ \ 
               \ \_\   \ \_\ \____\ \__/.\_\   \ \____/\ \____\/\____/\ \_\ \____ \ \_\ \_\
                \/_/    \/_/\/____/\/__/\/_/    \/___/  \/____/\/___/  \/_/\/___L\ \/_/\/_/
                                                                             /\____/       
                                                                             \_/__/
                                                                                                                                                         
            Graphic Design & Marketing | www.picadesign.com
        -->
        <meta charset="UTF-8">
        <title><?php wp_title() ?></title>
        <link rel="stylesheet" media="screen" href="<?php echo get_bloginfo('template_directory');?>/stylesheets/screen.css" />
        <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <!--[if gte IE 9]>
          <style type="text/css">
            .gradient {
               filter: none;
            }
          </style>
        <![endif]-->
        <?php //wp_head() ?>
    </head>
    
    <body>
    	<section class="website-wrapper">
        	
            <section class="masthead">
            	<figure class="website-logo">
                	<a href="<?php bloginfo('url') ?>" title="Next Step Maine Homepage">
	                	<img src="<?php bloginfo('template_directory') ?>/images/nextstepmaine-logo.jpg" alt="Next Step Maine Logo" />
                    </a>
                </figure>
                <article class="website-slogan">opportunities for the<br /> non-traditional student</article>
                <section class="website-controls">
                    <div class="website-quick-links"><?php wp_nav_menu(array('menu' => 'Quick Menu', 'container' => '')) ?></div>
                    <div class="website-search"><?php get_search_form(); ?></div>
                </section>
            </section><!-- .masthead -->
            
            <?php if (!empty($gallery->attachments)) : ?>
            <section class="slideshow">
            	<figure class="slideshow-prev"></figure>
            	<section class="slides">
                    <?php foreach ($gallery->attachments as $key => $slide) : $slide_class = ($key == 0) ? "first" : ""; ?>
                        
                        <figure class="slide <?php echo $slide_class ?>"><img src="<?php echo get_bloginfo('url') . "/wp-content/uploads/" . $slide->meta_data['_wp_attachment_metadata']['sizes']['slideshow']['file'] ?>" alt="<?php echo $slide->post_title ?>" /></figure><?php endforeach ?>
                </section>
            	<figure class="slideshow-next"></figure>                
            </section><!-- .slideshow -->
            <?php endif ?>
            
            <section class="next-step-header">
            	<h1>What's your next step?</h1>
                <span class="caption">(CHOOSE FROM ONE OF THE OPTIONS BELOW TO GET STARTED)</span>
            </section><!-- .next-step-header -->
            
            <nav class="next-step">
            	<div class="inner">
                    <?php wp_nav_menu(array('menu' => 'Next Step Menu', 'container' => '', 'before' => '<h2>', 'after' => '</h2>')) ?>
                </div>
            </nav><!-- nav.next-step -->