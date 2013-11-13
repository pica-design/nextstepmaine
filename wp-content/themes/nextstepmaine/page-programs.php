<?php 
    /*UPDATE nsm_wp_postmeta SET meta_value = REPLACE(meta_value, 'minor', 'associate') WHERE meta_key = '_nsm_program_type'*/

    global $wp;

	get_header() ;

    the_post();
    $programs_page_url = get_permalink($post->ID);

    //Declare our vars..
    $current_program_type = $associate_active = $bachelor_active = $master_active = $certificate_active  = $all_programs_active = "";
    $program_types = array(
        'certificate' => 'Certificate',
        'associate' => 'Associate',
        'bachelor' => 'Bachelor',
        'master' => 'Master'
    );

	//Capture the new query var for use, i.e. with site.com/programs/foo the following will output 'foo'
	$current_program_type = get_query_var('type');
    $current_program_category = get_query_var('category');

    $current_url = home_url($wp->request) . "/?" . $_SERVER['QUERY_STRING'];
    //echo $current_url;

    //This should only be used when changing 
    //if (!empty($current_program_type)) $current_url = add_query_arg('type', $current_program_type, $current_url);
?>
    <section class="content-wrapper">
        <div class="page-content wide">
        	<?php $breadcrumbs = new Breadcrumbs ?>
    		<h1><?php the_title() ?></h1>
    		<br />
        	<?php the_content() ?>
            <br />
        	<div class="filter-options">
        		<div class="filter-divider">Show me</div>
                <div class="program-categories">
                    <select name="program-categories">
                        <option value="<?php echo remove_query_arg('category', $current_url) ?>"><?php echo empty($current_program_category) ? 'Choose a Category' : 'Remove Category Filter' ?></option><?php 
                        $program_categories = get_terms('nsm_program_category');
                        foreach ($program_categories as $category) : ?>

                        <option value="<?php echo add_query_arg('category', $category->slug, $current_url) ?>" <?php selected($current_program_category, $category->slug) ?>>
                            <?php echo $category->name ?> (<?php echo $category->count ?>)
                        </option><?php endforeach ?>

                    </select>
                </div>
                <div class="program-types">
                    <select name="program-types">
                        <option value="<?php echo remove_query_arg('type', $current_url) ?>"><?php echo empty($current_program_type) ? 'Choose a Type' : 'Remove Type Filter' ?></option><?php foreach ($program_types as $key => $value) : ?><?php
                        $args = array(
                            'post_type' => 'nsm_program',
                            'meta_query' => array(array(
                                'key' => '_nsm_program_type',
                                'value' => $key
                            )),
                            'posts_per_page' => -1
                        );
                        //If a category filter is in place piggyback onto it and compound the filtering prowess
                        if (!empty($current_program_category)) $args['nsm_program_category'] = $current_program_category ;
                        $programs = get_posts($args); ?>

                        <option value="<?php echo add_query_arg('type', $key, $current_url) ?>" <?php selected($current_program_type, $key) ?>>                            
                            <?php echo $value ?> (<?php echo count($programs) ?>)
                        </option><?php endforeach ?>
                    </select>
                </div>
                <div class="filter-divider">Programs</div>
	        </div><?php
                //We want to display a little tooltip/help info for certain program types

                //Display a link for users to learn more about certificate programs
                if ($current_program_type == 'certificate') : ?>
                    Confused about the different types of certificates?  <a href='<?php bloginfo('url') ?>/faq/#what-are-all-of-these-certificate-programs' title='Certificiate Program FAQ'>Learn More here</a>.<br /><br /><?php endif ?>                    

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
                    //Build our query arguments to pull programs
                    $program_args = array(
                        'post_type' => 'nsm_program',
                        'order' => 'ASC',
                        'orderby' => 'title',
                        'posts_per_page' => 20
                    );

                    if(isset($paged)) $program_args['paged'] = $paged ;

                    if (!empty($current_program_category)) $program_args['posts_per_page'] = -1 ;

					//Bolt on any program type args via a meta query
					if (!empty($current_program_type)) : 
                        //However, if the program_type query var has been set via GET we want to only show programs of that type
                        //Note, the query var is set like site.com/programs/associate which is internally translated to ?program_type=associate
                        //and accessed via get_query_var('program_type'); above
						$program_args['meta_query'] = array(
                            array(
                                'key' => '_nsm_program_type',
                                'value' => "$current_program_type",
                                'compare' => 'LIKE'
                            )
                        );
                    endif;

                    //Bolt on any category args
                    if (!empty($current_program_category)) : 
                        $program_args['nsm_program_category'] = $current_program_category ;
                    endif;

                    //print_r($program_args);

                    $programs = new WP_Query($program_args); 
					while ($programs->have_posts()) : $programs->the_post();
                         $institution = get_user_by('id', $post->post_author); 
                         $location = get_post_meta($post->ID, '_nsm_program_location', true); ?>
                	<tr>
                        <td><?php 
							$program_categories = wp_get_post_terms($post->ID, 'nsm_program_category');
							foreach ($program_categories as $key => $category) :
								echo $category->name;
								if ($key < count($program_categories) - 1) echo ", ";
							endforeach ?>
                        </td>
                        <td><a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a></td>
                        <td><?php echo ucfirst(get_post_meta($post->ID, '_nsm_program_type', true)) ?></td>
                        <td><?php echo ucwords(get_post_meta($post->ID, '_nsm_program_format', true)) ?></td>
                        <td><?php echo get_post_meta($post->ID, '_nsm_program_cost', true) ?></td>
                        <td>
                            <a href="<?php echo get_user_profile_url($post->post_author) ?>" title="<?php echo $institution->display_name ?>"><?php echo $institution->display_name ?></a>
                            <?php if (strtolower($location) != 'n/a') echo " - " . $location ?>
                        </td>
                    </tr>
					<?php 
						endwhile ; 
						$last_updated_date = get_the_date();
						wp_reset_postdata(); ?>
				</tbody>
            </table>
            <div class="loop-controls">
                <?php 
                    $wp_query = $programs ;
                    pagination() ;
                ?>
            </div>
            <br /><br />
            <p><em>
            	Data obtained from the individual institutions listed above. Programs change often, so please inquire with the host institution in question to ensure their data has not changed. 
            	Last updated on <?php echo $last_updated_date ?>
            </em></p>
            <br />
        </div>
    </section>          
<?php get_footer(); ?>