<?php

class TD_Framework
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

    public function replace_post_image_on_category_image($image, $attachment_id, $size, $icon)
    {
        //get_the_post_thumbnail_url()
        return $image;
    }


    public function add_socials()
    {
        $face_book = '';
        $face_book .= '<div id="fb-root"></div>';
        $face_book .= "<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>'
    }";

        echo $face_book;
    }
}