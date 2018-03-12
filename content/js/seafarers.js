
var dpOptions = {
    changeMonth: true,
    changeYear: true,
    closeText: 'Закрыть',
    prevText: 'Пред',
    nextText: 'След',
    currentText:'Сегодня',
    monthNames:['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
    monthNamesShort: ['Янв','Фев','Мрт','Апр','Май','Июн','Июл','Авг','Сен','Окт','Нбр','Дек'],
    dayNames: ['Понедельник','Вторник','Среда','Чеверг','Пятница','Суббота','Воскресенье'],
    dayNamesShort: ['Пн','Вт','Ср','Чт','Пт','Сб','Вс'],
    dayNamesMin: ['Пн','Вт','Ср','Чт','Пт','Сб','Вс'],
    dateFormat: 'dd-mm-yy'
};


(function($,obj){

    $(document).ready(function(){
        $('.qode-news-holder').each(function(i,v){
            if($(this).has('div.qode-news-list-inner-holder').length==0)
            {
                var row = $(this).closest('div.vc_row');
                console.log(row);
                row.css('padding-top','25px !important');
                row.css('padding-bottom','25px !important');
                $(this).hide();

            }
        });
    });
    obj.query_params = {};
    obj.form = null;
    obj.form_url = null;
    obj.news_date_filter = function(options)
    {
        var opt = $.extend({
            container_id:"terekhindev-news-date-search",
        },options);
        opt = $.extend(opt,dpOptions);
        var dp = $("#"+opt.container_id).datepicker(opt);

    };

    obj.drop_down_element = function(element_id)
    {
        var _self = this,
            current_value_node = $("#"+element_id+" button span span");
        $("#"+element_id+" button[data-toggle='dropdown']").on('click',function(){
            if($j(this).parent().hasClass("open"))
            {
                $j(this).parent().removeClass("open");
            }
            else
            {
                $j(this).parent().addClass("open");
                $j(this).focus();
            }
        });

        $("#"+element_id+" ul li a").each(function(i,v){

            $(this).click(function(){
                _self.query_params[element_id] =  $(this).attr('data-item-value');
                console.log(_self.query_params);
                current_value_node.text($(this).attr('data-item-text'));
                _self.set_form_action();
            });
        });
        $(document).on("click",function(e){
            if($(e.target).closest("#"+element_id+" button[data-toggle='dropdown']").length===0)
            {
                $("#"+element_id+" button[data-toggle='dropdown']").parent().removeClass("open");
            }
        });
    };
    obj.set_form_action = function(form)
    {
        obj.form = form || obj.form;

        obj.form_url = obj.form_url || obj.form.attr('action');


        var arr = $.map(obj.query_params,function(v,i){
            return i+'='+v;
        });
        console.log(arr);
        if(arr.length>0) {
            obj.form.attr("action", obj.form_url + "?" + arr.join('&'));
        }
    }

})(jQuery,window.seafarers=window.seafarers||{});