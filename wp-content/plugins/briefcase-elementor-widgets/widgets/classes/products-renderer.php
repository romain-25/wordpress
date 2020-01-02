<?php
namespace Briefcase\Widgets\Classes;

use Elementor;
use Elementor\Plugin;
use Briefcase\Helper;
use WP_Meta_Query;
use WP_Tax_Query;
use WC_Query;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( class_exists( 'WooCommerce' ) ) {
class Products_Renderer extends \WC_Shortcode_Products {

	private $settings = [];
	private $is_added_product_filter = false;
	private $_check_ranges = false;

	public function __construct( $settings = [], $type = 'products', $template_id ) {
		$this->settings = $settings;
		$this->type = $type;
		$this->attributes = $this->parse_attributes( [
			'columns' => $settings['columns'],
			'rows' => '',
			'paginate' => $settings['paginate'],
			'cache' => false,
		] );
		$this->query_args = $this->parse_query_args();
				
		$this->template = $template_id;
		
	}
		
	public function bew_get_content() {
		return $this->product_loop();
	}

	protected function get_query_results() {
		$results = parent::get_query_results();
		if ( $this->is_added_product_filter ) {
			remove_action( 'pre_get_posts', [ wc()->query, 'product_query' ] );
		}

		return $results;
	}

	protected function parse_query_args() {
		$settings = &$this->settings;
		$query_args = [
			'post_type' => 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows' => false === wc_string_to_bool( $this->attributes['paginate'] ),
		];

		if ( 'shop' === $this->settings['grid_type'] ||  'current' === $this->settings['grid_type'] ) {
			if ( ! is_page( wc_get_page_id( 'shop' ) ) ) {
				$query_args = $GLOBALS['wp_query']->query_vars;
			}

			// Fix for parent::get_transient_name.
			if ( ! isset( $query_args['orderby'] ) ) {
				$query_args['orderby'] = '';
				$query_args['order'] = '';
			}
			
			$query_args['meta_query'] = WC()->query->get_meta_query();
			$query_args['tax_query'] = [];
			// @codingStandardsIgnoreEnd
						
			if ( isset( $_GET['min_price'] ) ) {
				$a = $_GET['min_price'];
			}
			
			if ( isset( $_GET['max_price'] ) ) {
				$b = $_GET['max_price'];
			}
			
			if(!is_null($a)  || !is_null($b)  ){

				if(is_null($b)){					
					$query_args['meta_query'] = array(
						array(
						'key' => '_price',
						'value' => $a,
						'compare' => '>=',
						'type' => 'NUMERIC'
						),
					);
				} elseif(is_null($a)){
				$query_args['meta_query'] = array(
						array(
						'key' => '_price',
						'value' => array(0 , $b),
						'compare' => 'BETWEEN',
						'type' => 'NUMERIC'
						),
					);
				} else {
					$query_args['meta_query'] = array(
						array(
						'key' => '_price',
						'value' => array($a , $b),
						'compare' => 'BETWEEN',
						'type' => 'NUMERIC'
						),
					);
				}
			}
			
			// Categories.
			$this->set_categories_query_args( $query_args );
			
			// Tags.
			$this->set_tags_query_args( $query_args );
			
			$query_args = apply_filters( 'woocommerce_shortcode_products_query', $query_args, $this->attributes, $this->type );

			add_action( 'pre_get_posts', [ wc()->query, 'product_query' ] );
			$this->is_added_product_filter = true;

		} else {
			$query_args = [
				'post_type' => 'product',
				'post_status' => 'publish',
				'ignore_sticky_posts' => true,
				'no_found_rows' => false === wc_string_to_bool( $this->attributes['paginate'] ),
				'orderby' => $settings['orderby'],
				'order' => strtoupper( $settings['order'] ),
			];

			$query_args['meta_query'] = WC()->query->get_meta_query();
			$query_args['tax_query'] = [];
			// @codingStandardsIgnoreEnd

			// Visibility.
			$this->set_visibility_query_args( $query_args );

			// SKUs.
			$this->set_featured_query_args( $query_args );

			// IDs.
			$this->set_ids_query_args( $query_args );

			// Set specific types query args.
			if ( method_exists( $this, "set_{$this->type}_query_args" ) ) {
				$this->{"set_{$this->type}_query_args"}( $query_args );
			}
			
			// Categories.
			$this->set_categories_query_args( $query_args );

			// Tags.
			$this->set_tags_query_args( $query_args );

			$query_args = apply_filters( 'woocommerce_shortcode_products_query', $query_args, $this->attributes, $this->type );
		} // End if().

		if ( 'yes' === $settings['paginate'] ) {
			$page = absint( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] );

			if ( 1 < $page ) {
				$query_args['paged'] = $page;
			}

			if ( 'yes' === $settings['sort'] ) {
				$ordering_args = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );
				$query_args['orderby'] = $ordering_args['orderby'];
				$query_args['order'] = $ordering_args['order'];
				if ( $ordering_args['meta_key'] ) {
					$query_args['meta_key'] = $ordering_args['meta_key'];
				}
			} else {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			}

			if ( 'yes' !== $settings['result_count'] ) {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			}
		}
		$query_args['posts_per_page'] = intval($settings['count']);

		// Always query only IDs.
		$query_args['fields'] = 'ids';

		return $query_args;
	}

	protected function set_ids_query_args( &$query_args ) {
		switch ( $this->settings['query_post_type'] ) {
			case 'by_id':
				$post__in = $this->settings['query_posts_ids'];
				break;
			case 'sale':
				$post__in = wc_get_product_ids_on_sale();
				break;
		}

		if ( ! empty( $post__in ) ) {
			$query_args['post__in'] = $post__in;
			remove_action( 'pre_get_posts', [ wc()->query, 'product_query' ] );
		}
	}

	protected function set_categories_query_args( &$query_args ) {
		
		$settings = &$this->settings;
		
		// Include category
		if ( ! empty( $settings['include_categories'] ) ) {
			$query_args['tax_query'][] = [
				'taxonomy' => 'product_cat',
				'terms' => $settings['include_categories'],
				'field' => 'id',
			];
		}
		
		// Exclude category
		if ( ! empty( $settings['exclude_categories'] ) ) {
			
			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'id',
				'terms'    => $settings['exclude_categories'],								
				'operator' => 'NOT IN',
			);
							
		}
		
	}

	protected function set_tags_query_args( &$query_args ) {
		
		$settings = &$this->settings;
		
		if ( ! empty( $this->settings['query_product_tag_ids'] ) ) {
			$query_args['tax_query'][] = [
				'taxonomy' => 'product_tag',
				'terms' => $this->settings['query_product_tag_ids'],
				'field' => 'term_id',
				'operator' => 'IN',
			];
		}
	}

	protected function set_featured_query_args( &$query_args ) {
		if ( 'featured' === $this->settings['query_post_type'] ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();

			$query_args['tax_query'][] = [
				'taxonomy' => 'product_visibility',
				'field' => 'term_taxonomy_id',
				'terms' => [ $product_visibility_term_ids['featured'] ],
			];
		}
	}
	
	protected function bew_shop_toolbar(){
		
		$settings = &$this->settings;	
		
	
			// Bew Tool Bar
			?>
			<div class="bew-toolbar">
				<div class="bew-toolbar-head">
					<div class="shop-display col-xl-7 col-lg-6">
						<?php
						if('yes' == $settings['result_count']){
						 woocommerce_result_count(); 
						} 
						?>
					</div>
					
					<div class="shop-filter col-xl-5 col-lg-6">
						<?php
						
						if('yes' == $settings['sort']){
						woocommerce_catalog_ordering();
						}
						
						if('yes' == $settings['col_switcher']){

							$columns = apply_filters( 'bew_shop_products_columns',
								array(
									'xs' => 1,
									'sm' => 2,
									'md' => 3,
									'lg' => 3,
									'xl' => get_option( 'woocommerce_catalog_columns', 5 ),
								) );

								?>
							<div class="col-switcher"
								 data-cols="<?php echo esc_attr( json_encode( $columns ) ); ?>"><?php esc_html_e( 'See:',
									'briefcase-elementor-widgets' ); ?>
								<a href="#" data-col="1">1</a>
								<a href="#" data-col="2">2</a>
								<a href="#" data-col="3">3</a>
								<a href="#" data-col="4">4</a>
								<a href="#" data-col="5">5</a>
								<a href="#" data-col="6">6</a>
							</div><!-- .col-switcher -->
						<?php } ?>

						<?php if('yes' == $settings['shop_filter']){?>
								<div class="bew-filter-buttons">
									<a href="#" class="open-filters"><?php esc_html_e( 'Filters', 'briefcase-elementor-widgets' ); ?></a>
								</div><!-- .bew-filter-buttons -->
						<?php } ?>
					</div>
				</div>
				
				<div class="bew-toolbar-content">
					<?php if('yes' == $settings['shop_filter']){?>
						<div class="filters-area">					
							<?php $this->bew_shop_filter(); ?>					
						</div><!--.filters-area-->

						<div class="active-filters">
							<?php the_widget( 'WC_Widget_Layered_Nav_Filters' ); ?>
						</div><!--.active-filters-->
					<?php } ?>
				</div>					
			
			</div><!--.shop-loop-head -->
			<?php	
	
	}
	
	
	protected function bew_shop_filter(){
		
		$settings = &$this->settings;	
		
	
			// Bew Shop Filter			
				$filters_columns = count($settings['filters']);
			?>
				
				<div class="filters-inner-area grid bew-filters-count-<?php echo $filters_columns ?> tablet-columns-4 mobile-columns-1">
				
					<?php 
										
					foreach ($settings['filters'] as $filter) :
					
					if('sort' == $filter['filter_type']){
						$title = $filter['filter_title'];
						$item  = 'elementor-repeater-item-' . $filter['_id'];	
						$this->bew_sorting($title, $item);
					}
					
					if('price' == $filter['filter_type']){
						$title = $filter['filter_title'];
						$item  = 'elementor-repeater-item-' . $filter['_id'];	
						$this->bew_price_filter($title, $item);
					}
					
					$attribute_taxonomies = wc_get_attribute_taxonomies();
					
					if ( $attribute_taxonomies ) {
						foreach ( $attribute_taxonomies as $tax ) {							
							if($tax->attribute_name == $filter['filter_type']){
								$title 	= $filter['filter_title'];
								$name 	= $tax->attribute_name;
								$layout = $filter['filter_layout'];
								$search_box = $filter['search_box'];
								$item  	= 'elementor-repeater-item-' . $filter['_id'];								
								$this->bew_layered_nav($title, $name, $layout, $item, $search_box);
							}					
						}
					}
					
					endforeach;
					
					if('categories' == $filter['filter_type']){
						$title = $filter['filter_title'];
						$item  = 'elementor-repeater-item-' . $filter['_id'];
						$search_box = $filter['search_box'];
						$this->bew_categories_filter($title, $item, $search_box);
					}
					
					
					?>
											
				</div><!-- .filters-inner-area -->
				
			<?php
	
	}
	
	protected function bew_sorting($title, $item){
		
		if ( ! woocommerce_products_will_display() ) {
				return;
			}

			$orderby                 = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby',
				get_option( 'woocommerce_default_catalog_orderby' ) );
			$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby',
					get_option( 'woocommerce_default_catalog_orderby' ) );
			$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby',
				array(
					'menu_order' => esc_html__( 'Default', 'briefcase-elementor-widgets' ),
					'popularity' => esc_html__( 'Popularity', 'briefcase-elementor-widgets' ),
					'rating'     => esc_html__( 'Average rating', 'briefcase-elementor-widgets' ),
					'date'       => esc_html__( 'Newness', 'briefcase-elementor-widgets' ),
					'price'      => esc_html__( 'Price: low to high', 'briefcase-elementor-widgets' ),
					'price-desc' => esc_html__( 'Price: high to low', 'briefcase-elementor-widgets' ),
				) );

			if ( wc_get_loop_prop( 'is_search' ) ) {
				$catalog_orderby_options = array_merge( array( 'relevance' => esc_html__( 'Relevance', 'briefcase-elementor-widgets' ) ),
					$catalog_orderby_options );
				unset( $catalog_orderby_options['menu_order'] );
				if ( 'menu_order' === $orderby ) {
					$orderby = 'relevance';
				}
			}

			if ( ! $show_default_orderby ) {
				unset( $catalog_orderby_options['menu_order'] );
			}

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
				unset( $catalog_orderby_options['rating'] );
			}

			?>
			<div class="bew-sorting-filter filter <?php echo $item; ?>">
			<h3 class="bew-sorting-title filter-title"><?php echo $title; ?></h3>
			<div class="bew-sorting-filter-list filter-list">
			
			<?php

			wc_get_template( 'loop/orderby.php',
				array(
					'catalog_orderby_options' => $catalog_orderby_options,
					'orderby'                 => $orderby,
					'show_default_orderby'    => $show_default_orderby,
					'list'                    => true,
				) );
			?>
			</div>
			</div>
			<?php
				
	}
	
	protected function bew_price_filter($title, $item){
		global $wp, $wp_the_query;

			if(Elementor\Plugin::instance()->editor->is_edit_mode()){				
			}else{
				if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
					return;
				}

				if ( ! $wp_the_query->post_count ) {
					return;
				}
			}

			$min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
			$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';

			// Find min and max price in current result set
			$prices = $this->get_filtered_price();
			$min    = floor( $prices->min_price );
			$max    = ceil( $prices->max_price );

			if ( $min === $max ) {
				return;
			}

			?>
			<div class="bew-price-filter filter <?php echo $item; ?>">
			<h3 class="bew-price-filter-title filter-title"><?php echo $title; ?></h3>
			
			<?php
			
			/**
			 * Adjust max if the store taxes are not displayed how they are stored.
			 * Min is left alone because the product may not be taxable.
			 * Kicks in when prices excluding tax are displayed including tax.
			 */
			if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
				$tax_classes = array_merge( array( '' ), WC_Tax::get_tax_classes() );
				$class_max   = $max;

				foreach ( $tax_classes as $tax_class ) {
					if ( $tax_rates = WC_Tax::get_rates( $tax_class ) ) {
						$class_max = $max + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max, $tax_rates ) );
					}
				}

				$max = $class_max;
			}

			$links = $this->generate_price_links( $min, $max, $min_price, $max_price );

			if ( ! empty( $links ) ) {
				?>
				<div class="bew-price-filter-list filter-list">
					<ul>
						<?php foreach ( $links as $link ) : ?>
							<li>
								<a href="<?php echo esc_url( $link['href'] ); ?>"
								   class="<?php echo esc_attr( $link['class'] ); ?>"><?php echo( $link['title'] ); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php
			}
			
		?>
		</div>
		<?php	
		
	}
	
	protected function bew_layered_nav($title, $name, $layout, $item, $search_box){
	
		if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
			return;
		}
		
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		$taxonomy           = isset( $name ) ? wc_attribute_taxonomy_name( $name ) : '';
		$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
		$display_type       = isset( $layout ) ? $layout : 'list';

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$get_terms_args = array( 'hide_empty' => '1' );

		$orderby = wc_attribute_orderby( $taxonomy );

		switch ( $orderby ) {
			case 'name' :
				$get_terms_args['orderby']    = 'name';
				$get_terms_args['menu_order'] = false;
				break;
			case 'id' :
				$get_terms_args['orderby']    = 'id';
				$get_terms_args['order']      = 'ASC';
				$get_terms_args['menu_order'] = false;
				break;
			case 'menu_order' :
				$get_terms_args['menu_order'] = 'ASC';
				break;
		}

		$terms = get_terms( $taxonomy, $get_terms_args );

		if ( 0 === sizeof( $terms ) ) {
			return;
		}

		switch ( $orderby ) {
			case 'name_num' :
				usort( $terms, '_wc_get_product_terms_name_num_usort_callback' );
				break;
			case 'parent' :
				usort( $terms, '_wc_get_product_terms_parent_usort_callback' );
				break;
		}

		ob_start();


		?>
		<div class="bew-layered-nav-filter filter <?php echo $item; ?>">
		<h3 class="bew-layered-nav-filter-title filter-title"><?php echo $title; ?></h3>
		<div class="bew-layered-nav-filter-list filter-list">
		<?php
		//search box
		if($search_box == 'yes'){
		?>
		<input type="text" id="search-box-<?php echo $name;?>" onkeyup="filtersSearch(this)" placeholder="Search <?php echo $name;?>.." data-name="<?php echo $name;?>" title="<?php echo $name;?>">
		<?php
		}
		if ( 'dropdown' === $display_type ) {
			$found = $this->layered_nav_dropdown( $terms, $taxonomy, $query_type );
		} else {
			$found = $this->layered_nav_list( $terms, $taxonomy, $query_type, $layout, $name );
		}

		?>
		</div>
		</div>
		<?php	

		// Force found when option is selected - do not force found on taxonomy attributes
		if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
			$found = true;
		}

		if ( ! $found ) {
			ob_end_clean();
		} else {
			echo ob_get_clean();
		}	
		
	}
	
	protected function bew_categories_filter($title, $item, $search_box){
		?>
		<div class="bew_categories_filter filter <?php echo $item; ?>">
		<h3 class="bew_categories_filter-title filter-title"><?php echo $title; ?></h3>
		<div class="bew_categories_filter-list filter-list">
		<?php
		
		$instance = array(
		'dropdown' => 0,
		'count'    => 1,
		'hide_empty' => 1,
		);
		//search box
		if($search_box == 'yes'){
			?>
			<input type="text" id="search-box-categories" onkeyup="filtersSearch(this)" placeholder="Search categories.." title="categories">
			<?php	
		}
				
		//filter widget
		the_widget( 'WC_Widget_Product_Categories', $instance);
		
		?>
		</div>
		</div>
		<?php
	}
	
	/**
	 * Get filtered min price for current products.
	 *
	 * @return int
	 */
	protected function get_filtered_price() {
		global $wpdb, $wp_the_query;

			$args       = $wp_the_query->query_vars;
			$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
			$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

			if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
				$tax_query[] = array(
					'taxonomy' => $args['taxonomy'],
					'terms'    => array( $args['term'] ),
					'field'    => 'slug',
				);
			}

			foreach ( $meta_query as $key => $query ) {
				if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
					unset( $meta_query[ $key ] );
				}
			}

			$meta_query = new WP_Meta_Query( $meta_query );
			$tax_query  = new WP_Tax_Query( $tax_query );

			$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

			$sql = "SELECT min( CAST( price_meta.meta_value AS UNSIGNED ) ) as min_price, max( CAST( price_meta.meta_value AS UNSIGNED ) ) as max_price FROM {$wpdb->posts} ";
			$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
			$sql .= " 	WHERE {$wpdb->posts}.post_type = 'product'
						AND {$wpdb->posts}.post_status = 'publish'
						AND price_meta.meta_key IN ('" . implode( "','",
					array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
						AND price_meta.meta_value > '' ";
			$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

			return $wpdb->get_row( $sql );
		}

	private function generate_price_links( $min, $max, $min_price, $max_price ) {
			$links = array();
			
			
			// Remember current filters/search
			$helper = new Helper();
			$link = $helper->get_shop_page_link( true );
			$link_no_price = remove_query_arg( 'min_price', $link );
			$link_no_price = remove_query_arg( 'max_price', $link_no_price );

			$need_more = false;

			$steps = apply_filters( 'bew_price_filter_steps', 5 );

			$step_value = $max / $steps;

			if ( $step_value < 10 ) {
				$step_value = 10;
			}

			$step_value = round( $step_value, - 1 );

			// Link to all prices
			$links[] = array(
				'href'  => $link_no_price,
				'title' => esc_html__( 'All', 'briefcase-elementor-widgets' ),
				'class' => '',
			);

			for ( $i = 0; $i < (int) $steps; $i ++ ) {

				$step_class = $href = '';

				$step_min = $step_value * $i;

				$step_max = $step_value * ( $i + 1 );

				if ( $step_max > $max ) {
					$need_more = true;
					$i ++;
					break;
				}

				$href = add_query_arg( 'min_price', $step_min, $link );
				$href = add_query_arg( 'max_price', $step_max, $href );

				$step_title = wc_price( $step_min ) . ' - ' . wc_price( $step_max );

				if ( ! empty( $min_price ) && ! empty( $max_price ) && ( $min_price >= $step_min && $max_price <= $step_max ) || ( $i == 0 && ! empty( $max_price ) && $max_price <= $step_max ) ) {
					$step_class = 'current-state';
				}

				if ( $this->check_range( $step_min, $step_max ) ) {
					$links[] = array(
						'href'  => $href,
						'title' => $step_title,
						'class' => $step_class,
					);
				}
			}

			if ( $max > $step_max ) {
				$need_more = true;
				$step_min  = $step_value * $i;
			}

			if ( $need_more ) {

				$step_class = $href = '';

				$href = add_query_arg( 'min_price', $step_min, $link );
				$href = add_query_arg( 'max_price', $max, $href );

				$step_title = wc_price( $step_min ) . ' +';

				if ( $min_price >= $step_min && $max_price <= $max ) {
					$step_class = 'current-state';
				}

				if ( $this->check_range( $step_min, $max ) ) {
					$links[] = array(
						'href'  => $href,
						'title' => $step_title,
						'class' => $step_class,
					);
				}
			}

			return $links;
	}

	private function check_range( $min, $max ) {

			if ( ! $this->_check_ranges ) {
				return true;
			}

			if ( 0 === sizeof( WC()->query->layered_nav_product_ids ) ) {
				$min = floor( $wpdb->get_var( "
					SELECT min(meta_value + 0)
					FROM {$wpdb->posts} as posts
					LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
					WHERE meta_key IN ('" . implode( "','",
						array_map( 'esc_sql',
							apply_filters( 'woocommerce_price_filter_meta_keys',
								array( '_price', '_min_variation_price' ) ) ) ) . "')
					AND meta_value != ''
				" ) );
				$max = ceil( $wpdb->get_var( "
					SELECT max(meta_value + 0)
					FROM {$wpdb->posts} as posts
					LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
					WHERE meta_key IN ('" . implode( "','",
						array_map( 'esc_sql',
							apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
				" ) );
			} else {
				$min = floor( $wpdb->get_var( "
					SELECT min(meta_value + 0)
					FROM {$wpdb->posts} as posts
					LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
					WHERE meta_key IN ('" . implode( "','",
						array_map( 'esc_sql',
							apply_filters( 'woocommerce_price_filter_meta_keys',
								array( '_price', '_min_variation_price' ) ) ) ) . "')
					AND meta_value != ''
					AND (
						posts.ID IN (" . implode( ',', array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
						OR (
							posts.post_parent IN (" . implode( ',',
						array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
							AND posts.post_parent != 0
						)
					)
				" ) );
				$max = ceil( $wpdb->get_var( "
					SELECT max(meta_value + 0)
					FROM {$wpdb->posts} as posts
					LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
					WHERE meta_key IN ('" . implode( "','",
						array_map( 'esc_sql',
							apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
					AND (
						posts.ID IN (" . implode( ',', array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
						OR (
							posts.post_parent IN (" . implode( ',',
						array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
							AND posts.post_parent != 0
						)
					)
				" ) );
			}

			return true;
	}

	function form( $instance ) {
			parent::form( $instance );
	}
	
	/**
	 * Return the currently viewed taxonomy name.
	 *
	 * @return string
	 */
	protected function get_current_taxonomy() {
		return is_tax() ? get_queried_object()->taxonomy : '';
	}

	/**
	 * Return the currently viewed term ID.
	 *
	 * @return int
	 */
	protected function get_current_term_id() {
		return absint( is_tax() ? get_queried_object()->term_id : 0 );
	}

	/**
	 * Return the currently viewed term slug.
	 *
	 * @return int
	 */
	protected function get_current_term_slug() {
		return absint( is_tax() ? get_queried_object()->slug : 0 );
	}

	/**
	 * Show dropdown layered nav.
	 *
	 * @param  array $terms
	 * @param  string $taxonomy
	 * @param  string $query_type
	 *
	 * @return bool Will nav display?
	 */
	protected function layered_nav_dropdown( $terms, $taxonomy, $query_type ) {
		$found = false;

		if ( $taxonomy !== $this->get_current_taxonomy() ) {
				$term_counts          = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ),
					$taxonomy,
					$query_type );
				$_chosen_attributes   = WC_Query::get_layered_nav_chosen_attributes();
				$taxonomy_filter_name = str_replace( 'pa_', '', $taxonomy );
				$taxonomy_label       = wc_attribute_label( $taxonomy );
				$any_label            = apply_filters( 'woocommerce_layered_nav_any_label',
					sprintf( __( 'Any %s', 'amely' ), $taxonomy_label ),
					$taxonomy_label,
					$taxonomy );

				echo '<a href="#" class="filter-pseudo-link link-taxonomy-' . $taxonomy_filter_name . '">' . esc_html__( 'Apply filter',
						'amely' ) . '</a>';

				echo '<select class="dropdown_layered_nav_' . $taxonomy_filter_name . '" data-filter-url="' . preg_replace( '%\/page\/[0-9]+%',
						'',
						str_replace( array(
							'&amp;',
							'%2C',
						),
							array(
								'&',
								',',
							),
							esc_js( add_query_arg( 'filtering',
								'1',
								remove_query_arg( array(
									'page',
									'_pjax',
									'filter_' . $taxonomy_filter_name,
								) ) ) ) ) ) . "&filter_" . esc_js( $taxonomy_filter_name ) . "=AMELY_FILTER_VALUE" . '">';

				echo '<option value="">' . esc_html( $any_label ) . '</option>';

				foreach ( $terms as $term ) {

					// If on a term page, skip that term in widget list
					if ( $term->term_id === $this->get_current_term_id() ) {
						continue;
					}

					// Get count based on current view
					$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
					$option_is_set  = in_array( $term->slug, $current_values );
					$count          = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

					// Only show options with count > 0
					if ( 0 < $count ) {
						$found = true;
					} elseif ( 0 === $count && ! $option_is_set ) {
						continue;
					}

					echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $option_is_set,
							true,
							false ) . '>' . esc_html( $term->name ) . '</option>';

				}

				echo '</select>';
		}

			return $found;
	}

	/**
	 * Get current page URL for layered nav items.
	 *
	 * @param string $taxonomy
	 *
	 * @return string
	 */
	protected function get_page_base_url( $taxonomy ) {

			if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
				$link = home_url();
			} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
				$link = get_post_type_archive_link( 'product' );
			} elseif ( is_product_category() ) {
				$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
			} elseif ( is_product_tag() ) {
				$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
			} else {
				$queried_object = get_queried_object();
				$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			}

			// Min/Max
			if ( isset( $_GET['min_price'] ) ) {
				$link = add_query_arg( 'min_price', wc_clean( $_GET['min_price'] ), $link );
			}

			if ( isset( $_GET['max_price'] ) ) {
				$link = add_query_arg( 'max_price', wc_clean( $_GET['max_price'] ), $link );
			}

			// Orderby
			if ( isset( $_GET['orderby'] ) ) {
				$link = add_query_arg( 'orderby', wc_clean( $_GET['orderby'] ), $link );
			}

			/**
			 * Search Arg.
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
			}

			// Post Type Arg
			if ( isset( $_GET['post_type'] ) ) {
				$link = add_query_arg( 'post_type', wc_clean( $_GET['post_type'] ), $link );
			}

			// Min Rating Arg
			if ( isset( $_GET['rating_filter'] ) ) {
				$link = add_query_arg( 'rating_filter', wc_clean( $_GET['rating_filter'] ), $link );
			}

			// All current filters
			if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
				foreach ( $_chosen_attributes as $name => $data ) {
					if ( $name === $taxonomy ) {
						continue;
					}
					$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );
					if ( ! empty( $data['terms'] ) ) {
						$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
					}
					if ( 'or' == $data['query_type'] ) {
						$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
					}
				}
			}

		return $link;
	}

	/**
	 * Count products within certain terms, taking the main WP query into consideration.
	 *
	 * @param  array $term_ids
	 * @param  string $taxonomy
	 * @param  string $query_type
	 *
	 * @return array
	 */
	protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
			global $wpdb;

			$tax_query  = WC_Query::get_main_tax_query();
			$meta_query = WC_Query::get_main_meta_query();

			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}

			$meta_query     = new WP_Meta_Query( $meta_query );
			$tax_query      = new WP_Tax_Query( $tax_query );
			$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

			// Generate query
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

			$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";

			if ( $search = WC_Query::get_main_search_query_sql() ) {
				$query['where'] .= ' AND ' . $search;
			}

			$query['group_by'] = "GROUP BY terms.term_id";
			$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query             = implode( ' ', $query );
			$results           = $wpdb->get_results( $query );

			return wp_list_pluck( $results, 'term_count', 'term_count_id' );
	}

	/**
	 * Show list based layered nav.
	 *
	 * @param  array $terms
	 * @param  string $taxonomy
	 * @param  string $query_type
	 *
	 * @return bool   Will nav display?
	 */
	protected function layered_nav_list( $terms, $taxonomy, $query_type, $layout, $name ) {

			$labels       = isset( $instance['labels'] ) ? $instance['labels'] : 'on';
			$items_count  = isset( $instance['items_count'] ) ? $instance['items_count'] : 'on';
			$display_type = isset( $layout ) ? $layout : 'list';

			$class = 'show-labels-' . $labels;
			$class .= ' show-display-' . $display_type;
			$class .= ' show-items-count-' . $items_count;
			$class .= ' list-search-box-' . $name;
			
			// List display
			echo '<ul class="' . esc_attr( $class ) . '">';

			$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ),
				$taxonomy,
				$query_type );
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;

			foreach ( $terms as $term ) {

				$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set  = in_array( $term->slug, $current_values );

				$count = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// Skip the term for the current archive
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}

				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 0 === $count && ! $option_is_set ) {
					continue;
				}

				$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',',
					wc_clean( $_GET[ $filter_name ] ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );

				if ( ! in_array( $term->slug, $current_filter ) ) {
					$current_filter[] = $term->slug;
				}

				$link = $this->get_page_base_url( $taxonomy );

				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[ $key ] );
					}

					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}
				}

				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

					// Add Query type Arg to URL
					if ( 'or' === $query_type && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ),
							'or',
							$link );
					}
				}

				$item_class = $option_is_set ? ' chosen' : '';

				// Add Swatches block
				$isw_settings = get_option( 'isw_settings' );
				$swatch_span  = $swatch_style = '';

				if ( class_exists( 'SitePress' ) ) {

					global $sitepress;

					if ( method_exists( $sitepress, 'get_default_language' ) ) {

						$default_language = $sitepress->get_default_language();
						$current_language = $sitepress->get_current_language();

						if ( $default_language != $current_language ) {
							$isw_settings = get_option( 'isw_settings_' . $current_language );
						}
					}
				}

				if ( isset( $isw_settings['isw_attr'] ) && is_array( $isw_settings['isw_attr'] ) && in_array( $taxonomy,
						$isw_settings['isw_attr'] )
				) {

					$isw_attr = $isw_settings['isw_attr'];

					if ( isset( $isw_settings['isw_style'] ) && is_array( $isw_settings['isw_style'] ) ) {
						$isw_style = $isw_settings['isw_style'];

						for ( $i = 0; $i < count( $isw_style ); $i ++ ) {

							if ( $taxonomy == $isw_attr[ $i ] ) {

								$tooltip = $isw_settings['isw_tooltip'][ $i ][ $term->slug ];

								switch ( $isw_style[ $i ] ) {

									case 'isw_color':
										$item_class .= ' swatch-color';

										if ( isset( $isw_settings['isw_custom'] ) && is_array( $isw_settings['isw_custom'] ) ) {

											$isw_custom = isset( $isw_settings['isw_custom'][ $i ] ) ? $isw_settings['isw_custom'][ $i ] : '';

											if ( is_array( $isw_custom ) ) {

												foreach ( $isw_custom as $key => $value ) {

													if ( $term->slug == $key ) {
														$swatch_style = 'background-color:' . $value . ';';
													}
												}
											}

											if ( ! empty( $swatch_style ) ) {
												$swatch_span = '<span class="filter-swatch hint--top hint--bounce" aria-label="' . esc_attr( $tooltip ? $tooltip : $term->name ) . '" style="' . $swatch_style . '"></span>';
											}
										}

										break;

									case 'isw_image':
										$item_class .= ' swatch-image';

										if ( isset( $isw_settings['isw_custom'] ) && is_array( $isw_settings['isw_custom'] ) ) {

											$isw_custom = isset( $isw_settings['isw_custom'][ $i ] ) ? $isw_settings['isw_custom'][ $i ] : '';

											if ( is_array( $isw_custom ) ) {

												foreach ( $isw_custom as $key => $value ) {

													if ( $term->slug == $key ) {

														$swatch_span = '<span class="filter-swatch hint--top hint--bounce" aria-label="' . esc_attr( $tooltip ? $tooltip : $term->name ) . '"><img src="' . esc_url( $value ) . '" alt="' . esc_attr( $term->slug ) . '"/></span>';
													}
												}
											}
										}

										break;

									case 'isw_html':
										$item_class .= ' swatch-html';

										if ( isset( $isw_settings['isw_custom'] ) && is_array( $isw_settings['isw_custom'] ) ) {

											$isw_custom = isset( $isw_settings['isw_custom'][ $i ] ) ? $isw_settings['isw_custom'][ $i ] : '';

											if ( is_array( $isw_custom ) ) {

												foreach ( $isw_custom as $key => $value ) {

													if ( $term->slug == $key ) {

														$swatch_span = '<span class="filter-swatch hint--top hint--bounce" aria-label="' . esc_attr( $tooltip ? $tooltip : $term->name ) . '">' . $value . '</span>';
													}
												}
											}
										}

										break;

									case 'isw_text':
									default:
										$item_class .= ' swatch-text';

										if ( isset( $isw_settings['isw_custom'] ) && is_array( $isw_settings['isw_custom'] ) ) {

											$isw_custom = isset( $isw_settings['isw_custom'][ $i ] ) ? $isw_settings['isw_custom'][ $i ] : '';

											if ( is_array( $isw_custom ) ) {

												foreach ( $isw_custom as $key => $value ) {

													if ( $term->slug == $key ) {

														$swatch_span = '<span class="filter-swatch hint--top hint--bounce" aria-label="' . esc_attr( $tooltip ? $tooltip : $term->name ) . '">' . $value . '</span>';
													}
												}
											}
										}

										break;
								}
							}
						}
					}
				} else {
					$item_class = ' no-swatch';
				}

				if ( $count > 0 || $option_is_set ) {
					$link      = esc_url( apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy ) );
					$term_html = '<a href="' . $link . '">' . $swatch_span . '<span class="term-name">' . esc_html( $term->name ) . '</span>' . '</a>';
				} else {
					$link      = false;
					$term_html = '<span>' . $swatch_span . '<span class="term-name">' . esc_html( $term->name ) . '</span>';
				}

				if ( $items_count == 'on' ) {
					$term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count',
							'<span class="count">' . absint( $count ) . '</span>',
							$count,
							$term );
				};

				echo '<li class="wc-layered-nav-term' . esc_attr( $item_class ) . '">';
				echo apply_filters( 'woocommerce_layered_nav_term_html',
					$term_html,
					$term,
					$link,
					$count );
				echo '</li>';

			}

			echo '</ul>';

			return $found;
	}
	

	
	/**
	 * Loop over found products.
	 *
	 * @since  1.3.2
	 * @return string
	 */
	protected function product_loop() {
		$columns  = absint( $this->attributes['columns'] );
		$classes  = $this->get_wrapper_classes( $columns );
		$products = $this->get_query_results();
		
		$template_id  = $this->template;				
		$settings = &$this->settings;
		
			// Vars		
			$toolbar_show 	= $settings['toolbar_show'];
		
		ob_start();		
		if ( $products && $products->ids ) {
			// Prime caches to reduce future queries.
			if ( is_callable( '_prime_post_caches' ) ) {
				_prime_post_caches( $products->ids );
			}

			// Setup the loop.
			wc_setup_loop(
				array(
					'columns'      => $columns,
					'name'         => $this->type,
					'is_shortcode' => true,
					'is_search'    => false,
					'is_paginated' => wc_string_to_bool( $this->attributes['paginate'] ),
					'total'        => $products->total,
					'total_pages'  => $products->total_pages,
					'per_page'     => $products->per_page,
					'current_page' => $products->current_page,
				)
			);

			$original_post = $GLOBALS['post'];

			do_action( "woocommerce_shortcode_before_{$this->type}_loop", $this->attributes );			
			
			// Shop Toolbar option
				if ( 'yes' == $toolbar_show) {
					$this->bew_shop_toolbar();						 
				} else {
				// Fire standard shop loop hooks when paginating results so we can show result counts and so on.
				if ( wc_string_to_bool( $this->attributes['paginate'] ) ) {
					do_action( 'woocommerce_before_shop_loop' );
				}	
				}
				
			// Check the shop template
			$helper = new Helper();			
			$check_template = $helper->get_woo_archive_template();
			
			// Bew Products Div										
			?>
			<div class="bew-products clr"">								
			<?php								
				
				
				//Star the Product Loop
				woocommerce_product_loop_start();

				if ( wc_get_loop_prop( 'total' ) ) {
					foreach ( $products->ids as $product_id ) {
						$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
						setup_postdata( $GLOBALS['post'] );

						// Set custom product visibility when quering hidden products.
						add_action( 'woocommerce_product_is_visible', array( $this, 'set_product_as_visible' ) );
												
						if($template_id == "0"){
							if(empty($check_template)  ){
							// Render product template.
							wc_get_template_part( 'content', 'product' );
							}else{							
							$template_id = $helper->get_woo_archive_template();
							
							// Render product template and get the current template.						
							$withcss =false;
							if(Elementor\Plugin::instance()->editor->is_edit_mode()){
							$withcss = true;
							}
									
							?>
							<li <?php wc_product_class(); ?>>							
								<div>
								<?php				
									echo Elementor\Plugin::instance()->frontend->get_builder_content( $template_id,$withcss );
								?>
								</div>
							</li>
							<?php
							}			
						}else {
							// Render product template and get the current template	.
							
							$withcss =false;
							if(Elementor\Plugin::instance()->editor->is_edit_mode()){
							$withcss = true;
							}
									
							?>
							<li <?php wc_product_class(); ?>>							
								<div>
								<?php				
									echo Elementor\Plugin::instance()->frontend->get_builder_content( $template_id,$withcss );
								?>
								</div>
							</li>
							<?php	
						}
						// Restore product visibility.
						remove_action( 'woocommerce_product_is_visible', array( $this, 'set_product_as_visible' ) );
					}
				}

				$GLOBALS['post'] = $original_post; // WPCS: override ok.
				woocommerce_product_loop_end();
				?>
			</div><!-- .bew-products-->
			<?php					
			// Fire standard shop loop hooks when paginating results so we can show result counts and so on.
			if ( wc_string_to_bool( $this->attributes['paginate'] ) ) {
				do_action( 'woocommerce_after_shop_loop' );
			}

			do_action( "woocommerce_shortcode_after_{$this->type}_loop", $this->attributes );

			wp_reset_postdata();
			wc_reset_loop();
		} else { 
			 ?>
				
			 <div class="empty-message"><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'briefcase-elementor-widgets' );?> </div> <div class="empty-message-back"> <INPUT TYPE="button" VALUE="Go Back" onClick="history.go(-1);"></div> 		
			<?php
		}

		return '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . ob_get_clean() . '</div>';
	}
	
}

}
