<?php
	
	/**
	 * gets the current post type in the WordPress Admin
	 */
	function get_current_post_type() {
	  global $post, $typenow, $current_screen;
		
	  //we have a post so we can just get the post type from that
	  if ( $post && $post->post_type )
	    return $post->post_type;
	    
	  //check the global $typenow - set in admin.php
	  elseif( $typenow )
	    return $typenow;
	    
	  //check the global $current_screen object - set in sceen.php
	  elseif( $current_screen && $current_screen->post_type )
	    return $current_screen->post_type;
	  
	  //lastly check the post_type querystring
	  elseif( isset( $_REQUEST['post_type'] ) )
	    return sanitize_key( $_REQUEST['post_type'] );
		
	  //we do not know the post type!
	  return null;
	}

	function get_user_profile_url ($post_author) {
         $nice_name = get_the_author_meta( 'user_nicename', $post_author );
         return get_site_url(1, "/institutions/$nice_name");
    }

	//generate_html_taxonomy_filter
	function generate_html_taxonomy_filter ($cpt, $taxonomy) {
		global $typenow;
		// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
		$taxonomies = array($taxonomy);
		// must set this to the post type you want the filter(s) displayed on
		if($typenow == $cpt) :
			foreach ($taxonomies as $tax_slug) :
				$tax_obj = get_taxonomy($tax_slug);
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if(count($terms) > 0) :
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>Show All $tax_name</option>";
					foreach ($terms as $term) :
						if (isset($_GET['tax_slug'])) : 
							if ($_GET[$tax_slug] == $term->slug) :
								$is_selected = ' selected="selected"' ;
							endif;
						else :
							$is_selected = ""; 
						endif;
						echo '<option value='. $term->slug, $is_selected,'>' . $term->name .' (' . $term->count .')</option>'; 
					endforeach;
					echo "</select>";
				endif;
			endforeach;
		endif;
	}//generate_html_taxonomy_filter

	 function pagination() {
        global $wp_query;
        $big = 999999999; // need an unlikely integer
        echo "<div class='pagination'>";
        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $wp_query->max_num_pages
        ) );
        echo "</div>";
    }
