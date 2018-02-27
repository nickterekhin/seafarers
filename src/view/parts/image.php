<?php

use TerekhinDevelopment\framework\TD_Framework;

$image_size = isset($image_size) ? $image_size : 'thumbnail';
$featured_image_meta = get_post_meta($post->ID, 'qode_blog_list_featured_image_meta', true);

$has_featured = !empty($featured_image_meta) || has_post_thumbnail($post->ID);

$blog_list_image_src = !empty($featured_image_meta) ? $featured_image_meta : '';
print_r($featured_image_meta);
$url=null;
if(!$has_featured) {
	/** @var TD_Framework $class */
	$url = $class->getCategoryImage($post->ID);
	if($url)$has_featured=true;
		}
?>
<?php if ( $has_featured ) { ?>
	<div class="qode-post-image">
	    <a itemprop="url" href="<?php echo get_the_permalink($post->ID); ?>" title="<?php the_title_attribute(array('post'=>$post->ID)); ?>">
        <?php
        if ( $image_size != 'custom' ) {
            if ($blog_list_image_src !== '') { ?>
                <img itemprop="image" class="qode-custom-post-image" src="<?php echo esc_url($blog_list_image_src); ?>"
                     alt="<?php esc_html_e('Blog list featured image', 'qode-news'); ?>"/>
            <?php } else { ?>
                <?php get_the_post_thumbnail($post->ID,$image_size); ?>
            <?php }
        }elseif ( $custom_image_width != '' && $custom_image_height != '' ) {
			if ( ! empty( $blog_list_image_src ) ) {
				echo qode_generate_thumbnail( $blog_list_image_src, $url, $custom_image_width, $custom_image_height );
			} else {
				echo qode_generate_thumbnail( get_post_thumbnail_id( $post->ID ), $url, $custom_image_width, $custom_image_height );
			}
		}?>
	    </a>
	</div>
<?php } ?>