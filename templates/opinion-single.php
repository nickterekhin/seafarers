<?php
global $wp_query;
$obj = $wp_query->get_queried_object();


$args = array(
    'post_type'=>'post',
    'orderby'=>'rand',
    'posts_per_page'=>1,
    'category_name'=>'marine-news',
    'tag'=>'opinion');

$q = new WP_Query($args);

wp_reset_postdata();


