<?php

global $terekhin_framework;

add_filter('qode_themename_filter_blog_template_read_more_button',array($terekhin_framework,'translate_read_more'),10,1);
add_filter('get_the_time',array($terekhin_framework,'format_post_date_masonry'),10,3);
add_filter('vc_gitem_template_attribute_post_image_url_value',array($terekhin_framework,'set_post_image_url_value_by_category'));
add_filter('vc_gitem_template_attribute_post_image_background_image_css_value',array($terekhin_framework,'set_post_image_css_value_from_category'));
add_filter("qode_title_text",array($terekhin_framework,'format_page_title'));

    add_action('wp_ajax_views_counting', array($terekhin_framework, 'save_post_views_empty'));
    add_action('wp_ajax_nopriv_views_counting', array($terekhin_framework, 'save_post_views'));


//add_action('save_post',array($terekhin_framework,'save_news_types'),10,3);