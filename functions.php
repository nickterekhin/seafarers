<?php

define("CHILD_THEME_PATH_URI",get_stylesheet_directory_uri());
define("CHILD_THEME_PATH",get_stylesheet_directory());
define("CHILD_THEME_MAIN_STYLE",get_stylesheet_uri());
define("CHILD_THEME_UPLOAD_URI",wp_upload_dir()['baseurl']);

include('inc/config.php');

add_action('wp_enqueue_scripts', 'main_style_setup',20);
function main_style_setup()
{
    wp_register_style( 'custom-css', CHILD_THEME_PATH_URI.'/content/css/custom.css?1');
    wp_enqueue_style( 'custom-css' );

}