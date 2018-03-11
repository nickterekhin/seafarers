
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

    obj.query_params = {};

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
            });
        });
        $(document).on("click",function(e){
            if($(e.target).closest("#"+element_id+" button[data-toggle='dropdown']").length===0)
            {
                $("#"+element_id+" button[data-toggle='dropdown']").parent().removeClass("open");
            }
        });
    }

})(jQuery,window.seafarers=window.seafarers||{});