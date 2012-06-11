<?php
/**
 * The template for displaying Search Results pages.
*/

get_header(); 

$search_query = str_replace('"', '', get_search_query());
?>

		<section class="content-wrapper search-results">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'nextstepmaine' ), '<strong>' . get_search_query() . '</strong>' ); ?></h1>
				</header>

				

				<?php while ( have_posts() ) : the_post(); ?>
					<br /><br />
					<div class="entry-summary">
                    	<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><strong>
							<?php 
								$title = get_the_title();
								echo str_replace(strtolower($search_query), "<span class='query'>" . ucwords($search_query) . "</span>", strtolower($title));
							?>
                        </strong></a>
						<?php 
							$excerpt = get_the_excerpt();
							$excerpt = str_replace('<strong class="search-excerpt">', '', $excerpt);
							$excerpt = str_replace('</strong>', '', $excerpt);
							echo str_replace(strtolower($search_query), "<span class='query'>" . ucwords($search_query) . "</span>", strtolower($excerpt));
						?>
                    </div><!-- .entry-summary -->
				<?php endwhile; ?>

				

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'nextstepmaine' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentyeleven' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			
		</section><!-- .content-wrapper -->

<?php get_footer(); ?>