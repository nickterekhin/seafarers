<?php

function terekhin_blog_category_rewrite($rules)
{


    $args = array(
        "hide_empty"=>0,
        'taxonomy'=> 'category',
        'parent'=>0,
    );

    $categories = get_categories($args);

    if(is_array($categories) && !empty($categories))
    {
        $categs_slugs=array();

        /** @var WP_Term $categs */
        foreach($categories as $categs)
        {
            if(is_object($categs) && !is_wp_error($categs))
            {
                $categs_slugs[] = $categs->slug;
            }
        }
//var_dump($categs_slugs);
        if(!empty($categs_slugs))
        {
            $rules = array();
            foreach($categs_slugs as $slug)
            {
                $rules['('.$slug.')'.'/([^/]+)/?$'] = 'index.php?category_name=$matches[1]&name=$matches[2]';
                $rules['('.$slug.')'.'/feed/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
                $rules['('.$slug.')'.'/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
                $rules['('.$slug.')'.'/embed/?$'] = 'index.php?category_name=$matches[1]&embed=true';
                $rules['('.$slug.')'.'/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
                $rules['('.$slug.')'.'/?$'] = 'index.php?category_name=$matches[1]';
            }
        }
    }

    //var_dump($rules);

    return $rules;
}
add_filter("category_rewrite_rules","terekhin_blog_category_rewrite");
add_filter("wp_get_nav_menu_items",'terekhin_rewrite_menu_items',10,3);

function terekhin_rewrite_menu_items($items,$menu,$args)
{

    /** @var WP_Post $t */
    foreach($items as $t)
    {
        if($t->post_type=='nav_menu_item' && $t->object=='category' && $t->type=='taxonomy')
        {

            $term = get_term($t->object_id,$t->object);
            $t->url = home_url().'/'.$term->slug;
        }
    }

    return $items;
}
add_filter("term_link","terekhin_rewrite_category_link",10,3);

function terekhin_rewrite_category_link($termlink, $term, $taxonomy)
{

    if($taxonomy=='category')
    {
        $taxonomy_object = get_taxonomy($taxonomy);
        $termlink = home_url().'/'.$term->slug;

        return $termlink;
    }
    return $termlink;
}