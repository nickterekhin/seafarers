<?php
global $terekhin_framework,$wp_query;
/** @var WP_Term $obj */
$obj=$wp_query->get_queried_object();
$sub_title = null;
if(is_single())
{
    $terms = wp_get_post_terms($obj->ID,'category');
    $terms_slugs_arr = array_map(function($e){
        return $e->slug;
    },$terms);

?>

        <?php
        $terekhin_framework->show_news_in_section(null,'Популярные',array('category_name'=>implode(',',$terms_slugs_arr),'display_categories'=>'yes','news_period'=>'2','post_not_in'=>$obj->ID,'posts_per_page'=>15,'extra_class_name'=>'td-news-list'));
        ?>
        <?php

            $args_opinion = array('tax_query'=>array(
                'relation'=>'OR',
                array(
                    'taxonomy'=>'category',
                    'field'=>'slug',
                    'terms'=>$terms_slugs_arr,
                    'operator'=>'AND'
                ),
                array(
                    'taxonomy'=>'category',
                    'field'=>'slug',
                    'terms'=>array('opinions')
                )
            ));
    $format = get_post_format($obj->ID);

    if($format=='video') {
        unset($args_opinion['tax_query']['relation']);
        unset($args_opinion['tax_query'][0]);
    }
    $args_opinion['sort']='latest';
    $args_opinion['post_not_in']=$obj->ID;
    $args_opinion['display_read_more_button']='yes';
    $args_opinion['read_more_button_slug']='opinions';
    $args_opinion['extra_class_name']='td-news-list';
            $terekhin_framework->show_news_in_section(null,'Мнения',$args_opinion);

    $args_video = array('tax_query'=>array(
        'relation'=>'OR',
        array(
            'taxonomy'=>'category',
            'field'=>'slug',
            'terms'=>$terms_slugs_arr,
            'operator'=>'AND'
        ),
        array(
            'taxonomy'=>'category',
            'field'=>'slug',
            'terms'=>array('videos')
        )
    ));

        $args_video['only_videos']='yes';
        $args_video['layout_view']='layout1';
        $args_video['image_size']='large';
        $args_video['sort']='latest';
        $args_video['post_not_in']=$obj->ID;
        $args_video['display_read_more_button']='yes';
        $args_video['read_more_button_slug']='videos';
    if($format!='video')
            $terekhin_framework->show_news_in_section(null,'Видео',$args_video);
            ?>

<?php
}else
{


if($obj) {
    $sub_title = $obj->name;

}else if(isset($wp_query->query_vars['year']) && isset($wp_query->query_vars['monthnum']))
{
    $sub_title = $terekhin_framework->getMonth($wp_query->query_vars['monthnum']).' '.$wp_query->query_vars['year'];
}
?>
<div class="column_inner">
    <?php $terekhin_framework->show_news_in_section($obj,($sub_title?$sub_title.' - Популярное':'Популярные Новости'),array('sort'=>'popular','news_period'=>'2','extra_class_name'=>'td-news-list'));?>
    <?php $terekhin_framework->show_news_in_section_by_category($obj,($sub_title?$sub_title.' - ':'')."События",'events',array('display_read_more_button'=>'yes','extra_class_name'=>'td-news-list'));?>
    <?php $terekhin_framework->show_news_in_section_by_category($obj, ($sub_title?$sub_title.' - ':'')."Мнения", 'opinions',array('display_read_more_button'=>'yes','extra_class_name'=>'td-news-list'));

    ?>
    <?php $terekhin_framework->show_news_in_section_by_category($obj,($sub_title?$sub_title.' - ':'')."Видеo",'videos',array('layout_view'=>'layout1','image_size'=>'large','display_read_more_button'=>'yes'));?>

</div>
<?php } ?>