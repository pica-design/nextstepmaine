<?php
	/********************************************
	JOBS IN DEMAND WIDGET
	********************************************/
	class Widget_Jobs_In_Demand extends WP_Widget {
		
		public function __construct () {
			parent::__construct(
				'nextstep-jobs-in-demand-widget', // Base ID
				'Jobs in Demand Widget', // Name
				array( 'description' => __( 'Display a short list of some jobs in demand (Pulled from the Jobs section)', 'nextstepmaine' ), ) // Args
			);
		}
		
		public function form ($instance) {
		}
		
		public function update ($new_instance, $old_instance) {
		}
		
		public function widget ($args, $instance) {
			global $post;
			?>
            <section class="widget" id="jobs-in-demand">
                <h3>Jobs In Demand<br />In Maine</h3>
                <ul>
                    <?php
						$jobs = new WP_Query('post_type=nsm_job&posts_per_page=4&orderby=rand');
						while ($jobs->have_posts()) : $jobs->the_post(); ?>
                        
							<li><a href="<?php the_permalink() ?>" title="Learn about <?php the_title() ?>">
								<?php 
									$title = get_the_title($post->ID) ;
									if (strlen($title) > 25) : 
										echo substr($title, 0, 25) . "..";
									else :
										echo $title;
									endif;
								?>
                            </a></li>
					
					<?php endwhile ?>
                </ul>
                <p class="tooltip"><a href="<?php bloginfo('url') ?>/resources/jobs" title="View all jobs in demand in Maine">See more jobs</a></p>
            </section>
            <?php
		}
	}//Widget_Jobs_In_Demand