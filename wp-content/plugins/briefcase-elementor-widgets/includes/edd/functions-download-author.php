<?php

/**
 * Download author options
 *
 * @since 1.0.0
 */
function bew_edd_download_author_options( $args = array() ) {

	// Set some defaults for the download sidebar when the widget is not in use.
	$defaults = apply_filters( 'bew_edd_download_author_defaults', array(
		'avatar'      => true,
		'avatar_size' => 80,
		'store_name'  => true,
		'name'        => true,
		'signup_date' => true,
		'website'     => true,
		'title'       => ''
	) );

	// Merge any args passed in from the widget with the defaults.
	$args = wp_parse_args( $args, $defaults );

	// If Frontend Submissions is active, show the author details by default.
	if(function_exists('bew_is_edd_fes_active')) {
	if ( bew_is_edd_fes_active() ) {
		$args['show'] = true;
	}
	}

	/**
	 * Return the final $args
	 * Developers can use this filter hook to override options from widget settings or on a per-download basis.
	 */
	return apply_filters( 'bew_edd_download_author_options', $args );

}

/**
 * Determine if the author details can be shown
 */
function bew_edd_show_download_author( $options = array() ) {

	// If no options are passed in, use the default options.
	if ( empty( $options ) ) {
		$options = bew_edd_download_author_options();
	}

	if ( isset( $options['show'] ) && true === $options['show'] && true === bew_edd_has_download_author( $options ) ) {
		return true;
	}

	return false;

}

/**
 * Determine if the current download has any author details.
 *
 * @since 1.0.0
 */
function bew_edd_has_download_author( $options = array() ) {

	// Remove "show" from the $options array since we don't want to check against it.
	unset( $options['show'] );

	// If (bool) true exists anywhere in the $options array then there are author details that need to be shown.
	if ( in_array( (bool) true, $options, true ) ) { // Uses strict mode.
		return true;
	}

	return false;

}
