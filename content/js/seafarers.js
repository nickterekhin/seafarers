
(function($,obj){

    obj.news_date_filter = function(options)
    {
        var opt = $.extend({
            container_id:"terekhindev-news-date-search"
        },options);
        $("#"+opt.container_id).datepicker(opt);
    }

})(jQuery,window.seafarers=window.seafarers||{});