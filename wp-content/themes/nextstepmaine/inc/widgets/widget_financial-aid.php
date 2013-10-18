<?php
	/********************************************
	FINANCIAL AID WIDGET
	********************************************/
	class Widget_Financial_Aid extends WP_Widget {
		
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
                <a href="<?php bloginfo('url') ?>/financial-aid/" title="Learn about Financial Aid">
	                <p class="slogan">Get help paying<br />for college.</p>
	                <!--<p class="tooltip">Click the link below<br />for more information</p>-->
	                <img src="<?php bloginfo('template_directory') ?>/images/content/fafsa-logo.jpg" alt="Federal Student Aid Logo" />
                </a>
            </section>
            <?php
		}
	}//NEXTSTEP_Financial_Aid_Widget