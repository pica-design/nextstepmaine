<?php 
	get_header(); 

	//Get the filter term
	$education_requirement = "";
	$education_requirement = get_query_var('education_requirement');
	

	$education_requirement_obj = "";
	$education_requirement_obj = get_term_by('slug', $education_requirement, 'nsm_job_education_requirement');
	if ($education_requirement_obj != "") : 
		$education_requirement_obj = $education_requirement_obj->term_id;
	endif;
?>
    <section class="content-wrapper">
        <div class="page-content full-width">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            <h1>High-Demand Jobs in Maine</h1>
            <br />
            <?php
				while (have_posts()) : the_post() ;
					the_content();
				endwhile;
			?>
			<?php
				//if (!empty($education_requirement)) : 
            		switch ($education_requirement) : 
            			case 'high-school' : $high_school_active = "active" ; break;
            			case 'associate' : $associate_active = "active" ; break;
            			case 'bachelor' : $bachelor_active = "active" ; break;
            			default: $all_jobs_active = "active" ; break;
            		endswitch ;
            	//endif ;
			?>
            <div class="filter-options">
	            <div class="title">View by education requirement:</div>
	            <div class="button gray inline padded rounded <?php echo $all_jobs_active ?>"><a href="<?php echo get_permalink($post->ID) ?>" title="All Jobs">All</a></div>
	            <div class="button gray inline padded rounded <?php echo $high_school_active ?>"><a href="<?php echo get_permalink($post->ID) ?>high-school" title="High School Only Jobs">High School Diploma</a></div>
	        	<div class="button gray inline padded rounded <?php echo $associate_active ?>"><a href="<?php echo get_permalink($post->ID) ?>associate" title="Associate Degree Only Jobs">Associate Degress</a></div>
	        	<div class="button gray inline padded rounded <?php echo $bachelor_active ?>"><a href="<?php echo get_permalink($post->ID) ?>bachelor" title="Bachelor Degree Only Programs">Bachelor Degree</a></div>
        	</div>
            <table cellpadding="0" cellspacing="0" border="0" class="tablesorter jobs">
            	<thead>
                	<tr>
                    	<th><strong>EDUCATION REQUIREMENT</strong><div class="sort-direction"></div></th>
                    	<th><strong>JOB NAME</strong></th>
                    	<th><strong>Number of Jobs in 2008</strong></th>
						<th><strong>Number of Jobs in 2018</strong></th>
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
					   		<td><?php echo get_post_meta($post->ID, '_nsm_job_employment_2008', true) ?></td>
							<td><?php echo get_post_meta($post->ID, '_nsm_job_employment_2018', true) ?></td>
		                    <td><?php echo get_post_meta($post->ID, '_nsm_job_growth_rate', true) ?></td>
		                    <td><?php echo get_post_meta($post->ID, '_nsm_job_annual_openings', true) ?></td>
		                    <td><?php echo get_post_meta($post->ID, '_nsm_job_entry_wage', true) ?></td>
		                    <td><?php echo get_post_meta($post->ID, '_nsm_job_median_wage', true) ?></td>
					   	</tr>
						<?php endwhile ?>
		                
					<?php endforeach ?>
				</tbody>
			</table>
			<br /><br />
            <em>Data obtained from the <a href="http://www.maine.gov/labor/cwri/data/oes/hwid.html" title="Maine Department of Labor" target="_blank">Maine Department of Labor</a> and <a href="http://www.onetcenter.org/" title="O*NET Resoruce Center" target="_blank">O*NET</a></em>
        </div>
        <!--
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    -->
    </section>          
<?php get_footer(); ?>