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

        $sql = $this->alien_db_service->Query("SELECT n.*, t.slug, u.username
FROM news n
INNEr JOIN topics t ON n.topic_id = t.id
INNER JOIN users u ON n.creator_id = u.id

WHERE n.timestamp >= DATE_SUB(DATE_SUB(CURDATE(),INTERVAL DAY(CURDATE())-1 DAY), INTERVAL 1 MONTH) ORDER BY n.timestamp DESC LIMIT 0,100");

        var_dump($sql->FetchAll());

    }

    function addPostPaged($page=1,$qty=100)
    {
        if($page>=0)
            $this->addPost(null,$page,$qty);
    }

   function addPost($id=null,$page=0,$qty=1000,$image=true)
    {
        $query_id = '';
        if($id)
            $query_id = ' AND n.id='.$id;

        $sql = $this->alien_db_service->Query("SELECT n.*, t.slug, u.username
FROM news n
INNEr JOIN topics t ON n.topic_id = t.id
INNER JOIN users u ON n.creator_id = u.id

WHERE n.timestamp >= DATE_SUB(DATE_SUB(CURDATE(),INTERVAL DAY(CURDATE())-1 DAY), INTERVAL 2 MONTH) $query_id ORDER BY n.timestamp DESC LIMIT 504,100");
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
                    'description'=>$res->description
                )
            );

            $post_ID = wp_insert_post($arr_posts);
            if(!is_wp_error($post_ID)) {
                update_post_meta($post_ID, "keywords", $res->keywords);
                update_post_meta($post_ID, "description", $res->description);

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
                    $this->addPostPaged();
                    break;

            }
        }catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


}