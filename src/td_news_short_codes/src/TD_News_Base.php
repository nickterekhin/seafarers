<?php


namespace TerekhinDevelopment\td_news_short_codes\src;


use TerekhinDevelopment\helpers\TD_Theme_Tools;

abstract class TD_News_Base implements ITD_News
{
    protected $short_code_slug;
    protected $short_code_title;
    protected $short_code_category = "TD News";
    protected $short_code_params;
    protected $short_code_content=null;
    protected $theme_tools;

    public function __construct($short_code_slug,$short_code_category=null)
    {
        if($short_code_category)
            $this->short_code_category = $short_code_category;
        $this->short_code_slug = $short_code_slug;
        add_shortcode($short_code_slug,array($this,"render"));
        $this->theme_tools = TD_Theme_Tools::getInstance();
    }

    abstract function init_params();
    abstract function render($attr,$content=null);

    function load()
    {
        add_action('vc_after_mapping',array($this,'mapping'));

    }

    function mapping()
    {
        if (function_exists('vc_map')) {
            $attr = array(
                "name" => $this->short_code_title,
                "base" => $this->short_code_slug,
                "category" => $this->short_code_category,
                "icon" => 'td-news-icon ' . $this->set_icon(),
                //"allowed_container_element"=>"vc_row",
                "params" => $this->init_params()
            );
            if ($this->is_container()) {
                //$attr['as_parent']=array('only'=>'vc_row');
                $attr['is_container'] = true;
                $attr["js_view"] = 'VcColumnView';
                //$attr["content_element"] = true;
            }

            vc_map($attr);

        }
    }

    private function is_container()
    {
        return false;
    }

    private function set_icon()
    {
        return "fa fa-newspaper";
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
}