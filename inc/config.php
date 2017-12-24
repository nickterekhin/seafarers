<?php
if(is_admin())
{
    add_action('admin_menu','terekhin_dev_remove_elements');
}

function terekhin_dev_remove_elements()
{
    global $menu;
var_dump($menu);
    if(!current_user_can('administrator'))
    {
        remove_menu_page('admin.php?page=vc-welcome');
        remove_menu_page('admin.php?page=vc-general');
        remove_menu_page('vc-general');

        remove_menu_page('tools.php');
    }
}