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
        $terekhin_framework->show_news_in_section(null,'Популярные',array('category_name'=>implode(',',$terms_slugs_arr),'display_categories'=>'yes','news_period'=>'2'));
        ?>
        <?php
            $args = array('tax_query'=>array(
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

            $terekhin_framework->show_news_in_section(null,'Мнения',$args);

    $args = array('tax_query'=>array(
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
        $args['only_videos']='yes';
        $args['layout_view']='layout1';
        $args['image_size']='large';
            $terekhin_framework->show_news_in_section(null,'Видео',$args);
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
    <?php $terekhin_framework->show_news_in_section($obj,($sub_title?$sub_title.' - Популярное':'Популярные Новости'),array('sort'=>'popular','news_period'=>'2'));?>
    <?php $terekhin_framework->show_news_in_section_by_category($obj,($sub_title?$sub_title.' - ':'')."События",'events');?>
    <?php $terekhin_framework->show_news_in_section_by_category($obj, ($sub_title?$sub_title.' - ':'')."Мнения", 'opinions');

    ?>
    <?php $terekhin_framework->show_news_in_section_by_category($obj,($sub_title?$sub_title.' - ':'')."Видеo",'videos',array('layout_view'=>'layout1','image_size'=>'large'));?>

</div>
<?php } ?>