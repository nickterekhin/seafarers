<?php
global $terekhin_framework,$wp_query;

?>
<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            seafarers.news_date_filter();
        });

    })(jQuery);
</script>
<div class="terekhindev-news-date-filter">
    <form action="<?php echo $terekhin_framework->getFormAction($obj); ?>" method="post" id="terekhindev-news-date-search-form">
        <button type="submit" class="qbutton default" name="submit" title="search">
            <i class="fa fa-2x fa-search"></i>
        </button>
        <div style="overflow: hidden">
            <input id="terekhindev-news-date-search" value="" placeholder="Select date" name="search-news-date" readonly="readonly" class="datepicker" type="text">
        </div>

    </form>
</div>
