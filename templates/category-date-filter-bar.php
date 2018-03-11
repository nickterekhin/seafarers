<?php
global $terekhin_framework,$wp_query;
$obj = $wp_query->get_queried_object();
if($obj){
    $today = time();
    $today_year = date("Y",$today);
    $today_month = date("F",$today);
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

            seafarers.drop_down_element('year');
            seafarers.drop_down_element('month');
            seafarers.drop_down_element('day');

        });

    })(jQuery);
</script>
<div class="terekhindev-news-date-filter">
    <form action="<?php echo $terekhin_framework->getFormAction($obj); ?>" method="post" id="terekhindev-news-date-search-form">
        <button type="submit" class="qbutton default" name="submit" title="search">
            <i class="fa fa-2x fa-search"></i>
        </button>
        <div class="td-search-dropdown" id="year">
            <button type="button" data-toggle="dropdown" aria-expanded="false">
                <span>
                    <span><?php echo $today_year;?></span>
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
        <div class="td-search-dropdown" id="month">
            <button type="button" data-toggle="dropdown" aria-expanded="false">
                <span>
                    <span><?php echo $today_month;?></span>
                    </span>
            </button>
            <ul role="menu">
                <?php

                for($i=1;$i<=12;$i++)
                {
                    ?>
                    <li>
                        <?php $dateObj = DateTime::createFromFormat('!m',$i); ?>
                        <a href="#" data-item-value="<?php echo $i;?>" data-item-text="<?php echo $dateObj->format('F')?>"><?php
                            echo $dateObj->format('F');
                            ?></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="td-search-dropdown" id="day">
            <button type="button" data-toggle="dropdown" aria-expanded="false">
                <span>
                    <span><?php echo $today_day;?></span>
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
        <?php if(isset($_REQUEST['date-filter']) && !empty($_REQUEST['date-filter'])) {?>
        <button type="button" onclick="location.href='<?php

            echo get_home_url().'/'.$wp_query->query_vars['category_name'];


        ?>'" title="Сбросить фильтр" class="terekhindev-clear-search"><i class="fa fa-remove"></i></button>
        <?php } ?>
        <div style="overflow: hidden">
            <input id="terekhindev-news-text-search" value="<?php echo isset($_GET['filter-search'])?$_GET['filter-search']:'';?>" placeholder="Поиск" name="filter-search" type="text">
        </div>

    </form>
</div>
<?php } ?>