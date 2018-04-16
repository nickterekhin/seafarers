<?php
if(is_admin())
{
    add_action('admin_menu','terekhin_dev_remove_elements');
}

function terekhin_dev_remove_elements()
{
    global $menu;

    if(!current_user_can('administrator'))
    {
        remove_menu_page('admin.php?page=vc-welcome');
        remove_menu_page('admin.php?page=vc-general');
        remove_menu_page('vc-general');
        remove_menu_page('vc-welcome');
        remove_menu_page('tools.php');
        remove_menu_page('edit.php?post_type=slides');
        remove_menu_page('edit.php?post_type=carousels');
        remove_menu_page('edit.php?post_type=tribe_events');
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('bulk-delete-posts');
        remove_menu_page('wpcf7');
        remove_menu_page('options-general.php');
        remove_menu_page('edit.php?post_type=acf-field-group');
        remove_menu_page('layerslider');
        remove_menu_page('revslider');
        remove_menu_page('link-manager.php');
        remove_menu_page('edit.php?post_type=masonry_gallery');
        remove_menu_page('edit.php?post_type=testimonials');
        remove_menu_page('edit.php?post_type=portfolio_page');
        remove_menu_page('admin.php?page=envato-market');
        remove_menu_page('disqus');
    }
    if(current_user_can('editor'))
    {


    }

}
add_action( 'admin_bar_menu', 'remove_wp_nodes', 999 );

function remove_wp_nodes()
{
    global /** @var WP_Admin_Bar $wp_admin_bar */
    $wp_admin_bar;

    if(!current_user_can('administrator'))
    {
        $wp_admin_bar->remove_node('updates');
        $wp_admin_bar->remove_node('new-page');
        $wp_admin_bar->remove_node('new-qode-quick-link');
        $wp_admin_bar->remove_node('new-qode-restaurant-menu');
        $wp_admin_bar->remove_node('new-portfolio_page');
        $wp_admin_bar->remove_node('new-testimonials');
        $wp_admin_bar->remove_node('new-slides');
        $wp_admin_bar->remove_node('new-carousels');
        $wp_admin_bar->remove_node('new-masonry_gallery');
        $wp_admin_bar->remove_node('new-user');
        $wp_admin_bar->remove_node('quform-new-form');
        $wp_admin_bar->remove_node('quform');
        $wp_admin_bar->remove_node('quform-dashboard');
        $wp_admin_bar->remove_node('quform-forms');
        $wp_admin_bar->remove_node('quform-add-new');
        $wp_admin_bar->remove_node('new-link');
        $wp_admin_bar->remove_node('ab-ls-add-new');
        $wp_admin_bar->remove_node('new-tribe_events');
        $wp_admin_bar->remove_node('archive');
        $wp_admin_bar->remove_menu('edit');
    }


}
add_action( 'do_meta_boxes', 'terekhin_remove_qode_meta_boxes');
function terekhin_remove_qode_meta_boxes()
{

    if(!current_user_can('administrator')){
        remove_meta_box('qodef-meta-box-page_general','post','advanced');
        remove_meta_box('qodef-meta-box-page_left_menu','post','advanced');
        //remove_meta_box('qodef-meta-box-post_layout','post','advanced');
        remove_meta_box('qodef-meta-box-page_header','post','advanced');
        remove_meta_box('qodef-meta-box-page_title','post','advanced');
        remove_meta_box('qodef-meta-box-page_title_animations','post','advanced');
        remove_meta_box('qodef-meta-box-page_content_bottom','post','advanced');
        remove_meta_box('qodef-meta-box-page_side_bar','post','advanced');
        //remove_meta_box('qodef-meta-box-post_seo','post','advanced');
    }

}
function terekhin_dev_category_template($single)
{
    global $post,$wp_query;
        if(is_author() || ($post && $post->post_type == 'post')) {
            return CHILD_THEME_PATH.'/category.php';
        }


    return $single;

}

add_filter('category_template','terekhin_dev_category_template',11);
add_filter('archive_template','terekhin_dev_category_template',11);
if(!current_user_can('administrator')) {
    add_filter('after_setup_theme', 'terekhin_dev_remove_post_formats', 11);
    function terekhin_dev_remove_post_formats()
    {
        add_theme_support('post-formats', array('video'));
    }
}
function terekhin_dev_comments_template($single)
{
    global $post;

    if(is_single() && (is_author() || $post->post_type == 'post')) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if ( is_plugin_active( 'disqus-comment-system/disqus.php' ) ) {
            return WP_PLUGIN_DIR.'/disqus-comment-system/public/partials/disqus-public-display.php';
        }

    }
    return $single;
}
add_filter('comments_template','terekhin_dev_comments_template');
/*
function edit_slider_query($posts,$obj)
{
    var_dump($posts);
    return $posts;
}
add_action('the_posts','edit_slider_query',10,2);*/
