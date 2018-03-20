<div class="td-news-item td-news-layout1">
    <div class="td-news-item-image-holder">
        <div class="td-news-image">

        <?php
        echo $obj->View('parts/image',$params);?>
        </div>
    </div>
    <div class="td-news-content">
        <?php echo $obj->View('parts/title',$params);?>
        <?php echo $obj->View('parts/excerpt',$params);?>
    </div>
</div>