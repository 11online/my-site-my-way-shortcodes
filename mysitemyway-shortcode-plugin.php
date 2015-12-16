<?php
/*
Plugin Name: Mysitemyway Theme Shortcodes
Description: Adding support for shortcodes from a Mysitemyway theme.
Version:     1.0
Author:      Eric Debelak & Joshua T Garcia
Author URI:  http://11online.us
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
/* Start Adding Functions Below this Line */

//define( 'THEME_SHORTCODES', THEME_LIBRARY . '/shortcodes' );
define( 'THEME_SHORTCODES', plugin_dir_path( __FILE__ ) . '/shortcodes' );

foreach (glob("shortcodes/*.php") as $filename)
{
    include($filename);
}
include('raw-shortcode.php');

function theme_name_scripts() {
	wp_enqueue_style( 'shortcode-css', plugins_url( 'shortcodes.css', __FILE__ ));
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );

/**
 *
 */
function mysite_shortcodes() {
	$shortcodes = array();
		if ( $dh = opendir( THEME_SHORTCODES ) ) {
			while ( false !== ( $file = readdir( $dh ) ) ) {
				if( $file != '.' && $file != '..' && stristr( $file, '.php' ) !== false )
					$shortcodes[] = $file;
			}

			closedir( $dh );
		}

	asort( $shortcodes );

	return $shortcodes;
}

if ( !function_exists( 'mysite_shortcodes_init' ) ) :
/**
 *
 */
function mysite_shortcodes_init() {
	foreach( mysite_shortcodes() as $shortcodes )
		require_once THEME_SHORTCODES . '/' . $shortcodes;

	if( is_admin() )
		return;

	# Long posts should require a higher limit, see http://core.trac.wordpress.org/ticket/8553
	@ini_set('pcre.backtrack_limit', 9000000);

	foreach( mysite_shortcodes() as $shortcodes ) {
		$class = 'mysite' . ucfirst( preg_replace( '/[0-9-_]/', '', str_replace( '.php', '', $shortcodes ) ) );
		$class_methods = get_class_methods( $class );

		foreach( $class_methods as $shortcode )
			if( $shortcode[0] != '_' && $class != 'mysiteLayouts' )
				add_shortcode( $shortcode, array( $class, $shortcode ) );
	}
}
endif;

mysite_shortcodes_init();

function mysite_remove_wpautop( $content ) {
	$content = do_shortcode( shortcode_unautop( $content ) );
	$content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
	return $content;
}

//the following functions were theme functions that threw errors that the functions were undefined. This would be a good place to start the cleanup effort 
function apply_atomic( $tag = '', $value = '' ) {
  //nothing here, just trying to avoid errors
}
function  mysite_get_page_query() {
  //nothing here, just trying to avoid errors
}

function  mysite_get_setting() {
  //nothing here, just trying to avoid errors
}
function  mysite_stripslashes()  {
  //nothing here, just trying to avoid errors
}
function mysite_encode()  {
  //nothing here, just trying to avoid errors
}


/* Stop Adding Functions Below this Line */
?>
