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
	                <img src="<?php bloginfo('template_directory') ?>/images/content/fafsa-logo.jpg" alt="Federal Student Aid Logo" />
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
			global $post;
			?>
            <section class="widget" id="institutions">
                <h3>Institutions</h3>
                <?php if (is_page('home')) : ?>
                	<p>(Choose from one of the<br />options in the drop down menu<br /><span class="blue-text"><strong>to search for program types<br />and schools)</strong></span></p>
                <?php endif ?>
                <select>
                	<option value="">Institutions</option>
                <?php
                	//Query all the institutions to populate our dropdown menu
					$institutions = new WP_Query(array(
						'post_type' => 'nsm_institution',
						'posts_per_page' => -1
					));					
					//cache the current page(post) for a condition (we're about to overwrite it)
					$current_page = $post;
					$current_insitution = "";

					while ($institutions->have_posts()) : $institutions->the_post(); 
						/* VIEWING SINGLE PROGRAM */
						if ($current_page->post_type == "nsm_program") : 
							//Cache the current $post as we're about to overwrite it
							$insitution_post = $post;

							// Find the current program's institution	
							$institution = p2p_type( 'Program Institution' )->get_connected( $current_page );
							while ( $institution->have_posts() ) : $institution->the_post(); 
								//cache the institution slug for a conditional later on down the page
								$program_institution_slug = $post->post_name;

								if ($post->post_name == $insitution_post->post_name) : 
									$current_insitution = "selected='selected'";
								else :
									$current_insitution = "";
								endif;

							endwhile; wp_reset_postdata(); 

							//Reinstate the previous $post
							$post = $insitution_post;
						endif;

						/* VIEWING SINGLE INSTITUTION */
						//If the user is currently viewing an institution mark the matching institution / parent institution as selected 
						if ($current_page->post_type == 'nsm_institution') :
							if ($current_page->post_name == $post->post_name) : 
								$current_insitution = "selected='selected'";
							else :
								$current_insitution = "";
							endif;
						endif;
					?>
						<option value="<?php the_permalink() ?>" <?php echo $current_insitution ?>><?php the_title() ?></option>
					<?php 
						endwhile; 
						wp_reset_postdata(); 
						//Reinstate the cached page(post) 
						$post = $current_page ; 
					?>
                 </select>
            </section>
            <?php
		}
	}
	
?>