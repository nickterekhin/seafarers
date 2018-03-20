<?php
/**
 * @var TD_News_Layout1 $obj
 */
use TerekhinDevelopment\td_news_short_codes\src\impl\TD_News_Layout1;
$articles =count($posts_arr);
if($articles>4) $articles=4;
    $posts_right = array_slice($posts_arr,1,$articles);

?>
<?php if($posts_arr){ ?>

<div class="td-news-holder qode-news-holder">
    <h3 style="text-align: left" class="vc_custom_heading td-header">Главные Новости</h3>
    <div class="td-news-section-1 td-news-column-<?php echo $section_1_columns_qty;?>"><div class="td-news-section-1-inner">
            <?php
                $params['display_excerpt']='yes';
                $params['title_tag']='h2';
                echo $obj->render_article($posts_arr[0],$params);
            ?>
       </div><div class="td-news-section-1-inner" >
            <div class="td-news-section-inner-holder">
                <div class="td-news-wrapper td-news-column-<?php echo count($posts_right);?>"><?php
                    if(count($posts_right)!=1)
                        unset($params['display_excerpt']);
                    $params['title_tag']='h3';
                    if(count($posts_right)>2)
                        $params['title_tag']='h4';
                    if(count($posts_right)>3)
                        $params['title_tag']='h5';

                    echo $obj->render_articles($posts_right,$params);
                    ?></div>

            </div>
        </div></div>
    <?php
    $posts_arr = array_slice($posts_arr,count($posts_right)+1);
    $articles_qty = count($posts_arr);
    ?>
    <div class="td-news-section-2">
        <div class="td-news-section-inner-holder">
            <div class="td-news-wrapper td-news-column-<?php echo $articles_qty;?>"><?php
                $params['title_tag']='h2';
                if($articles_qty==2)
                    $params['title_tag']='h3';
                if($articles_qty==3)
                    $params['title_tag']='h4';
                if($articles_qty==4)
                    $params['title_tag']='h5';
                $params['display_excerpt']='yes';

                echo $obj->render_articles($posts_arr,$params);?>
            </div>

        </div>
    </div>
</div>
<?php } ?>