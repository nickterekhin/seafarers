
(function($,obj){

    obj.news_date_filter = function(options)
    {
        var opt = $.extend({
            container_id:"terekhindev-news-date-search"
        },options);
        var dp = $("#"+opt.container_id).datepicker(opt);
        console.log(dp);
    }

})(jQuery,window.seafarers=window.seafarers||{});