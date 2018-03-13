<?php
global $terekhin_framework,$wp_query;
$obj = $wp_query->get_queried_object();
if($obj){
    $today = time();
    $today_year = date("Y",$today);
    $today_month = $terekhin_framework->getMonth(date("n",$today));
    $today_day = date("d",$today);
?>
<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            var form = $j("#terekhindev-news-date-search-form"),
                url = form.attr("action");

            var opt={
                onSelect:function(date,instance)
                {
                    var tmp_url = url;


                    if(/\?/.test(url))
                    {
                        var  m = tmp_url.match(/(.*?)(?=\?)/g);
                        tmp_url = m[0];
                    }
                    tmp_url+='/?date-filter='+date;

                    form.attr("action",tmp_url);
                }
            };
            seafarers.news_date_filter(opt);

            seafarers.set_form_action($('#terekhindev-news-date-search-form'));
            seafarers.drop_down_element('date_year');
            seafarers.drop_down_element('date_month');
            seafarers.drop_down_element('date_day');

        });

    })(jQuery);
</script>
<div class="terekhindev-news-date-filter">
    <form action="<?php echo $terekhin_framework->getFormAction($obj); ?>" method="post" id="terekhindev-news-date-search-form">
        <button type="submit" class="qbutton default" name="submit" title="search">
            <i class="fa fa-2x fa-search"></i>
        </button>
        <div class="td-search-dropdown hidden-xs" id="date_year">
            <button type="button" data-toggle="dropdown" aria-expanded="false">
                <span>
                    <span><?php echo 'Год';?></span>
                    </span>
            </button>
            <ul role="menu">
                <?php
                $curr_year = date("Y",time());
                for($y=$curr_year-2;$y<=$curr_year;$y++)
                {
                ?>
                    <li>
                            <a href="#" data-item-value="<?php echo $y;?>" data-item-text="<?php echo $y;?>"><?php echo $y;?></a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
        <div class="td-search-dropdown hidden-xs" id="date_month">
            <button type="button" data-toggle="dropdown" aria-expanded="false">
                <span>
                    <span><?php echo 'Месяц';?></span>
                    </span>
            </button>
            <ul role="menu">
                <?php

                for($i=1;$i<=12;$i++)
                {
                    ?>
                    <li>

                        <a href="#" data-item-value="<?php echo $i;?>" data-item-text="<?php echo $terekhin_framework->getMonth($i);?>"><?php
                            echo $terekhin_framework->getMonth($i);
                            ?></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="td-search-dropdown hidden-xs" id="date_day">
            <button type="button" data-toggle="dropdown" aria-expanded="false">
                <span>
                    <span><?php echo 'День';?></span>
                    </span>
            </button>
            <ul role="menu">
                <?php

                for($d=1;$d<=date('t',$today);$d++)
                {
                    ?>
                    <li>

                        <a href="#" data-item-value="<?php echo $d;?>" data-item-text="<?php echo $d?>"><?php
                            echo $d;
                            ?></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php if(isset($_REQUEST['date_year']) || isset($_REQUEST['date-month'])|| isset($_REQUEST['date-day'])|| isset($_REQUEST['filter_search'])) {?>
        <button type="button" onclick="location.href='<?php


                echo get_home_url() . '/' . $obj->slug;



        ?>'" title="Сбросить фильтр" class="terekhindev-clear-search"><i class="fa fa-remove"></i></button>
        <?php } ?>
        <div style="overflow: hidden">
            <input id="terekhindev-news-text-search" value="<?php echo isset($_REQUEST['filter_search'])?$_REQUEST['filter_search']:'';?>" placeholder="Найти новостьи" name="filter_search" type="text">
        </div>

    </form>
</div>
<?php } ?>