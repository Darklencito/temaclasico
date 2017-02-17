<?php
/**
 * The Header Logo part (default) for BuildPress Theme
 *
 * @package BuildPress
 */

?>

<div class="logo">
	<a href="<?php echo esc_url( home_url() ); ?>">
		<?php
			$logo              = esc_url( get_theme_mod( 'logo_img', false ) );
			$logo2x            = esc_url( get_theme_mod( 'logo2x_img', false ) );
			$logo_width_height = wp_kses_post( get_theme_mod( 'logo_width_height', '' ) );

			if ( ! empty( $logo ) ) :
		?>
			<img src="<?php echo $logo; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" srcset="<?php echo $logo; ?><?php echo empty ( $logo2x ) ? '' : ', ' . $logo2x . ' 2x'; ?>" class="img-responsive" <?php echo $logo_width_height; ?> />
		<?php
			else :
		?>
			<h1><?php bloginfo( 'name' ); ?></h1>
		<?php
			endif;
		?>
	</a>
</div>
