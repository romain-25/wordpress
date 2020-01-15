<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'site' ); ?>>

  <header class="site__header">
    <a href="<?php echo home_url( '/' ); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/img/logowp.svg" alt="Logo">
    </a>

    
  </header>

  <?php 

?>



<?php 
if ( is_user_logged_in() ):
	get_currentuserinfo(); 
?>
	<p>
        <?php echo $current_user->user_firstname; ?>
        <a href="<?php echo wp_login_url(); ?>"> Déconnexion </a>
	</p>
<?php else: ?>
    <p>
        <a href="<?php echo wp_login_url(); ?>"> Connexion </a>
	</p>
<?php endif; ?>

<body <?php body_class(); ?>>
    
    <?php wp_body_open(); ?>