<?php
/*
Template Name: Front Page With Slider
*/

buildpress_get_header();

// only include the jumbotron if we defined some slides
if ( have_rows( 'slides' ) ) {
	if ( ! buildpress_is_header_style_new() ) {
		get_template_part( 'part-jumbotron' );
	}
	else {
		get_template_part( 'template-parts/jumbotron', get_theme_mod( 'theme_style', 'classic' ) );
	}
}

?>
<div class="master-container">
	<div <?php post_class( 'container' ); ?> role="main">
		<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					the_content();
				}
			}
		?>
	</div>
</div>

<?php get_footer(); ?>
