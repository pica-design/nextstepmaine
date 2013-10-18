<?php
	
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