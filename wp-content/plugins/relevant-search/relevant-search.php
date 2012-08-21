<?php
/**
 * Plugin Name: Relevant Search
 * Plugin URI: http://snippets-tricks.org/proyecto/relevant-search/
 * Description: Relevant Search will provide contextual search and listing the results based on relevance. It automatically replaces the default WordPress search.
 * Version: 1.0.1
 * Author: Luis Alberto Ochoa Esparza
 * Author URI: http://luisalberto.org
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package Relevant Search
 * @version 1.0.1
 * @link http://snippets-tricks.org/proyecto/relevant-search/
 * @author Luis Alberto Ochoa Esparza <soy@luisalberto.org>
 * @copyright Copyright (C) 2011, Luis Alberto Ochoa Esparza
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Exit if accessed directly */
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Main Relevant Search Class
 *
 * @since 1.0.0
 */
class Relevant_Search
{
	/**
	 * The main Relevant Search loader.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		/* Filter the query in 'posts_request' */
		add_filter( 'posts_request', array( &$this, 'post_request' ) );

		/* Rebuild the Full-Text Index */
		add_action( 'save_post',   array( &$this, 'alter_index' ) );
		add_action( 'delete_post', array( &$this, 'alter_index' ) );

		/* Add actions to plugin activation and deactivation hooks */
		register_activation_hook  ( __FILE__, array( &$this, 'create_index' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'drop_index'   ) );
	}

	/**
	 * Creates a query that retrieves the posts depending on search terms
	 * and sorts by relevance.
	 *
	 * @since 1.0.0
	 */
	public function post_request( $request ) {

		/* If not the search page returns $request */
		if ( !is_search() ) return $request;

		global $wpdb, $wp_query;

		/* Gets the search terms */
		$s = stripslashes( $wp_query->query_vars['s'] );

		if ( !empty( $wp_query->query_vars['sentence'] ) )
			$terminos = array( $s );

		else {
			preg_match_all( '/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $s, $matches );
			$terminos = array_map( 'rs_search_terms_tidy', $matches[0] );
		}

		foreach( $terminos as $termino )
			$regexp[] = "[[:<:]]{$termino}[[:>:]]";

		$regexp = join( '|', $regexp );

		/* Creates the SQL query */
		$query[] = "SELECT *, MATCH (post_title, post_content) AGAINST ('{$s}') AS search_score FROM {$wpdb->posts} WHERE post_date_gmt <= NOW() AND post_status = 'publish'";

		/* Add the post that have no password if the user is not logged */
		if ( !is_user_logged_in() )
			$query[] = "AND post_password = ''";

		/* Set the post types not excluded from search */
		$search_post_types = get_post_types( array( 'exclude_from_search' => false ) );
		$query[] = "AND post_type IN ('" . join( "', '", $search_post_types ) . "')";

		/* Find matches in 'post_title' and 'post_content' */
		$query[] = "AND (post_title REGEXP '{$regexp}' OR post_content REGEXP '{$regexp}')";

		/* Limit and order the results */
		//$limit = absint( get_option( 'posts_per_page' ) );
		$limit = 500;
		$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		$offset = ( $paged > 1 ) ? ( ( $paged - 1 ) * $limit ) : 0;

		$query[] = "GROUP BY ID ORDER BY post_type DESC, search_score DESC, post_date DESC LIMIT {$offset}, {$limit}";

		/* Join the query */
		$request = join( ' ', $query );

		/* Remove filter to allow for other queries */
		remove_filter( 'posts_request', array( &$this, 'post_request' ) );

		return $request;
	}

	/**
	 * Rebuild the Full-Text Index.
	 *
	 * @since 1.0.1
	 */
	public function alter_index() {
		$this->drop_index();
		$this->create_index();
	}

	/**
	 * Build the Full-Text Index.
	 *
	 * @since 1.0.1
	 */
	public function create_index() {
		global $wpdb;

		if ( false == $wpdb->query( "SHOW INDEX FROM {$wpdb->posts} WHERE key_name = 'search';" ) )
			$wpdb->query( "CREATE FULLTEXT INDEX search ON {$wpdb->posts} (post_title, post_content);" );
	}

	/**
	 * Drops the Full-Text Index
	 *
	 * @since 1.0.1
	 */
	public function drop_index() {
		global $wpdb;

		if ( $wpdb->query( "SHOW INDEX FROM {$wpdb->posts} WHERE key_name = 'search';" ) )
			$wpdb->query( "ALTER TABLE {$wpdb->posts} DROP INDEX search;" );
	}

	/**
	 * @since 1.0.0
	 * @deprecated 1.0.1
	 */
	function rs_post_request( $request ) {}

	/**
	 * @since 1.0.0
	 * @deprecated 1.0.1
	 */
	function rs_activation() {}

	/**
	 * @since 1.0.0
	 * @deprecated 1.0.1
	 */
	function rs_desactivation() {}
}

/* Creates a new instance. */
$GLOBAL['relevat_search'] = new Relevant_Search();

/**
 * Used internally to tidy up the search terms.
 *
 * TODO: This function has been copied in WordPress Core
 *
 * @since 1.0.0
 * @param string $term
 * @return string
 */
function rs_search_terms_tidy( $term ) {
	return trim( $term, "\"'\n\r " );
}
