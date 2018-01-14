<?php

class TD_Framework
{
    private static $instance;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function replace_post_image_on_category_image($image, $attachment_id, $size, $icon)
    {
        //get_the_post_thumbnail_url()
        return $image;
    }


}