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
        return $this->render_news();
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
        private function render_news()
        {
            $html='<div class="td-news-holder td-complex-news">';
            //first article
            $this->short_code_params['posts_per_page']=9;
            $this->short_code_params['offset']=2;
            $query = $this->theme_tools->get_post_query($this->short_code_params);
            wp_reset_postdata();

            $post_count=0;
            $class_holder=array();
            if($query->have_posts())
            {
                if($query->post_count>1)
                    $class_holder[]='td-news-column-2';
                $html.= '<div class="td-news-holder '.implode(' ',$class_holder).'">';
                while($query->have_posts()):$query->the_post();

                    endwhile;
                /*while($query->have_posts()):$query->the_post();
                    if($post_count==0 && $post_count==1)
                        $html.='<div class="top-layout1">';
                    $post_count++;
                    $this->short_code_params['post_number'] = $post_count;
                    $html.=$this->render_news_item();
                    if($post_count==1)
                        $html.='</div>';
                    if($post_count==4)
                        $html.='</div>';
                endwhile;*/

            }

            return $html;
        }

    private function render_news_item()
    {
        return $this->View('l1/news_item',array_merge(array('obj'=>$this),$this->short_code_params));
    }
}