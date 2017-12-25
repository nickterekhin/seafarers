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
        var_dump($this->image_folder);
        var_dump(get_the_post_thumbnail(14));
        var_dump(wp_get_attachment_url(14));
        $sql = $this->alien_db_service->Query("SELECT n.*, t.slug, u.username
FROM news n
INNEr JOIN topics t ON n.topic_id = t.id
INNER JOIN users u ON n.creator_id = u.id

WHERE n.timestamp >= DATE_SUB(DATE_SUB(CURDATE(),INTERVAL DAY(CURDATE())-1 DAY), INTERVAL 1 MONTH)
AND n.id=39424
ORDER BY n.timestamp DESC");
        while($res=$sql->FetchRow())
        {
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
                'meta_input'     => array(
                    'keywords'=>$res->keywords,
                    'description'=>$res->description
                )
            );
            var_dump($arr_posts);


            $sql_tags = $this->alien_db_service->Query("SELECT t.* FROM tags t INNER JOIN tags_rel tr ON tr.tag_id = t.id
WHERE tr.news_id = ".$res->id);

            var_dump($res);
            $user = get_user_by('login',$res->username);

            $cat_slug = mb_strtolower(preg_replace('/\s+/isu','-',$res->slug));

            /** @var WP_Term $category */
            $category = get_category_by_slug($cat_slug);
            var_dump($user->ID);
            var_dump($category->term_id);
            $tags = array();
            while($res_tags=$sql_tags->FetchRow())
            {
                $tag_slug = mb_strtolower(preg_replace('/\s+/isu','-',$res_tags->tag));
                $results_tag = get_term_by('slug',$tag_slug,'post_tag');
                $tags[] = $results_tag->term_id;

            }

            var_dump($tags);
        }


    }

   function addPost($id=null,$page=0,$qty=1000,$image=true)
    {
        $query_id = '';
        if($id)
            $query_id = ' AND n.id='.$id;

        $query_limit='';
        if($page>=0)
            $query_limit = " LIMIT ".($page*$qty).", $qty";

        $sql = $this->alien_db_service->Query("SELECT n.*, t.slug, u.username
FROM news n
INNEr JOIN topics t ON n.topic_id = t.id
INNER JOIN users u ON n.creator_id = u.id

WHERE n.timestamp >= DATE_SUB(DATE_SUB(CURDATE(),INTERVAL DAY(CURDATE())-1 DAY), INTERVAL 1 MONTH) $query_id ORDER BY n.timestamp DESC $query_limit");
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
            update_post_meta($post_ID,"keywords",$res->keywords);
            update_post_meta($post_ID,"description",$res->description);

            $tags = $this->getTags($res->id);
            $category = $this->getCategoryByName($res->slug);

            if($category)
                wp_set_post_categories($post_ID,$category);

            if($tags)
            wp_set_post_terms($post_ID, $tags);

            $this->addComment($post_ID,$res->id);
            if($res->photo)
                if($image)
            $this->addImageToPost($post_ID,$this->image_folder.'/'.$res->photo);
            $index+=1;
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
            case 'post':
                $this->addPost(isset($_REQUEST['id'])?$_REQUEST['id']:null,isset($_REQUEST['page'])?$_REQUEST['page']:null,isset($_REQUEST['qty'])?$_REQUEST['qty']:$_REQUEST['qty'],isset($_REQUEST['image'])?false:true);
                break;

        }
    }


}