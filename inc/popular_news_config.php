<?php

global $terekhin_framework;

add_filter('wp_get_attachment_image_src',array($terekhin_framework,'replace_post_image_on_category_image'),10,4);
add_filter('qode_themename_filter_blog_template_read_more_button',array($terekhin_framework,'translate_read_more'),10,1);
//add_filter('get_post_metadata',array($framework,'check_post_metadata'),10,4);