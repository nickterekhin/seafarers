<?php
/**
 * @var WP_Post[] $posts
 * @var TD_Framework $class
**/
use TerekhinDevelopment\framework\TD_Framework;
?>
<?php if(count($posts)>0){ ?>
<div class="qode-news-holder qode-layout2 qode-news-columns-1 qode-nl-normal-space" data-post-status="publish" data-category-name="fyre-festival" data-ignore-sticky-posts="1" data-orderby="date" data-posts-per-page="6" data-sort="latest" data-paged="1" data-max-num-pages="2" data-next-page="2" data-title-tag="h5" data-image-size="custom" data-custom-image-width="70" data-custom-image-height="70" data-display-categories="no" data-display-excerpt="no" data-excerpt-length="10" data-display-date="yes" data-date-format="published" data-display-author="yes" data-display-share="no" data-display-hot-trending-icons="no" data-layout="qode_layout2">
    <div class="qode-news-list-title-holder">
        <h3 class="qode-news-layout-title"><?php echo $layout_title;?></h3>
    </div>
    <div class="qode-news-list-inner-holder" data-number-of-items="1">
        <?php foreach($posts as $p){ ?>
        <div class="qode-news-item qode-layout2-item">
            <div class="qode-news-item-inner">
                <div class="qode-news-item-image-holder">
                    <div class="qode-news-item-image-holder-inner">
                        <?php
                            $single['post']=$p;
                            $single['class']=$class;
                            echo $class->View('parts/image',$single);
                        ?>
                        <div class="qode-news-image-info-holder-left">
                        </div>
                        <div class="qode-news-image-info-holder-right">
                        </div>

                    </div>
                </div>
                <div class="qode-ni-content">
                <?php echo $class->View('parts/category',$single); ?>
                <?php echo $class->View('parts/title',$single); ?>
                <?php echo $class->View('parts/date',$single); ?>
                <?php echo $class->View('parts/excerpt',$single); ?>
                <?php echo $class->View('parts/author',$single); ?>

                </div>
            </div>
        </div>


        <?php } ?>


    </div>
</div>
<?php } ?>