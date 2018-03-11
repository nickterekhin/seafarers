<?php
global $terekhin_framework,$wp_query;
/** @var WP_Post $obj */
$obj=$wp_query->get_queried_object();
/** @var WP_Term[] $terms */
$terms = get_the_terms($obj->ID,'category');

?>
<?php foreach($terms as $term){ ?>
<?php $terekhin_framework->show_news_in_single_post($term->slug,'Читать так же в '.$term->name,'latest',6,'layout1-news');?>
    <?php $terekhin_framework->showSeparator('20','20',null,'transparent');?>
    <?php $terekhin_framework->showQ2Button($term->slug)?>
    <?php $terekhin_framework->showSeparator('20','20',null,'transparent');?>

<?php } ?>
