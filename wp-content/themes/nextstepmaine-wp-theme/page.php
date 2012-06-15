<?php get_header(); ?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
        	<?php if (have_posts()) : ?>
            	<?php while (have_posts()) : the_post() ?>
                	<?php 
						/*
						$content = get_the_content($post->ID);
						$content = do_shortcode($content);
						echo wpautop($content);
						*/
						$content = get_the_content($post->ID);
						$content = do_shortcode($content);
						echo nl2br($content);
					?>
                <?php endwhile ?>
            <?php endif ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('Page') ?>
        </div>
    </section>          
<?php get_footer(); ?>