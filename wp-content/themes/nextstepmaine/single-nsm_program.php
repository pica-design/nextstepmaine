<?php get_header(); ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
                    <p>Below are the details about the program you’ve chosen.  Contact the school directly for admissions and financial aid information, or use the link to go directly to the school’s website for more details.</p>
					<?php
                        $institution = get_user_by('id', $post->post_author);  
                        $institution->data->meta = get_user_meta($institution->ID);
                        $institution->data->meta['simple_local_avatar'][0] = unserialize($institution->data->meta['simple_local_avatar'][0]);
                        $image_url = $institution->meta['simple_local_avatar'][0]['full'] ;
                        if ($image_url != "") :
                    ?>
                    <img class="institution-image" src="<?php echo $image_url ?>" alt="<?php echo $institution->display_name ?> Logo" />
                    <?php else : ?>
                    <br />
                    <h2><?php echo $institution->display_name ?></h2>
                    <?php endif ?>

                    <div class="clear"></div>
                    <br />
                    <h3><?php the_title() ?></h3>
                    <br />
                    
                    <?php $content = get_the_content() ;
					if (!empty($content)) : echo $content ; ?><br /><br />
					<?php endif ?>
                    
                    <strong>Type:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_type', true) ?><br />
                    <strong>Format:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_format', true) ?><br />
                    <strong>Level:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_level', true) ?><br />
                    <strong>Location:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_location', true) ?><br />
                    <strong>Schedule:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_schedule', true) ?><br />
                    <strong>Url:</strong> <a href="<?php echo get_post_meta($post->ID, '_nsm_program_url', true) ?>" title="<?php the_title() ?>" target="_blank"><?php echo get_post_meta($post->ID, '_nsm_program_url', true) ?></a><br />
                    <strong>Timeframe:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_timeframe', true) ?><br />
                    <strong>Cost:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_cost', true) ?><br />
                    <br />

                    <?php
						//Pull other programs at the parent institution
						$programs = new WP_Query( array(
                            'post_type' => 'nsm_program',
                            'author' => $institution->ID,
                            'order' => 'ASC',
                            'orderby' => 'title',
                            'posts_per_page' => -1,
							'post__not_in' => array($post->ID)
						));
						
						?>
						<section class='accordion closed'>
                            <header>
                                <figcaption>Other programs at <?php echo $institution->display_name ?></figcaption>
                                <div><figure></figure></div>
                            </header>
                            <article>
								<?php while ( $programs->have_posts() ) : $programs->the_post(); ?>
                                <a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
                                - <em><?php echo get_post_meta($post->ID, '_nsm_program_type', true) ?></em>
                                <br />
                                <?php endwhile; wp_reset_postdata(); ?>
                        	</article>
                        </section>    
                <?php 
					endwhile;
	            endif;
			?>
            <br /><br />
            <em>* = University College supported program.  As the University of Maine System’s distance education organization, University College offers access to courses and programs from the seven universities at dozens of locations and online. <a href="http://learn.maine.edu" title="Univeristy College Programs" target="_blank">Click here to learn more.</a></em>
            <br /><br />
            <em>
                Data obtained directly from the institution listed above. Programs change often, so please inquire with the institution to ensure their data has not changed. 
                Last updated on <?php the_date() ?>
            </em>
            <br /><br />
        </div>
        <div class="aside vertical">
            <?php get_sidebar('page') ?>
        </div>
    </section>          
<?php get_footer(); ?>