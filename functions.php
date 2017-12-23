<?php

define("CHILD_THEME_PATH_URI",get_stylesheet_directory_uri());
define("CHILD_THEME_PATH",get_stylesheet_directory());
define("CHILD_THEME_MAIN_STYLE",get_stylesheet_uri());
define("CHILD_THEME_UPLOAD_URI",wp_upload_dir()['baseurl']);

function newser_enqueue_child_theme_styles() {
	wp_enqueue_style( 'newser-child-theme-style', get_stylesheet_uri(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'newser_enqueue_child_theme_styles', 30 );
