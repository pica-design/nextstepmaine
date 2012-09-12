<?php 
	get_header() ;

	//Capture the new query var for use, i.e. with site.com/programs/foo the following will output 'foo'
	$program_type = "";
	$program_type = get_query_var('program_type');
?>
    <section class="content-wrapper">
        <div class="page-content full-width">
        	<?php $breadcrumbs = new Breadcrumbs ?>
    		<h1><?php the_title() ?></h1>
    		<br />
        	<?php while (have_posts()) : the_post() ?>
        		<?php the_content() ?>
            <?php endwhile ?>
        	<?php 
        		$associate_active  = "";
        		$bachelor_active = "";
        		$master_active = "";
        		$certificate_active  = "";
        		$all_programs_active = "";

        		switch ($program_type) : 
        			case 'associate' : $associate_active = "active" ; break;
        			case 'bachelor' : $bachelor_active = "active" ; break;
        			case 'master' : $master_active = "active" ; break;
        			case 'certificate' : $certificate_active = "active" ; break;
        			default : $all_programs_active = "active" ; break;
        		endswitch ;
        	?>
        	<figure class="back-to-top"><div></div></figure>
        	<div class="filter-options">
        		<div class="title">View: </div>
        		<div class="button gray inline padded rounded <?php echo $all_programs_active ?>"><a href="<?php echo get_permalink($post->ID) ?>" title="All Programs">All</a></div>
                <div class="button gray inline padded rounded <?php echo $certificate_active ?>"><a href="<?php echo get_permalink($post->ID) ?>/certificate" title="Certificate Programs">Certificate Programs</a></div>
	        	<div class="button gray inline padded rounded <?php echo $associate_active ?>"><a href="<?php echo get_permalink($post->ID) ?>/associate" title="Associate Programs">Associate Programs</a></div>
	        	<div class="button gray inline padded rounded <?php echo $bachelor_active ?>"><a href="<?php echo get_permalink($post->ID) ?>/bachelor" title="Bachelor Programs">Bachelor Programs</a></div>
	        	<div class="button gray inline padded rounded <?php echo $master_active ?>"><a href="<?php echo get_permalink($post->ID) ?>/master" title="Master Programs">Master Programs</a></div>
	        </div>

            <?php
                //We want to display a little tooltip/help info for certain program types

                //Display a link for users to learn more about certificate programs
                if ($certificate_active == 'active') :
                    echo "Are you confused about the different types of certificates? Find out more and learn the <a href='".get_bloginfo('url')."/faq/#what-are-all-of-these-certificate-programs' title='Certificiate Program FAQ'>difference between certificates and degrees here</a>.<br /><br />";
                endif;
            ?>

            <table cellpadding="0" cellspacing="0" border="0" class="tablesorter programs">
            	<thead>
                	<tr>
                    	<th><strong>CATEGORY</strong><div class="sort-direction"></div></th>
                    	<th><strong>PROGRAM TITLE</strong></th>
                    	<th><strong>TYPE</strong></th>
                    	<th><strong>FORMAT</strong></th>
                    	<th><strong>COST</strong></th>
                    	<th><strong>INSTITUTION</strong></th>
                    </tr>
                </thead>
                <tbody>
            	<?php 
					//By default we want to pull all programs 
					if (empty($program_type)) : 
						$programs = new WP_Query('post_type=nsm_program&posts_per_page=-1&orderby=title&order=asc');
					else :
						//However, if the program_type query var has been set via GET we want to only show programs of that type
						//Note, the query var is set like site.com/programs/associate which is internally translated to ?program_type=associate
						//and accessed via get_query_var('program_type'); above
						$programs = new WP_Query(array(
							'post_type' => 'nsm_program',
							'posts_per_page' => '-1',
							'orderby' => 'title',
							'order' => 'asc',
							'meta_query' => array(
								array(
									'key' => '_nsm_program_type',
									'value' => "$program_type",
									'compare' => 'LIKE'
								)
							)
						));	
					endif;
					while ($programs->have_posts()) : $programs->the_post(); ?>
                	<tr>
                        <td>
							<?php 
								$categories = wp_get_post_terms($post->ID, 'nsm_program_category');
								foreach ($categories as $key => $category) :
									echo $category->name;
									if ($key < count($categories) - 1) : 
										echo ", ";
									endif;
								endforeach;
							?>
                        </td>
                        <td><a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a></td>
                        <td><?php echo get_post_meta($post->ID, '_nsm_program_type', true) ?></td>
                        <td><?php echo get_post_meta($post->ID, '_nsm_program_format', true) ?></td>
                        <td><?php echo get_post_meta($post->ID, '_nsm_program_cost', true) ?></td>
                        <td>
                        	<?php
								// Find connected pages
                        		$previous_post = $post;
								$institution = p2p_type( 'Program Institution' )->get_connected( $post );
								while ( $institution->have_posts() ) : $institution->the_post(); 
									the_title();
								endwhile; wp_reset_postdata(); 
								$post = $previous_post;
								
								$location = get_post_meta($post->ID, '_nsm_program_location', true);

								if (strtolower($location) != 'n/a') : 
									echo " - " . $location ;
								endif;
							?>
                        </td>
                    </tr>
					<?php 
						endwhile ; 
						$last_updated_date = get_the_date();
						wp_reset_postdata(); ?>
				</tbody>
            </table>
            <br /><br />
            <em>
            	Data obtained from the individual institutions listed above. Programs change often, so please inquire with the host institution in question to ensure their data has not changed. 
            	Last updated on <?php echo $last_updated_date ?>
            </em>
            <br /><br />
        </div>
    </section>          
<?php get_footer(); ?>