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
use TerekhinDevelopment\tools\WP_Union_Query;
use WP_Query;
use WP_Term;

class TD_PostSlider_Short_Code extends TD_ShortCodes implements ITD_ShortCodes
{
    private $default_attr = array(
        'content_in_grid' => 'yes',
        'column_number' => '1',
        'space_between_items' => 'no',
        'slider_size' => 'landscape',
        'title_tag' => 'h2',
        'title_length' => '',
        'image_size' => 'full',
        'custom_image_width' => '',
        'custom_image_height' => '',
        'excerpt_length' => '',
        'date_format' => '',
        'display_excerpt' => 'yes',
        'display_categories' => 'yes',
        'display_share' => 'yes',
        'content_padding' => '',
        'display_button' => 'yes',
        'display_category_image'=>'yes',
    );

    function __construct($sc_name)
    {
        parent::__construct($sc_name);
        $this->short_code_title = 'Post Slider';
        $this->css_class = 'qode-slider-td';

    }

    function renderShortCode($attr)
    {
        $sc_params_names = $this->short_codes_tools->get_short_code_params_name($this->initShortCode());
        $opts = $this->short_codes_tools->news_shortcode_atts($sc_params_names,$this->default_attr);
        $this->options = shortcode_atts($opts,$attr);
        return $this->showNewsSlider();
    }

    function initShortCode()
    {
        $params = array(
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Slider Size','qode-news'),
                'param_name' => 'slider_size',
                'value' => array(
                    esc_html__('Default', 'qode-news') => '',
                    esc_html__('Landscape', 'qode-news') => 'landscape',
                    esc_html__('Square', 'qode-news') => 'square',
                    esc_html__('Full Screen', 'qode-news') => 'full-screen'
                ),
                'group' => esc_html__('General','qode-news')
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Content in Grid','qode-news'),
                'param_name' => 'content_in_grid',
                'value' => array(
                    esc_html__('Default', 'qode-news') => '',
                    esc_html__('Yes', 'qode-news') => 'yes',
                    esc_html__('No', 'qode-news') => 'no'
                ),
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
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Content Padding','qode-news'),
                'param_name' => 'content_padding',
                'description' => esc_html__('Insert content padding in (0px 5px 0px 5px) form','qode-news'),
                'group' => esc_html__('General','qode-news')
            ),
            array (
                  'type' =>'textfield' ,
                  'heading' =>'Extra Class Name' ,
                  'param_name' =>'extra_class_name' ,
                  'group' =>'General' ,
  ),
    array (
      'type' =>'autocomplete' ,
      'heading' =>'Category' ,
      'param_name' =>'category_name' ,
      'settings' =>
        array (
          'multiple' => true,
          'sortable' => true,
          'unique_values' => true,
        ),
      'description' =>'Enter the categories of the posts you want to display (leave empty for showing all categories)' ,
      'group' =>'General' ,
  ),
    array (
      'type' =>'autocomplete' ,
      'heading' =>'Author' ,
      'param_name' =>'author_id' ,
      'settings' =>
        array (
          'multiple' => true,
          'sortable' => true,
          'unique_values' => true,
        ),
      'description' =>'Enter the authors of the posts you want to display (leave empty for showing all authors)' ,
      'group' =>'General' ,
  ),
    array (
      'type' =>'autocomplete' ,
      'heading' =>'Tag' ,
      'param_name' =>'tag' ,
      'settings' => 
        array (
          'multiple' => true,
          'sortable' => true,
          'unique_values' => true,
        ),
      'description' =>'Enter the tags of the posts you want to display (leave empty for showing all tags)' ,
      'group' =>'General' ,
  ),
    array (
      'type' =>'autocomplete' ,
      'heading' =>'Include Posts' ,
      'param_name' =>'post_in' ,
      'settings' => 
        array (
          'multiple' => true,
          'sortable' => true,
          'unique_values' => true,
        ),
      'description' =>'Enter the IDs or Titles of the posts you want to display' ,
      'group' =>'General' ,

  ),
    array (
      'type' =>'autocomplete' ,
      'heading' =>'Exclude Posts' ,
      'param_name' =>'post_not_in' ,
      'settings' => 
        array (
          'multiple' => true,
          'sortable' => true,
          'unique_values' => true,
        ),
      'description' =>'Enter the IDs or Titles of the posts you want to exclude' ,
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
            'type' => 'autocomplete',
            'heading' => esc_html__('Reaction','qode-news'),
            'param_name' => 'reaction',
            'settings'    => array(
                'multiple'      => false,
                'sortable'      => true,
                'unique_values' => true
            ),
            'dependency'    => array(
                'element' => 'sort',
                'value' => array(
                    'reactions'
                )
            ),
            'description' => esc_html__('Choose the reaction which you would like to use for sorting your posts. The posts that have the greatest number of your chosen reaction will be displayed first.','qode-news'),
            'group' => esc_html__('General','qode-news')
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
        )
    );

