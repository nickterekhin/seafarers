<?php
namespace TerekhinDevelopment\framework;

abstract class TD_Framework_Base
{
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
}