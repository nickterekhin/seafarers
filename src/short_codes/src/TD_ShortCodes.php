<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 10.02.2018
 * Time: 23:40
 */

namespace TerekhinDevelopment\short_codes\src;


use TerekhinDevelopment\short_codes\helpers\TD_Short_Codes_AutoComplete_Tools;
use TerekhinDevelopment\short_codes\helpers\TD_Short_Codes_Tools;
use WP_Query;

abstract class TD_ShortCodes
{
    protected $sc_category = "TD News";
    protected $short_code_title;
    protected $sc_name;
    protected $options = array();
    protected $base;
	protected $css_class;
    /**
     * @var TD_Short_Codes_Tools
     */
    protected $short_codes_tools;

    /**
     * TD_ShortCodes constructor.
     * @param $sc_name
     */
    public function __construct($sc_name)
    {
        $this->sc_name = $sc_name;
        add_shortcode($sc_name,array($this,'renderShortCode'));
        TD_Short_Codes_AutoComplete_Tools::getInstance($this->sc_name);
        $this->short_codes_tools = TD_Short_Codes_Tools::getInstance();
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

        if(function_exists('vc_map')) {
            vc_map(array(
                "name" => $this->short_code_title,
                "base" => $this->sc_name,
                "category" => $this->sc_category,
                "icon" => "dashicons dashicons-image-rotate",
                "allowed_container_element" => 'vc_row',
                "params" => $this->initShortCode()
            ) );
        }
    }

    function View($viewName, array $params=array())
    {

        if(is_array($params) && count($params)) {
            extract($params);
        }
        $file = CHILD_THEME_PATH . '/src/short_codes/view/'. $viewName . '.php';

        ob_start();
        include( $file );
        $ret_obj= ob_get_clean();


        return $ret_obj;
    }

    protected function getCategoryImage($postId)
    {

        $terms = get_the_terms($postId,'category');
        $background_image = '';
        if(count($terms)>0 && function_exists('get_field'))
            $background_image = get_field('header_image','category_'.$terms[0]->term_id,true);

        return $background_image;
    }

    protected function getAdditionalHolderClasses(){
        $additional_classes = array();

        return $additional_classes;
    }


    protected function news_get_holder_data_params($params) {
        $data_string = '';

        $query_result = $this->short_codes_tools->news_get_query($params, false);

        if ( ! empty( $query_result['paged'] ) ) {
            $query_result['next-page'] = $query_result['paged'] + 1;
        }

        if ( ! empty( $params['title_tag'] ) ) {
            $query_result['title_tag'] = $params['title_tag'];
        }

        if ( ! empty( $params['image_size'] ) ) {
            $query_result['image_size'] = $params['image_size'];
        }

        if ( ! empty( $params['custom_image_width'] ) ) {
            $query_result['custom_image_width'] = $params['custom_image_width'];
        }

        if ( ! empty( $params['custom_image_height'] ) ) {
            $query_result['custom_image_height'] = $params['custom_image_height'];
        }

        if ( ! empty( $params['display_categories'] ) ) {
            $query_result['display_categories'] = $params['display_categories'];
        }

        if ( ! empty( $params['display_excerpt'] ) ) {
            $query_result['display_excerpt'] = $params['display_excerpt'];
        }

        if ( ! empty( $params['excerpt_length'] ) ) {
            $query_result['excerpt_length'] = $params['excerpt_length'];
        }

        if ( ! empty( $params['display_date'] ) ) {
            $query_result['display_date'] = $params['display_date'];
        }

        if ( ! empty( $params['date_format'] ) ) {
            $query_result['date_format'] = $params['date_format'];
        }

        if ( ! empty( $params['display_author'] ) ) {
            $query_result['display_author'] = $params['display_author'];
        }

        if ( ! empty( $params['display_views'] ) ) {
            $query_result['display_views'] = $params['display_views'];
        }

        if ( ! empty( $params['display_share'] ) ) {
            $query_result['display_share'] = $params['display_share'];
        }

        if ( ! empty( $params['display_hot_trending_icons'] ) ) {
            $query_result['display_hot_trending_icons'] = $params['display_hot_trending_icons'];
        }

        if ( ! empty( $params['display_review'] ) ) {
            $query_result['display_review'] = $params['display_review'];
        }

        if ( ! empty( $params['featured_title_tag'] ) ) {
            $query_result['featured_title_tag'] = $params['featured_title_tag'];
        }

        if ( ! empty( $params['featured_image_size'] ) ) {
            $query_result['featured_image_size'] = $params['featured_image_size'];
        }

        if ( ! empty( $params['featured_display_categories'] ) ) {
            $query_result['featured_display_categories'] = $params['featured_display_categories'];
        }

        if ( ! empty( $params['featured_display_excerpt'] ) ) {
            $query_result['featured_display_excerpt'] = $params['featured_display_excerpt'];
        }

        if ( ! empty( $params['featured_excerpt_length'] ) ) {
            $query_result['featured_excerpt_length'] = $params['featured_excerpt_length'];
        }

        if ( ! empty( $params['featured_display_date'] ) ) {
            $query_result['featured_display_date'] = $params['featured_display_date'];
        }

        if ( ! empty( $params['featured_date_format'] ) ) {
            $query_result['featured_date_format'] = $params['featured_date_format'];
        }

        if ( ! empty( $params['featured_display_author'] ) ) {
            $query_result['featured_display_author'] = $params['featured_display_author'];
        }

        if ( ! empty( $params['featured_display_views'] ) ) {
            $query_result['featured_display_views'] = $params['featured_display_views'];
        }

        if ( ! empty( $params['featured_display_share'] ) ) {
            $query_result['featured_display_share'] = $params['featured_display_share'];
        }

        if ( ! empty( $params['featured_display_hot_trending_icons'] ) ) {
            $query_result['featured_display_hot_trending_icons'] = $params['featured_display_hot_trending_icons'];
        }

        if ( ! empty( $params['pagination_type'] ) && ( $params['pagination_type'] == 'standard') ) {
            if ( ! empty( $params['pagination_numbers_amount'] ) ) {
                $query_result['pagination_numbers_amount'] = $params['pagination_numbers_amount'];
            } else {
                $query_result['pagination_numbers_amount'] = 3;
            }
        }

        $query_result['layout'] = $this->sc_name;

        foreach ( $query_result  as $key => $value ) {
            if ( $key !== 'query_result' && $value !== '' && !is_array($value)) {
                $new_key = str_replace( '_', '-', $key );

                $data_string .= ' data-' . $new_key . '=' . esc_attr( $value );
            }
        }

        return $data_string;
    }


}