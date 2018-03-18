<?php


namespace TerekhinDevelopment\short_codes\helpers;


use TerekhinDevelopment\helpers\TD_Theme_Tools;
use WP_Query;

class TD_Short_Codes_Tools
{
    private static $instance;
    private $theme_tools;
    /**
     * TD_Short_Codes_Tools constructor.
     */
    private function __construct()
    {
        $this->theme_tools = TD_Theme_Tools::getInstance();
    }

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function news_get_query($params, $return_query = true) {
        return $this->theme_tools->get_post_query($params,$return_query);
    }

    public function get_short_code_params_name($params){
        $params_names = array();

        foreach ($params as $param) {
            $params_names[$param['param_name']] = '';
        }

        $params_names['offset'] = '';

        return $params_names;
    }
    function news_shortcode_atts($pairs, $atts){
        $atts = (array)$atts;
        $out = array();
        foreach ($pairs as $name => $default) {
            if (array_key_exists($name, $atts)) {
                $out[$name] = $atts[$name];
                unset($atts[$name]);
            }else {
                $out[$name] = $default;
            }
        }

        $merge = array_merge($out,$atts);

        return $merge;
    }

    function get_new_categories($params)
    {
        if($params['category_name']!='')
        {
            return preg_split('/\,/',$params['category_name'] );
        }else
        {
            $args = array(
                "hide_empty"=>1,
                'taxonomy'=> 'category',
                'parent'=>0,
            );

            return array_map(function($e){
                return $e->slug;
            },get_categories($args));

        }

    }
}