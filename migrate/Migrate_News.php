<?php
require_once(ABSPATH.'/wp-admin/includes/taxonomy.php');
require_once (CHILD_THEME_PATH.'/migrate/Migrate_Base.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Migrate_News extends Migrate_Base
{
    private static $instance;


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
        $params = array('three'=>3);
        $second_params = array('subthree'=>23);
        $opt = wp_parse_args($params, array('first'=>'1','second'=>wp_parse_args($second_params,array('subsecond'=>2))));

        var_dump($opt);

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

WHERE (n.opinion ='' OR n.opinion = '0') AND n.is_video!=1 AND n.timestamp  > DATE_SUB(CURDATE(),INTERVAL DAY(CURDATE())-1 DAY) ORDER BY n.timestamp DESC LIMIT $qty,1000");
        $index = 0;
        while($res=$sql->FetchRow())
        {
            try {
                $this->add_post($res);
                $index+=1;
            }catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }
        echo $this->counter."\n";
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
        if($qty>=0) {
            $limit = ' LIMIT ' . $qty . ', 1000 ';
        }

        $sql = $this->alien_db_service->Query("SELECT
n.id,
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
        echo $sql->NumRows();
        $added_posts = array();
        while($res=$sql->FetchRow())
        {
            $res_p = preg_match('/"http:\/\/www.(.*?).com\/embed\/(.*?)(?:\?(?:.*))?"/',$res->source,$m);

            if($res_p==1) {

                $arr_meta_posts = array(
                        'video_format_choose' => $m[0],
                        'video_format_link' => $m[1],
                );
                $arr_posts = array(
                    'post_author'=>1
                );
                $post_id = $this->add_post($res,$arr_posts,$arr_meta_posts,false);
                set_post_format($post_id,'video');
                $added_posts[]=$post_id;
                $index += 1;


            }
        }
        echo $index.' ['.join(',',$added_posts).']';
    }
    function addOpinionPaged($qty)
    {
        $limit = ' LIMIT 1';
        if($qty>=0) {
            $limit = ' LIMIT ' . $qty . ', 1000 ';
        }

        $sql = $this->alien_db_service->Query("SELECT
                                        n.id,
                                         n.uri,
                                        n.title,
                                        n.text,
                                        n.short_text,
                                        n.timestamp,
                                        n.photo,
                                        n.keywords,
                                        n.description,
                                        n.views,
                                        n.creator_id,
                                        t.slug,
                                        u.username


                                        FROM news n
                                        INNEr JOIN topics t ON n.topic_id = t.id
                                        INNER JOIN users u ON n.creator_id = u.id
                                        #INNER JOIN videos v ON n.id = v.news_id

                                        WHERE n.opinion != '' AND n.is_video=0 ".$limit);
        $index = 0;
        $added_posts = array();
        while($res = $sql->FetchRow())
        {
            $sql_wp = $this->db->prepare("SELECT * FROM ".$this->db->prefix."posts p WHERE p.post_name='%s'",$res->uri);
            $res_wp = $this->db->get_row($sql_wp);
            if($res_wp) {
                $category = $this->getCategoryByName('opinions');
                if($category)
                    wp_set_post_categories($res_wp->ID,$category,true);

                $added_posts[] = $res_wp->ID;

            }else
            {
                try {
                    $post_id = $this->add_post($res);
                    $category = $this->getCategoryByName('opinions');

                    if ($category)
                        wp_set_post_categories($post_id, $category, true);
                    $added_posts[] = $post_id;

                }catch(Exception $e)
                {
                    echo $e->getMessage();
                }


            }
            $index += 1;

        }
        echo $index.' ['.join(',',$added_posts).']';
    }

    function addImageForPost()
    {
        $sql = $this->alien_db_service->Query("SELECT p.ID,p.post_name,n.uri,n.photo FROM i4208320_wp2.wp_posts p
LEFT JOIN i4208320_wp2.wp_postmeta pm ON p.ID = pm.post_id AND pm.meta_key = '_thumbnail_id'
INNER JOIN seafarersj_db.news n ON n.uri = p.post_name
WHERE pm.meta_value IS NULL AND p.post_date >'2018-03-01' and n.is_video = 0");
        while($res = $sql->FetchRow())
        {
            //$this->addImageToPost($post_ID, $this->image_folder . '/' . $res->photo);
            echo $res->photo."\n";
            $this->addImageToPost($res->ID,$this->image_folder.'/'.$res->photo);
            var_dump(file_exists($this->image_folder.'/'.$res->photo));
        }
    }
    function cleanPost_Content()
    {
        //AND p.post_content REGEXP '[[:digit:]]+.[[:space:]]*[[:digit:]]+.[[:space:]]*[[:digit:]]+[[:space:]]*-[[:space:]]*SEAFARERS[[:space:]]*JOURNAL'
        //$sql = $this->db->prepare("SELECT p.ID, p.post_content FROM ".$this->db->prefix."posts p WHERE p.post_type='post' AND (p.post_content !='' OR p.post_content is NOT NULL) LIMIT 0,5");
        $res = $this->db->get_results("SELECT p.ID, p.post_content FROM ".$this->db->prefix."posts p WHERE p.post_type='post' AND (p.post_content !='' OR p.post_content is NOT NULL) ORDER BY p.post_date DESC LIMIT 0,5");
        var_dump($res);
        if($res)
        {
            foreach($res as $r) {
                if (preg_match('/(\d+\.\s?\d+\.\s?\d+\s?.\s?Seafarers\s?journal\.?)/i', $r->post_content, $m) == 1) {
                    var_dump($m);
                    $r->post_content = preg_replace('/' . $m[1] . '/', '', $r->post_content);
                }
                if (preg_match('/href="(.*?)(\/news\/view\/)(.*?)"/i', $r->post_content, $m1) == 1) {
                    var_dump($m1);
                    $terms = get_the_terms($r->ID, 'category');
                    if ($terms && count($terms) > 0) {
                        $r->post_content = preg_replace('/' . $m1[2] . '/', '/' . $terms[0]->slug . '/', $r->post_content);
                    } else {
                        $r->post_content = preg_replace('/' . $m1[2] . '/', '/', $r->post_content);
                    }

                }
                var_dump($r->post_content);
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
                    $this->addPostPaged(isset($_REQUEST['qty'])?$_REQUEST['qty']:-1);
                    break;
                case 'migrate-video':
                    $this->addVideoPaged(isset($_REQUEST['qty'])?$_REQUEST['qty']:-1);
                    break;
                case 'migrate-opinion':
                    $this->addOpinionPaged(isset($_REQUEST['qty'])?$_REQUEST['qty']:-1);
                    break;
                case 'set-post-views':
                    $this->addViewsToPost(isset($_REQUEST['qty'])?$_REQUEST['qty']:-1);
                    break;
                case 'add-image':
                    $this->addImageForPost();
                    break;
                case 'clean-content':
                    $this->cleanPost_Content();
                    break;

            }
        }catch(Exception $e)
        {
        echo $e->getMessage();
        }
    }


}