<?php

define("CHILD_THEME_PATH_URI",get_stylesheet_directory_uri());
define("CHILD_THEME_PATH",get_stylesheet_directory());
define("CHILD_THEME_MAIN_STYLE",get_stylesheet_uri());
define("CHILD_THEME_UPLOAD_URI",wp_upload_dir()['baseurl']);

include('inc/config.php');
include('framework/TD_Framework.php');
include('inc/popular_news_config.php');
include('inc/social_config.php');

add_action('wp_enqueue_scripts', 'main_style_setup',25);
function main_style_setup()
{
    wp_register_style( 'td_custom-css', CHILD_THEME_PATH_URI.'/content/css/custom.css');
    wp_enqueue_style( 'td_custom-css' );

}

