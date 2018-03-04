<?php get_header(); ?>
<?php 
global $wp_query,$terekhin_framework;
$id = $wp_query->get_queried_object_id();
$obj = $wp_query->get_queried_object();

$wp_query->query_vars['posts_per_page'] = 15;
$wp_query = new WP_Query(($wp_query->query_vars));

if(isset($_GET['date-filter']) && !empty($_GET['date-filter'])) {

	$wp_query->query_vars['date_query']=array(
		array(
			'after' => $_GET['date-filter'] . ' 00:00',
			//'before' => $_GET['date_filter'] . ' 23:59',
			'inclusive' => true
		),

	);
	$wp_query = new WP_Query($wp_query->query_vars);
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
											<?php echo $obj->description;?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<!--	<div class="separator  normal   " style="margin:20px 0 40px 0;"></div>-->
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
				<?php $terekhin_framework->showSeparator('Все Новости в разделе','separator_align_left');?>
				<?php 
					get_template_part('templates/category_blog', 'structure');
				?>
			<?php elseif($sidebar == "1" || $sidebar == "2"): ?>

				<div class="<?php if($sidebar == "1"):?>two_columns_66_33<?php elseif($sidebar == "2") : ?>two_columns_75_25<?php endif; ?> background_color_sidebar grid2 clearfix">
					<div class="column1">
						<?php $terekhin_framework->showSeparator('Все Новости в разделе','separator_align_left');?>
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
		</div>
        <?php if(isset($qode_options_proya['overlapping_content']) && $qode_options_proya['overlapping_content'] == 'yes') {?>
            </div></div>
        <?php } ?>
	</div>
<?php get_footer(); ?>