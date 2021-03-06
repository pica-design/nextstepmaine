<?php get_header(); ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
                    <?php
                        $image_url = get_post_meta($post->ID, '_nsm_institution_logo', true) ;
                        if ($image_url != "") :
                    ?>
					<img class="institution-image" src="<?php echo $image_url ?>" alt="<?php the_title() ?> Logo" />
                    <?php else : ?>
                    <br />
                    <h2><?php the_title() ?></h2>
                    <?php endif ?>
                    <div class="clear"></div>
                    <br />
                    <?php $content = get_the_content() ;
					if (!empty($content)) : echo $content ; ?><br /><br />
					<?php endif ?>
                    
                    <?php $website_url = get_post_meta($post->ID, '_nsm_institution_website_url', true) ?>
                    
                    <strong>Website:</strong> <a href="<?php echo $website_url ?>" title="<?php the_title() ?> Website" target="_blank"><?php echo $website_url ?></a><br />
                    <strong>Address:</strong> <?php echo get_post_meta($post->ID, '_nsm_institution_address', true) ?><br />
                    <strong>General Contact:</strong> <?php echo get_post_meta($post->ID, '_nsm_institution_phone', true) ?><br />
                    <strong>Financial Aid Contact:</strong> <?php echo get_post_meta($post->ID, '_nsm_institution_finaid_contact', true) ?><br />
                    <strong>Admissions Contact:</strong> <?php echo get_post_meta($post->ID, '_nsm_institution_admission_contact', true) ?><br />
                    <br />
                    
                    <?php
						//Pull other programs at the parent institution
						$programs = new WP_Query( array(
							'connected_type' => 'Program Institution',
							'connected_items' => $post,
							'nopaging' => true,
                            'order' => 'ASC',
                            'orderby' => 'title meta_value',
                            'meta_key' => '_nsm_program_type'
						) );
						
                        if (!empty($programs->posts)) :

						?>
						<section class='accordion closed'>
                            <header>
                                <figcaption>Programs at <?php the_title() ?></figcaption>
                                <div><figure></figure></div>
                            </header>
                            <article>
								<?php while ( $programs->have_posts() ) : $programs->the_post(); ?>
                                <a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
                                - <em><?php echo ucfirst(get_post_meta($post->ID, '_nsm_program_type', true)) ?></em>
                                - <em><?php echo get_post_meta($post->ID, '_nsm_program_location', true) ?></em>
                                <br />
                                <?php endwhile; wp_reset_postdata(); ?>
                        	</article>
                        </section>
                <?php 
                        else :
                            echo "There are currently no programs for this institution. Please check back soon!";
                        endif;
					endwhile;
	            endif;
			?>
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