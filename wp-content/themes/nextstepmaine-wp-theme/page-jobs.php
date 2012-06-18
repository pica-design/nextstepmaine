<?php get_header(); ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            <?php 
			//Select the Education Requirement Taxonomy terns applied to NSM Jobs 
			$job_education_levels = get_terms('nsm_job_education_requirement', 'orderby=custom_sort');
			//Loop through each term
			foreach ($job_education_levels as $education_level) : ?>
				<h2><?php echo $education_level->name ?></h2>
				<?php $jobs = new WP_Query(array(
					'post_type' => 'nsm_job',
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
				   <a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a><br />
				<?php endwhile ?>
				<br /><br />
			<?php endforeach ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>