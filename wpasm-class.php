<?php
/**
 * Handles seaching post meta table.
 */
class WPASM_Search {

	/**
	 * Initialize wp-admin post meta search.
	 *
	 * @return void
	 */
	public static function init() {

		self::add();
	}

	/**
	 * Adds filters on search.
	 *
	 * @return void
	 */
	public static function add() {
		add_filter( 'posts_join', array( __CLASS__, 'posts_join' ), 11, 2 );
		add_filter( 'posts_where', array( __CLASS__, 'posts_where' ), 11, 2 );
		add_filter( 'posts_groupby', array( __CLASS__, 'posts_groupby' ), 11, 2 );
	}

	/**
	 * Removes filters from search.
	 *
	 * @return void
	 */
	public static function remove() {
		remove_filter( 'posts_join', array( __CLASS__, 'posts_join' ), 11 );
		remove_filter( 'posts_where', array( __CLASS__, 'posts_where' ), 11 );
		remove_filter( 'posts_groupby', array( __CLASS__, 'posts_groupby' ), 11 );
	}

	/**
	 * Constructs JOIN part of query.
	 *
	 * @param string   $join  The JOIN clause of the query.
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 *
	 * @return string
	 */
	public static function posts_join( $join, $query ) {
		global $wpdb;

		if ( ! self::_is_active() ) {
			return $join;
		}

		$join .= " LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) ";

		return $join;
	}

	/**
	 * Constructs WHERE part of query.
	 *
	 * @param string   $where The WHERE clause of the query.
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 *
	 * @return string
	 */
	public static function posts_where( $where, $query ) {
		global $wpdb, $wp;

		if ( ! self::_is_active() ) {
			return $where;
		}

		$like = '%' . $wpdb->esc_like( $wp->query_vars['s'] ) . '%';

		$where = str_replace( "($wpdb->posts.post_excerpt LIKE", "($wpdb->postmeta.meta_value LIKE '$like') OR ($wpdb->posts.post_excerpt LIKE", $where );

		return $where;
	}

	/**
	 * Constructs GROUP BY part of query.
	 *
	 * @param string   $groupby The GROUP BY clause of the query.
	 * @param WP_Query $query   The WP_Query instance (passed by reference).
	 *
	 * @return string
	 */
	public static function posts_groupby( $groupby, $query ) {
		global $wpdb;

		if ( ! self::_is_active() ) {
			return $groupby;
		}

		if ( empty( $groupby ) ) {
			$groupby = "$wpdb->posts.ID";
		}

		return $groupby;
	}

	/**
	 * Checks if we are on right page.
	 *
	 * @return bool
	 */
	protected static function _is_active() {
		global $pagenow, $wp_query;

		if ( ! is_admin() ) {
			return false;
		}

		if ( 'edit.php' != $pagenow ) {
			return false;
		}

		if ( ! isset( $_GET['s'] ) ) {
			return false;
		}

		if ( ! $wp_query->is_search ) {
			return false;
		}

		return true;
	}

}
