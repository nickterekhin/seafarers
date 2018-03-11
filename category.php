<?php get_header(); ?>
<?php 
global $wp_query,$terekhin_framework;
$id = $wp_query->get_queried_object_id();
$obj = $wp_query->get_queried_object();

$wp_query->query_vars['posts_per_page'] = 15;


if((isset($_GET['date_year']) && !empty($_GET['date_year'])) || (isset($_GET['date_month']) && !empty($_GET['date_month'])) || (isset($_GET['date_day']) && !empty($_GET['date_day'])) || (isset($_REQUEST['filter_search']) && !empty($_REQUEST['filter_search']))) {

	add_filter('get_pagenum_link',array($terekhin_framework, 'add_search_params_to_pagination'));

	add_filter('posts_where',array($terekhin_framework,'search_bar_where_filter'));
	add_filter('posts_join',array($terekhin_framework,'search_bar_join_post_tag_filter'));
	$wp_query = new WP_Query($wp_query->query_vars);
	remove_filter('posts_where',array($terekhin_framework,'search_bar_where_filter'));
}else
{
	$wp_query = new WP_Query(($wp_query->query_vars));
}



if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
else { $paged = 1; }

$sidebar = $qode_options_proya['category_blog_sidebar'];

if(isset($qode_options_proya['blog_page_range']) && $qode_options_proya['blog_page_range'] != ""){
	$blog_page_range = $qode_options_proya['blog_page_range'];
} else{
	$blog_page_range = $wp_query->max_num_pages;
}

?>
	<?php if(get_post_meta($id, "qode_page_scroll_amount_for_sticky", true)) { ?>
		<script>
		var page_scroll_amount_for_sticky = <?php echo get_post_meta($id, "qode_page_scroll_amount_for_sticky", true); ?>;
		</script>
	<?php } ?>
		<?php get_template_part( 'templates/custom-category','title' ); ?>
		<div class="container">
        <?php if(isset($qode_options_proya['overlapping_content']) && $qode_options_proya['overlapping_content'] == 'yes') {?>
            <div class="overlapping_content"><div class="overlapping_content_inner">
        <?php } ?>
		<div class="container_inner default_template_holder clearfix">
			<?php if(isset($obj->description) && !empty($obj->description)):?>
				<div class="vc_row wpb_row section vc_row-fluid " style=" text-align:left;">
					<div class=" full_section_inner clearfix">
						<div class="wpb_column vc_column_container vc_col-sm-12">
							<div class="vc_column-inner row-bottom-margin">
								<div class="wpb_wrapper">
									<div class="wpb_text_column wpb_content_element ">
										<div class="wpb_wrapper" style="text-align:center">
											<p>
											<?php echo $obj->description;?>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php $terekhin_framework->showSeparator('20','40',null,'transparent'); ?>
			<?php endif;?>
			<?php //get_template_part( 'templates/opinion','single' ); ?>
			<div class="vc_row wpb_row section vc_row-fluid  grid_section" style=" text-align:left;">
				<div class=" section_inner clearfix">
					<div class="section_inner_margin clearfix">
						<div class="wpb_column vc_column_container vc_col-sm-12">
							<div class="vc_column-inner ">
								<div class="wpb_wrapper">
									<?php //$terekhin_framework->showSeparator('Популярное в разделе','separator_align_left');?>
									<?php
									$terekhin_framework->show_popular_news_in_section($obj,'Популрное в разделе');
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>



			<?php if(($sidebar == "default")||($sidebar == "")) : ?>
				<?php
				$terekhin_framework->showHeader('Все Новости',3,'left','td-header');
				$terekhin_framework->showSeparator('10','30',null,'transparent');
				?>
				<?php 
					get_template_part('templates/category_blog', 'structure');
				?>
			<?php elseif($sidebar == "1" || $sidebar == "2"): ?>

				<div class="<?php if($sidebar == "1"):?>two_columns_66_33<?php elseif($sidebar == "2") : ?>two_columns_75_25<?php endif; ?> background_color_sidebar grid2 clearfix">
					<div class="column1">
						<?php
						$terekhin_framework->showHeader('Все Новости',3,'left','td-header');
						$terekhin_framework->showSeparator('10','30',null,'transparent');
						?>
							<?php 
								get_template_part('templates/category_blog', 'structure');
							?>


					</div>
					<div class="column2">
						<div class="column_inner">
							<?php get_template_part('templates/td_sidebar','news_parts');?>
						</div>
						<?php get_sidebar(); ?>	
					</div>
				</div>
		<?php elseif($sidebar == "3" || $sidebar == "4"): ?>
				<div class="<?php if($sidebar == "3"):?>two_columns_33_66<?php elseif($sidebar == "4") : ?>two_columns_25_75<?php endif; ?> background_color_sidebar grid2 clearfix">
					<div class="column1">
					<?php get_sidebar(); ?>	
					</div>
					<div class="column2">
						<div class="column_inner">
							<?php 
								get_template_part('templates/category_blog', 'structure');
							?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="vc_row wpb_row section vc_row-fluid  grid_section" style=" text-align:left;">
				<div class=" section_inner clearfix">
					<div class="section_inner_margin clearfix">
						<div class="wpb_column vc_column_container vc_col-sm-12">
							<div class="vc_column-inner ">
								<div class="wpb_wrapper">
									<?php $terekhin_framework->showSeparator('20','20',null,'transparent'); ?>
									<?php $terekhin_framework->show_most_comments_in_section($obj);?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <?php if(isset($qode_options_proya['overlapping_content']) && $qode_options_proya['overlapping_content'] == 'yes') {?>
            </div></div>
        <?php } ?>
	</div>
<?php get_footer(); ?>