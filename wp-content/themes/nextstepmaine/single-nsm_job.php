<?php 
    get_header(); 
    the_post();

    //Connect to our onet database
    $onet = new wpdb(DB_USER, DB_PASSWORD, 'nextstep_onet', DB_HOST);

    $occupation_title = get_the_title();
    $occupation_wp_id = get_the_ID();

    //Grab the current occupation soc code
    $onetsoc_code = get_post_meta($post->ID, '_nsm_job_soc_code', true);

    $occupation_data = $onet->get_results("
        SELECT * 
        FROM occupation_data
        WHERE onetsoc_code LIKE '$onetsoc_code%'
    ");
?>
    <section class="content-wrapper">
        <div class="page-content">
            <?php $breadcrumbs = new Breadcrumbs ?>

            <h2><?php echo $occupation_title ?></h2>
            <br />
            <p><?php echo $occupation_data[0]->description ?></p>

            <strong>Number of Jobs in 2010:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_base_employment', true) ?><br />
            <strong>Number of Jobs in 2020:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_projected_employment', true) ?><br />
            <strong>Yearly Job Growth Rate:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_growth_rate', true) ?><br />
            <strong>Annual Openings:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_annual_openings', true) ?><br />
            <strong>Entry Wage:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_entry_wage', true) ?><br />
            <strong>Median Wage:</strong> <?php echo get_post_meta($post->ID, '_nsm_job_median_wage', true) ?><br />
            <strong>Education Requirement:</strong>
            <?php
                $terms = wp_get_post_terms($post->ID, 'nsm_job_education_requirement');
                foreach ($terms as $key => $term) : 
                    echo $term->name ;
                    if ($key != 0 && $key != (count($terms) - 1)) : 
                        echo ", ";
                    endif;
                endforeach;
            ?>
            <br /><br />
            <a class="button gray rounded padded" href="<?php echo get_post_meta($post->ID, '_nsm_job_onet_link', true) ?>" title="<?php echo $post->post_title ?>" target="_blank">Learn more about this job</a> 
            <br /><br />
            <?php
                /********************************
                    WORK TASKS
                ********************************/
                //Select some information about the current occupation
                $tasks = $onet->get_results("
                    SELECT task 
                    FROM task_statements 
                    WHERE onetsoc_code 
                    LIKE '%$onetsoc_code%'
                ");
                if (count($tasks) > 0) : 
            ?>
            <section class='accordion closed'>
                <header>
                    <figcaption>Job Tasks</figcaption>
                    <div><figure></figure></div>
                </header>
                <article>
                    <ul class="show-bullets">
                    <?php    
                        foreach ($tasks as $task) :
                            echo "<li>" . $task->task . "</li>";
                        endforeach;
                    ?>
                    </ul>
                </article>
            </section>
            <?php endif ?>

            <?php
                //Connect to our CIP to SOC Crosswalk database
                $cip_to_soc = new wpdb(DB_USER, DB_PASSWORD, 'nsm_cip_to_soc', DB_HOST);
                //Select the SOC codes which pair with the current program's CIP code
                $cip_codes = $cip_to_soc->get_results("SELECT CIP FROM cip_soc WHERE SOC = '$onetsoc_code'");
                $cip_soc_match = false;
                $programs = array();
                $meta_query = array(
                    'relation' => 'OR'
                );

                //There can be multiple CIP codes per occupation
                //Loop through all the occupations cip codes
                foreach ($cip_codes as $cip) : 
                    if (!empty($cip)) : 
                        //try to pull programs with a matching cip code
                        $tmp_programs = new WP_Query(array(
                            'post_type' => 'nsm_program',
                            'posts_per_page' => -1,
                            'meta_key' => '_nsm_program_cip',
                            'meta_value' => $cip->CIP,
                            'orderby' => 'title',
                            'order' => 'ASC'
                        ));
                        if ($tmp_programs->have_posts()) : 
                            //Merge the returned programs with those from other cip codes
                            $programs = array_merge($programs, $tmp_programs->posts);
                        endif;
                    endif;
                endforeach;

                if (!empty($programs)) : ?>
                    <section class='accordion closed'>
                        <header>
                            <figcaption>Maine Educational Programs</figcaption>
                            <div><figure></figure></div>
                        </header>
                        <article>
                            This occupation requires candidates complete a program similar to those listed below:<br />

                            <?php foreach ($programs as $program) : 
                            $author = get_user_by('id', $program->post_author); ?>
                            <a href="<?php echo get_permalink($program->ID) ?>" title="<?php echo $program->post_title ?>"><?php echo $program->post_title ?></a> at 
                            <a href="<?php echo get_user_profile_url($author->user_nicename) ?>" title="Learn more about <?php echo $author->display_name ?>"><?php echo $author->display_name ?></a><br />
                            <?php endforeach ?>
                        </article>
                    </section><?php 
                endif ;
            ?>
            <?php
                /********************************
                    RELATED OCCUPATIONS
                ********************************/
                //Select any occupations related to the currently viewed occupation
                $related_occupations = $onet->get_results("
                    SELECT occupation_data.title, related_occupations.onetsoc_code_related
                    FROM occupation_data
                    JOIN related_occupations
                        ON occupation_data.onetsoc_code = related_occupations.onetsoc_code_related
                    WHERE related_occupations.onetsoc_code LIKE '%$onetsoc_code%'
                ");
                
                if (count($related_occupations) > 0) : 
            ?>
            <section class='accordion closed'>
                <header>
                    <figcaption>Related Occupations</figcaption>
                    <div><figure></figure></div>
                </header>
                <article>
                    <p>Indivduals currently working as <?php echo $occupation_title ?> can easily transition into any of the occupations lisated below.</p>
                    <ul class="show-bullets">
                    <?php  
                        $show_annotation = false;
                          
                        //Loop through each related occupation
                        foreach ($related_occupations as $related_occupation) :
                        
                            //print_r($related_occupation);
                        
                            //Unfortunetly we have two different soc_code types
                                //The imported data from DOL has codes like 25-4021
                                //BUT the imported data from onet has codes like 25-4021.00
                                //This little explosion removes the . and anything after it
                            $onetsoc_code_related = explode('.', $related_occupation->onetsoc_code_related);
                            $onetsoc_code_related = $onetsoc_code_related[0];
                            
                            //Pull the related occupation nsm_job post so we can get it's permalink
                            $occupation = new WP_Query(array(
                                'post_type' => 'nsm_job',
                                'posts_per_page' => -1,
                                'meta_key' => '_nsm_job_soc_code',
                                'meta_value' => $onetsoc_code_related
                            ));
                            
                            
                            if ($occupation->have_posts()) : 
                                //Single loop the returned related occupation and display the title + a link
                                while ($occupation->have_posts()) : $occupation->the_post(); ?> 
                                <li><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo $related_occupation->title ?>"><?php echo $related_occupation->title ?></a><span class="annotation">*</span>
                                <?php endwhile ?>
                            <?php else : $show_annotation = true; ?>
                                <li><a href="http://www.onetonline.org/link/summary/<?php echo $related_occupation->onetsoc_code_related ?>" title="<?php echo $related_occupation->title ?>" target="_blank"><?php echo $related_occupation->title ?></a></li>
                            <?php endif ?>
                        <?php endforeach ?>
                    </ul>
                    <?php if ($show_annotation) : ?>
                    <br />
                    <em><span class="annotation">*</span> The occupation mentioned is in demand in the state of Maine, and is projected to employ new workers each year.</em>
                    <?php endif ?>
                </article>
            </section>
            <?php endif ?>

            <br /><br />
            <em>
                Data obtained from the <a href="http://www.maine.gov/labor/cwri/data/oes/hwid.html" title="Maine Department of Labor" target="_blank">Maine Department of Labor</a> 
                and <a href="http://www.onetcenter.org/" title="O*NET Resoruce Center" target="_blank">O*NET</a>
                <br />
                Last updated on <?php the_date() ?>
            </em>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('page') ?>
        </div>
    </section>
    <div class="clear"></div>
    <br /><br />          
<?php get_footer(); ?>