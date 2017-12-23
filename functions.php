<?php
/* =========================================
 * Enqueues parent theme stylesheet
 * ========================================= */

function newser_enqueue_child_theme_styles() {
	wp_enqueue_style( 'newser-child-theme-style', get_stylesheet_uri(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'newser_enqueue_child_theme_styles', 30 );
