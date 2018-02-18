<?php
namespace TerekhinDevelopment\short_codes;

use TerekhinDevelopment\short_codes\src\impl\TD_PostSlider_Short_Code;

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
        $this->short_codes[] = new TD_PostSlider_Short_Code('td_post_slider');

        foreach($this->short_codes as $s)
        {
            add_action('vc_after_set_mode', array($s, 'load'));
        }
    }

    public function init_resources()
    {

    }


}

TD_News_ShortCodes_VC_Service::getInstance()->init();