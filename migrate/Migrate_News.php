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

        $sql = $this->alien_db_service->Query("SELECT n.* FROM news n WHERE n.timestamp >= DATE_SUB(DATE_SUB(CURDATE(),INTERVAL DAY(CURDATE())-1 DAY), INTERVAL 1 MONTH) ORDER BY n.timestamp DESC;");
        while($res=$sql->FetchRow())
        {
            var_dump($res);
        }

    }


    function migrate_categories()
    {
        $categs = $this->alien_db_service->Query("SELECT * FROM topics WHERE id>0");

        while($c = $categs->FetchRow())
        {
            var_dump($c);
            $params = array(
                'cat_name'=>$c->topic,
                'category_description'=>$c->static_text,
                'taxonomy'=>'category',
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

    function migrate_news()
    {
        $res = $this->alien_db_service->RowQuery("SELECT * FROM news WHERE id=38 AND creator_id=1");


        $arr_posts = array(

            'comment_status' => 'open',
            'ping_status'    => 'closed',
            'post_author'    => 1,
            'post_content'   => $res->text,
            'post_date'      => $res->timestamp,
            'post_date_gmt'  => $res->timestamp,
            'post_excerpt'   => $res->short_text,
            'post_name'      => $res->uri,
            'post_status'    => 'publish',
            'post_title'     => $res->title,
            'post_type'      => 'place',
            'meta_input'     => array(
                'keywords'=>$res->keywords
            )
        );
        $post_ID = wp_insert_post($arr_posts);
        update_field("keywords",$res->keywords,$post_ID);

        wp_set_post_terms($post_ID, $categories, 'place_category', false);
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
    public function routes()
    {
        $_REQUEST['a'] = (isset($_REQUEST['a']) && !empty($_REQUEST['a'])) ? $_REQUEST['a'] : 'show';
        switch($_REQUEST['a']) {
            case 'show':
                $this->show();
                break;
            case 'categories':
                $this->migrate_categories();
                break;
            case 'tags':
                $this->migrate_tags();
                break;
        }
    }


}