<?php get_header() ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post() ?>
                    <?php the_content() ?>
                <?php endwhile ?>
            <?php endif ?>
            <br />
            <?php
                $terms = get_terms('nsm_faq_category');
                foreach ($terms as $term) :

                    $faqs = new WP_Query("post_type=nsm_faq&posts_per_page=-1&nsm_faq_category=$term->slug");
            ?>
                <?php if ($faqs->have_posts()) : ?>
                    <h3><?php
                        //This is a little hack because we can't use html in tag names
                        if ($term->name == "Taking the GED&reg; test") :
                            echo "Taking the GED<sup>&reg;</sup> test";
                        else : 
                            echo $term->name ;
                        endif; 
                    ?></h3>
                    <?php while ($faqs->have_posts()) : $faqs->the_post() ?>
                        <section class='accordion closed'>
                            <header>
                                <figcaption>
                                    <a name="<?php echo $post->post_name ?>"><?php the_title() ?></a>
                                </figcaption>
                                <div><figure></figure></div>
                            </header>
                            <article>
                                <figure class="link-icon"><a href="#<?php echo $post->post_name ?>" title="Permalink to <?php the_title() ?>"></a></figure>
                                <?php the_content() ?>
                            </article>
                        </section>
                    <?php endwhile ?>
                <?php endif ?>
                <br /><br />
            <?php endforeach ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('page') ?>
        </div>
    </section>          
<?php get_footer(); ?>