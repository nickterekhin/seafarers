<?php
$title_tag = isset($title_tag) ? $title_tag : 'h3';
global $terekhin_framework;
$post_type_icon = $terekhin_framework->get_post_typeicon(get_the_ID());
?>

<<?php echo esc_attr($title_tag);?> itemprop="name" class="entry-title qode-post-title">
<a itemprop="url" href="<?php echo get_the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
    <?php echo '<span>'.$post_type_icon.' '.the_title().'</span>'; ?>
</a>
</<?php echo esc_attr($title_tag);?>>