<?php
	//Remove single asterisks from post titles
	//then update_post_meta($post->ID, '_nsm_program_uc', 1)

	global $wpdb ;

	//select all posts with a single asterisk in the title
	//make sure we don't select posts with a double asterisk in the title (thats for something else)

	$posts = $wpdb->get_results("
		SELECT ID, post_title
		FROM $wpdb->posts
		WHERE post_title LIKE '%*%' AND post_title NOT LIKE '%**%'
	");

	print_r($posts);

	/*
	foreach ($posts as $post) : 
		wp_update_post(array(
			'ID' => $post->ID,
			'post_title' => str_replace('*', '', $post->post_title)
		));
		update_post_meta($post->ID, '_nsm_program_uc', 'on');
	endforeach;
	*/