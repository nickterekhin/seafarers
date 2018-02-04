<?php
namespace TerekhinDevelopment;


use TD_Class_Loader;

if(!class_exists('TD_Class_Loader'))
{
    require_once CHILD_THEME_PATH.'/src/helpers/TD_Class_Loader.php';
}

define("TD_SOURCE_DIR",CHILD_THEME_PATH.'/src/');

$autoLoader = TD_Class_Loader::getInstance();
$autoLoader->setPrefixes(array("TerekhinDevelopment"=>TD_SOURCE_DIR));
$autoLoader->register_auto_loader();
