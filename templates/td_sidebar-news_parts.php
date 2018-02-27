<?php
global $terekhin_framework,$wp_query;
$obj=$wp_query->get_queried_object();
?>
<div class="column_inner">
    <?php $terekhin_framework->show_grid_post($obj,"События в разделе",'events');?>
    <?php $terekhin_framework->show_grid_post($obj,"Мнения в разделе",'opinions');?>
    <?php $terekhin_framework->show_grid_post($obj,"Видеo в разделе",'videos');?>

</div>
