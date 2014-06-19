<?php get_header(); ?>
    <section class="content-wrapper">
        
        <section class="aside horizontal">
	        <?php get_sidebar('homepage') ?>
	        <div class="clear"></div>

	        <div class="line-break"></div>

	        <div class="widget two-thirds maine-learning-initiative">
	        	<h3>Maine Employer Initiative</h3>
	        	<p>
	        		There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text... <a href="">Learn More</a>
	        	</p>
	        	<nav>
	        		<ul>
	        			<li class="alignleft"><a href="<?php $resources = get_post('9229'); echo $resources->guid ?>">Access to Resources</a> | </li>	
	        			<li><a href="">&nbsp;Join the Maine Employers Initiative</a></li>	
	        		</ul>
	        	</nav>

	        	<small><a href="">Already a MEI partner?</a></small>
	        	<p class="tooltip"><a href="">Apply for a Scholarship Today!</a></p>

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