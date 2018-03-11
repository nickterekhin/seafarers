<?php
global $terekhin_framework,$wp_query;
/** @var WP_Term $obj */
$obj=$wp_query->get_queried_object();
$sub_title = null;

if($obj) {
    $sub_title = $obj->name;

}else if(isset($wp_query->query_vars['year']) && isset($wp_query->query_vars['monthnum']))
{
    $sub_title = $terekhin_framework->getMonth($wp_query->query_vars['monthnum']).' '.$wp_query->query_vars['year'];
}
?>
<div class="column_inner">
    <?php $terekhin_framework->show_hot_news_in_section($obj,($sub_title?$sub_title.' - Горячие':'Горячие Новости'));?>
    <?php $terekhin_framework->show_post_in_section($obj,($sub_title?$sub_title.' - ':'')."События в разделе",'events');?>
    <?php $terekhin_framework->show_post_in_section($obj, ($sub_title?$sub_title.' - ':'')."Мнения в разделе", 'opinions');

    ?>
    <?php $terekhin_framework->show_post_in_section($obj,($sub_title?$sub_title.' - ':'')."Видеo в разделе",'videos');?>

</div>
