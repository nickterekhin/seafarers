<div class="qode-news-item qode-layout1-item">
                                <div class="qode-news-item-image-holder">
                                    <div class="qode-post-image">
                                        <?php
                                        echo $obj->View('parts/image',$params);?>
                                    </div>
                                    <div class="qode-news-image-info-holder-left">
                                    </div>
                                    <div class="qode-news-image-info-holder-right">
                                    </div>
                                </div>
                                <div class="qode-ni-content">


                                    <?php echo $obj->View('parts/category',$params);?>
                                    <?php echo $obj->View('parts/title',$params);?>
                                    <?php echo $obj->View('parts/excerpt',$params);?>
                                    <?php echo $obj->View('parts/date',$params);?>


                                </div>
                            </div>