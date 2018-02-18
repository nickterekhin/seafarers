<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 10.02.2018
 * Time: 23:40
 */

namespace TerekhinDevelopment\short_codes\src;


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

    protected function news_get_query($params, $return_query = true) {
        $params = shortcode_atts(
            array(
                'post_type' => 'post',
                'posts_per_page' => '-1',
                'category_name' => '',
                'author_id' => '',
                'tag' => '',
                'reaction' => '',
                'post_in' => '',
                'post_not_in' => '',
                'sort' => '',
                'order' => '',
                'offset' => '0',
                'paged' => '',
                'display_pagination' => 'no',
                'pagination_type' => '',
                'pagination_range_limit' => '',
                'post_status' => 'publish',
                'only_videos' => '',
                'next_page' => '1'
            ), $params);

        $query_array = array();


        $query_array['post_status'] = $params['post_status']; //to ensure that ajax call will not return 'private' posts

        if ($params['category_name'] !== '') {
            $query_array['category_name'] = $params['category_name'];
        }

        if ($params['author_id'] !== '') {
            $query_array['author_id'] = str_replace(' ', '', $params['author_id']); //because of the data and quotes spaces need to be erased
            $query_array['author'] = str_replace(' ', '', $params['author_id']); //because of the data and quotes spaces need to be erased
        }

        if (!empty($params['tag'])) {
            $query_array['tag'] = str_replace(' ', '', $params['tag']);
        }
        if (!empty($params['post_not_in'])) {

            $query_array['post__not_in'] = explode(",", $params['post_not_in']);
            if (!$return_query) {
                $query_array['post_not_in'] = str_replace(' ', '', $params['post_not_in']);
            }
        }
        if (!empty($params['post_in'])) {
            $query_array['post__in'] = explode(",", $params['post_in']);
            if (!$return_query) {
                $query_array['post_in'] = str_replace(' ', '', $params['post_in']);
            }
        }

        if ($params['only_videos'] == 'yes') {
            $query_array['tax_query'] = array(
                array(
                    'taxonomy' => 'post_format',
                    'field'    => 'slug',
                    'terms'    => array( 'post-format-video' ),
                )
            );

            if (!$return_query) {
                $query_array['only_videos'] = 'yes';
            }
        }

        $query_array['ignore_sticky_posts'] = '1';

        switch ($params['sort']) {
            case 'latest':
                $query_array['orderby'] = 'date';
                break;

            case 'random':
                $query_array['orderby'] = 'rand';
                break;

            case 'random_today':
                $query_array['orderby'] = 'rand';
                $query_array['year'] = date('Y');
                $query_array['monthnum'] = date('n');
                $query_array['day'] = date('j');
                break;

            case 'random_seven_days':
                $query_array['date_query'] = array(
                    'column' => 'post_date_gmt',
                    'after' => '1 week ago'
                );
                $query_array['orderby'] = 'rand';
                break;

            case 'comments':
                $query_array['orderby'] = 'comment_count';
                $query_array['order'] = 'DESC';
                break;

            case 'title':
                $query_array['orderby'] = 'title';
                break;

            case 'popular':
                $query_array['meta_key'] = 'qode_count_post_views_meta';
                $query_array['orderby'] = 'meta_value_num';
                $query_array['order'] = 'DESC';
                break;

            case 'featured_first':
                //to get posts by featured, and afterwards when featured is not set
                $query_array['meta_query'] = array(
                    'relation' => 'OR',
                    'featured' => array(
                        'key' => 'qode_news_post_featured_meta',
                        'value' => 'a',
                        'compare' => '>'
                    ),
                    'rest' => array(
                        'key' => 'qode_news_post_featured_meta',
                        'value' => 'exists',
                        'compare' => 'NOT EXISTS'
                    )
                );

                $query_array['order'] = 'DESC';
                $query_array['orderby'] = 'meta_value date';

                if (!$return_query) {
                    $query_array['orderby'] = 'meta_value,date';
                }
                break;

            case 'trending_first':
                //to get posts by trending, and afterwards when trending is not set
                $query_array['meta_query'] = array(
                    'relation' => 'OR',
                    'trending' => array(
                        'key' => 'qode_news_post_trending_meta',
                        'value' => 'a',
                        'compare' => '>'
                    ),
                    'rest' => array(
                        'key' => 'qode_news_post_trending_meta',
                        'value' => 'exists',
                        'compare' => 'NOT EXISTS'
                    )
                );

                $query_array['order'] = 'DESC';
                $query_array['orderby'] = 'meta_value date';

                if (!$return_query) {
                    $query_array['orderby'] = 'meta_value,date';
                }
                break;

            case 'hot_first':
                //to get posts by hot, and afterwards when hot is not set
                $query_array['meta_query'] = array(
                    'relation' => 'OR',
                    'hot' => array(
                        'key' => 'qode_news_post_hot_meta',
                        'value' => 'a',
                        'compare' => '>'
                    ),
                    'rest' => array(
                        'key' => 'qode_news_post_hot_meta',
                        'value' => 'exists',
                        'compare' => 'NOT EXISTS'
                    )
                );

                $query_array['order'] = 'DESC';
                $query_array['orderby'] = 'meta_value date';

                if (!$return_query) {
                    $query_array['orderby'] = 'meta_value,date';
                }
                break;

            case 'reactions':
                if ($params['reaction'] !== ''){

                    if (!$return_query) {
                        $query_array['reaction'] = $params['reaction'];
                    }

                    //to get posts by featured, and afterwards when featured is not set
                    $query_array['meta_query'] = array(
                        'relation' => 'OR',
                        'featured' => array(
                            'key' => 'qode_news_reaction_'.$params['reaction'],
                            'value' => '-1',
                            'compare' => '>',
                            'type' => 'NUMERIC'
                        ),
                        'rest' => array(
                            'key' => 'qode_news_reaction_'.$params['reaction'],
                            'value' => 'exists',
                            'compare' => 'NOT EXISTS'
                        )
                    );

                    $query_array['order'] = 'DESC';
                    $query_array['orderby'] = 'meta_value date';

                    if (!$return_query) {
                        $query_array['orderby'] = 'meta_value,date';
                    }
                } else {
                    $query_array['order'] = 'DESC';
                    $query_array['orderby'] = 'date';
                }
                break;
        }

        $query_array['posts_per_page'] = $params['posts_per_page'];

        if (!empty($params['order'])) {
            $query_array['order'] = $params['order'];
        }

        if (!$return_query) {
            $query_array['sort'] = $params['sort'];
        }

        if ( ! empty( $params['next_page'] ) ) {
            $query_array['paged'] = $params['next_page'];
        } else {
            $query_array['paged'] = 1;
        }

        if (!empty($params['offset'])) {
            if ($query_array['paged'] > 1) {
                $query_array['offset'] = $params['offset'] + (($params['paged'] - 1) * $params['posts_per_page']);
            } else {
                $query_array['offset'] = $params['offset'];
            }
        }

        $list_query = new WP_Query($query_array);

        if (!empty($params['offset']) && $params['offset'] > '0') {
            $list_query->max_num_pages = ceil(($list_query->found_posts - $params['offset']) / $params['posts_per_page']);
        }

        $query_array['max_num_pages'] = $list_query->max_num_pages;

        if ($return_query) {
            return $list_query;
        } else {
            return $query_array;
        }
    }
    protected function news_get_holder_data_params($params) {
        $data_string = '';

        $query_result = $this->news_get_query($params, false);

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

        $query_result['layout'] = $this->base;

        foreach ( $query_result  as $key => $value ) {
            if ( $key !== 'query_result' && $value !== '' && !is_array($value)) {
                $new_key = str_replace( '_', '-', $key );

                $data_string .= ' data-' . $new_key . '=' . esc_attr( $value );
            }
        }

        return $data_string;
    }
}