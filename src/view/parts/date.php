<?php
$month = get_the_time('m',$post->ID);
$year = get_the_time('Y',$post->ID);
$title = get_the_title($post->ID);

$date_format = isset($date_format) && $date_format !== '' ? $date_format : 'published';
$difference = human_time_diff( get_the_time('U',$post->ID), current_time('timestamp') ) . esc_html__(' ago','qode-news');

$display_date = isset($display_date) && $display_date !== '' ? $display_date : 'yes';

$comments_qty = get_comments_number(get_the_ID());
$views_qty = get_post_meta(get_the_ID(),'qode_count_post_views_meta',true);




if ($display_date == 'yes'){ ?>
	<div itemprop="dateCreated" class="qode-post-info-date entry-date published updated td-additional-post-info-layouts">

				<ul>
					<li><?php if(empty($title) && qode_blog_item_has_link()) { ?>
						<a itemprop="url" href="<?php the_permalink($post->ID) ?>">
							<?php } else { ?>
							<a itemprop="url" href="<?php echo get_month_link($year, $month); ?>">
								<?php } ?>
								<i class="dripicons-alarm"></i>
								<?php if ($date_format == 'published') {

									echo get_the_time(get_option('date_format'),$post->ID);
								} else {
									echo esc_html($difference);
								} ?>
							</a></li>
			<?php if($comments_qty>0 || $views_qty>0){ ?>
			<?php if($views_qty>0){ ?>
					<li><span><i class="fa fa-eye"></i><?php echo $views_qty;?></php></span></li>
			<?php } ?>
			<?php if($comments_qty>0){ ?>
					<li><span><i class="fa fa-comments"></i><?php echo $comments_qty;?></span></li>
			<?php } ?>
			<?php } ?>
				</ul>
	    <meta itemprop="interactionCount" content="UserComments: <?php echo get_comments_number(qode_get_page_id()); ?>"/>
	</div>
<?php } ?>