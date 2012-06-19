<!-- you need to register your public variables (query_vars filter hook) first then catch it with get_query_var() via 'template_redirect' action hook

might want to read http://codex.wordpress.org/Custom_Queries 

http://wordpress.org/support/topic/using-custom-_get-variables-with-templates-and-permalinks?replies=5
-->

<?php

	
	
	
?>

<?php get_header() ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
                	<?php 
						
						$content = get_the_content($post->ID);
						/*$content = do_shortcode($content);*/
						echo wpautop($content);
						
					?>
                <?php endwhile ?>
            <?php endif ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>