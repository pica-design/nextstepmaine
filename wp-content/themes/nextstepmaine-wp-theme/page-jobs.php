<?php get_header(); ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
            <?php
				while (have_posts()) : the_post() ;
					the_content();
				endwhile;
			?>
            
            <br /><br />
            <?php 
			//Select the Education Requirement Taxonomy terns applied to NSM Jobs 
			$job_education_levels = get_terms('nsm_job_education_requirement', 'orderby=custom_sort');
			//Loop through each term
			foreach ($job_education_levels as $education_level) : ?>
				<h1><?php echo $education_level->name ?></h1>
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
				   <a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a><br />
				<?php endwhile ?>
                <br /><br />
			<?php endforeach ?>
            <em>Data obtained from the <a href="http://www.maine.gov/labor/cwri/data/oes/hwid.html" title="Maine Department of Labor" target="_blank">Maine Department of Labor</a> and <a href="http://www.onetcenter.org/" title="O*NET Resoruce Center" target="_blank">O*NET</a></em>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>