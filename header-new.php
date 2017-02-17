<?php
/**
 * The Header for BuildPress Theme
 *
 * @package BuildPress
 */

$header_template = get_theme_mod( 'theme_style', 'classic' );

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<!-- W3TC-include-js-head -->
		<?php wp_head(); ?>
		<!-- W3TC-include-css -->
	</head>

	<body <?php body_class( buildpress_body_class() ); ?>>
	<div class="boxed-container">


	<?php get_template_part( 'template-parts/top-bar', $header_template ); ?>

	<?php get_template_part( 'template-parts/header', $header_template ); ?>
