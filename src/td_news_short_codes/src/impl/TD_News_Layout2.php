<?php


namespace TerekhinDevelopment\td_news_short_codes\src\impl;


use TerekhinDevelopment\td_news_short_codes\src\TD_News_Base;

class TD_News_Layout2 extends TD_News_Base
{
    public function __construct()
    {
        parent::__construct('td_news_layout2');
        $this->short_code_title = "TDN Layout2";
        $this->template_layout='layout2';

    }
    function init_params()
    {
        $params_array[] = array(
            'type' => 'textfield',
            'heading' => esc_html__('Number of Posts','qode-news'),
            'param_name' => 'posts_per_page',
            'value' => '6',
            'save_always' => true,
            'group' => esc_html__('General','qode-news')
        );

        $params_array[] = array(
            'type' => 'dropdown',
            'heading' => esc_html__('Number of Columns','qode-news'),
            'param_name' => 'column_number',
            'value' => array(
                '' => '',
                esc_html__('One','qode-news') => 1,
                esc_html__('Two','qode-news') => 2,
                esc_html__('Three','qode-news') => 3,
                esc_html__('Four','qode-news') => 4,
            ),
            'group' => esc_html__('General','qode-news')
        );
        return array_merge($params_array,$this->item_params());
    }

    function render($attr, $content = null)
    {

        $this->default_params = wp_parse_args($this->item_options,$this->default_params);

        $this->short_code_params = shortcode_atts($this->default_params,$attr);

        return $this->render_news_holder();
    }

    function item_render($params)
    {
        return $this->View('l2/qode_news_item',array_merge(array('obj'=>$this),$this->short_code_params));
    }
}