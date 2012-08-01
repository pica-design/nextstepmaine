<?php 

	get_header(); 
	
	//Connect to our O*NET database
	$onet = new wpdb('root', '1309piCa', 'onet', 'localhost');

	//$onet = new wpdb('root', '1309jamM@', 'onet', 'localhost');
	
	//Grab the current occupation soc code
	$onetsoc_code = get_post_meta($post->ID, '_nsm_job_soc_code', true);
?>
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
                    <a class="button gray rounded padded" href="<?php echo get_post_meta($post->ID, '_nsm_job_onet_link', true) ?>" title="<?php echo $post->post_title ?>" target="_blank">Learn more about this job</a> 
                    <br /><br />
					<strong>Number of Jobs in 2008:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_employment_2008', true) ?><br />
					<strong>Number of Jobs in 2018:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_employment_2018', true) ?><br />
                    <strong>Yearly Job Growth Rate:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_growth_rate', true) ?><br />
                    <strong>Annual Openings:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_annual_openings', true) ?><br />
                    <strong>Entry Wage:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_entry_wage', true) ?><br />
                    <strong>Median Wage:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_median_wage', true) ?><br />
                    
                    <?php
						/********************************
							WORK TASKS
						********************************/
						//Select some information about the current occupation
						$tasks = $onet->get_results("
							SELECT task 
							FROM task_statements 
							WHERE onetsoc_code 
							LIKE '%$onetsoc_code%'
						");
						if (count($tasks) > 0) : 
					?>
                    <br />
                    <section class='accordion closed'>
                        <header>
                        	<figcaption>Job Tasks</figcaption>
                        	<div><figure></figure></div>
                        </header>
                        <article>
                            <ul class="show-bullets">
                            <?php    
                                foreach ($tasks as $task) :
                                    echo "<li>" . $task->task . "</li>";
                                endforeach;
                            ?>
                            </ul>
                        </article>
                    </section>
                    <?php endif ?>
                    
                    <!--
                    <?php
						/********************************
							WORK ACTIVITES
						********************************/
						//Select some information about the current occupation
						/*
						$activities = $onet->get_results("
							SELECT content_model_reference.element_name, content_model_reference.description
							FROM content_model_reference
							JOIN dwas_to_content_model 
								ON content_model_reference.element_id = dwas_to_content_model.element_id
							JOIN occupations_to_dwas 
								ON occupations_to_dwas.dwa_code = dwas_to_content_model.dwa_code
							WHERE occupations_to_dwas.onetsoc_code 
								LIKE '%25-4021%'
						");
						if (count($activities) > 0) : 
						*/
					?>
                    <br />
                    <div class='accordion closed'>
                        <div class='title'>Job Activities</div>
                        <div class='content'>
                            <ul class="show-bullets">
                            <?php foreach ($activities as $activity) : ?>
                            	<li><strong><?php echo $activity->element_name ?> - <?php echo $activity->description ?></strong></li>
                            <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                    <?php //endif ?>
                    -->
                    
                    <?php
						/********************************
							RELATED OCCUPATIONS
						********************************/
						//Select any occupations related to the currently viewed occupation
						$related_occupations = $onet->get_results("
							SELECT occupation_data.title, related_occupations.onetsoc_code_related
							FROM occupation_data
							JOIN related_occupations
								ON occupation_data.onetsoc_code = related_occupations.onetsoc_code_related
							WHERE related_occupations.onetsoc_code 
								LIKE '%$onetsoc_code%'
						");
						
						if (count($related_occupations) > 0) : 
					?>
                    <section class='accordion closed'>
                        <header>
                        	<figcaption>Related Jobs</figcaption>
                        	<div><figure></figure></div>
                       	</header>
                        <article>
                            <ul class="show-bullets">
                            <?php  
								$show_annotation = false;
								  
								//Loop through each related occupation
                                foreach ($related_occupations as $related_occupation) :
								
									//print_r($related_occupation);
								
									//Unfortunetly we have two different soc_code types
										//The imported data from DOL has codes like 25-4021
										//BUT the imported data from onet has codes like 25-4021.00
										//This little explosion removes the . and anything after it
									$onetsoc_code_related = explode('.', $related_occupation->onetsoc_code_related);
									$onetsoc_code_related = $onetsoc_code_related[0];
									
									//Pull the related occupation nsm_job post so we can get it's permalink
									$occupation = new WP_Query(array(
										'post_type' => 'nsm_job',
										'posts_per_page' => -1,
										'meta_key' => '_nsm_job_soc_code',
										'meta_value' => $onetsoc_code_related
									));
									
									
									if ($occupation->have_posts()) : 
										//Single loop the returned related occupation and display the title + a link
										while ($occupation->have_posts()) : $occupation->the_post(); ?>	
										<li><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo $related_occupation->title ?>"><?php echo $related_occupation->title ?></a>
										<?php endwhile ?>
                                    <?php else : $show_annotation = true; ?>
                                    	<li><a href="http://www.onetonline.org/link/summary/<?php echo $related_occupation->onetsoc_code_related ?>" title="<?php echo $related_occupation->title ?>" target="_blank"><?php echo $related_occupation->title ?></a> <span class="annotation">*</span></li>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </ul>
                            <?php if ($show_annotation) : ?>
                            <br />
                            <em><span class="annotation">*</span> The related job mentioned is not listed as 'In-Demand' or 'High-growth' in Maine.</em>
                            <?php endif ?>
                        </article>
                    </section>
                    <?php endif ?>
                <?php endwhile ?>
            <?php endif ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>