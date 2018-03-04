<?php
global $terekhin_framework,$wp_query;
/** @var WP_Term $obj */
$obj=$wp_query->get_queried_object();

?>
<div class="column_inner">
    <?php $terekhin_framework->show_hot_news_in_section($obj);?>
    <?php $terekhin_framework->showSeparator("События в разделе",$obj,'separator_align_left');?>
    <?php $terekhin_framework->show_post_in_section($obj,null,'events');?>
    <?php $terekhin_framework->showSeparator("Мнения в разделе",$obj,'separator_align_left');?>
    <?php $terekhin_framework->show_post_in_section($obj,null,'opinions');?>
    <?php $terekhin_framework->showSeparator("Видеo в разделе",$obj,'separator_align_left');?>
    <?php $terekhin_framework->show_post_in_section($obj,null,'videos');?>

</div>
