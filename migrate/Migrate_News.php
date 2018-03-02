<?php
require_once(ABSPATH.'/wp-admin/includes/taxonomy.php');
require_once (CHILD_THEME_PATH.'/migrate/Migrate_Base.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Migrate_News extends Migrate_Base
{
    private static $instance;

    private $image_folder;
    private $qty = 1000;

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Migrate_News constructor.
     */
    protected function __construct()
    {
        parent::__construct();
    }

    function setPathToImages($path)
    {
        if(file_exists($path)) {
            $this->image_folder = $path;
        }else
        {
            echo 'file or folder does not exists';
        }

    }

    function show()
    {
        var_dump(CHILD_THEME_PATH);


    }

    function addPostPaged($qty=-1)
    {
        if($qty>=0)
            $this->addPost($qty);
    }

   function addPost($qty=0)
    {

        $sql = $this->alien_db_service->Query("SELECT n.*, t.slug, u.username
FROM news n
INNEr JOIN topics t ON n.topic_id = t.id
INNER JOIN users u ON n.creator_id = u.id

WHERE (n.opinion ='' OR n.opinion = '0') AND n.is_video!=1 AND n.timestamp  > DATE_SUB(DATE_SUB(CURDATE(),INTERVAL DAY(CURDATE())-1 DAY), INTERVAL 2 MONTH) ORDER BY n.timestamp DESC LIMIT $qty,1000");
        $index = 0;
        while($res=$sql->FetchRow())
        {
            $arr_posts = array(

                'comment_status' => 'open',
                'ping_status'    => 'open',
                'post_author'    => $res->creator_id==1?$this->getPostAuthor('dmitriy@sj.com'):$this->getPostAuthor($res->username),
                'post_content'   => $res->text,
                'post_date'      => $res->timestamp,
                'post_date_gmt'  => $res->timestamp,
                'post_excerpt'   => $res->short_text,
                'post_name'      => $res->uri,
                'post_status'    => 'publish',
                'post_title'     => $res->title,
                'meta_input'     => array(
                    'keywords'=>$res->keywords,
                    'description'=>$res->description,
                    'qode_seo_keywords'=>$res->keywords,
                    'qode_seo_description'=>$res->description,
                    'qode_count_post_views_meta'=>$res->views
                )
            );

            $post_ID = wp_insert_post($arr_posts);
            if(!is_wp_error($post_ID)) {
                update_post_meta($post_ID, "keywords", $res->keywords);
                update_post_meta($post_ID, "description", $res->description);
                update_post_meta($post_ID, "qode_seo_keywords", $res->keywords);
                update_post_meta($post_ID, "qode_seo_description", $res->description);
                update_post_meta($post_ID, "qode_count_post_views_meta", $res->views);


                $tags = $this->getTags($res->id);
                $category = $this->getCategoryByName($res->slug);

                if ($category)
                    wp_set_post_categories($post_ID, $category);

                if ($tags)
                    wp_set_post_terms($post_ID, $tags);

                $this->addComment($post_ID, $res->id);
               if ($res->photo)
                    $this->addImageToPost($post_ID, $this->image_folder . '/' . $res->photo);
                $index += 1;
            }else
            {
                echo $post_ID->get_error_message();
            }
        }
        echo $index;
    }


    function migrate_categories()
    {
        $categs = $this->alien_db_service->Query("SELECT * FROM topics WHERE id>0");

        while($c = $categs->FetchRow())
        {
            $params = array(
                'cat_name'=>$c->topic,
                'category_description'=>$c->static_text,
                'taxonomy'=>'category',
                'category_nicename'=>$c->slug
            );
            if(!term_exists($c->topic,'category')){
                $category_id =wp_insert_category($params);
                if(is_wp_error($category_id))
                {

                    var_dump($category_id->get_error_message());
                }else
                {
                    var_dump($category_id);
                }
            }
        }
    }

    function migrate_tags()
    {
            $sql = $this->alien_db_service->Query("SELECT COUNT(t.id) as qty, t.tag,t.topic_id FROM tags t GROUP BY t.tag,t.topic_id
HAVING COUNT(t.id)=1");
        while($res = $sql->FetchRow()) {
            if (!term_exists($res->tag, 'post_tag')) {
                $term_id = wp_insert_term($res->tag,'post_tag');
                if(is_wp_error($term_id))
                {

                    var_dump($term_id->get_error_message());
                }else
                {
                    var_dump($term_id);
                }
            }

        }

    }
    function addVideoPaged($qty=-1)
    {
        $limit = ' LIMIT 1';
        if($qty>0) {
            $limit = ' LIMIT ' . $qty . ', 1000 ';
        }

        $sql = $this->alien_db_service->Query("SELECT
 n.uri,
n.title,
n.text,
n.short_text,
n.timestamp,
n.photo,
n.keywords,
n.description,
n.views,
 'videos',
v.source
FROM news n
INNER JOIN videos v ON n.id = v.news_id

WHERE n.is_video=1 ".$limit);
        $index=0;
        $added_posts = array();
        while($res=$sql->FetchRow())
        {
            if(preg_match('/http:\/\/www.(.*?)\/embed\/(.*?)\?+/',$res->source,$m)==1) {
                $arr_posts = array(

                    'comment_status' => 'open',
                    'ping_status' => 'open',
                    'post_author' => 1,
                    'post_content' => $res->text,
                    'post_date' => $res->timestamp,
                    'post_date_gmt' => $res->timestamp,
                    'post_excerpt' => $res->short_text,
                    'post_name' => $res->uri,
                    'post_status' => 'publish',
                    'post_title' => $res->title,
                    'meta_input' => array(
                        'keywords' => $res->keywords,
                        'description' => $res->description,
                        'qode_seo_keywords' => $res->keywords,
                        'qode_seo_description' => $res->description,
                        'qode_count_post_views_meta' => $res->views,
                        'video_format_choose' => $m[0],
                        'video_format_link' => $m[1]
                    )
                );

                $sql_wp = $this->db->prepare("SELECT * FROM ".$this->db->prefix."posts p WHERE p.post_title='%s'",$res->uri);
                $res_wp = $this->db->get_row($sql_wp);
                /** @var WP_Term $tax */
                $tax = get_term_by('slug','videos','category');
                $tax_video = get_term_by('slug','post-format-video','post_format');

                /** @var WP_Post $res_wp */
                if($res_wp)
                {
                    update_post_meta($res_wp->ID,'video_format_link',$m[2]);
                    update_post_meta($res_wp->ID,'video_format_choose',$m[1]);
                    update_post_meta($res_wp->ID,'qode_seo_keywords',$res->keywords);
                    update_post_meta($res_wp->ID,'qode_seo_description',$res->description);
                    update_post_meta($res_wp->ID,'qode_count_post_views_meta',$res->views);
                    wp_set_post_categories($res_wp->ID,array($tax->term_id,$tax_video->term_id),true);

                    $added_posts[]=$res_wp->ID;
                }else
                {

                    $post_ID = wp_insert_post($arr_posts);
                    if(!is_wp_error($post_ID)) {
                        update_post_meta($post_ID, "keywords", $res->keywords);
                        update_post_meta($post_ID, "description", $res->description);
                        update_post_meta($post_ID, "qode_seo_keywords", $res->keywords);
                        update_post_meta($post_ID, "qode_seo_description", $res->description);
                        update_post_meta($post_ID, "qode_count_post_views_meta", $res->views);
                        update_post_meta($post_ID,'video_format_link',$m[2]);
                        update_post_meta($post_ID,'video_format_choose',$m[1]);


                        $tags = $this->getTags($res->id);
                        $category = $this->getCategoryByName($res->slug);

                        if ($category)
                            wp_set_post_categories($post_ID, $category);

                        wp_set_post_categories($res->ID,array($tax->term_id,$tax_video->term_id),true);

                        if ($tags)
                            wp_set_post_terms($post_ID, $tags);

                        $this->addComment($post_ID, $res->id);
                        if ($res->photo)
                            $this->addImageToPost($post_ID, $this->image_folder . '/' . $res->photo);

                        $index += 1;
                        $added_posts[] = $post_ID;
                    }else
                    {
                        echo $post_ID->get_error_message();
                    }

                }
            }
        }
        echo $index.' ['.join(',',$added_posts).']';
    }

    public function routes()
    {
        try {
            $_REQUEST['a'] = (isset($_REQUEST['a']) && !empty($_REQUEST['a'])) ? $_REQUEST['a'] : 'show';
            switch ($_REQUEST['a']) {
                case 'show':
                    $this->show();
                    break;
                case 'categories':
                    $this->migrate_categories();
                    break;
                case 'tags':
                    $this->migrate_tags();
                    break;
                case 'migrate-post':
                    $this->addPostPaged(isset($_REQUEST['qty'])?$_REQUEST['qty']:-1);
                    break;
                case 'migrate-video':
                    $this->addVideoPaged(isset($_REQUEST['qty'])?$_REQUEST['qty']:-1);
                    break;
                case 'migrate-opinion':
                    $this->addOpinonPaged(isset($_REQUEST['qty'])?$_REQUEST['qty']:-1);
                    break;
                case 'set-post-as video':
                    break;

            }
        }catch(Exception $e)
        {
        echo $e->getMessage();
        }
    }


}