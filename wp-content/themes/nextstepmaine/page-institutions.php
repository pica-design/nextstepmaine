<?php 
	get_header() ;
?>
    <section class="content-wrapper">
        <div class="page-content wide">
        	<?php $breadcrumbs = new Breadcrumbs ?>
    		<h1><?php echo $post->post_title ?></h1>
    		<br />
        	<?php while (have_posts()) : the_post() ?>
        		<?php the_content() ?>
            <?php endwhile ?>
            <table cellpadding="0" cellspacing="0" border="0" class="tablesorter programs">
            	<thead>
                	<tr>
                    	<th><strong>INSTITUTION TITLE</strong><div class="sort-direction"></div></th>
                        <th><strong>CATEGORY</strong></th>
                    	<th><strong>WEBSITE</strong></th>
                    	<th><strong>PROGRAMS</strong></th>
                    </tr>
                </thead>
                <tbody>
            	<?php 
					//Build the custom database query to fetch all user IDs
                    global $wpdb ;
                    $all_user_ids = $wpdb->get_col("
                        SELECT users.ID 
                        FROM $wpdb->users AS users
                        WHERE users.ID != 2
                        ORDER BY users.user_nicename ASC
                    ");

                    foreach ( $all_user_ids as $user_id ) : 
                        $user = get_user_by('id', $user_id);  
                        $user->data->meta = get_user_meta($user->ID);
                    ?>
                	<tr>
                        <td><a href="<?php echo get_user_profile_url($user_id) ?>" title="<?php echo $user->display_name ?>"><?php echo $user->display_name ?></a></td>
                        <td>
                            <?php echo $user->meta['type'][0] ?>
                        </td>
                        <td>
                            <?php $website_url = $user->user_url ?>
                            <a href="<?php echo $website_url ?>" title="<?php echo $user->display_name ?> Website" target="_blank"><?php echo $website_url ?></a>
                        </td>
                        <td>
                            <?php
                                //Pull other programs at the parent institution
                                $programs = new WP_Query(array(
                                    'post_type' => 'nsm_program',
                                    'author' => $user->ID,
                                    'order' => 'ASC',
                                    'orderby' => 'meta_value title',
                                    'meta_key' => '_nsm_program_type',
                                    'posts_per_page' => -1
                                ));
                                echo $programs->post_count . ' Programs';
                            ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
				</tbody>
            </table>
            <!--<br /><br />
            <em>
            	Data obtained from the individual institutions listed above.
            	Last updated on <?php echo $last_updated_date ?>
            </em>
            <br /><br />-->
            <div class="clear"></div><br /><br /><br />
        </div>
    </section>          
<?php get_footer(); ?>