<?php
namespace TerekhinDevelopment\short_codes;

class TD_News_ShortCodes_VC_Service
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
    /**
     * TD_News_ShortCodes_VC_Service constructor.
     */
    public function __construct()
    {

    }

    public function init()
    {
        if(is_admin())
        {
            add_action("admin_enqueue_scripts", array($this,'init_resources'));
        }
        add_action('vc_before_init',array($this,'init_short_codes'));
    }

    public function init_short_codes()
    {
        foreach($this->short_codes as $s)
        {
            add_action('vc_after_set_mode', array($s, 'load'));
        }
    }


}

TD_News_ShortCodes_VC_Service::getInstance()->init();