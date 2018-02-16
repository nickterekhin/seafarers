<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 10.02.2018
 * Time: 23:40
 */

namespace TerekhinDevelopment\short_codes\src;


abstract class TD_ShortCodes
{
    protected $sc_category = "HighFusion";
    protected $sc_name;
    protected $options = array();

    /**
     * TD_ShortCodes constructor.
     * @param $sc_name
     */
    public function __construct($sc_name)
    {
        $this->sc_name = $sc_name;
        add_shortcode($sc_name,array($this,'renderShortCode'));
    }

    abstract function renderShortCode($attr);
    abstract function initShortCode();
    abstract function initAttributes($value,$data);

    public function load()
    {
        add_action('vc_after_mapping', array($this,'mapShortCodes') );
    }

    function mapShortCodes()
    {
        $params = $this->initShortCode();
        vc_map($params);
    }

    function View($viewName, array $args=array())
    {


        ob_start();
        if(!empty($args)) {
            $args = apply_filters('terekhin_dev_shortcodes_vc', $args, $viewName);

            foreach ($args AS $key => $val) {
                $$key = $val;
            }
        }


        $file = CHILD_THEME_PATH . '/src/short_codes/view/'. $viewName . '.php';

        include( $file );

        $ret_obj= ob_get_clean();


        return $ret_obj;
    }
}