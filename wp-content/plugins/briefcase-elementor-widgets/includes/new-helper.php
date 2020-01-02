<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper functions
 *
 * @package   New Helpers for features 
 */
if ( ! class_exists( 'Bew_Helper' ) ) {

	class Bew_Helper {

		public function __construct() {
			add_action( 'wp_ajax_bew_ajax_search', array( $this, 'ajax_search' ) );
			add_action( 'wp_ajax_nopriv_bew_ajax_search', array( $this, 'ajax_search' ) );
			add_filter( 'posts_where', array( $this, 'title_like_posts_where' ), 10, 2 );

		}


		/**
		 * AJAX SEARCH
		 */
		public function ajax_search() {
			
				
			if ( ! empty( $_REQUEST['query'] ) ) {
				
				$query          = sanitize_text_field( $_REQUEST['query'] );
				$post_type      = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'product';
				$posts_per_page = isset( $_REQUEST['limit'] ) ? $_REQUEST['limit'] : '6';
				$product_cat    = isset( $_REQUEST['product_cat'] ) ? $_REQUEST['product_cat'] : '0';
				$cat            = isset( $_REQUEST['cat'] ) ? $_REQUEST['cat'] : '0';
				$search_by      = isset( $_REQUEST['search_by'] ) ? $_REQUEST['search_by'] : 'title';
				$thumb_size     = 'shop_thumbnail';
				
				$search_by_multiple      = explode(',', $search_by);
				
				$args = array(
					'post_type'           => $post_type,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => 1,
					'posts_per_page'      => $posts_per_page,
					'orderby'             => 'title',
					'order'               => 'ASC',
					'tax_query'           => array(
						'relation' => 'AND',
					),
				);

				if ( class_exists( 'WooCommerce' ) ) {

					global $woocommerce;

					$ordering_args = $woocommerce->query->get_catalog_ordering_args( 'title', 'asc' );

					$args['orderby'] = $ordering_args['orderby'];
					$args['order']   = $ordering_args['order'];
				}

				if ( $post_type == 'product' ) {

					if ( version_compare( WC()->version, '3.0.0', '<' ) ) {
						$args['meta_query'] = array(
							array(
								'key'     => '_visibility',
								'value'   => array( 'search', 'visible' ),
								'compare' => 'IN',
							),
						);
					} else {
						$product_visibility_term_ids = wc_get_product_visibility_term_ids();

						$args['tax_query'][] = array(
							'taxonomy' => 'product_visibility',
							'field'    => 'term_taxonomy_id',
							'terms'    => $product_visibility_term_ids['exclude-from-search'],
							'operator' => 'NOT IN',
						);
					}

					$args['suppress_filters'] = false;

					if ( $product_cat != '0' ) {

						$args['tax_query'][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => $_REQUEST['product_cat'],
						);
					}
					
					
					
					if ( $search_by == 'title' ) {

						$args['post_title_like'] = $query;

					} elseif ( $search_by == 'sku' ) {

						$search_ids = $this->search_by_sku( $query );

						if ( ! empty( $search_ids ) ) {
							$args['post__in'] = $search_ids;
						} else {
							$suggestions = array(
								'suggestions' => array(
									array(
										'id'    => - 1,
										'value' => esc_html__( 'Sorry, but nothing matched your search terms. Please try again with different keywords',
											'amely' ),
										'url'   => '',
									),
								),
							);

							wp_send_json( $suggestions );
						}
					} elseif ( $search_by == 'excerpt' ) {

						$search_ids = $this->search_by_excerpt( $query );

						if ( ! empty( $search_ids ) ) {
							$args['post__in'] = $search_ids;
						} else {
							$suggestions = array(
								'suggestions' => array(
									array(
										'id'    => - 1,
										'value' => esc_html__( 'Sorry, but nothing matched your search terms. Please try again with different keywords',
											'amely' ),
										'url'   => '',
									),
								),
							);

							wp_send_json( $suggestions );
						}
					}else { // title & SKU & excerpt
						
		
						
						if (in_array("sku", $search_by_multiple)) {												
						$search_ids = $this->search_by_sku( $query );
						}
						
						if (in_array("excerpt", $search_by_multiple)) {
							if ( empty( $search_ids ) ) {
							$search_ids = $this->search_by_excerpt( $query );
							}
						}
						
						// If SKU is not found, find by title
						if ( empty( $search_ids ) ) {
							if (in_array("title", $search_by_multiple)) {
							$args['post_title_like'] = $query;
							}
						} else {
							$args['post__in'] = $search_ids;
						}
					}
				}

				if ( $post_type == 'post' ) {

					if ( $cat != '0' ) {
						$args['category_name'] = $_REQUEST['cat'];
					}
				}
				
				
				$posts       = new WP_Query( $args );
				$suggestions = array();

				if ( $posts->have_posts() ) {

					$wptexturize = remove_filter( 'the_title', 'wptexturize' );

					while ( $posts->have_posts() ) {

						global $post;

						$posts->the_post();

						if ( $post_type == 'product' ) {

							$product = wc_get_product( $post );

							$suggestions[] = array(
								'id'        => $product->get_id(),
								'value'     => strip_tags( $product->get_title() ),
								'url'       => $product->get_permalink(),
								'thumbnail' => $product->get_image( 'shop_thumbnail' ),
								'price'     => $product->get_price_html(),
								'sku'       => $product->get_sku(),
								'excerpt'   => $post->post_excerpt,
							);
						}

						if ( $post_type == 'post' ) {

							$date = get_the_date( '', $post );

							if ( ! $date ) {
								$date = get_the_modified_date( '', $post );
							}

							$suggestions[] = array(
								'id'        => get_the_ID(),
								'value'     => get_the_title(),
								'url'       => get_permalink( $post ),
								'date'      => $date,
								'thumbnail' => get_the_post_thumbnail( $post->ID, $thumb_size ),
								'excerpt'   => $post->post_excerpt,
							);

						}
					}

					if ( $wptexturize ) {
						add_filter( 'the_title', 'wptexturize' );
					}

					// add view all link
					$query_str = '?s=' . $query . '&post_type=' . $post_type;

					if ( $product_cat != '0' ) {
						$query_str .= '&product_cat=' . $product_cat;
					}

					if ( $cat != '0' ) {
						$query_str .= '&category_name=' . $cat;
					}

					if ( intval( $posts->found_posts ) > $posts_per_page ) {

						$suggestions[] = array(
							'id'     => - 2,
							'value'  => esc_html__( 'View All', 'amely' ),
							'url'    => esc_url( home_url( '/' ) ) . $query_str,
							'target' => apply_filters( 'amely_search_view_all_target', '_blank' ),
						);
					}

					wp_reset_postdata();

				} else {
					$suggestions[] = array(
						'id'    => - 1,
						'value' => esc_html__( 'Sorry, but nothing matched your search terms. Please try again with different keywords',
							'amely' ),
						'url'   => '',
					);
				}

				$suggestions = array(
					'suggestions' => $suggestions,
				);

				wp_send_json( $suggestions );
			}

		}
		
		public function title_like_posts_where( $where, $wp_query ) {
			global $wpdb;
			if ( $post_title_like = $wp_query->get( 'post_title_like' ) ) {
				$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $post_title_like ) ) . '%\'';
			}

			return $where;
		}
		
		public function search_by_sku( $sku ) {

			global $wpdb;

			// search for variations with a matching sku and return the parent.
			$sku_to_parent_id = $wpdb->get_col( $wpdb->prepare( "SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent",
				sanitize_text_field( $sku ) ) );

			//Search for a regular product that matches the sku.
			$sku_to_id = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value LIKE '%%%s%%';",
				wc_clean( $sku ) ) );
				
			return array_merge( $sku_to_id, $sku_to_parent_id);

		}
		
		public function search_by_excerpt( $query ) {

			global $wpdb;
			
			$mypostids = $wpdb->get_col("select ID from $wpdb->posts where post_excerpt like '%$query%' ");
			
			return $mypostids;

		}
		
	}

	new Bew_Helper();
}
