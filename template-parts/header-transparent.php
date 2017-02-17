<?php
/**
 * The Header (transparent style) for BuildPress Theme
 *
 * @package BuildPress
 */

?>

<header class="header__container" role="banner">
	<div class="container">
		<div class="header">
			<!-- Logo -->
			<?php get_template_part( 'template-parts/header-parts/logo', 'default' ); ?>

			<!-- Toggle Button for Mobile Navigation -->
			<?php get_template_part( 'template-parts/header-parts/mobile-button', 'default' ); ?>

			<!-- Main Navigation -->
			<?php get_template_part( 'template-parts/header-parts/nav', 'default' ); ?>
		</div>
	</div>
</header>
