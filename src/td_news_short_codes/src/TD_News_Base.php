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
    protected $template_layout;
    protected $default_params = array(
        'news_period'=>'',
        'news_title'=>'',
        'category_name'=>'',
        'author_id'=>'',
        'sort'=>'',
        'order'=>'',
        'offset'=>'',
        'display_category_image'=>'',
        'extra_class_name'=>'',
        'display_categories'=>'yes'

    );
    protected $item_options = array(
        'posts_per_page'=>'',
        'column_number'=>1,
        'title_tag' => 'h5',
        'image_size' => 'portfolio-landscape',
        'custom_image_width' => '',
        'custom_image_height' => '',
        'excerpt_length' => '',
        'date_format' => '',
        'display_excerpt' => 'no',
        'display_date' => 'yes',
        'display_categories' => 'yes',
        'display_author' => 'no',
        'display_share' => 'no',
        'display_hot_trending_icons' => 'no',
        'display_image' => 'no',
    );

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
    abstract function item_render($params);
    function base_params()
    {
        $params = array(
            array (
                'type' =>'textfield' ,
                'heading' =>'Period (in months)' ,
                'param_name' =>'news_period' ,

                'description' =>'Set period in months to show the news' ,
                'group' =>'General' ,
            ),
            array (
                'type' =>'textfield' ,
                'heading' =>'Title' ,
                'param_name' =>'news_title' ,

                'description' =>'Title of the news block' ,
                'group' =>'General' ,
            ),
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

        return array_merge($params,$this->init_params());
    }
    function item_params()
    {
        $params_array = array();

            $params_array[] = array(
                'type'        => 'dropdown',
                'param_name'  => 'title_tag',
                'heading'     => esc_html__( 'Title Tag', 'qode-news' ),
                'value'       => array_flip( qode_get_title_tag( true ) ),
                'group' 	  => esc_html__('Post Item','qode-news'),
            );
        $params_array[] = array(
            'type'		  => 'dropdown',
            'param_name'  => 'display_image',
            'heading'	  => esc_html__('Display Image','qode-news'),
            'value'		  => array_flip(qode_get_yes_no_select_array()),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );
        $params_array[] = array(
            'type'        => 'dropdown',
            'param_name'  => 'image_size',
            'heading'     => esc_html__( 'Image Size', 'qode-news' ),
            'value'		  => array(
                esc_html__('Default','qode-news') => '',
                esc_html__('Thumbnail','qode-news') => 'thumbnail',
                esc_html__('Medium','qode-news') => 'medium',
                esc_html__('Large','qode-news') => 'large',
                esc_html__('Landscape','qode-news') => 'portfolio-landscape',
                esc_html__('Portrait','qode-news') => 'portfolio-portrait',
                esc_html__('Square','qode-news') => 'portfolio-square',
                esc_html__('Full','qode-news') => 'full',
                esc_html__('Custom','qode-news') => 'custom',
            ),
            'description' => esc_html__( 'Choose image size', 'qode-news' ),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        $params_array[] = array(
            'type'        => 'textfield',
            'param_name'  => 'custom_image_width',
            'heading'     => esc_html__( 'Custom Image Width', 'qode-news' ),
            'description' => esc_html__( 'Enter image width in px', 'qode-news' ),
            'dependency'  => array('element' => 'image_size', 'value' => 'custom'),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        $params_array[] = array(
            'type'        => 'textfield',
            'param_name'  => 'custom_image_height',
            'heading'     => esc_html__( 'Custom Image Height', 'qode-news' ),
            'description' => esc_html__( 'Enter image height in px', 'qode-news' ),
            'dependency'  => array('element' => 'image_size', 'value' => 'custom'),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        $params_array[] = array(
            'type'		  => 'dropdown',
            'param_name'  => 'display_categories',
            'heading'	  => esc_html__('Display Categories','qode-news'),
            'value'		  => array_flip(qode_get_yes_no_select_array()),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        $params_array[] = array(
            'type'		  => 'dropdown',
            'param_name'  => 'display_excerpt',
            'heading'	  => esc_html__('Display Excerpt','qode-news'),
            'value'		  => array_flip(qode_get_yes_no_select_array()),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        $params_array[] = array(
            'type'        => 'textfield',
            'heading'	  => esc_html__('Max. Excerpt Length','qode-news'),
            'param_name'  => 'excerpt_length',
            'description' => esc_html__('Enter max of words that can be shown for excerpt','qode-news'),
            'dependency'  => array('element' => 'display_excerpt', 'value' => array('','yes')),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        $params_array[] = array(
            'type'		  => 'dropdown',
            'param_name'  => 'display_date',
            'heading'	  => esc_html__('Display Date','qode-news'),
            'value'		  => array_flip(qode_get_yes_no_select_array()),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        $params_array[] = array(
            'type'        => 'dropdown',
            'heading'	  => esc_html__('Publication Date Format','qode-news'),
            'param_name'  => 'date_format',
            'value' 	  => array(
                esc_html__('Default','qode-news') => '',
                esc_html__('Time from Publication','qode-news') => 'difference',
                esc_html__('Date of Publication','qode-news') => 'published'
            ),
            'dependency'  => array('element' => 'display_date', 'value' => array('','yes')),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        $params_array[] = array(
            'type'		  => 'dropdown',
            'param_name'  => 'display_author',
            'heading'	  => esc_html__('Display Author','qode-news'),
            'value'		  => array_flip(qode_get_yes_no_select_array()),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        $params_array[] = array(
            'type'		  => 'dropdown',
            'param_name'  => 'display_share',
            'heading'	  => esc_html__('Display Share','qode-news'),
            'value'		  => array_flip(qode_get_yes_no_select_array()),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        $params_array[] = array(
            'type'		  => 'dropdown',
            'param_name'  => 'display_hot_trending_icons',
            'heading'	  => esc_html__('Display Hot/Trending Icons','qode-news'),
            'value'		  => array_flip(qode_get_yes_no_select_array()),
            'group' 	  => esc_html__('Post Item','qode-news'),
        );

        return $params_array;
    }
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
                "params" => $this->base_params()
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

    protected function render_news_holder()
    {
        $html='';

        if(isset($this->short_code_params['news_period']) && !empty($this->short_code_params['news_period']))
            add_filter('posts_where',array($this,'custom_where_filter_posts'));

        $query = $this->theme_tools->get_post_query($this->short_code_params);

        if(isset($this->short_code_params['news_period']) && !empty($this->short_code_params['news_period']))
            remove_filter('posts_where',array($this,'custom_where_filter_posts'));


            if($query->have_posts())
            {

                $this->short_code_params['posts_qty']=$query->post_count;
                $html.='<div class="qode-news-holder qode-'.$this->template_layout.' qode-news-columns-'.$this->short_code_params['column_number'].' qode-nl-tiny-space qode-center-alignment '.$this->short_code_params['extra_class_name'].'">';
                    $html.='<div class="qode-news-list-inner-holder" data-number-of-items="'.$query->post_count.'">';
                while($query->have_posts()):$query->the_post();
                    $html.=$this->item_render($this->short_code_params);
                endwhile;
                    $html.='</div>';
                $html.='</div>';
            }


        wp_reset_postdata();
        return $html;
    }
    function custom_where_filter_posts($where)
    {
        $where .= " AND post_date >= DATE_SUB(CURDATE(),INTERVAL ".$this->short_code_params['news_period']." MONTH)";
        return $where;
    }
}