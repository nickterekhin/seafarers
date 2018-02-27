<?php
namespace TerekhinDevelopment\framework;

use WP_Term;

class TD_Framework extends TD_Framework_Base
{
    private static $instance;

    private function __construct()
    {

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
    function format_post_date_masonry($date,$format,$post)
    {

        return $date;
    }
    function showSeparator($text,$obj)
    {
        global $shortcode_tags;

        echo do_shortcode('[vc_separator type="transparent" up="20" down="20"][vc_text_separator title="'.$text.'" i_icon_monosocial="vc-mono vc-mono-star" css_animation="fadeInLeft" border="no" el_class="td-news-separator"][vc_separator type="transparent" up="20" down="20"]');
    }

    /**
     * @param WP_Term $obj
     * @param $title
     * @param $category_slug
     * @param string $post_type
     * @param int $post_per_pages
     * @return string
     */
    function show_grid_post($obj,$title,$category_slug,$post_type='post',$post_per_pages=6)
    {
        global $shortcode_tags;

        /** @var WP_Term $tax */
        $tax = get_term_by('slug',$category_slug,'category');
            $argv = array(
                'post_type'=>$post_type,
                'posts_per_page'=>$post_per_pages,
                'oderby'=>'date',
                'order'=>'DESC',
                'tax_query'=>array(
                    'relation'=>'OR',
                    array(
                        'taxonomy'=>'category',
                        'field'=>'slug',
                        'terms'=>array($obj->slug,$tax->slug),
                        'operator'=>'AND'
                    ),
                    array(
                        'taxonomy'=>'category',
                        'field'=>'slug',
                        'terms'=>$tax->slug
                    )
                )
            );

        $posts = (new \WP_Query($argv))->posts;

         wp_reset_postdata();
        $params= array();
        $params['obj']=$this;
        $params['posts']=$posts;
        $params['layout_title']=$title;
        $params['single']=array(
            'post'=>null,
            'image_size'=>'custom',
            'custom_image_height'=>'70px',
            'custom_image_width'=>'70px',
            'display_categories'=>'no',
            'title_tag'=>'h5',
            'display_excerpt'=>'no',
            'display_author'=>'no'

        );


        echo $this->View('layout2-news',$params);

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
