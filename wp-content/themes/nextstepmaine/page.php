<?php 
    get_header() ;
    the_post() ;
?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>

            <?php the_content() ?>
        </div>
        <div class="aside vertical">
            <?php get_sidebar('page') ?>
        </div>
    </section>      
        
<?php get_footer(); ?>