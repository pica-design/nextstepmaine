<?php 
    /* Template Name: MEI Pages */
    get_header() ;
    the_post() ;
    $post_ID = $post->ID
?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            <h2><?php echo $post->post_title; ?></h2><br />
            <?php the_content(); ?>
        </div>
        <div class="aside vertical">
            <?php 
            //print_r($post->ID);
            $args = array(
                'post_parent' => '9221',
                'post_type'   => 'page', 
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC'
            ); 

             $children_array = new WP_Query( $args );
             
            ?>

            <nav><?php
                if ( $children_array->have_posts() ) :
                    echo '<ul>';
                        while ( $children_array->have_posts() ) :
                            $children_array->the_post(); ?>
                                    <li class="<?php if($post_ID == $post->ID) echo 'current-page'; ?>"><a href="<?php echo $post->guid ?>"><?php echo $post->post_title ?></a></li>
                        <?php endwhile;
                    echo '</ul>'; 
                else :
                    // no posts found
                endif; ?>
            </nav>
        </div>
    </section>     
        
<?php get_footer(); ?>