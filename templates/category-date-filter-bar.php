<?php
global $terekhin_framework,$wp_query;
$obj = $wp_query->get_queried_object();
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
        });

    })(jQuery);
</script>
<div class="terekhindev-news-date-filter">
    <form action="<?php echo $terekhin_framework->getFormAction($obj); ?>" method="post" id="terekhindev-news-date-search-form">
        <button type="submit" class="qbutton default" name="submit" title="search">
            <i class="fa fa-2x fa-search"></i>
        </button>
        <?php if(isset($_REQUEST['date-filter']) && !empty($_REQUEST['date-filter'])) {?>
        <button type="button" onclick="location.href='<?php

            echo get_home_url().'/'.$wp_query->query_vars['category_name'];


        ?>'" title="Сбросить фильтр" class="terekhindev-clear-search"><i class="fa fa-remove"></i></button>
        <?php } ?>
        <div style="overflow: hidden">
            <input id="terekhindev-news-date-search" value="<?php echo isset($_GET['date-filter'])?$_GET['date-filter']:'';?>" placeholder="Select date" name="search-news-date" readonly="readonly" class="datepicker" type="text">
        </div>

    </form>
</div>
