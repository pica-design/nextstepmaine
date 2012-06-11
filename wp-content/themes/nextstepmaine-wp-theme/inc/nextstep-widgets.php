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
			?>
            <section class="widget" id="jobs-in-demand">
                <h3>Jobs In Demand</h3>
                
                <ul>
                	<li><a href="">Advertising Sales Agents</a></li>
					<li><a href="">Bill and Account Collectors</a></li>
					<li><a href="">Bookkeeping, Accounting, and Auditing Clerks</a></li>
					<li><a href="">Cabinetmakers and Bench Carpenters</a></li>
                </ul>
                
                <p class="tooltip"><a href="" title="View all jobs in demand in Maine">See more jobs</a></p>
            </section>
            <?php
		}
	}
	
	
	/********************************************
	PROGRAMS & INSTITUTIONS WIDGET
	********************************************/
	class NEXTSTEP_Programs_Types_Widget extends WP_Widget {
		
		public function __construct () {
			parent::__construct(
				'nextstep-programs-types-widget', // Base ID
				'Programs Types Widget', // Name
				array( 'description' => __( 'Display a quick menu of program types.', 'nextstepmaine' ), ) // Args
			);
		}
		
		public function form ($instance) {
		}
		
		public function update ($new_instance, $old_instance) {
		}
		
		public function widget ($args, $instance) {
			?>
            <section class="widget" id="program-types">
                <h3>Programs &amp; Institutions</h3>
                <p>(Choose from one of the<br />options in the drop down menu<br /><span class="blue-text"><strong>to search for program types<br />and schools)</strong></span></p>
                PROGRAM TYPE DROPDOWN
            </section>
            <?php
		}
	}
	
?>