<?php
/**
 * @var TD_News_Layout1 $obj
 */
use TerekhinDevelopment\td_news_short_codes\src\impl\TD_News_Layout1;

?>

<br/>
<div class="td-news-holder">
    <div class="td-news-section-1 td-news-column-<?php echo $section_1_columns_qty;?>">
        <div class="td-news-section-1-inner">
            <?php
                echo $obj->render_article($posts_arr[0]);
            ?>
       </div>
        <?php
            $articles =count($posts_arr);
            if($articles>4) $articles=4;
            $posts_right = array_slice($posts_arr,1,$articles);
        ?>
        <div class="td-news-section-1-inner td-news-column-<?php echo count($posts_right);?>">

            <div class="td-news-section-inner-holder">
                <?php
                    echo $obj->render_articles($posts_right);
                ?>
            </div>

        </div>
    </div>
    <?php
    $posts_arr = array_slice($posts_arr,count($posts_right)+1);
    ?>
    <div class="td-news-section-2 td-news-column-4">
        <div class="td-news-section-inner-holder">
           <?php
           echo $obj->render_articles($posts_arr);
           ?>
        </div>
    </div>
</div>
