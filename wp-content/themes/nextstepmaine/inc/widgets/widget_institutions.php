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
                <select>
                	<option value="">Choose an Institution to View Programs Available</option><?php
					//Build the custom database query to fetch all user IDs
					global $wpdb ;
					$all_user_ids = $wpdb->get_col("
						SELECT users.ID 
						FROM $wpdb->users AS users
						WHERE users.ID != 2
						ORDER BY users.user_nicename ASC
					");

					foreach ( $all_user_ids as $user_id ) : $user = get_userdata( $user_id );
						//VIEWING SINGLE PROGRAM
						if ($current_page->post_type == "nsm_program") : 
							if ($user_id == $post->post_author) : 
								$current_insitution = "selected='selected'";
							else :
								$current_insitution = "";
							endif;
						endif;
						//VIEWING SINGLE INSTITUTION
						//If the user is currently viewing an institution mark the matching institution / parent institution as selected 
						$current_insitution = "";
						
						if (is_author()) :
							if (get_query_var('author_name') == $user->user_nicename) : 
								$current_insitution = "selected='selected'";
							else :
								$current_insitution = "";
							endif;
						endif; ?>

					<option value="<?php echo get_user_profile_url($user_id) ?>" <?php echo $current_insitution ?>><?php 
						//Determine the display name (if not set already by the user)
					    if (!empty($user->first_name)) : 
					    	echo $user->first_name. ' ' . $user->last_name;    
					    else : 
					        echo $user->user_login;
					    endif ?>
					</option><?php endforeach ?>

                 </select>
                 <p>(Programs listed are just a few of all the programs available at Maine institutions.  These programs offer a flexible schedule that may work best for adult learners juggling other obligations.  To see all programs, go directly to each school’s website.)</p>
            </section>
            <?php
		}
	}//Widget_Institutions