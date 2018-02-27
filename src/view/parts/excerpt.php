<?php
$display_excerpt = isset($display_excerpt) && $display_excerpt !== '' ? $display_excerpt : 'yes';
if ($display_excerpt == 'yes'){
    if(post_password_required()) {
        echo get_the_password_form();
    } else {
        $excerpt_length = isset($excerpt_length) ? $excerpt_length : ''; ?>


        <div class="qode-post-excerpt-holder">
            <?php echo wp_kses_post(qode_news_excerpt($excerpt_length)); ?>
        </div>

    <?php }
} ?>