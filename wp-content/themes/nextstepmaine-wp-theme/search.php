<?php
/**
 * The template for displaying Search Results pages.
*/

get_header(); 

$search_query = str_replace('"', '', get_search_query());
$search_query_words = explode(' ', $search_query);

?>

		<section class="content-wrapper search-results">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h3 class="page-title"><?php printf( __( 'Search Results for: %s', 'nextstepmaine' ), '<strong>' . get_search_query() . '</strong>' ); ?></h3>
				</header>

				
				<?php 
					$cpt_posts_count = Array();
					
					while ( have_posts() ) : the_post();
						++$cpt_posts_count[$post->post_type];
					endwhile ;
				?>

				<?php $current_cpt = "" ?>

				<?php while ( have_posts() ) : the_post(); ?>
					<?php 
						if ($post->post_type != $current_cpt) : 
							$cpt = get_post_type_object($post->post_type); 
							$current_cpt = $post->post_type;
					?>
					
					<br /><br />
					<h2 class="search-results-post-type">
						<?php echo $cpt_posts_count[$post->post_type] ?>
						<?php echo $cpt->labels->human_friendly ?>
					</h2>

					<?php endif ?>
					
					<div class="search-entry">
						<a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
	                    	<h4 class="entry-title">
								<?php 
									$title = get_the_title();
									//Wrap the exact query in the post title with a span so we can highlight the relevent query
									echo ucwords(str_ireplace($search_query, "<span class='query'>$search_query</span>", $title));
								?>
		                    </h4>
		                    <article class="entry-excerpt">
							<?php 
								$excerpt = get_the_excerpt();
								//We use the Search Excerpt plugin to return the relative snippet excerpt around the search term
								//We want to remove the <strong> they wrap around the excerpt
								$excerpt = str_replace('<strong class="search-excerpt">', '', $excerpt);
								$excerpt = str_replace('</strong>', '', $excerpt);

								/*
								Replace Method
								1) Look for the exact search query, wrap it with a <span>
								2) Exact query not found
									2a) Explode the query and search on a per word basis, wrapping each found with a <span>
								*/
								//Look for our exact search query within the excerpt
								if (stripos($excerpt, $search_query) !== false) : 
									//The exact query was found in the excerpt
									//Wrap the exact query in the post excerpt with a span so we can highlight the relevent query
									$excerpt = str_ireplace($search_query, "<span class='query'>$search_query</span>", $excerpt);	
								else :
									//Look for individual words from the query within the excerpt
									foreach ($search_query_words as $word) : 
										//Wrap the exact word in the post excerpt with a span so we can highlight the relevent query words
										$excerpt = str_ireplace($word, "<span class='query'>$word</span>", $excerpt);		
									endforeach;
								endif;

								//Output the final excerpt
								echo ucwords($excerpt);
							?>
							</article>
							<small class="entry-link"><?php the_permalink() ?></small>
						</a>
                    </div><!-- .search-entry -->
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