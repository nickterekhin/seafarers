
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



    obj.news_date_filter = function(options)
    {
        var opt = $.extend({
            container_id:"terekhindev-news-date-search"
        },options);
        opt = $.extend(opt,dpOptions);
        var dp = $("#"+opt.container_id).datepicker(opt);
        console.log(dp);
    }

})(jQuery,window.seafarers=window.seafarers||{});