<?php


namespace TerekhinDevelopment\td_news_short_codes\src\impl;


use TerekhinDevelopment\td_news_short_codes\src\TD_News_Base;

class TD_News_Complex_Layout extends TD_News_Base
{

    public function __construct()
    {
        parent::__construct('td_news_complex');
        $this->short_code_title = "Show News Complex View";
    }

    function render($attr, $content = null)
    {
        $this->short_code_params = shortcode_atts($this->default_params,$attr);
        return $this->render_template();
    }


    function init_params()
    {
        return array();
    }
    private function render_template()
        {

            $this->short_code_params['posts_per_page']=9;
            $this->short_code_params['order']='DESC';

            if(isset($this->short_code_params['news_period']) && !empty($this->short_code_params['news_period']))
            add_filter('posts_where',array($this,'custom_where_filter_posts'));

            $query = $this->theme_tools->get_post_query($this->short_code_params);

            if(isset($this->short_code_params['news_period']) && !empty($this->short_code_params['news_period']))
            remove_filter('posts_where',array($this,'custom_where_filter_posts'));

            $this->short_code_params['section_1_columns_qty']='1';
            $this->short_code_params['section_1_col']='vc_col-sm-12';
            if($query->post_count>1) {
                $this->short_code_params['section_1_columns_qty'] = '2';
                $this->short_code_params['section_1_col']='vc_col-sm-6';
            }

                $this->short_code_params['posts_arr']=$query->posts;
                $this->short_code_params['posts_qty']=$query->post_count;
            wp_reset_postdata();
            return $this->View('qode_template_complex',array_merge(array('obj'=>$this),$this->short_code_params));
        }


    public function render_article($post_q,$params=array())
    {
        global $post;
        $post = $post_q;

        return $this->View('l1/qode_news_item',wp_parse_args($params,array('obj'=>$this)));
    }
    public function render_articles($post_arr,$params=array())
    {
        $html='';
        foreach($post_arr as $p) {
            $html.= $this->render_article($p,$params);
        }
        return $html;
    }

    function item_render($params)
    {
        // TODO: Implement item_render() method.
    }
}