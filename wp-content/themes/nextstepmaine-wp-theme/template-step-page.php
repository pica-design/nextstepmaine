<?php
	/* Template Name: Step Page */
    
	get_header() ;
?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
                	<?php 
                        the_content();
					?>
                <?php endwhile ?>
            <?php endif ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('page') ?>
        </div>
    </section>          
<?php get_footer(); ?>