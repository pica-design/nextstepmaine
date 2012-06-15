<?php get_header(); ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
                	<h2><?php the_title() ?></h2>
                    <br />
                    <?php the_content() ?>
                    <br /><br />
                    <a href="<?php echo get_post_meta($post->ID, '_onet_link', true) ?>" title="<?php echo $post->post_title ?>" target="_blank">More Information</a> 
                    <br /><br />
					Number of Jobs in 2008: <?php echo get_post_meta($post->ID, '_employment_2008', true) ?><br />
					Number of Jobs in 2018: <?php echo get_post_meta($post->ID, '_employment_2018', true) ?><br />
                    Yearly Job Growth Rate: <?php echo get_post_meta($post->ID, '_growth_rate', true) ?><br />
                    Annual Openings: <?php echo get_post_meta($post->ID, '_annual_openings', true) ?><br />
                    Entry Wage: <?php echo get_post_meta($post->ID, '_entry_wage', true) ?><br />
                    Median Wage: <?php echo get_post_meta($post->ID, '_median_wage', true) ?><br />
                    <br /><br />
                    <h3>Tasks</h3>
                    <ul class="show-bullets">
					<?php
						//Connect to our O*NET database
						$onet = new wpdb('root', '1309piCa', 'onet', 'localhost');
						//Select some information about the current occupation
						$tasks = $onet->get_results("SELECT task FROM task_statements WHERE onetsoc_code LIKE '%" . get_post_meta($post->ID, '_soc_code', true) . "%'");
						
						foreach ($tasks as $task) :
							echo "<li>" . $task->task . "</li>";
						endforeach;
					?>
                	</ul>
                <?php endwhile ?>
            <?php endif ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>