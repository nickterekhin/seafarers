<?php
namespace TerekhinDevelopment\framework;

use WP_Post;
use WP_Post_Type;
use WP_Query;
use WP_Term;
include(CHILD_THEME_PATH.'/libs/simple_html_dom.php');
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
            if($obj->taxonomy!='category' && $obj->taxonomy=='post_tag')
                $obj->slug = 'tag/'.$obj->slug;

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
    function showHeader($text,$header_num,$align='left',$css_class='')
    {
        $attr = array(
            'font_container'=>'tag:h'.$header_num.'|text_align:'.$align,
            'use_theme_fonts'=>'yes',
            'text'=>$text,
            'el_class'=>$css_class
        );
            echo qode_execute_shortcode('vc_custom_heading',$attr);
    }

    function showQ2Button($slug,$title='Еще новости')
    {
        $args = array(
            'target'=>"_self",
            'icon_pack'=>'font_awesome',
            'icon'=>"fa-newspaper-o",
            'hover_effect'=>'icon_rotate',
            'gradient'=>'no',
            'text'=>$title,
            'link'=>'/'.$slug,
            'icon_border_color'=>"#ffffff",
            'icon_border_hover_color'=>'#ffffff',
            'icon_background_color'=>'#085e89',
            'icon_background_hover_color'=>"#a6d430",
            'custom_class'=>'td-all-news-btn'

        );
        return qode_execute_shortcode('qode_button_v2',$args);

    }
    function showSeparator($margin_top='20',$margin_bottom='20',$color='#a9a9a9',$type='normal')
    {
        $attr= array(
            'type'=>$type,
            'color'=>$color,
            'up'=>$margin_top,
            'down'=>$margin_bottom,
            'el_class'=>'td-news-simple-separator'
        );
        return qode_execute_shortcode('vc_separator',$attr);
    }

    function showSeparatorWithText($text,$title_align=null)
    {

        $title_align = $title_align?'title_align="'.$title_align.'"':'';
        echo do_shortcode('[vc_separator type="transparent" up="20" down="20"][vc_text_separator title="'.$text.'" i_icon_monosocial="vc-mono vc-mono-star" css_animation="fadeInLeft" border="no" el_class="td-news-separator" '.$title_align.'][vc_separator type="transparent" up="20" down="20"]');
    }


    function show_most_comments_in_section($obj,$title='Комментируют в разделе')
    {
        global $wp_query;

        $args = array(
            'sort'=>'comments',
            'layout_title'=>$title,
            'posts_per_page'=>9
        );
        if($obj && $obj->taxonomy=='category') {
            $args['category_name'] = $obj->slug;
        }else if($obj && $obj->taxonomy=='post_tag')
        {
            $args['tag']=$obj->slug;
            $args['single']=array( 'display_categories' => 'yes');
        }

        if (isset($wp_query->query_vars['year']) && isset($wp_query->query_vars['monthnum']) && $wp_query->query_vars['year'] && $wp_query->query_vars['monthnum']) {
            $args['year'] = $wp_query->query_vars['year'];
            $args['monthnum'] = $wp_query->query_vars['monthnum'];
            $args['single']=array( 'display_categories' => 'yes');
        }

        $this->show_grid_post($args,'layout2-news-vertical');
    }


    function show_news_in_section($obj,$title=null,$attr)
    {
        global $wp_query;

        $args = array(
            'sort'=>'featured_first',
            'posts_per_page'=>6,
            'layout_title'=>$title,
            'title_align'=>'separator_align_left',
            'column_number' =>1,
            'image_size'=>'thumbnail',
            'news_period'=>null,
            'layout_view'=>'layout2'
        );

        $args = wp_parse_args($attr,$args);

        if($obj && $obj->taxonomy=='category') {
            $args['category_name'] = $obj->slug;
        }else if($obj && $obj->taxonomy=='post_tag')
        {
            $args['tag']=$obj->slug;
            //$args['single']=array( 'display_categories' => 'yes');
            $args['display_categories']= 'yes';
        }

        if (isset($wp_query->query_vars['year']) && isset($wp_query->query_vars['monthnum']) && $wp_query->query_vars['year'] && $wp_query->query_vars['monthnum']) {
            $args['year'] = $wp_query->query_vars['year'];
            $args['monthnum'] = $wp_query->query_vars['monthnum'];
            //$args['single']=array( 'display_categories' => 'yes');
            $args['display_categories']= 'yes';
            unset($args['news_period']);
        }



        echo qode_execute_shortcode('td_news_'.$args['layout_view'],$args,null);
        //$this->show_grid_post($args,'layout2-news-vertical');
    }
    function show_news_in_section_by_category($obj,$title,$category_slug,$attr=array())
    {
        global $wp_query;

        if($obj && $obj->slug==$category_slug)
            return;

        $args = array(
            'sort'=>'latest',
            'layout_title'=>$title,
            'title_align'=>'separator_align_left',
            'posts_per_page'=>6,
            'layout_view'=>'layout2',
            'image_size'=>'thumbnail'
        );
        $attr['read_more_button_slug']=$category_slug;
        $args = wp_parse_args($attr,$args);
        $tax = get_term_by('slug', $category_slug, 'category');
        if ($tax) {
            if($tax->slug=='videos')
                $args['only_videos']='yes';

            if ($obj) {
                $args['tax_query'] = array(
                    'relation' => 'OR'
                );
                if($obj->taxonomy!='category')
                {
                    $args['tax_query']['relation']='AND';
                    $args['tax_query'][]=
                        array(
                            'taxonomy' => $obj->taxonomy,
                            'field' => 'slug',
                            'terms' => array($obj->slug),
                        );
                    //$args['single']=array( 'display_categories' => 'yes');
                    $args['display_categories']='yes';
                }else {
                 $args['tax_query'][]=
                        array(
                            'taxonomy' => 'category',
                            'field' => 'slug',
                            'terms' => array($obj->slug, $tax->slug),
                            'operator' => 'AND'
                        );

                }


            } else if (isset($wp_query->query_vars['year']) && isset($wp_query->query_vars['monthnum']) && $wp_query->query_vars['year']!=0 && $wp_query->query_vars['monthnum']!=0) {
                $args['year'] = $wp_query->query_vars['year'];
                $args['monthnum'] = $wp_query->query_vars['monthnum'];
                //$args['single']=array( 'display_categories' => 'yes');
                $args['display_categories']='yes';
            }
            $args['tax_query'][]= array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $tax->slug
                    );
        }
        echo qode_execute_shortcode('td_news_'.$args['layout_view'],$args);
        //$this->show_grid_post($args);
    }

    /**
     * @param $category_slug
     * @param $title
     * @param string $sort_type
     * @param int $posts_qty
     * @param string $layout
     * @param string $width
     * @param string $height
     * @internal param WP_Post $post
     */
    public function show_news_in_single_post($category_slug,$title,$sort_type='latest',$posts_qty=6,$layout='layout2-news',$width='70px',$height='70px')
    {
        $args = array(
            'sort'=>$sort_type,
            'layout_title'=>$title,
            'posts_per_page'=>$posts_qty,
            'category_name'=>$category_slug
        );
        $args['single']=array('custom_image_height' => $height,
            'custom_image_width' => $width);
        $this->show_grid_post($args,$layout);
    }

    /**
     * @param $params
     * @param string $view_name
     */
    private function show_grid_post($params,$view_name='layout2-news')
    {

            $params = wp_parse_args($params,array('columns_number'=>1,'title_align'=>'separator_align_left'));

            add_filter('posts_where',array($this,'custom_where_filter_posts'));
            $posts_query = $this->tools->get_post_query($params);
            remove_filter('posts_where',array($this,'custom_where_filter_posts'));
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
    function custom_where_filter_posts($where)
    {
        $today = date("Y-m-d",time());
        $where .= " AND post_date >= '2017-01-01' AND post_date <= '".$today."'";
        return $where;
    }
    function set_post_image_url_value_by_category($output)
    {
        global $post;
        return $output;
    }
    function set_post_image_css_value_from_category($output)
    {
        global $post;
            if(preg_match('/url\(\'(.*?vc_gitem_image.png)\'\)/',$output,$m)==1) {

                $cat = wp_get_post_terms($post->ID, 'category');
                $img = get_field('header_image', 'category_' . $cat[0]->term_id);
                if ($img) {
                    $output = 'background-image: url(\'' . $img . '\') !important';
                }
            }

        return $output;
    }
    function about_page_content($content)
    {

        global $post;

        if($post && $post->ID==30631) {



            $content = $this->set_counter($content,1,"Морские новости",'marine-news');
            $content = $this->set_counter($content,2,"Происшествия",'incidents');
            $content = $this->set_counter($content,3,"Финансы",'money');
            $content = $this->set_counter($content,4,"Общесвто",'sociaty');
        }

        return $content;
    }

    private function set_counter($content,$counter_id,$text,$section_slug)
    {
        $attr = array(
            'type' => 'zero',
            'box' => 'no',
            'position' => 'center',
            'font_color'=>'#fff',
            'text_font-weight' => '500',
            'text_transform' => 'uppercase',
            'separator' => 'yes',
            'digit' => $this->get_news_quantity_in_section($section_slug),
            'font_size' => 28,
            'text' => $text,
            'text_size'=>14,
            'text_color'=>'#fff',
            'separator_color'=>'#fff'
        );

        $counter = qode_execute_shortcode('counter', $attr);
        $counter = preg_replace('/class="(.*?)"/','class="$1 td-counter-holder"',$counter);
        return preg_replace('/(el_id="counter_'.$counter_id.'"\])/','$1'.$counter,$content);
    }
    public function get_news_quantity_in_section($section_slug)
    {
        $sql = $this->db->prepare("SELECT COUNT(p.ID) as qty FROM wp_posts p
INNER JOIN wp_term_relationships r ON p.ID = r.object_id
INNER JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = r.term_taxonomy_id AND tt.taxonomy = 'category'
INNER JOIN wp_terms t ON tt.term_id = t.term_id
WHERE t.slug = %s AND p.post_type='post' AND p.post_status='publish'",$section_slug);
        $res = $this->db->get_row($sql);
        return $res->qty;
    }

    public function format_page_title($title)
    {
        global $wp_query;

        $obj = $wp_query->get_queried_object();
        if($obj && $obj instanceof WP_Post_Type && $obj->name=='tribe_events')
        {
            $title = 'Мероприятия - Seafarers Journal';
        }
        else if($obj && $obj->taxonomy=='post_tag')
        {
            $title = preg_replace('/tag/i','',$title);
            $title = preg_replace('/(^\s+|\s+$)/','',$title);

        }
        return $title;
    }

    public function getMonth($n,$rus=true)
    {
        $ruMonths = array( 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь' );
        $enMonths = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
        if($rus)
        return $ruMonths[$n-1];

        return $enMonths[$n-1];
    }

    public function search_bar_where_filter($where)
    {
        global $wp_query;
        $obj = $wp_query->get_queried_object();

        if((isset($_GET['date_year']) && !empty($_GET['date_year'])) || (isset($_GET['date_month']) && !empty($_GET['date_month'])) || (isset($_GET['date_day']) && !empty($_GET['date_day']))) {

            $where .= ' AND (';
            $where_date = array();
            if(isset($_GET['date_year']))
                $where_date[]=$this->db->prepare('YEAR( wp_posts.post_date ) = %d',$_GET['date_year']);
            if(isset($_GET['date_month']))
                $where_date[]=$this->db->prepare('MONTH( wp_posts.post_date ) = %d',$_GET['date_month']);
            if(isset($_GET['date_day']))
                $where_date[]=$this->db->prepare('DAYOFMONTH( wp_posts.post_date ) = %d',$_GET['date_day']);

            $where.= implode(' AND ',$where_date).')';

        }

        if(isset($_REQUEST['filter_search']) && !empty($_REQUEST['filter_search']))
        {
            $where .=" AND (";
            $where.=" ".$this->db->prefix."posts.post_title REGEXP '[[:<:]]".sanitize_text_field($_REQUEST['filter_search'])."[[:>:]]' OR ";
            if($obj && $obj->taxonomy=='category') {
                $where .= " pm_tag.tag_name REGEXP '[[:<:]]" . sanitize_text_field($_REQUEST['filter_search']) . "[[:>:]]' OR ";
            }
            $where.=" ".$this->db->prefix."posts.post_excerpt REGEXP '[[:<:]]".sanitize_text_field($_REQUEST['filter_search'])."[[:>:]]' )";

        }

        return $where;
    }

    public function add_search_params_to_pagination($result)
    {

        $result = $this->setPaginationParam($_REQUEST,'filter_search',$result);
        /*$result = $this->setPaginationParam($_REQUEST,'date_year',$result);
        $result = $this->setPaginationParam($_REQUEST,'date_month',$result);
        $result = $this->setPaginationParam($_REQUEST,'date_day',$result);*/

        return $result;
    }

    private function setPaginationParam($param_method,$query_string,$result)
    {
        if((isset($param_method[$query_string]) && !empty($param_method[$query_string])) && preg_match('/'.$query_string.'=/',$result,$m)==0)
        {
            if(preg_match('/\?/',$result,$m)==1)
            {
                $result.='&'.$query_string.'='.$param_method[$query_string];
            }else
            {
                $result.='?'.$query_string.'='.$param_method[$query_string];
            }
        }
        return $result;
    }

    public function search_bar_join_post_tag_filter($join)
    {
        if(isset($_REQUEST['filter_search']) && !empty($_REQUEST['filter_search']))
        {
            $sql_tags = $this->get_sql_by_taxonomy('post_tag');
            $join .=" LEFT JOIN (".$sql_tags.") pm_tag ON (".$this->db->prefix."posts.ID = pm_tag.object_id)";
        }
        return $join;
    }

    public function get_post_featured_image($post_id,&$is_image_category=false)
    {

        $image_category = null;
        $args = get_the_terms($post_id,'category');
        if($args && count($args)>0) {
            if(count($args)>1) {
                $args = array_filter($args, function ($e) {
                    return $e->slug != 'opinions' && $e->slug != 'videos';
                });
                $args = array_values($args);
            }
            $image_category = $this->getImageTitle($args[0]->taxonomy, $args[0]->term_id);
        }

        $image = get_the_post_thumbnail_url($post_id);
        if($image) {
            $image_url_obj = parse_url($image);
            if (file_exists($_SERVER['DOCUMENT_ROOT'].$image_url_obj['path'])) {
                list($image_width, $image_height, $image_type, $image_attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $image_url_obj['path']);

                if ($image_width < 1920 || $image_height < 640)
                {
                    $image = $image_category;
                    $is_image_category=true;
                }
            }

        }

        return $image;
    }

    public function set_post_views($postID)
    {
        $views_key = 'qode_count_post_views_meta';

        $count = get_post_meta($postID,$views_key,true);
        if($count==''){
            $count=0;
            delete_post_meta($postID,$views_key);
            add_post_meta($postID,$views_key,$count);
        }
        else
        {
            $count++;
            update_post_meta($postID,$views_key,$count);
        }
    }
    function get_post_typeicon($post_id)
    {

        $_post_format = get_post_format($post_id);
        $post_types = '';
        $trending_news = get_post_meta($post_id,"qode_news_post_trending_meta",true);
        if($trending_news && $trending_news=='yes')
            $post_types .= '<i class="fa fa-star" title="Актуальная новость"></i>';

        $featured_news = get_post_meta($post_id,"qode_news_post_featured_meta",true);
        if($featured_news && $featured_news=='yes')
            $post_types .= '<i class="fa fa-anchor" title="Главная Новость"></i>';

        $hot_news = get_post_meta($post_id,"qode_news_post_hot_meta",true);
        if($hot_news && $hot_news=='yes')
            $post_types .= '<i class="fa fa-bolt" title="Топовая Новость"></i>';

        if($_post_format=='video')
            $post_types .= '<i class="fa fa-video-camera" title="Видео новость"></i>';

        return $post_types;
    }
    function save_news_types($post_id,$post,$update)
    {
        $post_type = get_post_type($post_id);
        var_dump($post_type);

        if($post_type!='post') return;

            $this->save_post_meta_type($post_id,'qode_news_post_featured_meta');
        var_dump($_POST);
        //exit;
    }

    private function save_post_meta_type($post_id,$meta_value)
    {
        if ( isset( $_POST[ $meta_value ] ) && trim( $_POST[ $meta_value ] !== '') ) {

            $value = $_POST[ $meta_value ];
            var_dump($value);
            // Auto-paragraphs for any WYSIWYG
            update_post_meta( $post_id, $meta_value, $value );
        } else {
            delete_post_meta( $post_id, $meta_value );
        }
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
