<?php

global $terekhin_framework;

add_filter('qode_themename_filter_blog_template_read_more_button',array($terekhin_framework,'translate_read_more'),10,1);
add_filter('get_the_time',array($terekhin_framework,'format_post_date_masonry'),10,3);
add_filter('vc_gitem_template_attribute_post_image_url_value',array($terekhin_framework,'set_post_image_url_value_by_category'));