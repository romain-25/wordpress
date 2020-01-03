<?php

function capitaine_remove_menu_pages() {
	remove_menu_page( 'tools.php' );
    remove_menu_page( 'edit-comments.php' );
}

register_nav_menus( array(
	'main' => 'Menu Principal',
	'footer' => 'Bas de page',
) );

register_sidebar( array(
	'id' => 'blog-sidebar',
	'name' => 'Blog',
) );

register_sidebar( array(
    'id' => 'blog-sidebar',
    'name' => 'Blog',
    'before_widget'  => '<div class="site__sidebar__widget %2$s">',
    'after_widget'  => '</div>',
    'before_title' => '<p class="site__sidebar__widget__title">',
    'after_title' => '</p>',
  ) );


function capitaine_register_assets() {
    
    wp_deregister_script( 'jquery' ); // On annule l'inscription du jQuery de WP
    // Déclarer jQuery
    wp_enqueue_script( 
        'jquery', 
        'https://code.jquery.com/jquery-3.4.1.min.js', 
        false, 
        '3.4.1', 
        true 
    );
    
    // Déclarer le JS
	wp_enqueue_script( 
        'capitaine', 
        get_template_directory_uri() . 'script.js', 
        array( 'jquery' ), 
        '1.0', 
        true
    );
    
    // Déclarer style.css à la racine du thème
    wp_enqueue_style( 
        'capitaine',
        get_stylesheet_uri(), 
        array(), 
        '1.0'
    );
  	
    // Déclarer un autre fichier CSS
    wp_enqueue_style( 
        'capitaine', 
        get_template_directory_uri() . 'style.css',
        array(), 
        '1.0'
    );
}

function capitaine_register_post_types() {

    // CPT Portfolio
    $labels = array(
        'name' => 'Portfolio',
        'all_items' => 'Tous les projets',  // affiché dans le sous menu
        'singular_name' => 'Projet',
        'add_new_item' => 'Ajouter un projet',
        'edit_item' => 'Modifier le projet',
        'menu_name' => 'Portfolio'
    );

	$args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor','thumbnail' ),
        'menu_position' => 5, 
        'menu_icon' => 'dashicons-admin-customizer',
    );
    
    register_post_type( 'portfolio', $args );

    $labels = array(
        'name' => 'Type de projets',
        'new_item_name' => 'Nom du nouveau Projet',
    	'parent_item' => 'Type de projet parent',
    );
    
    $args = array( 
        'labels' => $labels,
        'public' => true, 
        'show_in_rest' => true,
        'hierarchical' => true, 
    );
    register_taxonomy( 'type-projet', 'portfolio', $args );
	
}
add_action( 'init', 'capitaine_register_post_types' ); // Le hook init lance la fonction


// Ajouter la prise en charge des images mises en avant
add_theme_support( 'post-thumbnails' );

// Ajouter automatiquement le titre du site dans l'en-tête du site
add_theme_support( 'title-tag' );

add_action( 'admin_menu', 'capitaine_remove_menu_pages' );
// Configuration du thème
require_once get_template_directory() . '/inc/config.php';

// Types de publication et taxonomies
require_once get_template_directory() . '/inc/post-types.php';

// Fonctionnalités
require_once get_template_directory() . '/inc/features.php';

// Ajouter la prise en charge des images mises en avant
add_theme_support( 'post-thumbnails' );

// Définir la taille des images mises en avant
set_post_thumbnail_size( 2000, 400, true );

// Définir d'autres tailles d'images
add_image_size( 'products', 800, 600, false );
add_image_size( 'square', 256, 256, false );

// Ajouter automatiquement le titre du site dans l'en-tête du site
add_theme_support( 'title-tag' );

add_action( 'wp_enqueue_scripts', 'capitaine_register_assets' ); 