        $item_array = array(
            array(
                'type'		  => 'dropdown',
                'param_name'  => 'display_button',
                'heading'	  => esc_html__("Display 'Read More' Button",'qode-news'),
                'value'		  => array_flip(qode_get_yes_no_select_array()),
                'group' 	  => esc_html__('Post Item','qode-news'),
            ),
            array( //has to be here since it will not work without 'show date' field
                'type'        => 'dropdown',
                'heading'	  => esc_html__('Publication Date Format','qode-news'),
                'param_name'  => 'date_format',
                'value' 	  => array(
                    esc_html__('Default','qode-news') => '',
                    esc_html__('Time from Publication','qode-news') => 'difference',
                    esc_html__('Date of Publication','qode-news') => 'published'
                ),
                'group' 	  => esc_html__('Post Item','qode-news'),
            )

        );

        return array_merge($params,$item_array);
    }

    function initAttributes($value, $data)
    {
        // TODO: Implement initAttributes() method.
    }

    private function showNewsSlider()
    {
        $html='';
        $categs = $this->short_codes_tools->get_new_categories($this->options);
        unset($this->options['category_name']);
        $q_args=array();
        /** @var WP_Term $c */
        foreach($categs as $c)
        {
            $args = array(
                'post_type'=>"post",
                'category_name'=>$c,
                'posts_per_page'=>1,
                'post_status'=>'publish'
            );
            $args = array_merge($this->options,$args);

            $tmp_query_args = $this->short_codes_tools->news_get_query($args,false);
            $q_args[] = $tmp_query_args;
        }

        $args = array(
            'posts_per_page'=>count($categs),
            'sublimit'=>1,
            'args'=>$q_args
        );

        $query = new WP_Union_Query($args);

        $holder_classes = $this->getHolderClasses();
        $html.='<div '.qode_get_class_attribute($holder_classes).' '.$this->getNewsShortCodesHolderDataParams().' >';

        $html.=$this->renderQuery($query);

        $html.='</div>';


        return $html;
    }
    private function getHolderInnerClass()
    {
        $holder_inner_classes = array();
        $holder_inner_classes[] = 'qode-news-list-inner-holder';

        $holder_inner_classes[] = 'qode-slider1-owl';
        $holder_inner_classes[] = 'td-slider-owl';
        $holder_inner_classes[] = 'qode-owl-slider-style';

        return implode(' ', $holder_inner_classes);
    }

    private function getHolderClasses() {
        $holder_classes = array();
        $holder_classes[] = 'qode-news-holder';
        $holder_classes[] = $this->css_class;

        if (isset($this->options['extra_class_name']) && $this->options['extra_class_name'] !== '') {
            $holder_classes[] = $this->options['extra_class_name'];
        }

        if (isset($this->options['block_proportion']) && $this->options['block_proportion'] !== '') {
            $holder_classes[] = 'qode-news-block-pp-'.$this->options['block_proportion'];
        }

        if (isset($this->options['column_number']) && ! $this->isSlider()) {
            if ($this->options['column_number'] !== ''){
                $holder_classes[] = 'qode-news-columns-' . $this->options['column_number'];
            } else{
                $holder_classes[] = 'qode-news-columns-3';
            }
        }

        if (isset($this->options['space_between_items']) && $this->options['space_between_items'] !== ''){
            $holder_classes[] = 'qode-nl-'.$this->options['space_between_items'].'-space';
        }

        $classes = array_merge($holder_classes, $this->getAdditionalHolderClasses());

        return implode(' ', $classes);
    }


    private function isSlider()
    {
        return true;
    }

    protected function getHolderInnerData() {
        $holder_inner_data = array();
         $holder_inner_data[] = 'data-number-of-items="'.$this->options['column_number'].'"';

        return implode(' ', $holder_inner_data);
    }
    /**
     * @param WP_Query $query
     * @return string
     */
    private function renderQuery($query)
    {
        $html='';
        $post_count =0;
        $inner_classes = $this->getHolderInnerClass();
        $inner_data = $this->getHolderInnerData();
            if($query->have_posts())
            {
                $html.='<div '.qode_get_class_attribute($inner_classes).' '.$inner_data.' >';
                while($query->have_posts()):$query->the_post();
                    $post_count++;
                $this->options['post_number']=$post_count;
                $html.=$this->render();
                endwhile;
                $html .= '</div>';
            }
        wp_reset_postdata();
        return $html;

    }
    private function render()
    {
        $this->options['item_classes'] = $this->getClasses();
        $this->options['background_style'] = $this->getBackgroundStyle();
        $this->options['content_style'] = $this->getContentStyle();

        $this->options['item_data_params'] = $this->getItemDataParams();

        return $this->View('post_slider_template',array_merge(array('obj'=>$this),$this->options));
    }
    private function getBackgroundStyle(){
        $background_image = '';
        $image_size = 'full';
        $background_style = array();

        if ($this->options['image_size'] !== ''){
            $image_size = $this->options['image_size'];
        }

        $featured_image_meta = get_post_meta(get_the_ID(), 'qode_blog_list_featured_image_meta', true);

        if ($featured_image_meta !== ''){
            $background_image = $featured_image_meta;
        } else {
            $background_image = get_the_post_thumbnail_url(get_the_ID(),$image_size);
        }

        if(!empty($this->options['display_category_image']) && $this->options['display_category_image']=='yes' || !$background_image)
            $background_image = $this->getCategoryImage(get_the_ID());

        $background_style[] = 'background-image: url('.esc_url($background_image).')';

        return implode(';', $background_style);
    }

    private function getClasses(){
        $classes = array();

        if ($this->options['slider_size'] !== ''){
            $classes[] = 'qode-slider-size-'.$this->options['slider_size'];
        } else {
            $classes[] = 'qode-slider-size-landscape';
        }

        if($this->options['content_in_grid'] !== 'yes'){
            $classes[] = 'qode-slider1-item-wide';
        }

        return implode(' ', $classes);
    }

    private function getContentStyle(){
        $content_style = array();

        if ($this->options['content_padding'] !== ''){
            $content_style[] = 'padding: '.$this->options['content_padding'];
        }

        return implode(';', $content_style);
    }
    public function getItemDataParams() {


        $thumbnail = get_the_post_thumbnail_url(null,'thumbnail');
        if(!empty($this->options['display_category_image']) && $this->options['display_category_image']=='yes' || !$thumbnail)
        {
            $thumbnail = $this->getCategoryImage(get_the_ID());
            $thumbnail = preg_replace('/(\.jpg)$/','-70x70$1',$thumbnail);
        }

        $data = 'data-thumb-url="'.$thumbnail.'" ';
        $date_format = isset($date_format) && $date_format !== '' ? $date_format : 'published';
        $difference = human_time_diff( get_the_time('U'), current_time('timestamp') ) . esc_html__(' ago','qode-news');
        if ($date_format == 'published') {
            $date = get_the_time(get_option('date_format'));
        } else {
            $date = esc_html($difference);
        }

        $data .= 'data-date="'. $date .'"';
        return $data;
    }

    public function getNewsShortCodesHolderDataParams() {

        $data_params_string='';

        if($this->options['content_in_grid'] == 'yes'){
            $data_params_string .= ' data-content-in-grid=yes ';
        }

        if($this->options['content_padding'] !== ''){
            $data_params_string .= ' data-content-padding='.str_replace(" ", ",", $this->options['content_padding']).'';
        }

        return $data_params_string;

    }
}