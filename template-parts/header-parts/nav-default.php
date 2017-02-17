<?php
/**
 * The Header nav part (default) for BuildPress Theme
 *
 * @package BuildPress
 */

?>

<div class="sticky-offset  js-sticky-offset"></div>
<div class="navigation" role="navigation">
	<div class="collapse  navbar-collapse" id="buildpress-navbar-collapse">
		<?php
			if ( has_nav_menu( 'main-menu' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'main-menu',
					'container'      => false,
					'menu_class'     => 'navigation--main  js-dropdown'
				) );
			}
		?>
	</div>
</div>
