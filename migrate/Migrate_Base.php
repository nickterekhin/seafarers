<?php

require_once (CHILD_THEME_PATH.'/migrate/DBService.php');
require_once (CHILD_THEME_PATH.'/migrate/init.php');
abstract class Migrate_Base
{
    const ALIEN_HOST = HOST;
    const ALIEN_USER = USER;
    const ALIEN_PASS = PASS;
    const ALIEN_DB   = DB;
    const ALiEN_PREFIX = PREFIX;

    protected $alien_db_service;
    protected $db;
    protected $image_folder;
    protected $counter= 0;

    /**
     * Migrate_Base constructor.
     */
    protected function __construct()
    {
        global $wpdb;
        $this->db=$wpdb;
        $this->alien_db_service = new DBService(self::ALIEN_HOST,self::ALIEN_USER,self::ALIEN_PASS,self::ALIEN_DB);
    }

    protected function getCategoryByName($cat_slug)
    {
        //$cat_slug = mb_strtolower(preg_replace('/\s+/isu','-',$category_name));

        /** @var WP_Term $category */
        $category = get_category_by_slug($cat_slug);
        if($category)
            return array($category->term_id);

        return null;
    }
    protected function getTags($news_id)
    {
        $sql_tags = $this->alien_db_service->Query("SELECT t.* FROM tags t INNER JOIN tags_rel tr ON tr.tag_id = t.id
WHERE tr.news_id = ".$news_id);

        $tags = array();
        while($res_tags=$sql_tags->FetchRow())
        {
            $tag_slug = mb_strtolower(preg_replace('/\s+/isu','-',$res_tags->tag));
            $results_tag = get_term_by('slug',$tag_slug,'post_tag');
            if($results_tag)
                $tags[] = $results_tag->term_id;

        }
        if($tags)
            return $tags;

        return null;

    }

    protected function getPostAuthor($authorName)
    {
        $user = get_user_by('login',$authorName);

        if($user)
            return $user->ID;

        return 1;
    }

    protected function addComment($post_id,$news_id)
    {
        $sql_comments = $this->alien_db_service->Query("SELECT * FROM news_comments nc WHERE nc.news_id=".$news_id);
        while($res_comments = $sql_comments->FetchRow())
        {
            $comment_args = array(
                'comment_approved'=>1,
                'comment_author'=>$res_comments->name,
                'comment_content'=>$res_comments->text,
                'comment_date'=>$res_comments->timestamp,
                'comment_date_gnt'=>$res_comments->timestamp,
                'comment_post_ID'=>$post_id,
            );
            wp_insert_comment($comment_args);
        }
    }

    protected function addImageToPost($post_id,$file)
    {
        $filename = basename($file);
        $upload_file = wp_upload_bits($filename, null, file_get_contents($file));

        if(!$upload_file['error']) {
            $file_type = wp_check_filetype($filename, null);
            $wp_upload_dir = wp_upload_dir();
            $arr_image = array(
                'guid' => $wp_upload_dir['url'] . '/' . $upload_file['file'],
                'post_mime_type' => $file_type['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment($arr_image, $upload_file['file'], $post_id);
            if(!is_wp_error($attach_id)) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');

// Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);

                set_post_thumbnail($post_id, $attach_id);
            }else
            {
                var_dump($attach_id->get_error_message());
            }
        }
        else{
        var_dump($upload_file['error']);
        }
    }

    function add_post($res,$params=array(),$meta_params=array(),$add_image=true)
    {
        $arr_posts = wp_parse_args($params,array(

            'comment_status' => 'open',
            'ping_status'    => 'open',
            'post_author'    => (isset($res->creator_id) && $res->creator_id==1)?$this->getPostAuthor('dmitriy@sj.com'):$this->getPostAuthor($res->username),
            'post_content'   => $res->text,
            'post_date'      => $res->timestamp,
            'post_date_gmt'  => $res->timestamp,
            'post_excerpt'   => $res->short_text,
            'post_name'      => $res->uri,
            'post_status'    => 'publish',
            'post_title'     => $res->title,
            'meta_input'     => wp_parse_args($meta_params,array(
                'keywords'=>$res->keywords,
                'description'=>$res->description,
                'qode_seo_keywords'=>$res->keywords,
                'qode_seo_description'=>$res->description,
                'qode_count_post_views_meta'=>$res->views,
                 'qode_show-sidebar'=>'default',
                        'qode_news_post_hot_meta'=>'no',
                        'qode_news_post_trending_meta'=>'no',
                        'qode_news_post_featured_meta'=>(isset($res->bold) && $res->bold==1)?'yes':'no',
                        'qode_post_style_masonry_date_image'=>'full',
                        'qode_post_style_masonry_gallery'=>'default',
                        'qode_hide-featured-image'=>'no',
                        'qode_page_background_image_fixed'=>'yes'
            ))
        ));

        $res->uri = preg_replace('/\-$/','',$res->uri);
        $res->uri = preg_replace('/(\-{2,})/','-',$res->uri);
        $sql_wp = $this->db->prepare("SELECT * FROM ".$this->db->prefix."posts p WHERE p.post_name=%s",$res->uri);
        $res_wp = $this->db->get_row($sql_wp);

        if($res_wp)
        {

                foreach ($arr_posts['meta_input'] as $k => $v) {
                    update_post_meta($res_wp->ID, $k, $v);
                }
                echo $res_wp->ID . " - edit\n";
                $this->counter += 1;
                return $res_wp->ID;

        }else
        {

            $post_ID = wp_insert_post($arr_posts);
            if(!is_wp_error($post_ID)) {
                foreach($arr_posts['meta_input'] as $k=>$v)
                {
                    update_post_meta($post_ID, $k, $v);
                }


                $tags = $this->getTags($res->id);
                $category = $this->getCategoryByName(isset($res->slug)?$res->slug:'videos');

                if ($category)
                    wp_set_post_categories($post_ID, $category);

                if ($tags)
                    wp_set_post_terms($post_ID, $tags);

                $this->addComment($post_ID, $res->id);
                if ($res->photo && $add_image)
                    $this->addImageToPost($post_ID, $this->image_folder . '/' . $res->photo);
                return $post_ID;
            }else
            {
                throw new Exception($post_ID->get_error_message());
            }
        }


    }


    /*
     $config['dbhost'] = "localhost";
$config['dbuser'] = "seafarersj";
$config['dbpass'] = "mG_kBGf;GgX+";
$config['dbname'] = "seafarers_job";
$config['dbprefix'] = "u";*/
}