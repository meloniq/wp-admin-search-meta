<?php

class WPASM_Search {

	/**
	 * Initialize wp-admin post meta search
	 */
	static function init() {

		self::add();
	}

	/**
	 * Adds filters on search
	 */
	static function add() {
		add_filter( 'posts_join', array( __CLASS__, 'posts_join' ) );
		add_filter( 'posts_where', array( __CLASS__, 'posts_where' ) );
	}

	/**
	 * Removes filters from search
	 */
	static function remove() {
		remove_filter( 'posts_join', array( __CLASS__, 'posts_join' ) );
		remove_filter( 'posts_where', array( __CLASS__, 'posts_where' ) );
	}

	/**
	 * Constructs JOIN query
	 *
	 * @param string $join
	 * @return string
	 */
	static function posts_join( $join ) {
		global $wpdb;

		if ( ! self::_is_active() )
			return $join;

		$join .= " LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) ";

		return $join;
	}

	/**
	 * Constructs WHERE query
	 *
	 * @param string $where
	 * @return string
	 */
	static function posts_where( $where ) {
		global $wpdb, $wp;

		if ( ! self::_is_active() )
			return $where;

		$where = preg_replace( "/(wp_posts.post_title LIKE '%{$wp->query_vars['s']}%')/i", "$0 OR $wpdb->postmeta.meta_value LIKE '%{$wp->query_vars['s']}%' ", $where );
		//$where .= " OR ( $wpdb->postmeta.meta_value LIKE '%{$wp->query_vars['s']}%' ) ";

		return $where;
	}

	/**
	 * Checks if we are on right page
	 *
	 * @return bool
	 */
	static function _is_active() {
		global $pagenow, $wp_query;

		if ( ! is_admin() )
			return false;

		if ( 'edit.php' != $pagenow )
			return false;

		if ( ! isset( $_GET['s'] ) )
			return false;

		if ( ! $wp_query->is_search )
			return false;

		return true;
	}

}
