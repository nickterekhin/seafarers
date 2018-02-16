<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 11.02.2018
 * Time: 00:05
 */

namespace TerekhinDevelopment\short_codes\src\impl;

use TerekhinDevelopment\short_codes\src\ITD_ShortCodes;
use TerekhinDevelopment\short_codes\src\TD_ShortCodes;

class TD_PostSlider_Short_Code extends TD_ShortCodes implements ITD_ShortCodes
{
    private $default_attr = array(

    );

    function __construct($sc_name)
    {
        parent::__construct($sc_name);

    }

    function renderShortCode($attr)
    {
        $this->options = shortcode_atts($this->default_attr,$attr);
    }

    function initShortCode()
    {
        // TODO: Implement initShortCode() method.
    }

    function initAttributes($value, $data)
    {
        // TODO: Implement initAttributes() method.
    }

}