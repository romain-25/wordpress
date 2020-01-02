<?php

function capitaine_remove_menu_pages() {
	remove_menu_page( 'tools.php' );
    remove_menu_page( 'edit-comments.php' );
}

add_action( 'admin_menu', 'capitaine_remove_menu_pages' );

// Ajouter la prise en charge des images mises en avant
add_theme_support( 'post-thumbnails' );

// Ajouter automatiquement le titre du site dans l'en-tête du site
add_theme_support( 'title-tag' );

// Configuration du thème
require_once get_template_directory() . '/inc/config.php';

// Types de publication et taxonomies
require_once get_template_directory() . '/inc/post-types.php';

// Fonctionnalités
require_once get_template_directory() . '/inc/features.php';

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
        get_template_directory_uri() . '/js/script.js', 
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
        get_template_directory_uri() . '/css/style.css',
        array(), 
        '1.0'
    );
}
add_action( 'wp_enqueue_scripts', 'capitaine_register_assets' ); 