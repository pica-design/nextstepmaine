<?php
	//Load the template header section
	get_header(); 

	//Load the post content
	//the_post();

	//Dimmension a variable to hold user ouput notices
	$notice = "";

	//Access the currently logged in user details
	//This is how we'll know if the user is trying to edit their own profile
	global $current_user, $wpdb;//, $post;
	if ($current_user->ID != 0) $current_user->data->meta = get_user_meta($current_user->ID);
	//Debugging of the currently logged in user
	//echo "<pre>" . print_r($current_user, true) . "</pre>" ;

	//Gather some info on the author being displayed
	$user_name = get_query_var('author_name');
	$user = get_user_by('slug', $user_name);  
    $user->data->meta = get_user_meta($user->ID);
    $user->data->meta['simple_local_avatar'][0] = unserialize($user->data->meta['simple_local_avatar'][0]);
?>
	
	<section class="content-wrapper">
        <div class="page-content">
        	<?php //$breadcrumbs = new Breadcrumbs ?>
            
            <?php
                $image_url = $user->meta['simple_local_avatar'][0]['full'] ;
                if ($image_url != "") :
            ?>
			<img class="institution-image" src="<?php echo $image_url ?>" alt="<?php echo $user->display_name ?> Logo" />
            <?php else : ?>
            <br />
            <h2><?php echo $user->display_name ?></h2>
            <?php endif ?>

            <div class="clear"></div>
            <br />
            <?php $content = apply_filters('the_content', $user->meta['description'][0]) ;
			
			if (!empty($content)) : echo $content ; ?>
			<?php endif ?>
            
            <?php //$website_url = get_post_meta($post->ID, '_nsm_institution_website_url', true) ?>
            
            <strong>Website:</strong> <a href="<?php echo $user->user_url ?>" title="<?php $user->display_name ?> Website" target="_blank"><?php echo $user->user_url ?></a><br />
            <strong>Address:</strong> <?php echo $user->meta['address'][0] ?><br />
            <strong>General Contact:</strong> <?php echo $user->meta['phone'][0] ?><br />
            <strong>Financial Aid Contact:</strong> <?php echo $user->meta['finaid_contact'][0] ?><br />
            <strong>Admissions Contact:</strong> <?php echo $user->meta['admission_contact'][0] ?><br /><?php

				//Pull other programs at the parent institution
				$programs = new WP_Query( array(
					'post_type' => 'nsm_program',
					'author' => $user->ID,
                    'order' => 'ASC',
                    'orderby' => 'meta_value title',
                    'meta_key' => '_nsm_program_type',
                    'posts_per_page' => -1
				) );
				
                if (!empty($programs->posts)) : ?>
				<section class='accordion closed'>
                    <header>
                        <figcaption>Programs at <?php echo $user->display_name ?></figcaption>
                        <div><figure></figure></div>
                    </header>
                    <article><?php while ( $programs->have_posts() ) : $programs->the_post(); ?>

                        <a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
                        - <em><?php echo ucfirst(get_post_meta($post->ID, '_nsm_program_type', true)) ?></em>
                        - <em><?php echo get_post_meta($post->ID, '_nsm_program_location', true) ?></em><br /><?php endwhile; wp_reset_postdata(); ?>

                	</article>
                </section><?php 
                else :
                    echo "There are currently no programs for this institution. Please check back soon!";
                endif; ?>
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
<?php 
	//Load the template footer section
	get_footer(); 
?>