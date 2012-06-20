<?php get_header() ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
                	<?php 
						$content = get_the_content($post->ID);
						echo wpautop($content);
					?>
                    
                    <?php
						//Capture the new query var for use, i.e. with site.com/programs/foo the following will output 'foo'
						echo get_query_var('prog_edu_lvl');
					?>
                    
                    <table cellpadding="0" cellspacing="0" border="0">
                    	<tr>
                        	<th><strong>CATEGORY</strong></th>
                            <th><strong>PROGRAM TITLE</strong></th>
                            <th><strong>FORMAT</strong></th>
                            <th><strong>COST</strong></th>
                            <th><strong>INSTITUTION</strong></th>
                        </tr>
                    <?php 
						
						$programs = new WP_Query('post_type=nsm_program&posts_per_page=-1&orderby=title&order=asc');
						
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
                                <td><?php echo get_post_meta($post->ID, '_nsm_program_format', true) ?></td>
                                <td><?php echo get_post_meta($post->ID, '_nsm_program_cost', true) ?></td>
                                <td>INSTITUTION</td>
                            </tr>
						
					<?php endwhile ; wp_reset_postdata(); ?>
                    </table>
                <?php endwhile ?>
            <?php endif ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>