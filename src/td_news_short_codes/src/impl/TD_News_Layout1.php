<?php


namespace TerekhinDevelopment\td_news_short_codes\src\impl;


use TerekhinDevelopment\td_news_short_codes\src\TD_News_Base;

class TD_News_Layout1 extends TD_News_Base
{

    private $default_params = array(
        'category_name'=>'',
        'author_id'=>'',
        'sort'=>'',
        'order'=>'',
        'offset'=>'',
        'display_category_image'=>'',
        'extra_class_name'=>''

    );

    public function __construct()
    {
        parent::__construct('td_news_l1');
        $this->short_code_title = "Show News Layout 1";
    }

    function render($attr, $content = null)
    {
        $this->short_code_params = shortcode_atts($this->default_params,$attr);
        return $this->render_template();
    }


    function init_params()
    {
        $params = array(

            array (
                'type' =>'textfield' ,
                'heading' =>'Category' ,
                'param_name' =>'category_name' ,

                'description' =>'Enter the categories of the posts you want to display (comma separate) (leave empty for showing all categories)' ,
                'group' =>'General' ,
            ),
            array (
                'type' =>'textfield' ,
                'heading' =>'Author' ,
                'param_name' =>'author_id' ,
                'settings' =>
                    array (
                        'multiple' => true,
                        'sortable' => true,
                        'unique_values' => true,
                    ),
                'description' =>'Enter the authors of the posts you want to display (comma separate) (leave empty for showing all authors)' ,
                'group' =>'General' ,
            ),
        array (
            'type' =>'dropdown' ,
            'heading' =>'Sort' ,
            'param_name' =>'sort' ,
            'value' =>
                array (
                    '' =>'' ,
                    'Latest' =>'latest' ,
                    'Random' =>'random' ,
                    'Random Posts Today' =>'random_today' ,
                    'Random in Last 7 Days' =>'random_seven_days' ,
                    'Most Commented' =>'comments' ,
                    'Title' =>'title' ,
                    'Popular' =>'popular' ,
                    'Featured Posts First' =>'featured_first' ,
                    'Trending Posts First' =>'trending_first' ,
                    'Hot Posts First' =>'hot_first' ,
                    'By Reaction' =>'reactions' ,
                ),
            'description' =>'' ,
            'group' =>'General' ,

            ),
            array(
                'type'       => 'dropdown',
                'param_name' => 'order',
                'heading'    => esc_html__('Order', 'qode-news'),
                'value'      => array_flip(qode_get_query_order_array()),
                'dependency'    => array(
                    'element' => 'sort',
                    'value' => array(
                        'title'
                    )
                ),
                'save_always' => true,
                'group' => esc_html__('General','qode-news')
            ),
            array(
                'type'       => 'textfield',
                'param_name' => 'offset',
                'heading'    => esc_html__('Offset', 'qode-news'),
                'save_always' => true,
                'group' => esc_html__('General','qode-news')
            ),
             array(
                 'type' => 'dropdown',
                 'heading' => esc_html__('Use Only Category Image','qode-news'),
                 'param_name' => 'display_category_image',
                 'value' => array(
                     esc_html__('Default', 'qode-news') => '',
                     esc_html__('Yes', 'qode-news') => 'yes',
                     esc_html__('No', 'qode-news') => 'no'
                 ),
                 'group' => esc_html__('General','qode-news')
             ),
            array (
                'type' =>'textfield' ,
                'heading' =>'Extra Class Name' ,
                'param_name' =>'extra_class_name' ,
                'group' =>'General' ,
            ),
        );

        return $params;
    }
        private function render_template()
        {

            $this->short_code_params['posts_per_page']=9;
            $this->short_code_params['orderby']='date';
            //add_filter('posts_where',array($this,'custom_where_filter_posts'));
            $query = $this->theme_tools->get_post_query($this->short_code_params);
            //remove_filter('posts_where',array($this,'custom_where_filter_posts'));
            $this->short_code_params['section_1_columns_qty']='1';
            if($query->post_count>1)
                $this->short_code_params['section_1_columns_qty']='2';

                $this->short_code_params['posts_arr']=$query->posts;
                $this->short_code_params['posts_qty']=$query->post_count;
            wp_reset_postdata();
            return $this->View('l1/template',array_merge(array('obj'=>$this),$this->short_code_params));
        }

    function custom_where_filter_posts($where)
    {
        $today = date("Y-m-d",time());
        $where .= " AND post_date >= DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
        return $where;
    }
    public function render_article($post_q,$params=array())
    {
        global $post;
        $post = $post_q;

        return $this->View('l1/news_item',wp_parse_args($params,array('obj'=>$this)));
    }
    public function render_articles($post_arr,$params=array())
    {
        $html='';
        foreach($post_arr as $p) {
            $html.= $this->render_article($p,$params);
        }
        return $html;
    }
}