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

    $user_contact_details = array(
        'phones' => array (
            'General Phone' => $user->meta['general_phone'][0],
            'Financial Aid Phone' => $user->meta['finaid_phone'][0],
            'Admissions Phone' => $user->meta['admission_phone'][0]
        ),
        'General Email' => $user->meta['general_email'][0],
        'Financial Aid Email' => $user->meta['finaid_email'][0],
        'Admissions Email' => $user->meta['admission_email'][0],
        'Website' => $user->user_url,
        'Address' => $user->meta['address'][0]
    );
?>
	
	<section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            <?php if (is_user_logged_in()) : ?>
                <?php if (current_user_can('manage_options') || $user->ID == $current_user->ID) : ?>
                    <a href="<?php echo get_edit_user_link($user->ID) ?>" class="button blue padded rounded alignright">Edit Your Information</a>
                <?php endif ?>
            <?php endif ?>
            
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

            <?php foreach ($user_contact_details['phones'] as $title => $value) : ?>
                <?php if (!empty($value)) : ?>
                    <strong><?php echo $title ?></strong>: <?php echo $value ?>
                    <br />
                <?php endif ?>
            <?php endforeach ?>
            <br />

            <?php if (!empty($user_contact_details['Admissions Email'])) : ?>
            <a class="button gray padded rounded" href="<?php echo eae_encode_str("mailto:{$user_contact_details['Admissions Email']}?subject=$user->display_name Admissions Inquiry") ?>" title="Send an Email to <?php echo $user->display_name ?> Admissions">Email Admissions</a>
            &nbsp; &nbsp; 
            <?php endif ?>

            <?php if (!empty($user_contact_details['Financial Aid Email'])) : ?>
            <a class="button gray padded rounded" href="<?php echo eae_encode_str("mailto:{$user_contact_details['Financial Aid Email']}?subject=$user->display_name Financial Aid Inquiry") ?>" title="Send an Email to <?php echo $user->display_name ?> Financial Aid">Email Financial Aid</a>
            &nbsp; &nbsp; 
            <?php endif ?>

            <?php if (!empty($user_contact_details['Website'])) : ?>
            <a class="button gray padded rounded" href="<?php echo $user_contact_details['Website'] ?>" title="Visit the <?php echo $user->display_name ?> Website" target="_blank">Visit Website</a>
            &nbsp; &nbsp; 
            <?php endif ?>

            <?php if (!empty($user_contact_details['Address'])) : ?>
            <a class="button gray padded rounded" href="http://www.google.com/maps?q=<?php echo urlencode($user_contact_details['Address']) ?>" title="View at Google Maps" target="_blank">View Map</a>
            <?php endif ?>

            <br /><br />
            <?php

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
        </div>
        <div class="aside vertical">
            <?php get_sidebar('page') ?>
        </div>
        <div class="clear"></div>
        <br /><br /><br />
    </section>    
<?php 
	//Load the template footer section
	get_footer(); 
?>