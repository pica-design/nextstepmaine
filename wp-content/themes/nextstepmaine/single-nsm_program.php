<?php 
    global $current_user;

    get_header(); 
?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            <?php if (is_user_logged_in()) : ?>
                <?php //print_r($post); ?>
                <?php if (current_user_can('manage_options') || current_user_can('edit_pages') || $post->post_author == $current_user->ID) : ?>
                    <a href="<?php echo get_edit_post_link($post->ID) ?>" class="button blue padded rounded alignright">Edit this Program</a>
                <?php endif ?>
            <?php endif ?>
            
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
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
                            'cip' => get_post_meta($post->ID, '_nsm_program_cip', true),
                            'uc' => get_post_meta($post->ID, '_nsm_program_uc', true)
                        );

                        //print_r($program);
                    ?>
                    <!--<p>Below are the details about the program you’ve chosen.  Contact the school directly for admissions and financial aid information, or use the link to go directly to the school’s website for more details.</p>-->
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
                    
                    <?php the_content() ?>

                    <?php if ($program['uc'] == 'on') : ?>
                    <p>This is a University College supported program.  As the University of Maine System’s distance education organization, University College offers access to courses and programs from the seven universities at dozens of locations and online. <a href="http://learn.maine.edu" title="Univeristy College Programs" target="_blank">Click here to learn more about University College.</a></p>
                    <?php endif ?>
                    
                    <?php if (!empty($program['type'])) : ?>
                    <label title="Program types include Certificate, Associate, Bachelor, and Master."><strong>Program Type:</strong> <?php echo ucfirst($program['type']) ?></label><br />
                    <?php endif ?>
                    <?php if (!empty($program['format'])) : ?>
                    <label title="Program Format indicates where you physically attend the program. I.e. in the 'Classroom' or 'Online'."><strong>Program Format:</strong> <?php echo ucfirst($program['format']) ?></label><br />
                    <?php endif ?>
                    <?php if (!empty($program['level'])) : ?>
                    <label title="Program Level indicates the level of course credit you'll recieve. I.e. 'Undergraduate' credits apply towardes an undergraduate degree."><strong>Program Level:</strong> <?php echo ucfirst($program['level']) ?></label><br />
                    <?php endif ?>
                    <?php if (!empty($program['schedule'])) : ?>
                    <label title="Program schedule is either 'Fixed' or 'Flexible'. Meaning the course is flexible to your needs and may afford you non-traditional options like taking a test at your convenience."><strong>Program Schedule:</strong> <?php echo ucfirst($program['schedule']) ?></label><br />
                    <?php endif ?>
                    <?php if (!empty($program['timeframe'])) : ?>
                    <label title="The program timeframe gives you an idea how long it typically takes to complete the program."><strong>Program Timeframe:</strong> <?php echo $program['timeframe'] ?></label><br />
                    <?php endif ?>
                    <?php if (!empty($program['location'])) : ?>
                    <label title="Location describes the physical location where you would attend the program (If there is one)."><strong>Program Location:</strong> <?php echo ucfirst($program['location']) ?></label><br />
                    <?php endif ?>
                    <?php if (!empty($program['cost'])) : ?>
                    <label title="Completing a program affords you a certain number of credit hours. I.e. Many programs give you 3 credit hours upon completion. This cost is per credit hour. I.e. a program worth 3 credit hours at $200 per credit = $600 program."><strong>Program Cost:</strong> <?php echo $program['cost'] ?></label><br />
                    <?php endif ?>
                    <?php if (!empty($program['url'])) : ?>
                    <br /><a class="button gray padded rounded" href="<?php echo $program['url'] ?>" title="<?php the_title() ?>" target="_blank">Click here to Learn More</a><br />
                    <?php endif ?>

                    <br />

                    <?php 
                        $show_annotation = false ;
                        $occupations = array();

                        if (!empty($program['cip'])) : 

                            //Connect to our CIP to SOC Crosswalk database
                            $cip_to_soc = new wpdb(DB_USER, DB_PASSWORD, 'nextstep_cip_to_soc', DB_HOST);
                            //Select the SOC codes which pair with the current program's CIP code
                            $sql = "SELECT SOC FROM cip_soc WHERE CIP IN ({$program['cip']}) AND SOC != 'NO MATCH'";
                            $soc_codes = $cip_to_soc->get_results($sql);

                            if (!empty($soc_codes)) : ?>
                                <section class='accordion closed'>
                                    <header>
                                        <figcaption>Occupations</figcaption>
                                        <div><figure></figure></div>
                                    </header>
                                    <article>
                                        This program qualifies students for the following occupation(s):<br /><?php 
                                //Connect to our onet database
                                $onet = new wpdb(DB_USER, DB_PASSWORD, 'nextstep_onet', DB_HOST);

                                foreach ($soc_codes as $soc) : 
                                    //Select occupations with the current SOC code
                                    $soc_occupations = $onet->get_results("SELECT * 
                                                                           FROM occupation_data
                                                                           WHERE onetsoc_code LIKE '$soc->SOC%'");

                                    //Build an an array of just the soc codes and occupation titles
                                    foreach ($soc_occupations as $occ) : 
                                        //Unfortunetly we have two different soc_code types
                                        //The imported data from DOL has codes like 25-4021
                                        //BUT the imported data from onet has codes like 25-4021.00
                                        //This little explosion removes the . and anything after it
                                        $onetsoc_code = explode('.', $occ->onetsoc_code);
                                        $onetsoc_code = $onetsoc_code[0];
                                        $occupations[$onetsoc_code] = $occ->title;
                                    endforeach;
                                endforeach;
                                
                                //Sort the occupations by title
                                asort($occupations);

                                //Loop through the occupations and output them
                                foreach ($occupations as $onetsoc_code => $occupation) : 
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
                                        <a href="<?php echo the_permalink() ?>" title="<?php echo $occupation ?>"><?php echo $occupation ?></a> <span class="annotation">*</span><br />
                                        <?php endwhile ?>
                                    <?php else : $show_annotation = true; ?>
                                        <a href="http://www.onetonline.org/link/summary/<?php echo $onetsoc_code ?>" title="<?php echo $occupation ?>" target="_blank"><?php echo $occupation ?></a><br /><?php 
                                        endif ;
                                endforeach ;

                                if ($show_annotation) : ?>

                                <br /><em><span class="annotation">*</span> The occupation mentioned is in demand in the state of Maine, and is projected to employ new workers each year.</em><br /><?php endif ?>

                                </article>
                            </section><?php 
                            endif; 
                        endif;

						//Pull other programs at the parent institution
						$programs = new WP_Query( array(
                            'post_type' => 'nsm_program',
                            'author' => $institution->ID,
                            'order' => 'ASC',
                            'orderby' => 'title',
                            'posts_per_page' => -1,
							'post__not_in' => array($post->ID)
						)); ?>

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
                        </section><?php 
					endwhile;
	            endif; ?>

            <br /><br />
            <em>
                Data entered and maintained by the institution listed above. Programs change often; please inquire with the institution to ensure their data has not changed. 
                <br /><br />
                <?php if (!empty($occupations)) : ?>
                Occupations for the given program are determined from <a href="http://nces.ed.gov/ipeds/cipcode/Default.aspx?y=55" title="What is the CIP?" target="_blank">CIP codes</a> entered for the program, which are then correlated to <a href="http://www.onetcenter.org/database.html" title="O*NET Occupations" target="_blank">O*NET SOC Codes</a> via the <a href="http://www.bls.gov/soc/soccrosswalks.htm" title="2010 CIP to SOC Crosswalk" target="_blank">2010 CIP to SOC Crosswalk</a>. 
                <br /><br />
                <?php endif ?>
                Last updated on <?php the_date() ?>
            </em>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('page') ?>
        </div>
        <div class="clear"></div>
        <br /><br /><br />
    </section>          
<?php get_footer(); ?>