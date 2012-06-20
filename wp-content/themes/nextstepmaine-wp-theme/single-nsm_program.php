<?php get_header(); ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
					<?php
						// Find connected pages
						$institution = p2p_type( 'Program Institution' )->get_connected( $post );
						
						while ( $institution->have_posts() ) : $institution->the_post(); 
							$institution_post = $post;
							$institution_title = get_the_title($post->ID); 
							$institution_internal_url = get_permalink($post->ID); 
						?>
							<img class="institution-image" src="<?php echo get_post_meta($post->ID, '_nsm_institution_logo', true) ?>" alt="<?php the_title() ?> Logo" />
							<h2><?php echo $institution_title ?></h2>
                            
					<?php endwhile; wp_reset_postdata(); ?>
                    
                    <br />
                    <h3><?php the_title() ?></h3>
                    <br />
                    
                    <?php $content = get_the_content() ;
					if (!empty($content)) : echo $content ; ?><br /><br />
					<?php endif ?>
                    
                    <strong>Type:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_type', true) ?><br />
                    <strong>Format:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_format', true) ?><br />
                    <strong>Location:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_location', true) ?><br />
                    <strong>Schedule:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_schedule', true) ?><br />
                    <strong>Url:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_schedule', true) ?><br />
                    <strong>Timeframe:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_timeframe', true) ?><br />
                    <strong>Cost:</strong> <?php echo get_post_meta($post->ID, '_nsm_program_cost', true) ?><br />
                    
                    <br />

                    <?php
						//Pull other programs at the parent institution
						$programs = new WP_Query( array(
							'connected_type' => 'Program Institution',
							'connected_items' => $institution_post,
							'nopaging' => true,
							'post__not_in' => array($post->ID)
						) );
						
						?>
						<div class='accordion open'>
                            <div class='title'>Other programs at <?php echo $institution_title ?></div>
                            <div class='content'>
								<?php while ( $programs->have_posts() ) : $programs->the_post(); ?>
                                <a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
                                - <em><?php echo get_post_meta($post->ID, '_nsm_program_type', true) ?></em>
                                <br />
                                <?php endwhile; wp_reset_postdata(); ?>
                        	</div>
                        </div>    
                <?php 
					endwhile;
	            endif;
			?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>