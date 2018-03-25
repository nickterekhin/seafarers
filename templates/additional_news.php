<?php
global $terekhin_framework,$wp_query;
/** @var WP_Post $obj */
$obj=$wp_query->get_queried_object();
/** @var WP_Term[] $terms */
$terms = get_the_terms($obj->ID,'category');

?>
<?php foreach($terms as $term){ ?>
<?php
    $args=array(
        'layout_view'=>'layout1',
        'sort'=>'latest',
        'post_not_in'=>$obj->ID,
        'image_size'=>'large',
        'posts_per_page'=>9,
        'category_name'=>$term->slug,
        'column_number'=>3,
        'display_read_more_button'=>'yes'
    );

    $terekhin_framework->show_news_in_section(null,'Читать так же в '.$term->name,$args);
    //$terekhin_framework->show_news_in_single_post($term->slug,'Читать так же в '.$term->name,'latest',6,'layout1-news','310px','190px');

    ?>


<?php } ?>
