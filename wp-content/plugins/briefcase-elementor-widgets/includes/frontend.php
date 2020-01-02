<?php
namespace Briefcase;

use Elementor;
use Elementor\Plugin;
use Elementor\Post_CSS_File;
use WP_Query;

class Frontend{

    private static $_instance = null;
   

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {	 
	
    }

	
	/**
	 * Render elementor template.
	 *
	 * @since 1.1.0
	 */
	public function render_insert_elementor($template_id,$with_css = false){
        if(!isset($template_id) || empty($template_id)){
            return '';
        }

        $post_id = $template_id;

        // check if page is elementor page

        $edit_mode = get_post_meta($post_id,'_elementor_edit_mode','');
		
			$with_css = false;
			if(Elementor\Plugin::instance()->editor->is_edit_mode()){
			$with_css = true;
			}

        ob_start();
        if(Plugin::$instance->db->is_built_with_elementor( $post_id )) {
            ?>
            <div class="bew_data elementor elementor-<?php echo $post_id; ?>" data-bewtid="<?php echo $post_id; ?>">
                <?php echo Elementor\Plugin::instance()->frontend->get_builder_content( $post_id,$with_css ); ?>
            </div>
            <?php
        }else{
            echo __('Not a valid elementor page','wts_bew');
        }
        $response = ob_get_contents();
        ob_end_clean();
        return $response;
    }
	
	/**
	 * Apply product template.
	 *
	 * @since 1.1.0
	 */
	public function apply_bew_wc_product_template(){
        global $product;
        $helper = new Helper();
        $bew_product_template = $helper->get_bew_active_template($product->get_id(),'woo-product');

        if($bew_product_template != '' && is_numeric($bew_product_template)){
            $template_content = $this->render_insert_elementor($bew_product_template);
            $wc_sd = new \WC_Structured_Data();
            $wc_sd->generate_product_data();
            echo $template_content;
        }
    }
	/**
	 * Apply shop template.
	 *
	 * @since 1.1.0
	 */
    public function apply_bew_wc_shop_template(){
        $helper = new Helper();
        $bew_shop_template = $helper->get_woo_archive_template();		
		
        if($bew_shop_template != '' && is_numeric($bew_shop_template)){
            $template_content = $this->render_insert_elementor($bew_shop_template);
            echo $template_content;
        }
    }
	
	/**
	 * Apply category template.
	 *
	 * @since 1.1.9
	 */
    public function apply_bew_wc_category_template($category ){
        $helper = new Helper();
        $bew_category_template = $helper->get_woo_category_template();
		
		global $bewcategory;
		
		$bewcategory = $category;
				
        if($bew_category_template != '' && is_numeric($bew_category_template)){
            $template_content = $this->render_insert_elementor($bew_category_template);
            echo $template_content;
        }
    }
	
	/**
	 * Check if product template is set.
	 *
	 * @since 1.1.0
	 */
	public function check_wc_product_template(){
        global $product;
        $helper = new Helper();
        $bew_product_template = $helper->get_bew_active_template($product->get_id(),'woo-product');
		
		return $bew_product_template;
		        
    }
	
	/**
	 * Check if shop template is set.
	 *
	 * @since 1.1.0
	 */
	public function check_wc_shop_template(){
       
        $helper = new Helper();
        $bew_shop_template = $helper->get_woo_archive_template();
		
		return $bew_shop_template;		      
    }

	/**
	 * Check if category template is set.
	 *
	 * @since 1.1.9
	 */
	public function check_wc_category_template(){
       
        $helper = new Helper();
        $bew_category_template = $helper->get_woo_category_template();
		
		return $bew_category_template;		      
    }
		    
}

Frontend::instance();