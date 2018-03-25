<?php
/**
 * @var TD_News_Complex_Layout $obj
 */
use TerekhinDevelopment\td_news_short_codes\src\impl\TD_News_Complex_Layout;
$articles =count($posts_arr);
if($articles>4) $articles=4;
$posts_right = array_slice($posts_arr,1,$articles);
?>
<?php if($posts_arr){ ?>
    <h3 style="text-align: left" class="vc_custom_heading td-header"><?php echo $news_title?></h3>
<div class="vc_row wpb_row section vc_row-fluid vc_inner td-news-container td-news-holder-column-<?php echo $section_1_columns_qty;?>" style=" text-align:left;"><div class=" full_section_inner clearfix"><div class="td-news-section-1 wpb_column vc_column_container <?php echo $section_1_col;?>"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="qode-news-holder qode-layout1 qode-news-columns-1 qode-nl-tiny-space qode-center-alignment" data-post-status="publish" data-category-name="marine-news" data-ignore-sticky-posts="1" data-orderby="date" data-posts-per-page="1" data-sort="latest" data-paged="1" data-offset="1" data-max-num-pages="14597" data-next-page="2" data-title-tag="h2" data-image-size="portfolio-landscape" data-display-categories="no" data-display-excerpt="yes" data-display-date="yes" data-date-format="published" data-display-author="no" data-display-share="no" data-display-hot-trending-icons="no" data-layout="qode_layout1"><div class="qode-news-list-inner-holder" data-number-of-items="1"><?php
                            $params['display_excerpt']='yes';
                            $params['title_tag']='h2';
                            echo $obj->render_article($posts_arr[0],$params);
                            ?>
                        </div></div></div></div></div><div class="td-news-section-2 wpb_column vc_column_container <?php echo $section_1_col;?>"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="qode-news-holder qode-layout1 qode-news-columns-<?php echo (count($posts_right)%2!=0)?count($posts_right):count($posts_right)/2;?> qode-nl-tiny-space" data-post-status="publish" data-category-name="marine-news" data-ignore-sticky-posts="1" data-orderby="date" data-posts-per-page="6" data-sort="latest" data-paged="1" data-offset="2" data-max-num-pages="2433" data-next-page="2" data-title-tag="h5" data-image-size="thumbnail" data-display-categories="no" data-display-excerpt="no" data-excerpt-length="10" data-display-date="yes" data-date-format="published" data-display-author="no" data-display-share="no" data-display-hot-trending-icons="no" data-layout="qode_layout1"><div class="qode-news-list-inner-holder" data-number-of-items="<?php echo count($posts_right);?>">
                            <?php
                            if(count($posts_right)!=1)
                                unset($params['display_excerpt']);
                            $params['title_tag']='h2';
                            if(count($posts_right)>=2)
                                $params['title_tag']='h3';
                            if(count($posts_right)>3)
                                $params['title_tag']='h5';

                            echo $obj->render_articles($posts_right,$params);
                            ?>
                        </div></div></div></div></div></div></div>
    <div class="vc_empty_space" style="height: 32px"><span class="vc_empty_space_inner">
			<span class="empty_space_image"></span>
		</span></div>
    <?php
    $posts_arr = array_slice($posts_arr,count($posts_right)+1);
    $articles_qty = count($posts_arr);?><div class="qode-news-holder qode-layout1 <?php echo $articles_qty>1?'td-news-grid-article':'td-news-holder-grid';?> qode-news-columns-<?php echo $articles_qty;?> qode-nl-small-space qode-center-alignment" data-post-status="publish" data-category-name="marine-news" data-ignore-sticky-posts="1" data-orderby="date" data-posts-per-page="4" data-sort="latest" data-paged="1" data-offset="8" data-max-num-pages="3648" data-next-page="2" data-title-tag="h5" data-image-size="large" data-display-categories="no" data-display-excerpt="yes" data-display-date="yes" data-date-format="published" data-display-author="no" data-display-share="no" data-display-hot-trending-icons="no" data-layout="qode_layout1"><div class="qode-news-list-inner-holder" data-number-of-items="<?php echo $articles_qty;?>"><?php $params['title_tag']='h2';
            if($articles_qty==2)
                $params['title_tag']='h3';
            if($articles_qty==3)
                $params['title_tag']='h4';
            if($articles_qty==4)
                $params['title_tag']='h5';
            $params['display_excerpt']='yes';
            echo $obj->render_articles($posts_arr,$params);?></div></div>
<?php } ?>