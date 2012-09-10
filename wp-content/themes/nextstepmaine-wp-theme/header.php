<?php 
    //Determine if the user is viewing a nextstep page
    if (is_object($post)) : 
        switch ($post->post_name) :
            case 'get-a-ged' : $nextstep = 0 ; break;
            case 'start-college' : $nextstep = 1 ; break;
            case 'finish-college' : $nextstep = 2 ; break;
            case 'change-my-career' : $nextstep = 3 ; break;
            case 'home' : 
                $nextstep = false ;
                setcookie("nextstep", "", time() + 3600, "/");
            break;
            default : $nextstep = false ; break;
        endswitch;

        //Pull our post gallery if one exists
        $gallery = new Post_Gallery($post->ID);

        //Grab the page seo settings
        $metadesc = get_post_meta($post->ID, '_yoast_wpseo_metadesc', true);
        $metakeywords = get_post_meta($post->ID, '_yoast_wpseo_metakeywords', true);
    else :
         $nextstep = false ;
    endif;
    //If they are we'll set a cookie with the step value (so we can keep the menu positioned on their step)
        //e.g. Viewed 'Change my Career' and then clicked around the website, we want to keep 'Change my Career' highlighted
    if ($nextstep !== false) : setcookie("nextstep", $nextstep, time() + 3600, "/"); endif ;
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
                                                                                                                                                         
            Design + Marketing | www.picadesign.com
        -->
        <meta charset="UTF-8">
        <title><?php wp_title() ?></title>
        <meta name="msvalidate.01" content="5EEAF8559BB30800CEB7EB72D4B159B9" />
        <meta property="og:title" content="<?php bloginfo('name') ?>" />
        <meta property="og:description" content="<?php bloginfo('description') ?>" />
        <meta property="og:image" content="<?php bloginfo('template_directory') ?>/images/content/nextstepmaine-logo.jpg" />
        <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/stylesheets/style.css" />
        <link rel="shortcut icon" href="<?php bloginfo('template_directory') ?>/images/content/favicon.jpg" />
        <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <!--[if gte IE 9]><style type="text/css">.gradient { filter: none; }</style><![endif]-->
        <?php //wp_head() ?>
        <script type="text/javascript">
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-4265805-56']);
          _gaq.push(['_trackPageview']);
          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
        </script>
    </head>
    <body>
    	<section class="website-wrapper">
            <section class="masthead">
            	<figure class="website-logo">
                	<a href="<?php bloginfo('url') ?>" title="Next Step Maine Homepage">
	                	<img src="<?php bloginfo('template_directory') ?>/images/content/nextstepmaine-logo.jpg" alt="Next Step Maine Logo" />
                    </a>
                </figure>
                <article class="website-slogan">Connect to education.<br />Launch your future.</article>
                <section class="website-controls">
                    <div class="website-quick-links"><?php wp_nav_menu(array('menu' => 'Mastehad Quick Menu', 'container' => '')) ?></div>
                    <div class="clear"></div>
                    <div class="website-search"><?php get_search_form(); ?></div>
                </section>
            </section><!-- .masthead -->
            
            <?php if (!empty($gallery->attachments)) : ?>
            <section class="slideshow">
            	<figure class="slideshow-prev"></figure>
            	<section class="slides">
                    <?php foreach ($gallery->attachments as $key => $slide) : $slide_class = ($key == 0) ? "first" : ""; ?>
                        <figure class="slide <?php echo $slide_class ?>">
                            <?php
                                if (isset($slide->meta_data['_wp_attachment_metadata']['sizes']['slideshow'])) :
                                    $slide_source = get_bloginfo('url') . "/wp-content/uploads/" . $slide->meta_data['_wp_attachment_metadata']['sizes']['slideshow']['file'];
                                else :
                                    $slide_source = $slide->guid;
                                endif;
                            ?>
                            <img src="<?php echo $slide_source ?>" alt="<?php echo $slide->post_title ?>" />
                        </figure><?php endforeach ?>
                </section>
            	<figure class="slideshow-next"></figure>                
            </section><!-- .slideshow -->
            <?php endif ?>
            
            <section class="next-step-header">
            	<h1>What's your next step?</h1>
                <span class="caption">(CHOOSE ONE OF THE OPTIONS<br />BELOW TO GET STARTED TODAY)</span>
            </section><!-- .next-step-header -->
            
            <nav class="next-step">
            	<div class="inner">
                    <?php wp_nav_menu(array('menu' => 'Next Step Menu', 'container' => '', 'before' => '<h2>', 'after' => '</h2>')) ?>
                </div>
            </nav><!-- nav.next-step -->