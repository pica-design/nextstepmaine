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
                    <h3><?php echo $term->name ?></h3>
                    <?php while ($faqs->have_posts()) : $faqs->the_post() ?>
                        
                        <section class='accordion-tall closed'>
                            <header>
                                <figcaption><?php the_title() ?></figcaption>
                                <figure></figure>
                            </header>
                            <article>
                                <?php the_content() ?>
                            </article>
                        </section>
                    <?php endwhile ?>
                <?php endif ?>
                <br /><br />
            <?php endforeach ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>