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
global $terekhin_framework;

add_filter('the_content',array($terekhin_framework,'about_page_content'));