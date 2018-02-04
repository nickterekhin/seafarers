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

        if(is_author() || $post->post_type == 'post') {
            return CHILD_THEME_PATH.'/category.php';
        }


    return $single;

}
add_filter('category_template','terekhin_dev_category_template',11);
