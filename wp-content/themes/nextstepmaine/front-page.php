<?php get_header(); ?>
    <section class="content-wrapper">
        <?php 
        	$mei_page = get_page('9221'); 
        	$resources = get_post('9229'); 
        ?>
        <?php //print_r($mei_page); ?>
        <section class="aside horizontal">
	        <?php get_sidebar('homepage') ?>
	        <div class="clear"></div>

	        <div class="line-break"></div>
	
	        <div class="widget two-thirds maine-learning-initiative">
	        	<h3><a href="<?php echo $mei_page->guid ?>"><?php echo $mei_page->post_title; ?></a></h3>
	        		<?php echo substr($mei_page->post_content,0, 475) . '...' . '<a href="' . $mei_page->guid . '">Learn More</a>'; ?>
	        	<nav>
	        		<ul>
	        			<li class="alignleft"><a href="<?php echo $resources->guid ?>">Access to Resources</a> | </li>	
	        			<li><a href="<?php echo get_page('9233')->guid ?>">&nbsp;Join the Maine Employers Initiative</a></li>	
	        		</ul>
	        	</nav>

	        	<small>Already a MEI partner?</small>
	        	<p class="tooltip"><a href="<?php echo get_page('9250')->guid ?>">Apply for a Scholarship Today!</a></p>

	        </div>
	        <div class="widget one-third prior-learning-assessment">
	        	<h3>Prior Learning Assessment</h3>
	        	<nav>
	        		<ul>
	        			<li><a href="">What is it?</a></li>
	        			<li><a href="">How does it work?</a></li>
	        		</ul>
	        	</nav>
	        	<p class="tooltip"><a href="">Take an Assessment Now!</a></p>
	        </div>
	        
        </section>

    </section>          
<?php get_footer(); ?>