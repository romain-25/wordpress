<?php
function capitaine_register_assets() {

    // Déclarer jQuery
    wp_deregister_script( 'jquery' ); // On annule l'inscription du jQuery de WP
    wp_enqueue_script( // On déclare une version plus moderne
        'jquery', 
        'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', 
        false, 
        '3.3.1', 
        true 
    );
    
    
    // Déclarer le JS
	wp_enqueue_script( 
        'theme_wp', 
        get_template_directory_uri() . 'assets/js/script.js', 
        array( 'jquery' ), 
        '1.0', 
        true
    );
    
    // Déclarer style.css à la racine du thème
    wp_enqueue_style( 
        'theme_wp',
        get_stylesheet_uri(), 
        array(), 
        '1.0'
    );
  	
    // Déclarer un autre fichier CSS
    wp_enqueue_style( 
        'theme_wp', 
        get_template_directory_uri() . 'assets/css/main.css',
        array(), 
        '1.0'
    );
}

add_action( 'wp_enqueue_scripts', 'capitaine_register_assets' );

	// Configuration du thème
	require_once get_template_directory() . '/inc/config.php';

	// Types de publication et taxonomies
	require_once get_template_directory() . '/inc/post-types.php';

	// Fonctionnalités
	require_once get_template_directory() . '/inc/features.php';



function capitaine_remove_menu_pages() {
	remove_menu_page( 'tools.php' );
    remove_menu_page( 'edit-comments.php' );
};

add_action( 'admin_menu', 'capitaine_remove_menu_pages' );
add_theme_support( 'post-thumbnails' );

// Définir la taille des images mises en avant
set_post_thumbnail_size( 2000, 400, true );

// Définir d'autres tailles d'images
add_image_size( 'products', 800, 600, false );
add_image_size( 'square', 256, 256, false );

register_nav_menus( array(

    'footer' => 'Bas de page',) );
    
    register_sidebar( array(
        'id' => 'blog-sidebar',
        'name' => 'Blog',
        'before_widget'  => '<div class="site__sidebar__widget %2$s">',
        'after_widget'  => '</div>',
        'before_title' => '<p class="site__sidebar__widget__title">',
        'after_title' => '</p>',
        ) );

        
// CREER UNE METABOX

        add_action('add_meta_boxes','initialisation_metaboxes');
        function initialisation_metaboxes(){
            add_meta_box('slogan', 'Slogan de la Page', 'function_slogan', 'page', 'side', 'high');
        }
        function function_slogan($page){
            $val = get_post_meta($page->ID,'_ma_valeur',true);
            echo '<label for="mon_champ">Mon champ : </label>';
            echo '<input id="mon_champ" type="text" name="mon_champ" value="'.$val.'" />';
          }
        
          add_action('save_post','save_metaboxes');
          function save_metaboxes($post_ID){
            // si la metabox est définie, on sauvegarde sa valeur
            if(isset($_POST['mon_champ'])){
              update_post_meta($post_ID,'_ma_valeur', esc_html($_POST['mon_champ']));
            }
          }
            $val = get_post_meta($page->ID,'_ma_valeur',true);
            // $val renverra 'la valeur de mon champ'
            $val = get_post_meta($page->ID,'_ma_valeur',false);
            // $val renverra array('la valeur de mon champ','la seconde valeur', 'une autre valeur')