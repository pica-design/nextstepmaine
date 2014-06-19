<?php 
    get_header() ;
    the_post() ;
?>
    <section class="content-wrapper">
        <div class="page-content">
        	<?php $breadcrumbs = new Breadcrumbs ?>
            <h2><?php echo $post->post_title; ?></h2>
            <?php the_content() ?>
        </div>
        <div class="aside vertical">
            <?php 
            //print_r($post->ID);
            $args = array(
                'post_parent' => $post->ID,
                'post_type'   => 'page', 
                'posts_per_page' => -1
            ); 

             $children_array = get_children( $args, $output );
             
            ?>

            <nav>
                <ul>
                    <?php 
                        foreach ($children_array as $child) : ?>
                            <li><a href="<?php echo $child->guid ?>"><?php echo $child->post_title ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </section>      
        
<?php get_footer(); ?>