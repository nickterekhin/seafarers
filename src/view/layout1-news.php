
<?php
global $post;
if(count($posts)>0):
    ?>
<div class="qode-news-holder qode-layout1 qode-news-columns-3 qode-nl-normal-space qode-center-alignment"><?php if(isset($layout_title)):?> <div class="qode-news-list-title-holder"><h3 class="qode-news-layout-title"><?php echo $layout_title;?></h3></div><?php endif;?><div class="qode-news-list-inner-holder" data-number-of-items="3"><?php foreach($posts as $p): ?><div class="qode-news-item qode-layout1-item"><div class="qode-news-item-image-holder">                <div class="qode-post-image"><?php
                    if(!is_single()) {
                        $post = !$post ? $p : $post;
                    }else
                    {
                        $post = $p;
                    }

                    $single['class']=$class;
                    echo $class->View('parts/image',$single);
                    ?>
                </div>
                <div class="qode-news-image-info-holder-left">
                </div>
                <div class="qode-news-image-info-holder-right">
                </div>
            </div>
            <div class="qode-ni-content">
                <?php echo $class->View('parts/category',$single);?>
                <?php echo $class->View('parts/title',$single);?>
                <?php echo $class->View('parts/excerpt',$single);?>
                <?php echo $class->View('parts/date',$single);?>
                <?php echo $class->View('parts/author',$single);?>
            </div></div><?php endforeach;?></div></div><?php endif;?>