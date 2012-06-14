<?php get_header(); ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
                	<h2><?php the_title() ?></h2>
                    <br />
                    <?php the_content() ?>
                    <br /><br />
					Number of Jobs in 2008: <?php echo get_post_meta($post->ID, '_employment_2008', true) ?><br />
					Number of Jobs in 2018: <?php echo get_post_meta($post->ID, '_employment_2018', true) ?><br />
                    Yearly Job Growth Rate: <?php echo get_post_meta($post->ID, '_growth_rate', true) ?><br />
                    Annual Openings: <?php echo get_post_meta($post->ID, '_annual_openings', true) ?><br />
                    Entry Wage: <?php echo get_post_meta($post->ID, '_entry_wage', true) ?><br />
                    Median Wage: <?php echo get_post_meta($post->ID, '_median_wage', true) ?><br />
                    <br />
                    <a href="<?php echo get_post_meta($post->ID, '_onet_link', true) ?>" title="<?php echo $post->post_title ?>" target="_blank">More Information</a> <br />
					
                	
                <?php endwhile ?>
            <?php endif ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>