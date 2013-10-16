<?php 
	get_header() ;

	//Capture the new query var for use, i.e. with site.com/programs/foo the following will output 'foo'
	/*
    $program_type = "";
	$program_type = get_query_var('program_type');
    */
?>
    <section class="content-wrapper">
        <div class="page-content full-width">
        	<?php $breadcrumbs = new Breadcrumbs ?>
    		<h1><?php echo $post->post_title ?></h1>
    		<br />
        	<?php while (have_posts()) : the_post() ?>
        		<?php the_content() ?>
            <?php endwhile ?>
            <table cellpadding="0" cellspacing="0" border="0" class="tablesorter programs">
            	<thead>
                	<tr>
                    	<th><strong>CATEGORY</strong><div class="sort-direction"></div></th>
                    	<th><strong>INSTITUTION TITLE</strong></th>
                    	<th><strong>WEBSITE</strong></th>
                    	<th><strong>PROGRAMS</strong></th>
                    </tr>
                </thead>
                <tbody>
            	<?php 
					//By default we want to pull all Institutions
					$institutions = new WP_Query(array(
						'post_type' => 'nsm_institution',
						'posts_per_page' => '-1',
						'orderby' => 'title',
						'order' => 'asc'
					));	
					while ($institutions->have_posts()) : $institutions->the_post(); ?>
                	<tr>
                        <td>
							<?php 
								$categories = wp_get_post_terms($post->ID, 'nsm_institution_category');
								foreach ($categories as $key => $category) :
									echo $category->name;
									if ($key < count($categories) - 1) : 
										echo ", ";
									endif;
								endforeach;
							?>
                        </td>
                        <td><a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a></td>
                        <td>
                            <?php $website_url = get_post_meta($post->ID, '_nsm_institution_website_url', true) ?>
                            <a href="<?php echo $website_url ?>" title="<?php the_title() ?> Website" target="_blank"><?php echo $website_url ?></a>
                        </td>
                        <td>
                            <?php
                                //Pull other programs at the parent institution
                                $programs = new WP_Query( array(
                                    'connected_type' => 'Program Institution',
                                    'connected_items' => $post,
                                    'nopaging' => true
                                ) );
                                
                                if (!empty($programs->posts)) :
                                    echo count($programs->posts) . ' Programs';
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
            	Data obtained from the individual institutions listed above.
            	Last updated on <?php echo $last_updated_date ?>
            </em>
            <br /><br />
        </div>
    </section>          
<?php get_footer(); ?>