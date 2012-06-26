<?php 
	get_header() ;

	//Capture the new query var for use, i.e. with site.com/programs/foo the following will output 'foo'
	$program_type = get_query_var('program_type');
?>
    <section class="content-wrapper">
        <div class="page-content full-width">
        	<?php $breadcrumbs = new Breadcrumbs ?>
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
                	<?php 
						$content = get_the_content($post->ID);
						echo wpautop($content);
					?>
                    
                    <table cellpadding="0" cellspacing="0" border="0">
                    	<tr>
                        	<th><strong><a href="" title="Sort by Program Category">CATEGORY</a></strong><div class="sort-direction"><div class="up"></div><div class="down"></div></div></th>
                            <th><strong><a href="" title="Sort by Program Title">PROGRAM TITLE</a></strong><div class="sort-direction"><div class="up"></div><div class="down"></div></div></th>
                            <th><strong><a href="" title="Sort by Program Type">TYPE</a></strong><div class="sort-direction"><div class="up"></div><div class="down"></div></div></th>
                            <th><strong><a href="" title="Sort by Program Format">FORMAT</a></strong><div class="sort-direction"><div class="up"></div><div class="down"></div></div></th>
                            <th><strong><a href="" title="Sort by Program Cost">COST</a></strong><div class="sort-direction"><div class="up"></div><div class="down"></div></div></th>
                            <th><strong><a href="" title="Sort by Program Institution">INSTITUTION</a></strong><div class="sort-direction"><div class="up"></div><div class="down"></div></div></th>
                        </tr>
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
						
						//print_r($programs);

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
                                <td><?php the_title() ?></td>
                                <td><?php echo get_post_meta($post->ID, '_nsm_program_type', true) ?></td>
                                <td><?php echo get_post_meta($post->ID, '_nsm_program_format', true) ?></td>
                                <td><?php echo get_post_meta($post->ID, '_nsm_program_cost', true) ?></td>
                                <td>
                                	<?php
										// Find connected pages
										$institution = p2p_type( 'Program Institution' )->get_connected( $post );
										
										while ( $institution->have_posts() ) : $institution->the_post(); 
											
											the_title();
											
										endwhile; wp_reset_postdata(); 
									?>
                                </td>
                            </tr>
						
					<?php endwhile ; wp_reset_postdata(); ?>
                    </table>
                <?php endwhile ?>
            <?php endif ?>
        </div>
        <!--<div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>-->
    </section>          
<?php get_footer(); ?>