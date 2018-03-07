<?php
namespace TerekhinDevelopment\framework;

use TerekhinDevelopment\helpers\TD_Theme_Tools;
use TerekhinDevelopment\short_codes\helpers\TD_Short_Codes_Tools;

abstract class TD_Framework_Base
{

    protected $tools;
    protected $db;
    /**
     * TD_Framework_Base constructor.
     */
    protected function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->tools = TD_Theme_Tools::getInstance();
    }

    function View($viewName, array $params=array())
    {

        if(is_array($params) && count($params)) {
            extract($params);
        }
        $file = CHILD_THEME_PATH . '/src/view/'. $viewName . '.php';

        ob_start();
        include( $file );
        $ret_obj= ob_get_clean();


        return $ret_obj;
    }
    public function getCategoryImage($postId)
    {

        $terms = get_the_terms($postId,'category');
        $background_image = '';
        if(count($terms)>0 && function_exists('get_field'))
            $background_image = get_field('header_image','category_'.$terms[0]->term_id,true);

        return $background_image;
    }
}