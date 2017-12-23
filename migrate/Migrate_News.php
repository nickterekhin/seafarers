<?php

require_once (CHILD_THEME_PATH.'/migrate/Migrate_Base.php');
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
        $this->image_folder = $path;
    }

    function show()
    {

        $res = $this->alien_db_service->RowQuery("SELECT * FROM news WHERE id=2391 AND creator_id=1");
        if($res) {
            $categories = $this->alien_db_service->Query("SELECT * FROM topics WHERE id=" . $res->topic_id);
            $tags = $this->alien_db_service->Query('SELECT t.* FROM tags t INNER JOIN tags_rel tr ON t.id = tr.tag_id INNER JOIN news n ON n.id = tr.news_id WHERE n.id=' . $res->id);
            var_dump($res);
            var_dump($categories->FetchAll());
            var_dump($tags->FetchAll());
        }
        $categs = $this->alien_db_service->Query("SELECT * FROM topics WHERE id>0");
        var_dump($categs->FetchAll());
    }


    function migrate_categories()
    {
        $categs = $this->alien_db_service->Query("SELECT * FROM topics WHERE id>0");
        foreach($categs as $c)
        {
            $params = array(
                'cat_name'=>$c->topic,
                'category_description'=>$c->static_text,
            );
            $category_id = wp_insert_category($params);
            if(!is_wp_error($category_id))
            {
                /** @var WP_Error $category_id */
                var_dump($category_id->get_error_message());
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
        }
    }


}