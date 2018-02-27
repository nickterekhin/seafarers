<?php
$title_tag = isset($title_tag) ? $title_tag : 'h3';
?>

<<?php echo esc_attr($title_tag);?> itemprop="name" class="entry-title qode-post-title">
<a itemprop="url" href="<?php echo get_the_permalink($post->ID); ?>" title="<?php the_title_attribute(array('post'=>$post->ID)); ?>">
    <?php echo get_the_title($post->ID); ?>
</a>
</<?php echo esc_attr($title_tag);?>>