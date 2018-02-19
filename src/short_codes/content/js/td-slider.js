(function($) {
    "use strict";

    var slider1 = {};
    //qode.modules.slider1 = slider1;

    slider1.qodeInitslider1 = qodeInitslider1;

    slider1.qodeOnDocumentReady = qodeOnDocumentReady;
    slider1.qodeOnWindowLoad = qodeOnWindowLoad;
    slider1.qodeOnWindowResize = qodeOnWindowResize;

    $(document).ready(qodeOnDocumentReady);
    $(window).load(qodeOnWindowLoad);
    $(window).resize(qodeOnWindowResize);

    /*
     All functions to be called on $(document).ready() should be in this function
     */
    function qodeOnDocumentReady() {

        qodeInitslider1();
    }

    /*
     All functions to be called on $(window).load() should be in this function
     */
    function qodeOnWindowLoad() {
    }

    /*
     All functions to be called on $(window).resize() should be in this function
     */
    function qodeOnWindowResize() {
    }

    /**
     * Init Owl Carousel
     */
    function qodeInitslider1() {
        var sliders = $('.td-slider-owl');

        if (sliders.length) {
            sliders.each(function () {
                var slider = $(this),
                    slideItemsNumber = slider.children().length,
                    numberOfItems = 1,
                    loop = false,
                    autoplay = true,
                    autoplayHoverPause = false,
                    sliderSpeed = 4000,
                    sliderSpeedAnimation = 600,
                    margin = 0,
                    responsiveMargin = 0,
                    stagePadding = 0,
                    stagePaddingEnabled = false,
                    center = false,
                    autoWidth = false,
                    navigation = false,
                    pagination = true;


                if (slideItemsNumber <= 1) {
                    loop = false;
                    autoplay = false;
                    navigation = false;
                    pagination = false;
                }

                slider.owlCarousel({
                    items: numberOfItems,
                    loop: loop,
                    autoplay: autoplay,
                    autoplayHoverPause: autoplayHoverPause,
                    autoplaySpeed: sliderSpeedAnimation,
                    autoplayTimeout: sliderSpeed,
                    margin: margin,
                    stagePadding: stagePadding,
                    center: center,
                    autoWidth: autoWidth,
                    dots: pagination,
                    nav: navigation,
                    animateIn: 'fadeIn',
                    animateOut: 'fadeOut',
                    navText: [
                        '<span class="qode-prev-icon ion-ios-arrow-left"></span>',
                        '<span class="qode-next-icon ion-ios-arrow-right"></span>'
                    ],
                    onInitialize: function () {
                        slider.css('visibility', 'visible');
                    },
                    onInitialized: function (e) {
                        var paginationHolder = slider.find('.owl-dots'),
                            pagination = paginationHolder.find('.owl-dot'),
                            paginationPadding = slider.parents('.qode-slider-td').data('content-padding');

                        if (slider.parent().data('content-in-grid') == 'yes') {
                            paginationHolder.wrapAll('<div class="qode-slider1-nav-holder"><div class="container_inner"><div class="qode-slider-nav-holder-inner"><div class="qode-slider-nav-holder-inner2"></div></div></div></div>');
                        } else {
                            paginationHolder.wrapAll('<div class="qode-slider1-nav-holder"><div class="qode-slider-nav-holder-inner"><div class="qode-slider-nav-holder-inner2"></div></div></div>');
                        }

                        if (typeof paginationPadding !== 'undefined') {
                            paginationHolder.parents('.qode-slider-nav-table').css({padding: paginationPadding.replace(/,/g, ' ')});
                        }

                        pagination.each(function (e) {
                            var thisPag = $(this),
                                thisElement = slider.find('.owl-item').eq(e),
                                thisElementDate = thisElement.find('.qode-news-item').data('date'),
                                thisElementTitle = thisElement.find('.qode-post-title a').html(),
                                thisElementThumb = thisElement.find('.qode-news-item').data('thumb-url');

                            thisPag.html('<div class="qode-slider1-pag-thumb"><img alt="thumb" src="' + thisElementThumb + '" /></div><div class="qode-slider1-pag-info-holder"><h5 class="qode-slider1-pag-title">' + thisElementTitle + '</h5><div class="qode-slider1-pag-date"><i class="dripicons-alarm"></i>' + thisElementDate + '</div></div>');
                        });

                        qodeSlider1NavigationScroll(paginationHolder);
                    }
                });
            });
        }
    }

    function qodeSlider1NavigationScroll(paginationHolder) {

        if (paginationHolder.length) {
            paginationHolder.niceScroll({
                scrollspeed: 60,
                mousescrollstep: 40,
                cursorwidth: '2px',
                cursorborder: '0',
                cursorborderradius: 0,
                cursorcolor: "#fff",
                background: "rgba(255,255,255,0.5)",
                autohidemode: false,
                horizrailenabled: false,
                zindex: 5
            });
        }
    }
})(jQuery);