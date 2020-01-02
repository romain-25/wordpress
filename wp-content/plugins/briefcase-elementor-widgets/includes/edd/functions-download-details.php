<?php
/**
 * Download details functions
 */

 
/**
 * Is EDD Frontend Submissions active?
 *
 * @since 1.0.0
 * @return bool
 */
function bew_is_edd_fes_active() {
	return class_exists( 'EDD_Front_End_Submissions' );
}

/**
 * Download details options.
 *
 * @since  1.0.0
 * @param  array $args Download Details options passed in from the Themedd: Download Details widget
 *
 * @return array $args The final Download Details options
 */
function bew_edd_download_details_options( $args = array() ) {

	// Set some defaults for the download sidebar when the widget is not in use.
	$defaults = apply_filters( 'bew_edd_download_details_defaults', array(
		'show'           => true,
		'sale_count'     => false,
		'date_published' => false,
		'categories'     => true,
		'tags'           => true,
		'version'        => false,
		'title'          => ''
	) );

	
	// Set some defaults when Frontend Submissions is activated.
	if ( bew_is_edd_fes_active() ) {
		$defaults['title']          = sprintf( __( '%s Details', 'briefcase-elementor-widgets' ), edd_get_label_singular() );
		$defaults['date_published'] = true;
		$defaults['sale_count']     = true;
	}

	// Set some defaults when Software Licensing is activated.
	if ( bew_is_edd_sl_active() ) {
		$defaults['version'] = true;
	}

	// Merge any args passed in from the widget with the defaults.
	$args = wp_parse_args( $args, $defaults );

	/**
	 * Return the final $args
	 * Developers can use this filter hook to override options from widget settings or on a per-download basis.
	 */
	return apply_filters( 'bew_edd_download_details_options', $args );

}

/**
 * Determine if the download details can be shown.
 *
 * @since 1.0.0
 */
function bew_edd_show_download_details( $options = array() ) {

	// If no options are passed in, use the default options.
	if ( empty( $options ) ) {
		$options = bew_edd_download_details_options();
	}

	if ( isset( $options['show'] ) && true === $options['show'] && true === bew_edd_has_download_details( $options ) ) {
		return true;
	}

	return false;

}

/**
 * Determine if the current download has any download details.
 *
 * @since 1.0.0
 */
function bew_edd_has_download_details( $options = array() ) {

	$return = false;

	$download_id = get_the_ID();

	if (
		true === $options['categories'] && bew_edd_download_categories( $download_id ) || // Download categories are enabled and exist.
		true === $options['tags'] && bew_edd_download_tags( $download_id )             || // Download tags are enabled and exist.
		true === $options['sale_count']                                                    || // Sale count has been enabled from the "Themedd: Download Details" widget.
		true === $options['date_published']                                                || // Date published as been enabled from the "Themedd: Download Details" widget.
		true === $options['version'] && bew_edd_download_version( $download_id )          // Version number is allowed, and the download has a version number, the download details can be shown.
	) {
		$return = true;
	}

	return apply_filters( 'themedd_edd_has_download_details', $return, $options );

}

/**
 * Get the download categories of a download, given its ID
 *
 * @since 1.0.0
 */
function bew_edd_download_categories( $download_id = 0, $before = '', $sep = ', ', $after = '' ) {

	if ( ! $download_id ) {
		return false;
	}

	$categories = get_the_term_list( $download_id, 'download_category', $before, $sep, $after );

	if ( $categories ) {
		return $categories;
	}

	return false;

}

/**
 * Get the download tags of a download, given its ID.
 *
 * @since 1.0.0
 */
function bew_edd_download_tags( $download_id = 0, $before = '', $sep = ', ', $after = '' ) {

	if ( ! $download_id ) {
		return false;
	}

	$tags = get_the_term_list( $download_id, 'download_tag', $before, $sep, $after );

	if ( $tags ) {
		return $tags;
	}

	return false;

}

/**
 * Get the version number of a download, given its ID.
 *
 * @since 1.0.0
 */
function bew_edd_download_version( $download_id = 0 ) {

	if ( ! $download_id ) {
		return false;
	}

	if ( bew_is_edd_sl_active() && (new Themedd_EDD_Software_Licensing)->has_licensing_enabled() ) {
		// Get version number from EDD Software Licensing.
		return get_post_meta( $download_id, '_edd_sl_version', true );
	}

	return false;

}

/**
 * Date published
 *
 * @since 1.0.0
 */
function bew_edd_download_date_published() {

	$time_string = '<time class="entry-date published-bew" datetime="%1$s">%2$s</time>';
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	return $time_string;

}
