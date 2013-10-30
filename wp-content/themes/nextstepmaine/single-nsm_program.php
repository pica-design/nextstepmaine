<?php 
    global $current_user;

    get_header(); 
?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            <?php if (is_user_logged_in()) : ?>
                <?php if (current_user_can('manage_options') || current_user_can('edit_pages') || $post->post_author == $current_user->ID) : ?>
                    <a href="<?php echo get_edit_post_link() ?>" class="button blue padded rounded">Edit this Program</a>
                    <br /><br />
                <?php endif ?>
            <?php endif ?>
            
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
                    
                    <?php
                        $program = array(
                            'type' => get_post_meta($post->ID, '_nsm_program_type', true),
                            'level' => get_post_meta($post->ID, '_nsm_program_level', true),
                            'format' => get_post_meta($post->ID, '_nsm_program_format', true),
                            'schedule' => get_post_meta($post->ID, '_nsm_program_schedule', true),
                            'timeframe' => get_post_meta($post->ID, '_nsm_program_timeframe', true),
                            'cost' => get_post_meta($post->ID, '_nsm_program_cost', true),
                            'location' => get_post_meta($post->ID, '_nsm_program_location', true),
                            'url' => get_post_meta($post->ID, '_nsm_program_url', true),
                            'cip' => get_post_meta($post->ID, '_nsm_program_cip', true)
                        );
                    ?>
                    <?php if (!empty($program['type'])) : ?>
                    <strong>Type:</strong> <?php echo ucfirst($program['type']) ?><br />
                    <?php endif ?>
                    <?php if (!empty($program['format'])) : ?>
                    <strong>Format:</strong> <?php echo ucfirst($program['format']) ?><br />
                    <?php endif ?>
                    <?php if (!empty($program['level'])) : ?>
                    <strong>Level:</strong> <?php echo ucfirst($program['level']) ?><br />
                    <?php endif ?>
                    <?php if (!empty($program['schedule'])) : ?>
                    <strong>Schedule:</strong> <?php echo ucfirst($program['schedule']) ?><br />
                    <?php endif ?>
                    <?php if (!empty($program['timeframe'])) : ?>
                    <strong>Timeframe:</strong> <?php echo $program['timeframe'] ?><br />
                    <?php endif ?>
                    <?php if (!empty($program['location'])) : ?>
                    <strong>Location:</strong> <?php echo ucfirst($program['location']) ?><br />
                    <?php endif ?>
                    <?php if (!empty($program['cost'])) : ?>
                    <strong>Cost:</strong> <?php echo $program['cost'] ?><br />
                    <?php endif ?>
                    <?php if (!empty($program['url'])) : ?>
                    <strong>Link:</strong> <a href="<?php echo $program['url'] ?>" title="<?php the_title() ?>" target="_blank"><?php echo $program['url'] ?></a><br />
                    <?php endif ?>

                    <br />

                    <?php if (!empty($program['cip'])) : ?>
                        This program qualifies students for the following occupation(s):<br />
                        <?php
                            //Connect to our CIP to SOC Crosswalk database
                            $cip_to_soc = new wpdb(DB_USER, DB_PASSWORD, 'nsm_cip_to_soc', DB_HOST);
                            //Select the SOC codes which pair with the current program's CIP code
                            $soc_codes = $cip_to_soc->get_results("SELECT SOC FROM cip_soc WHERE CIP = '{$program['cip']}'");

                            //Connect to our onet database
                            $onet = new wpdb(DB_USER, DB_PASSWORD, 'nsm_onet', DB_HOST);

                            foreach ($soc_codes as $soc) : 
                                $occupations = $onet->get_results("SELECT * 
                                                            FROM occupation_data
                                                            WHERE onetsoc_code LIKE '$soc->SOC%'");
                                foreach ($occupations as $occupation) : ?>
                                    <?php
                                        //Unfortunetly we have two different soc_code types
                                            //The imported data from DOL has codes like 25-4021
                                            //BUT the imported data from onet has codes like 25-4021.00
                                            //This little explosion removes the . and anything after it
                                        $onetsoc_code = explode('.', $occupation->onetsoc_code);
                                        $onetsoc_code = $onetsoc_code[0];
                                        
                                        //Pull the related occupation nsm_job post so we can get it's permalink
                                        $high_occupation = new WP_Query(array(
                                            'post_type' => 'nsm_job',
                                            'posts_per_page' => -1,
                                            'meta_key' => '_nsm_job_soc_code',
                                            'meta_value' => $onetsoc_code
                                        ));
                                        
                                        if ($high_occupation->have_posts()) : 

                                            //Single loop the returned related occupation and display the title + a link
                                            while ($high_occupation->have_posts()) : $high_occupation->the_post(); ?> 
                                            <a href="<?php echo the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a><br />
                                            <?php endwhile ?>
                                        <?php else : $show_annotation = true; ?>
                                            <a href="http://www.onetonline.org/link/summary/<?php echo $occupation->onetsoc_code ?>" title="<?php echo $occupation->title ?>" target="_blank"><?php echo $occupation->title ?></a> <span class="annotation">*</span><br />
                                        <?php endif ?>
                                <?php endforeach;
                            endforeach;
                        endif
                    ?>
                    <?php if ($show_annotation) : ?>
                    <em><span class="annotation">*</span> The related job mentioned is not listed as 'In-Demand' or 'High-growth' in Maine.</em>
                    <?php endif ?>

                    <br /><br />
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