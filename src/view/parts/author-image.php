<?php 
$display_author = isset($display_author) && $display_author !== '' ? $display_author : 'yes';
$author_image = get_avatar(get_the_author_meta('ID'));

if ($display_author == 'yes'){ ?>
	<div class="qode-post-info-author-image">
		<?php echo wp_kses_post($author_image); ?>
	</div>
<?php } ?>