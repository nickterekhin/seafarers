<?php
global $terekhin_framework,$wp_query;
/** @var WP_Term $obj */
$obj=$wp_query->get_queried_object();
?>
<div class="column_inner">
    <?php $terekhin_framework->show_hot_news_in_section($obj);?>
    <?php $terekhin_framework->show_post_in_section($obj,"События в разделе",'events');?>
    <?php $terekhin_framework->show_post_in_section($obj,"Мнения в разделе",'opinions');?>
    <?php $terekhin_framework->show_post_in_section($obj,"Видеo в разделе",'videos');?>

</div>
