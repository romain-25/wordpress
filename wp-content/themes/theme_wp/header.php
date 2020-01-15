<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" rel="stylesheet"></link>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<header class="header">
    <a href="<?php echo home_url( '/' ); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Logo">
    </a>  
<div class="menu_search">
    <?php 
    	wp_nav_menu( 
        array( 
            'theme_location' => 'main', 
            'container' => 'ul', // afin d'éviter d'avoir une div autour 
            'menu_class' => 'site__header__menu', // ma classe personnalisée 
        ) 
    ); 
?>
<?php get_search_form(); ?>

</div>

  </header>
    <?php wp_body_open(); ?>