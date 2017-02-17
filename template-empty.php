<?php
/**
 * Empty page template.
 *
 * Template Name: Empty Template (no header and footer)
 *
 * @package BuildPress
 */

$bg_type     = get_field( 'bg_type' );
$style_array = array();

switch ( $bg_type ) {
	case 'color':
		$bg_color = get_field( 'bg_color' );
		$style_array['background'] = $bg_color;
		break;

	case 'image':
		$bg_image = get_field( 'bg_image' );

		if ( ! empty( $bg_image ) ) {
			$style_array = array(
				'background-image'      => $bg_image,
				'background-position'   => get_field( 'bg_image_horizontal_position' ) . ' ' . get_field( 'bg_image_vertical_position' ),
				'background-repeat'     => get_field( 'bg_image_repeat' ),
				'background-attachment' => get_field( 'bg_image_attachment' ),
				'background-size'       => get_field( 'bg_image_size' ),
			);

			$bg_color = get_field( 'bg_color' );
			if ( ! empty( $bg_color ) ) {
				$style_array['background-color'] = $bg_color;
			}
		}
		break;

	case 'video':
		$bg_video_image = get_field( 'bg_video_image' );
		$bg_video_mp4   = get_field( 'bg_video_mp4' );
		$bg_video_webm  = get_field( 'bg_video_webm' );
		break;
}


$body_style = buildpress_create_style_attr( $style_array );

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

	<body <?php body_class( buildpress_body_class() ); ?><?php echo $body_style; ?>>

	<?php if ( 'video' === $bg_type ) : ?>
		<div class="full-page-bg-video-overlay"></div>
		<video poster="<?php echo esc_url( $bg_video_image ); ?>" class="full-page-bg-video" playsinline autoplay muted loop>
			<source src="<?php echo esc_url( $bg_video_webm ); ?>" type="video/webm">
			<source src="<?php echo esc_url( $bg_video_mp4 ); ?>" type="video/mp4">
		</video>
	<?php endif; ?>

	<div class="boxed-container">

		<div class="master-container">
			<div <?php post_class( 'container' ); ?> role="main">

				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
					?>

						<div class="entry-content">
							<?php the_content(); ?>
						</div>

					<?php
					endwhile;
				endif;
				?>

			</div>
		</div>

	</div><!-- end of .boxed-container -->

	<?php wp_footer(); ?>
	</body>
</html>
