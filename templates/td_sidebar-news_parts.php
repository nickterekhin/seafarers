<?php
global $terekhin_framework,$wp_query;
/** @var WP_Term $obj */
$obj=$wp_query->get_queried_object();
$sub_title = null;
if(is_single())
{
?>

        <?php $terekhin_framework->show_news_in_single_post('','Горячие Новости','hot_first');?>
        <?php $terekhin_framework->show_news_in_single_post('opinions','Мнения');?>
        <?php $terekhin_framework->show_news_in_single_post('videos','Видео');?>


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
    <?php $terekhin_framework->show_post_in_section($obj,($sub_title?$sub_title.' - ':'')."События в разделе",'events');?>
    <?php $terekhin_framework->show_post_in_section($obj, ($sub_title?$sub_title.' - ':'')."Мнения в разделе", 'opinions');

    ?>
    <?php $terekhin_framework->show_post_in_section($obj,($sub_title?$sub_title.' - ':'')."Видеo в разделе",'videos');?>

</div>
<?php } ?>