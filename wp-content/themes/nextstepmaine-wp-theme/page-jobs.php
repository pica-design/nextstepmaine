<?php get_header(); ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php 
				$jobs = new WP_Query('post_type=nsm_job&posts_per_page=-1');
				while ($jobs->have_posts()) : $jobs->the_post(); 
			?>

                	<h3><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
                    <br />
                <?php endwhile ?>

        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>