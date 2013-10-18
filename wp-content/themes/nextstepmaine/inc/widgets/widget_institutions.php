<?php
	/********************************************
	PROGRAMS & INSTITUTIONS WIDGET
	********************************************/
	class Widget_Institutions extends WP_Widget {
		
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
                	<!--<p class="tooltip">(Choose from one of the<br />options in the drop down menu<br />to search for program types<br />and schools)</p>-->
                <?php endif ?>
                <select>
                	<option value="">Choose an Institution to View Programs Available</option>
                <?php
                	//Query all the institutions to populate our dropdown menu
					$institutions = new WP_Query(array(
						'post_type' => 'nsm_institution',
						'posts_per_page' => -1,
						'orderby' => 'title',
						'order' => 'ASC'
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
                 <p>(Programs listed are just a few of all the programs available at Maine institutions.  These programs offer a flexible schedule that may work best for adult learners juggling other obligations.  To see all programs, go directly to each school’s website.)</p>
            </section>
            <?php
		}
	}//Widget_Institutions