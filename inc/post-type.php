<?php
/**
 * Post Type
 *
 * @package FarmFactory
 */

/**
 * Register Post Type farmfactory
 */
function farmfactory_post_type() {

	$labels = array(
		'name'                  => esc_html__( 'Farm', 'farmfactory' ),
		'singular_name'         => esc_html__( 'Farm', 'farmfactory' ),
		'menu_name'             => esc_html__( 'Farm', 'farmfactory' ),
		'name_admin_bar'        => esc_html__( 'Farm', 'farmfactory' ),
		'all_items'             => esc_html__( 'All Farms', 'farmfactory' ),
		'add_new_item'          => esc_html__( 'Add New Farm', 'farmfactory' ),
		'add_new'               => esc_html__( 'Add New', 'farmfactory' ),
		'new_item'              => esc_html__( 'New Farm', 'farmfactory' ),
		'edit_item'             => esc_html__( 'Edit Farm', 'farmfactory' ),
		'update_item'           => esc_html__( 'Update Farm', 'farmfactory' ),
		'search_items'          => esc_html__( 'Search Farm', 'farmfactory' ),
		'not_found'             => esc_html__( 'Not found', 'farmfactory' ),
		'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'farmfactory' ),
		'featured_image'        => esc_html__( 'Reward Token Icon', 'farmfactory' ),
		'set_featured_image'    => esc_html__( 'Set token icon', 'farmfactory' ),
		'remove_featured_image' => esc_html__( 'Remove token icon', 'farmfactory' ),
		'use_featured_image'    => esc_html__( 'Use as token icon', 'farmfactory' ),
	);
	$args   = array(
		'labels'             => $labels,
		'supports'           => array( 'title', 'thumbnail' ),
		'hierarchical'       => false,
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_admin_bar'  => false,
		'show_in_nav_menus'  => false,
		'can_export'         => true,
		'publicly_queryable' => false,
		'capability_type'    => 'post',
		'menu_icon'          => 'dashicons-admin-site-alt3',
	);
	register_post_type( 'farmfactory', $args );

}
add_action( 'init', 'farmfactory_post_type' );

/**
 * Remove date from posts column
 *
 * @param array $columns Columns.
 */
function farmfactory_remove_date_column( $columns ) {
	unset( $columns['date'] );
	return $columns;
}
add_filter( 'manage_farmfactory_posts_columns', 'farmfactory_remove_date_column' );

/**
 * Remove quick edit
 *
 * @param array  $actions Actions.
 * @param object $post Post.
 */
function farmfactory_remove_quick_edit( $actions, $post ) {
	if ( 'farmfactory' === $post->post_type ) {
		unset( $actions['inline hide-if-no-js'] );
	}
	return $actions;
}
add_filter( 'post_row_actions', 'farmfactory_remove_quick_edit', 10, 2 );
