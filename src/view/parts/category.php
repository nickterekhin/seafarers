<?php
$display_categories = isset($display_categories) && $display_categories !== '' ? $display_categories : 'yes';

if ($display_categories == 'yes'){ ?>
    <div class="qode-post-info-category">
        <?php foreach(get_the_category() as $category){  echo qode_category_color_name($category);} ?>
    </div>
<?php } ?>