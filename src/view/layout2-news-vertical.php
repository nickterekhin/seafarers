<?php
/**
 * @var WP_Post[] $posts
 * @var TD_Framework $class
 **/
use TerekhinDevelopment\framework\TD_Framework;
?>

<?php if(count($posts)>0) {
    if(isset($layout_title))
        //$class->showSeparator($layout_title,'separator_align_left');
?>
<div class="qode-news-holder qode-layout2 qode-news-columns-3 qode-nl-small-space">
        <?php if(count($posts)>0){ ?>
    <div class="qode-news-list-title-holder">
        <?php if(isset($layout_title)) { ?>
            <h3 class="qode-news-layout-title"><?php echo $layout_title;?></h3>
        <?php } ?>
    </div>
        <?php } ?>
    <div class="qode-news-list-inner-holder" data-number-of-items="3" style="font-size:0">
        <?php foreach($posts as $p){ ?>
        <div class="qode-news-item qode-layout2-item" >
            <div class="qode-news-item-inner">
                <div class="qode-news-item-image-holder">
                    <div class="qode-news-item-image-holder-inner">
                        <div class="qode-post-image">
                            <?php
                            $single['post']=$p;
                            $single['class']=$class;
                            echo $class->View('parts/image',$single);
                            ?>
                        </div>
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