<?php get_header(); ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
                	<h2><?php the_title() ?></h2>
                    <br />
                    <?php $content = get_the_content() ;
					if (!empty($content)) : echo $content ; ?><br /><br />
					<?php endif ?>
                    <a href="<?php echo get_post_meta($post->ID, '_nsm_job_onet_link', true) ?>" title="<?php echo $post->post_title ?>" target="_blank">More Information</a> 
                    <br /><br />
					<strong>Number of Jobs in 2008:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_employment_2008', true) ?><br />
					<strong>Number of Jobs in 2018:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_employment_2018', true) ?><br />
                    <strong>Yearly Job Growth Rate:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_growth_rate', true) ?><br />
                    <strong>Annual Openings:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_annual_openings', true) ?><br />
                    <strong>Entry Wage:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_entry_wage', true) ?><br />
                    <strong>Median Wage:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_median_wage', true) ?><br />
                    
                    <?php
						//Connect to our O*NET database
						$onet = new wpdb('root', '1309piCa', 'onet', 'localhost');
						//Select some information about the current occupation
						$tasks = $onet->get_results("SELECT task FROM task_statements WHERE onetsoc_code LIKE '%" . get_post_meta($post->ID, '_nsm_job_soc_code', true) . "%'");
						
						if (count($tasks) > 0) : 
					?>
                    <br />
                    <div class='accordion closed'>
                        <div class='title'>Job Tasks</div>
                        <div class='content'>
                            <ul class="show-bullets">
                            <?php    
                                foreach ($tasks as $task) :
                                    echo "<li>" . $task->task . "</li>";
                                endforeach;
                            ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif ?>
                <?php endwhile ?>
            <?php endif ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>