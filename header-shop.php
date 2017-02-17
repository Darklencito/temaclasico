<?php
/**
 * The Header of the WooCommerce shop.
 *
 * @package BuildPress
 */

if ( buildpress_is_header_style_new() ) {
	get_template_part( 'header-new' );
}
else {
	get_template_part( 'header' );
}
