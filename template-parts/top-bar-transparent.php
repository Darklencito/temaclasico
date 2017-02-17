<?php
/**
 * The Header Top Bar for BuildPress Theme
 *
 * @package BuildPress
 */

?>

<?php if ( 'no' !== get_theme_mod( 'top_bar_visibility', 'yes' ) ) : ?>
	<div class="top">
		<div class="container">
			<div class="row">
				<div class="col-xs-12  col-md-6">
					<div class="top__left">
						<?php echo get_bloginfo( 'description' ); ?>
					</div>
				</div>
				<div class="col-xs-12  col-md-6">
					<div class="top__right top__right--widgets">
						<?php dynamic_sidebar( 'top-bar-right' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
