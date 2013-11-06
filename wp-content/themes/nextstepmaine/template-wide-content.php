<?php 
    /* Template Name: Wide Content */
    get_header() ;
    the_post() ;
?>
    <section class="content-wrapper">
        <div class="page-content wide">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            
            <?php the_content() ?>
        </div>
    </section>

<?php get_footer(); ?>