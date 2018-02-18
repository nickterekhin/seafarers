<?php
/**
 * @var TD_PostSlider_Short_Code $obj
 * @var array $params
**/
use TerekhinDevelopment\short_codes\src\impl\TD_PostSlider_Short_Code;

?>
<div class="qode-news-item qode-slider1-item <?php echo esc_attr($item_classes);?>" <?php echo $item_data_params; ?>>
    <div class="qode-news-item-image-holder" <?php qode_inline_style($background_style);?>></div>
    <div class="qode-ni-content">
        <?php if ($content_in_grid == 'yes') { ?>
        <div class="container_inner">
            <?php } ?>
            <div class="qode-ni-content-table" <?php qode_inline_style($content_style);?>>
                <div class="qode-ni-content-table-cell">
                    <?php if ($display_categories == 'yes') { ?>
                        <div class="qode-ni-info-top-holder">
                            <div class="qode-ni-info qode-ni-info-top">
                                <?php echo $obj->View('templates/slider_parts/category',$params);?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="qode-ni-title-holder">
                        <?php echo $obj->View('templates/slider_parts/title',$params);?>
                    </div>
                    <?php if ($display_button == 'yes') { ?>
                        <div class="qode-news-info-holder">
                            <?php
                            echo qode_get_button_v2_html(array(
                                'text' => 'Read More',
                                'link' => get_the_permalink()
                            ));
                            ?>
                        </div>
                    <?php } ?>
                    <?php echo $this->View('templates/slider_parts/excerpt',$params);?>
                    <?php echo $this->View('templates/slider_parts/share',$params);?>
                </div>
            </div>
            <?php if ($content_in_grid == 'yes') { ?>
        </div>
    <?php } ?>
    </div>
</div>