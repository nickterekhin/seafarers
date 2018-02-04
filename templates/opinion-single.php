<?php
global $wp_query;
$obj = $wp_query->get_queried_object();

var_dump($obj);

$args = array(
    'post_type'=>'post',
    'orderby'=>'rand',
    'posts_per_page'=>1,
    'category_name'=>'marine-news',
    'tag'=>'opinion');

$q = new WP_Query($args);

var_dump($q->have_posts());
var_dump($q->posts[0]);
wp_reset_postdata();


