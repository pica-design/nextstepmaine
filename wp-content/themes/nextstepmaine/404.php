<?php get_header() ?>
    <section class="content-wrapper">
        <div class="page-content">
            
            <?php $error_page = new WP_Query('page_id=5605') ?>
        	<?php if ($error_page->have_posts()) : ?>
            	<?php while ($error_page->have_posts()) : $error_page->the_post() ?>
                	<?php the_content() ?>
                <?php endwhile ?>
            <?php endif ?>

            <div class="website-search"><?php get_search_form(); ?></div>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('page') ?>
        </div>
    </section>          
<?php get_footer(); ?>