<?php


namespace TerekhinDevelopment\td_news_short_codes;


use TerekhinDevelopment\td_news_short_codes\src\impl\TD_News_Complex_Layout;

class TD_News
{
    private static $instance;

    private $short_codes = array();

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
    private function __construct()
    {

    }

    function init()
    {
        if(is_admin())
        {
            add_action("admin_enqueue_scripts",array($this,"init_resource"));
        }
        add_action("vc_before_init",array($this,'vc_integration'));
    }

    function vc_integration()
    {

        $this->short_codes[] = new TD_News_Complex_Layout();

        foreach($this->short_codes as $sc)
        {
            add_action('vc_after_set_mode',array($sc,'load'));
        }
    }

    function init_resource()
    {

    }
}

TD_News::getInstance()->init();