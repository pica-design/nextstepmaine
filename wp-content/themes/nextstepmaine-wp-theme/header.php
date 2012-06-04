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
        <link rel="stylesheet" href="<?php echo get_bloginfo('template_directory');?>/style.css" />
        <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <?php //wp_head() ?>
    </head>
    
    <body>
    	<section class="website-wrapper">
        	
            <section class="masthead">
            	<figure class="website-logo"><img src="<?php bloginfo('template_directory') ?>/images/nextstepmaine-logo.jpg" alt="Next Step Maine Logo" /></figure>
                <article class="website-slogan">opportunities for the<br /> non-traditional student</article>
                <section class="website-controls">
                    <div class="website-quick-links"><?php wp_nav_menu(array('menu' => 'Quick Menu', 'container' => '')) ?></div>
                    <div class="website-search">Search</div>
                </section>
            </section><!-- .masthead -->
            
            <section class="slideshow">
            	<figure class="slideshow-prev"><img src="<?php bloginfo('template_directory') ?>/images/slideshow-prev.png" alt="Previous Slideshow Control" /></figure>
            	<section class="slides">
                    <figure class="slide">
                        <img src="<?php bloginfo('template_directory') ?>/images/slideshow-slide-01.jpg" alt="Slide 01" />
                    </figure>
                </section>
            	<figure class="slideshow-next"><img src="<?php bloginfo('template_directory') ?>/images/slideshow-next.png" alt="Next Slideshow Control" /></figure>                
            </section><!-- .slideshow -->
            
            <section class="next-step-header">
            	<h1>What's your next step?</h1>
                <span class="caption">(CHOOSE FROM ONE OF THE OPTIONS BELOW TO GET STARTED)</span>
            </section><!-- .next-step-header -->
            
            <nav class="next-step">
            	<div class="inner">
                    <?php wp_nav_menu(array('menu' => 'Next Step Menu', 'container' => '')) ?>
                </div>
            </nav><!-- nav.next-step -->