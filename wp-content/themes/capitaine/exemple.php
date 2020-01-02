<?php 
	$title = get_the_title();
	
	// Si le titre contient le mot "promo", j'ajoute un emoji
	if( strpos( $title, 'Promo' ) ) {
		$title = '💰' . $title; 
    }
?>
	<h1><?php echo $title; ?></h1>