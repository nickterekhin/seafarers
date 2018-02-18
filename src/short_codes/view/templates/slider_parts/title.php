<?php
$title_tag = isset($title_tag) ? $title_tag : 'h3';
?>

<<?php echo esc_attr($title_tag);?> itemprop="name" class="entry-title qode-post-title">
<a itemprop="url" href="<?php echo get_the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
    <?php the_title(); the_ID(); ?>
</a>
</<?php echo esc_attr($title_tag);?>>