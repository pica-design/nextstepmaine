<?php
	
	/********************************************
	FINANCIAL AID WIDGET
	********************************************/
	class NEXTSTEP_Financial_Aid_Widget extends WP_Widget {
		
		public function __construct () {
			parent::__construct(
				'nextstep-financial-aid-widget', // Base ID
				'Financial Aid Widget', // Name
				array( 'description' => __( 'Display useful financial aid information in a widget.', 'nextstepmaine' ), ) // Args
			);
		}
		
		public function form ($instance) {
		}
		
		public function update ($new_instance, $old_instance) {
		}
		
		public function widget ($args, $instance) {
			?>
            <section class="widget" id="financial-aid">
                <h3>Financial Aid</h3>
                <p class="slogan">Get help paying<br />for college.</p>
                <p class="tooltip">Click the link below<br />for more information</p>
                <a href="http://www.fafsa.ed.gov/" title="Federal Student Aid Website" target="_blank">
	                <img src="<?php bloginfo('template_directory') ?>/images/fafsa-logo.jpg" alt="Federal Student Aid Logo" />
                </a>
            </section>
            <?php
		}
	}
	
	
	/********************************************
	JOBS IN DEMAND WIDGET
	********************************************/
	class NEXTSTEP_Jobs_In_Demand_Widget extends WP_Widget {
		
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
                <h3>Jobs In Demand</h3>
                <ul>
                    <?php
						$jobs = new WP_Query('post_type=nsm_job&posts_per_page=4&orderby=rand');
						while ($jobs->have_posts()) : $jobs->the_post(); ?>
                        
							<li><a href="<?php the_permalink() ?>">
								<?php 
									$title = get_the_title($post->ID) ;
									if (strlen($title) > 40) : 
										echo substr($title, 0, 40) . "...";
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
	}
	
	
	/********************************************
	PROGRAMS & INSTITUTIONS WIDGET
	********************************************/
	class NEXTSTEP_Institutions_Widget extends WP_Widget {
		
		public function __construct () {
			parent::__construct(
				'nextstep-programs-types-widget', // Base ID
				'Institutions Widget', // Name
				array( 'description' => __( 'Display a quick menu of program types.', 'nextstepmaine' ), ) // Args
			);
		}
		
		public function form ($instance) {
		}
		
		public function update ($new_instance, $old_instance) {
		}
		
		public function widget ($args, $instance) {
			?>
            <section class="widget" id="institutions">
                <h3>Institutions</h3>
                <p>(Choose from one of the<br />options in the drop down menu<br /><span class="blue-text"><strong>to search for program types<br />and schools)</strong></span></p>
                
                <select>
                	<option value="">Institutions</option>
                <?php
					$institutions = new WP_Query(array(
						'post_type' => 'nsm_institution',
						'posts_per_page' => -1
					));
					
					while ($institutions->have_posts()) : $institutions->the_post(); ?>
						<option value="<?php the_permalink() ?>"><?php the_title() ?></option>
					<?php endwhile ?>
                 </select>
            </section>
            <?php
		}
	}
	
?>