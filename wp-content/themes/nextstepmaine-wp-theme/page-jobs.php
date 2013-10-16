<?php 
	get_header(); 

	//Get the filter term from the query
	$education_requirement = "";
	$education_requirement = get_query_var('education_requirement');

	//Grab the education requirement term id
	$education_requirement_obj = "";
	$education_requirement_obj = get_term_by('slug', $education_requirement, 'nsm_job_education_requirement');
	if ($education_requirement_obj != "") : 
		$education_requirement_obj = $education_requirement_obj->term_id;
	endif;
?>
    <section class="content-wrapper">
        <div class="page-content full-width">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            <h1><?php the_title() ?></h1>
            <br />
            <?php
            	//Display the page content entered within WordPress for the 'Jobs' page
				while (have_posts()) : the_post() ; the_content(); endwhile;
			?>
			<figure class="back-to-top"><div></div></figure>
            <div class="filter-options">
	            <div class="title">View by education requirement:</div>
	            <div class="button gray inline padded rounded <?php echo $education_requirement == '' ? 'active' : '' ?>">
	            	<?php $all_jobs = get_posts(array('numberposts' => -1,'post_type' => 'nsm_job',)); ?>
	            	<a href="<?php echo get_permalink($post->ID) ?>" title="All Jobs">All (<?php echo count($all_jobs) ?>)</a>
	            </div>

	            <?php
	            	//Display the taxonomy terms as a button list / links for filtering the query
	            	$terms = get_terms('nsm_job_education_requirement');
	            	foreach($terms as $term) : 
	            ?>
	            	<div class="button gray inline padded rounded <?php echo $education_requirement == $term->slug ? 'active' : '' ?>">
		            	<a href="<?php echo get_permalink($post->ID) . $term->slug ?>" title="<?php echo $term->name ?> Only Jobs"><?php echo $term->name ?> (<?php echo $term->count ?>)</a>
		            </div>
	            <?php endforeach ?>
        	</div>
            <table cellpadding="0" cellspacing="0" border="0" class="tablesorter jobs">
            	<thead>
                	<tr>
                    	<th><strong>EDUCATION REQUIREMENT</strong><div class="sort-direction"></div></th>
                    	<th><strong>JOB NAME</strong></th>
                    	<th><strong>Number of Jobs in 2010</strong></th>
						<th><strong>Number of Jobs in 2020</strong></th>
	                    <th><strong>Yearly Job Growth Rate</strong></th>
	                    <th><strong>Annual Openings</strong></th>
	                    <th><strong>Entry Wage</strong></th>
	                    <th><strong>Median Wage</strong></th>
                    </tr>
                </thead>
                <tbody>
		            <?php 
					//Select the Education Requirement Taxonomy terns applied to NSM Jobs 
					$job_education_levels = get_terms('nsm_job_education_requirement', array(
						'orderby' => 'custom_sort',
						'include' => $education_requirement_obj
					));
					
					//Loop through each term
					foreach ($job_education_levels as $education_level) : ?>
						<?php $jobs = new WP_Query(array(
							'post_type' => 'nsm_job',
							'posts_per_page' => -1,
							'orderby' => 'title',
							'order' => 'asc',
							'tax_query' => array(
								array(
									'taxonomy' => 'nsm_job_education_requirement',
									'field' => 'slug',
									'terms' => $education_level->slug
								)
							)
						));
						?>
						<?php
						while ($jobs->have_posts()) : $jobs->the_post(); ?>
					   	<tr>
					   		<td><?php echo $education_level->name ?></td>
					   		<td><a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a></td>
					   		<td><?php echo get_post_meta($post->ID, '_nsm_job_base_employment', true) ?></td>
							<td><?php echo get_post_meta($post->ID, '_nsm_job_projected_employment', true) ?></td>
		                    <td><?php echo get_post_meta($post->ID, '_nsm_job_growth_rate', true) ?></td>
		                    <td><?php echo get_post_meta($post->ID, '_nsm_job_annual_openings', true) ?></td>
		                    <td><?php echo get_post_meta($post->ID, '_nsm_job_entry_wage', true) ?></td>
		                    <td><?php echo get_post_meta($post->ID, '_nsm_job_median_wage', true) ?></td>
					   	</tr>
						<?php endwhile ; $last_updated_date = get_the_date(); ?>
					<?php endforeach ?>
				</tbody>
			</table>
			<br /><br />
            <em>
            	Data obtained from the 
            	<a href="http://www.maine.gov/labor/cwri/data/oes/hwid.html" title="Maine Department of Labor" target="_blank">Maine Department of Labor</a> 
            	and <a href="http://www.onetcenter.org/" title="O*NET Resoruce Center" target="_blank">O*NET</a>
            	Last updated on <?php echo $last_updated_date ?>
            </em>
            <br /><br />
        </div>
    </section>          
<?php get_footer(); ?>