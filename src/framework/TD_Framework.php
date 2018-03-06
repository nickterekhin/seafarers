<?php
namespace TerekhinDevelopment\framework;

use WP_Post;
use WP_Term;

class TD_Framework extends TD_Framework_Base
{
    private static $instance;

    protected function __construct()
    {
        parent::__construct(); // TODO: Change the autogenerated stub
    }


    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getImageTitle($taxonomy,$term_id)
    {
        return get_field('header_image', $taxonomy . '_' . $term_id);
    }

    public function translate_read_more($array)
    {
        $array['text'] = esc_html__('Читать далее', 'qode-news');
        return $array;
    }
    public function add_socials()
    {
        new \WP_Query()
        ?>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

        <?php
    }

    function getFormAction($obj)
    {
        if($obj)
        {
            $filter_value = '';
            if(isset($_GET['date-filter']) && !empty($_GET['date-filter']))
            {
                $filter_value = '?date-filter='.$_GET['date-filter'];
            }
            return home_url($obj->slug).$filter_value;
        }
        return get_home_url();
    }
    //show news int category
    function getPopularInSection($category_slug)
    {

    }

    /**
     * @param $date
     * @param $format
     * @param WP_Post $post
     * @return mixed
     */
    function format_post_date_masonry($date,$format,$post)
    {

        return $date;
    }
    function showSeparator($text,$title_align=null)
    {

        $title_align = $title_align?'title_align="'.$title_align.'"':'';
        echo do_shortcode('[vc_separator type="transparent" up="20" down="20"][vc_text_separator title="'.$text.'" i_icon_monosocial="vc-mono vc-mono-star" css_animation="fadeInLeft" border="no" el_class="td-news-separator" '.$title_align.'][vc_separator type="transparent" up="20" down="20"]');
    }

    /**
     * @param WP_Term $obj
     * @param string $title
     */
    function show_hot_news_in_section($obj,$title="Горячие новости")
    {
        $args = array(
            'sort'=>'hot_first',
            'layout_title'=>$title,
            'posts_per_page'=>6,
            'category_name'=>$obj->slug,
        );
        $this->show_grid_post($args);

    }

    function show_popular_news_in_section($obj,$title=null,$title_align='separator_align_left')
    {
        $args = array(
            'sort'=>'popular',
            'posts_per_page'=>6,
            'layout_title'=>$title,
            'title_align'=>$title_align,
            'category_name'=>$obj->slug,
            'columns_number' =>3
        );

        $this->show_grid_post($args,'layout2-news-vertical');
    }
    function show_post_in_section($obj,$title,$category_slug,$title_align='separator_align_left')
    {
        $args = array(
            'sort'=>'latest',
            'layout_title'=>$title,
            'title_align'=>$title_align,
            'posts_per_page'=>6
        );
        $tax = get_term_by('slug',$category_slug,'category');
        if($tax) {
            $args['tax_query'] = array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => array($obj->slug, $tax->slug),
                    'operator' => 'AND'
                ),
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => $tax->slug
                )
            );
        }
        $this->show_grid_post($args);
    }

    /**
     * @param $params
     * @param string $view_name
     */
    private function show_grid_post($params,$view_name='layout2-news')
    {

            $params = wp_parse_args($params,array('columns_number'=>1,'title_align'=>'separator_align_left'));

            $posts_query = $this->tools->get_post_query($params);

            $posts = $posts_query->posts;

            $params['class'] = $this;
            $params['posts'] = $posts;
            $params['single'] = wp_parse_args(isset($params['single'])?$params['single']:array(),array(
                'post' => null,
                'image_size' => 'custom',
                'custom_image_height' => '70px',
                'custom_image_width' => '70px',
                'display_categories' => 'no',
                'title_tag' => 'h5',
                'display_excerpt' => 'no',
                'display_author' => 'no',
            ));

            echo $this->View($view_name, $params);
    }
    function set_post_image_url_value_by_category($output)
    {
        global $post;
        //var_dump($posFt->ID);
        return $output;
    }
    function set_post_image_css_value_from_category($output)
    {
        global $post;

        if(is_front_page())
        {

            if(preg_match('/url(\'(.*?vc_gitem_image_\.png\')/',$output,$m)==1) {

                $cat = wp_get_post_terms($post->ID, 'category');
                $img = get_field('header_image', 'category_' . $cat[0]->term_id);
                if ($img) {
                    $output = 'background-image: url(\'' . $img . '\') !important';
                }
            }
        }
        return $output;
    }
}

function init_framework()
{
    global $terekhin_framework;
    if(!$terekhin_framework)
        $terekhin_framework = TD_Framework::getInstance();

    return $terekhin_framework;
}
init_framework();
