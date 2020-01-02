
(function($){
    "use strict";

    var editMode		= false;


    var animatedProgressbar = function(id, type, value, strokeColor, trailColor, strokeWidth, strokeTrailWidth){
        var triggerClass = '.ma-el-progress-bar-'+id;
        if("line" == type) {
            new ldBar(triggerClass, {
                "type"              : 'stroke',
                "path"              : 'M0 10L100 10',
                "aspect-ratio"      : 'none',
                "stroke"			: strokeColor,
                "stroke-trail"	    : trailColor,
                "stroke-width"      : strokeWidth,
                "stroke-trail-width": strokeTrailWidth
            }).set(value);
        }
        if("line-bubble" == type) {
            new ldBar(triggerClass, {
                "type"              : 'stroke',
                "path"              : 'M0 10L100 10',
                "aspect-ratio"      : 'none',
                "stroke"			: strokeColor,
                "stroke-trail"		: trailColor,
                "stroke-width"      : strokeWidth,
                "stroke-trail-width": strokeTrailWidth
            }).set(value);
            $($('.ma-el-progress-bar-'+id).find('.ldBar-label')).animate({
                left: value + '%'
            }, 1000, 'swing');
        }
        if("circle" == type){
            new ldBar(triggerClass, {
                "type"				: 'stroke',
                "path"			    : 'M50 10A40 40 0 0 1 50 90A40 40 0 0 1 50 10',
                "stroke-dir"		: 'normal',
                "stroke"		    : strokeColor,
                "stroke-trail"	    : trailColor,
                "stroke-width"	    : strokeWidth,
                "stroke-trail-width": strokeTrailWidth,
            }).set(value);
        }
        if("fan" == type){
            new ldBar(triggerClass, {
                "type": 'stroke',
                "path": 'M10 90A40 40 0 0 1 90 90',
                "stroke": strokeColor,
                "stroke-trail": trailColor,
                "stroke-width": strokeWidth,
                "stroke-trail-width": strokeTrailWidth,
            }).set(value);
        }
    };


    var getElementSettings = function( $element ) {
        var elementSettings = {},
            modelCID 		= $element.data( 'model-cid' );

        if ( elementorFrontend.isEditMode && modelCID ) {
            var settings 		= elementorFrontend.config.elements.data[ modelCID ],
                settingsKeys 	= elementorFrontend.config.elements.keys[ settings.attributes.widgetType || settings.attributes.elType ];

            jQuery.each( settings.getActiveControls(), function( controlKey ) {
                if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
                    elementSettings[ controlKey ] = settings.attributes[ controlKey ];
                }
            } );
        } else {
            elementSettings = $element.data('settings') || {};
        }

        return elementSettings;
    };


    var Master_Addons = {

//
//         try {
//         (function($) {
//
//         })(jQuery);
// } catch(e) {
//         //We can also throw from try block and catch it here
//         // No Error Show
//     }
//
        // Master Addons: Headlines

        MA_Headlines: function($scope, $) {
            try {
                (function($) {

                    /*----------- Animated Headlines --------------*/

                    //set animation timing
                    var animationDelay = 2500,
                        //loading bar effect
                        barAnimationDelay = 3800,
                        barWaiting = barAnimationDelay - 3000, //3000 is the duration of the transition on the loading bar - set in the scss/css file
                        //letters effect
                        lettersDelay = 50,
                        //type effect
                        typeLettersDelay = 150,
                        selectionDuration = 500,
                        typeAnimationDelay = selectionDuration + 800,
                        //clip effect
                        revealDuration = 600,
                        revealAnimationDelay = 1500;

                    initHeadline();


                    function initHeadline() {
                        //insert <i> element for each letter of a changing word
                        singleLetters($('.cd-headline.letters').find('b'));
                        //initialise headline animation
                        animateHeadline($('.cd-headline'));
                    }

                    function singleLetters($words) {
                        $words.each(function(){
                            var word = $(this),
                                letters = word.text().split(''),
                                selected = word.hasClass('is-visible');
                            for (i in letters) {
                                if(word.parents('.rotate-2').length > 0) letters[i] = '<em>' + letters[i] + '</em>';
                                letters[i] = (selected) ? '<i class="in">' + letters[i] + '</i>': '<i>' + letters[i] + '</i>';
                            }
                            var newLetters = letters.join('');
                            word.html(newLetters).css('opacity', 1);
                        });
                    }

                    function animateHeadline($headlines) {
                        var duration = animationDelay;
                        $headlines.each(function(){
                            var headline = $(this);

                            if(headline.hasClass('loading-bar')) {
                                duration = barAnimationDelay;
                                setTimeout(function(){ headline.find('.cd-words-wrapper').addClass('is-loading') }, barWaiting);
                            } else if (headline.hasClass('clip')){
                                var spanWrapper = headline.find('.cd-words-wrapper'),
                                    newWidth = spanWrapper.width() + 10
                                spanWrapper.css('width', newWidth);
                            } else if (!headline.hasClass('type') ) {
                                //assign to .cd-words-wrapper the width of its longest word
                                var words = headline.find('.cd-words-wrapper b'),
                                    width = 0;
                                words.each(function(){
                                    var wordWidth = $(this).width();
                                    if (wordWidth > width) width = wordWidth;
                                });
                                headline.find('.cd-words-wrapper').css('width', width);
                            };

                            //trigger animation
                            setTimeout(function(){ hideWord( headline.find('.is-visible').eq(0) ) }, duration);
                        });
                    }

                    function hideWord($word) {
                        var nextWord = takeNext($word);

                        if($word.parents('.cd-headline').hasClass('type')) {
                            var parentSpan = $word.parent('.cd-words-wrapper');
                            parentSpan.addClass('selected').removeClass('waiting');
                            setTimeout(function(){
                                parentSpan.removeClass('selected');
                                $word.removeClass('is-visible').addClass('is-hidden').children('i').removeClass('in').addClass('out');
                            }, selectionDuration);
                            setTimeout(function(){ showWord(nextWord, typeLettersDelay) }, typeAnimationDelay);

                        } else if($word.parents('.cd-headline').hasClass('letters')) {
                            var bool = ($word.children('i').length >= nextWord.children('i').length) ? true : false;
                            hideLetter($word.find('i').eq(0), $word, bool, lettersDelay);
                            showLetter(nextWord.find('i').eq(0), nextWord, bool, lettersDelay);

                        }  else if($word.parents('.cd-headline').hasClass('clip')) {
                            $word.parents('.cd-words-wrapper').animate({ width : '2px' }, revealDuration, function(){
                                switchWord($word, nextWord);
                                showWord(nextWord);
                            });

                        } else if ($word.parents('.cd-headline').hasClass('loading-bar')){
                            $word.parents('.cd-words-wrapper').removeClass('is-loading');
                            switchWord($word, nextWord);
                            setTimeout(function(){ hideWord(nextWord) }, barAnimationDelay);
                            setTimeout(function(){ $word.parents('.cd-words-wrapper').addClass('is-loading') }, barWaiting);

                        } else {
                            switchWord($word, nextWord);
                            setTimeout(function(){ hideWord(nextWord) }, animationDelay);
                        }
                    }

                    function showWord($word, $duration) {
                        if($word.parents('.cd-headline').hasClass('type')) {
                            showLetter($word.find('i').eq(0), $word, false, $duration);
                            $word.addClass('is-visible').removeClass('is-hidden');

                        }  else if($word.parents('.cd-headline').hasClass('clip')) {
                            $word.parents('.cd-words-wrapper').animate({ 'width' : $word.width() + 10 }, revealDuration, function(){
                                setTimeout(function(){ hideWord($word) }, revealAnimationDelay);
                            });
                        }
                    }

                    function hideLetter($letter, $word, $bool, $duration) {
                        $letter.removeClass('in').addClass('out');

                        if(!$letter.is(':last-child')) {
                            setTimeout(function(){ hideLetter($letter.next(), $word, $bool, $duration); }, $duration);
                        } else if($bool) {
                            setTimeout(function(){ hideWord(takeNext($word)) }, animationDelay);
                        }

                        if($letter.is(':last-child') && $('html').hasClass('no-csstransitions')) {
                            var nextWord = takeNext($word);
                            switchWord($word, nextWord);
                        }
                    }

                    function showLetter($letter, $word, $bool, $duration) {
                        $letter.addClass('in').removeClass('out');

                        if(!$letter.is(':last-child')) {
                            setTimeout(function(){ showLetter($letter.next(), $word, $bool, $duration); }, $duration);
                        } else {
                            if($word.parents('.cd-headline').hasClass('type')) { setTimeout(function(){ $word.parents('.cd-words-wrapper').addClass('waiting'); }, 200);}
                            if(!$bool) { setTimeout(function(){ hideWord($word) }, animationDelay) }
                        }
                    }

                    function takeNext($word) {
                        return (!$word.is(':last-child')) ? $word.next() : $word.parent().children().eq(0);
                    }

                    function takePrev($word) {
                        return (!$word.is(':first-child')) ? $word.prev() : $word.parent().children().last();
                    }

                    function switchWord($oldWord, $newWord) {
                        $oldWord.removeClass('is-visible').addClass('is-hidden');
                        $newWord.removeClass('is-hidden').addClass('is-visible');
                    }


                })(jQuery);
            } catch(e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }

        },

        // Master Addons: Accordion
        MA_Accordion:function($scope, $) {

            var $advanceAccordion = $scope.find(".ma-advanced-accordion"),
                $accordionHeader = $scope.find(".ma-advanced-accordion-header"),
                $accordionType = $advanceAccordion.data("accordion-type"),
                $accordionSpeed = $advanceAccordion.data("toogle-speed");

            // Open default actived tab
            $accordionHeader.each(function() {
                if ($(this).hasClass("active-default")) {
                    $(this).addClass("show active");
                    $(this)
                        .next()
                        .slideDown($accordionSpeed);
                }
            });

            // Remove multiple click event for nested accordion
            $accordionHeader.unbind("click");

            $accordionHeader.click(function(e) {
                e.preventDefault();

                var $this = $(this);

                if ($accordionType === "accordion") {
                    if ($this.hasClass("show")) {
                        $this.removeClass("show active");
                        $this.next().slideUp($accordionSpeed);
                    } else {
                        $this
                            .parent()
                            .parent()
                            .find(".ma-advanced-accordion-header")
                            .removeClass("show active");
                        $this
                            .parent()
                            .parent()
                            .find(".ma-accordion-tab-content")
                            .slideUp($accordionSpeed);
                        $this.toggleClass("show active");
                        $this.next().slideDown($accordionSpeed);
                    }
                } else {

                    // For acccordion type 'toggle'
                    if ($this.hasClass("show")) {
                        $this.removeClass("show active");
                        $this.next().slideUp($accordionSpeed);
                    } else {
                        $this.addClass("show active");
                        $this.next().slideDown($accordionSpeed);
                    }
                }
            });



        },



        // Master Addons: Tabs

        MA_Tabs:function($scope, $) {

            try {
                (function($) {

                    var $tabsWrapper = $scope.find('[data-tabs]').eq(0);
                    $tabsWrapper.each( function() {
                        var tab = $(this);
                        var isTabActive = false;
                        var isContentActive = false;
                        tab.find('[data-tab]').each( function (){
                            if($(this).hasClass('active')){
                                isTabActive = true;
                            }
                        });
                        tab.find('.ma-el-advance-tab-content').each( function (){
                            if($(this).hasClass('active')){
                                isContentActive = true;
                            }
                        });
                        if(!isContentActive){
                            tab.find('.ma-el-advance-tab-content').eq(0).addClass('active');
                        }
                        if(!isTabActive){
                            tab.find('[data-tab]').eq(0).addClass('active');
                        }
                        tab.find('[data-tab]').click(function() {
                            tab.find('[data-tab]').removeClass('active');
                            tab.find('.ma-el-advance-tab-content').removeClass('active');
                            $(this).addClass('active');
                            tab.find('.ma-el-advance-tab-content').eq($(this).index()).addClass('active');
                        });
                    });

                })(jQuery);
            } catch(e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },


        //Master Addons: Progressbar
        MA_ProgressBar: function ($scope, $){

            try {
                (function($) {

                    const $progressBarWrapper = $scope.find('[data-progress-bar]').eq(0);
                    $progressBarWrapper.waypoint(function () {
                        var element = $(this.element);
                        var id = element.data('id');
                        var type = element.data('type');
                        var value = element.data('progress-bar-value');
                        var strokeWidth = element.data('progress-bar-stroke-width');
                        var strokeTrailWidth = element.data('progress-bar-stroke-trail-width');
                        var color = element.data('stroke-color');
                        var trailColor = element.data('stroke-trail-color');
                        animatedProgressbar(id, type, value, color, trailColor, strokeWidth, strokeTrailWidth);
                        this.destroy();
                    }, {
                        offset: 'bottom-in-view'
                    });

                })(jQuery);
            } catch(e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },

        //Master Addons: Image Filter Gallery
        MA_Image_Filter_Gallery: function ($scope, $){

            let $ma_el_image_filter_gallery_container = $scope.find('.ma-el-image-filter-gallery');
            let $ma_el_image_filter_gallery_nav       = $scope.find('.ma-el-image-filter-nav');

            if ( $.isFunction($.fn.imagesLoaded) ) {
                $ma_el_image_filter_gallery_container.imagesLoaded( function() {
                    if ( $.isFunction($.fn.isotope) ) {
                        $ma_el_image_filter_gallery_container.isotope({
                            filter: '*',
                            itemSelector: '.ma-el-image-filter-item',
                        });
                    }
                });
            }

            $ma_el_image_filter_gallery_nav.on( 'click', 'li', function() {
                $ma_el_image_filter_gallery_nav.find('.active').removeClass('active');
                $(this).addClass('active');

                if ( $.isFunction($.fn.isotope) ) {
                    var selector = $(this).attr('data-filter');
                    $ma_el_image_filter_gallery_container.isotope({
                        filter: selector,
                    });
                    return false;
                }
            });
        },

        //Master Addons: Timeline
        MA_Timeline: function ($scope, $){

            try {
                (function($) {

                    var horzTimeline = $scope.find(".ma-el-timeline-slider-inner");

                    $( horzTimeline ).slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        autoplay: false,
                        autoplaySpeed: 2000,
                        pauseOnHover: false,
                        adaptiveHeight: true,
                        // prevArrow: $('.ma-el-blog-timeline-slider-prev'),
                        // nextArrow: $('.ma-el-blog-timeline-slider-next'),
                        responsive: [
                            {
                                breakpoint: 768,
                                settings: {
                                    arrows: false,
                                    slidesToShow: 2
                                }
                            },
                            {
                                breakpoint: 480,
                                settings: {
                                    arrows: false,
                                    slidesToShow: 1
                                }
                            }
                        ]
                    });


                })(jQuery);
            } catch(e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },

        // Master Addons: Table of Contents
        MA_Table_Of_Contents: function ($scope, $) {

            var tableOfContent = $scope.find(".ma-el-table-of-content"),
                tableDesign = tableOfContent.data('design'),
                tableDropdownMode = tableOfContent.data('dropdown-mode');
            tocbot.init(tableOfContent.data('settings'));

            // console.log(tableDesign);

                // Where to render the table of contents.
                // tocSelector: '.js-toc',
                // contentSelector: '.js-toc-content',
                // headingSelector: 'h1, h2, h3',
                // collapseDepth: 0,
                //
                // ignoreSelector: '.js-toc-ignore',
                // hasInnerContainers: false,
                // linkClass: 'toc-link',
                // extraLinkClasses: '',
                // activeLinkClass: 'is-active-link',
                // listClass: 'toc-list',
                // extraListClasses: '',
                // isCollapsedClass: 'is-collapsed',
                // collapsibleClass: 'is-collapsible',
                // listItemClass: 'toc-list-item',
                //
                // scrollSmooth: true,
                // scrollSmoothDuration: 420,
                // headingsOffset: 400,
                // throttleTimeout: 50,
                // positionFixedSelector: null,
                // positionFixedClass: 'is-position-fixed',
                // fixedSidebarOffset: 'auto',
                // includeHtml: false,
                // onClick: false,
                // orderedList: true,
                // scrollContainer: null,
                // skipRendering: false


            // Offcanvas TOC
            if( tableDesign == "offcanvas"){
                $('.ma-el-toggle-button').on('click', function() {
                    $('body').addClass('show-sidemenu');
                });

                $('.ma-el-offcanvas-close').on('click', function() {
                    $('body').removeClass('show-sidemenu');
                });
            }

            if( tableDesign == "dropdown"){
                // Dropdown TOC
                $('.ma-el-toggle-button').on('click', function() {
                    $('body').addClass('show-sidemenu');
                });
            }



            // Dopdown on click
            if( tableDropdownMode === "click") {
                var ma_el_drop = $('.ma-el-drop').data('ma-el-drop');

                $('.ma-el-drop').removeClass('animated');
                $('.ma-el-drop').removeClass(ma_el_drop);

                $('.table-of-content-layout-dropdown .ma-el-toggle-button').on('click', function() {
                    $('body').removeClass('show-sidemenu');
                    $('.ma-el-drop').toggleClass('animated');
                    $('.table-of-content-layout-dropdown').toggleClass('show-sidemenu');
                    $('.ma-el-drop').toggleClass(ma_el_drop);
                });
            }

            if( tableDropdownMode === "hover"){
                $('.ma-el-toggle-button').on('hover', function() {
                    $('body').addClass('show-sidemenu');
                });
            }


        },




        //Master Addons: News Ticker
        MA_NewsTicker: function ($scope, $){

            try {
                (function($) {
                    $(window).load(function(e) {

                        var newsTickerWrapper = $scope.find(".ma-el-news-ticker"),
                            tickerType = newsTickerWrapper.data('tickertype'),
                            tickerid = newsTickerWrapper.data('tickerid'),
                            feedUrl = newsTickerWrapper.data('feedurl'),
                            feedAnimation = newsTickerWrapper.data('feedanimation'),
                            limitPosts = newsTickerWrapper.data('limitposts'),
                            tickerStyleEffect = newsTickerWrapper.data('scroll'),
                            autoplay = newsTickerWrapper.data('autoplay'),
                            timer = newsTickerWrapper.data('timer');

                        if( tickerType === "content"){

                            $("#" + tickerid + "").breakingNews({
                                effect: "" + tickerStyleEffect + "",
                                autoplay: autoplay,
                                timer: timer,
                                border: false,
                                feed: false,
                                feedlabels: false
                            });
                        }

                        if( tickerType === "feed") {

                            jQuery(function ($) {

                                $(".ma-el-ticker-content-inner").rss( feedUrl ,
                                    {
                                        // how many entries do you want?
                                        // default: 4
                                        // valid values: any integer
                                        limit: limitPosts,

                                        // want to offset results being displayed?
                                        // default: false
                                        // valid values: any integer
                                        offsetStart: false, // offset start point
                                        offsetEnd: false, // offset end point

                                        // will request the API via https
                                        // default: false
                                        // valid values: false, true
                                        ssl: true,


                                        // which server should be requested for feed parsing
                                        // the server implementation is here: https://github.com/sdepold/feedr
                                        // default: feedrapp.info
                                        // valid values: any string
                                        // host: 'my-own-feedr-instance.com',


                                        // option to seldomly render ads
                                        // ads help covering the costs for the feedrapp server hosting and future improvements
                                        // default: true
                                        // valid values: false, true
                                        support: false,


                                        // formats the date with moment.js (optional)
                                        // default: 'dddd MMM Do'
                                        // valid values: see http://momentjs.com/docs/#/displaying/
                                        dateFormat: 'MMMM Do, YYYY',


                                        // localizes the date with moment.js (optional)
                                        // default: 'en'
                                        dateLocale: 'de',


                                        // outer template for the html transformation
                                        // default: "<ul>{entries}</ul>"
                                        // valid values: any string


                                        layoutTemplate: '<ul class="ma-el-ticker-content-items">{entries}</ul>',

                                        // inner template for each entry
                                        // default: '<li><a href="{url}">[{author}@{date}] {title}</a><br/>{shortBodyPlain}</li>'
                                        // valid values: any string
                                        // entryTemplate: '<p>{title}</p>',
                                        // entryTemplate: '<li><a href="{url}">[{author}@{date}] {title}</a>{teaserImage}{shortBodyPlain}</li>'
                                        entryTemplate: '<li> {teaserImage} <a href="{url}"> {title}</a></li>',

                                        // the effect, which is used to let the entries appear
                                        // default: 'show'
                                        // valid values: 'show', 'slide', 'slideFast', 'slideSynced', 'slideFastSynced'
                                        effect: feedAnimation,

                                    }, function() {

                                        $("#" + tickerid + "").breakingNews({
                                            effect: "" + tickerStyleEffect + "",
                                            autoplay: autoplay,
                                            timer: timer
                                        });
                                    })
                            });

                        }

                    }); // End of Window load

                })(jQuery);
            } catch(e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },


        /*
         * Master Addons: MA Blog Posts
         */

        MA_Blog: function ($scope, $){
            var blogElement = $scope.find(".ma-el-blog-wrapper"),
                colsNumber  = blogElement.data("col"),
                carousel    = blogElement.data("carousel"),
                grid        = blogElement.data("grid");

            $scope.find(".ma-el-blog-cats-container li a").click(function (e) {
                e.preventDefault();

                $scope
                    .find(".ma-el-blog-cats-container li .active")
                    .removeClass("active");

                $(this).addClass("active");

                var selector = $(this).attr("data-filter");

                blogElement.isotope({ filter: selector });

                return false;
            });

            var masonryBlog = blogElement.hasClass( "ma-el-blog-masonry" );

            if ( masonryBlog && ! carousel ) {
                blogElement.imagesLoaded( function () {
                    blogElement.isotope({
                        itemSelector: ".ma-el-post-outer-container",
                        percentPosition: true,
                        animationOptions: {
                            duration: 750,
                            easing: "linear",
                            queue: false
                        }
                    });
                });
            }

            if ( carousel && grid ) {

                var autoPlay    = blogElement.data("play"),
                    speed       = blogElement.data("speed"),
                    fade        = blogElement.data("fade"),
                    arrows      = blogElement.data("arrows"),
                    dots        = blogElement.data("dots"),
                    prevArrow   = null,
                    nextArrow   = null;

                if ( arrows ) {
                    prevArrow= "<div class='ma-el-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
                        nextArrow= "<div class='ma-el-team-carousel-next'><i class='fa fa-angle-right'></i></div>"
                    // prevArrow   = '<a type="button" data-role="none" class="carousel-arrow carousel-prev" aria-label="Next" role="button" style=""><i class="fas fa-angle-left" aria-hidden="true"></i></a>',
                    //     nextArrow   = '<a type="button" data-role="none" class="carousel-arrow carousel-next" aria-label="Next" role="button" style=""><i class="fas fa-angle-right" aria-hidden="true"></i></a>';
                } else {
                    prevArrow = prevArrow = '';
                }

                $( blogElement ).slick({
                    infinite:       true,
                    slidesToShow:   colsNumber,
                    slidesToScroll: colsNumber,
                    responsive:     [
                        {
                            breakpoint: 769,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 481,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ],
                    autoplay:       autoPlay,
                    autoplaySpeed:  speed,
                    nextArrow:      nextArrow,
                    prevArrow:      prevArrow,
                    fade:           fade,
                    draggable:      true,
                    dots:           dots,
                    customPaging:   function () {
                        return (
                            '<i class="fas fa-circle"></i>'
                        );
                    }
                });
            }
        },


        /**** MA Image Carousel ****/
        MA_Image_Carousel: function($scope,$){

            try {
                (function($) {

                    let $imageCarouselWrapper = $scope.find('.ma-el-image-carousel').eq(0),
                        $carousel_nav = $imageCarouselWrapper.data("carousel-nav"),
                        $loop = ($imageCarouselWrapper.data("loop") !== undefined) ? $imageCarouselWrapper.data("loop") : false,
                        $slidesToShow = $imageCarouselWrapper.data("slidestoshow"),
                        $slidesToScroll = $imageCarouselWrapper.data("slidestoscroll"),
                        $autoPlay = ($imageCarouselWrapper.data("autoplay") !== undefined) ? $imageCarouselWrapper.data("autoplay") : false,
                        $autoplaySpeed = ($imageCarouselWrapper.data("autoplayspeed") !== undefined) ? $imageCarouselWrapper.data("autoplayspeed") : false,
                        $transitionSpeed = $imageCarouselWrapper.data("speed"),
                        $pauseOnHover = ($imageCarouselWrapper.data("pauseonhover") !== undefined) ? $imageCarouselWrapper.data("pauseonhover") : false


                    // Team Carousel
                    if ($carousel_nav == "arrows" ) {
                        var arrows = true;
                        var dots = false;
                    } else {
                        var arrows = false;
                        var dots = true;
                    }

                    $imageCarouselWrapper.slick({
                        infinite: $loop,
                        slidesToShow : $slidesToShow,
                        slidesToScroll: $slidesToScroll,
                        autoplay: $autoPlay,
                        autoplaySpeed: $autoplaySpeed,
                        speed: $transitionSpeed,
                        pauseOnHover: $pauseOnHover,
                        dots: dots,
                        arrows: arrows,
                        prevArrow: "<div class='ma-el-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
                        nextArrow: "<div class='ma-el-team-carousel-next'><i class='fa fa-angle-right'></i></div>",
                        rows: 0,
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 3,
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    slidesToShow: 2,
                                }
                            }
                        ],
                    });

                })(jQuery);
            } catch(e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }
        },


        /**** MA Team Slider ****/

        MA_TeamSlider: function ($scope, $){

            let $teamCarouselWrapper = $scope.find('.ma-el-team-members-slider-section').eq(0),
                $team_preset = $teamCarouselWrapper.data("team-preset"),
                $ma_el_team_circle_image_animation = $teamCarouselWrapper.data("ma_el_team_circle_image_animation");

            if( $team_preset == "-content-drawer"){

                // console.log("Content Drawer Loaded");

                try {
                    (function($) {

                        $('.gridder').gridderExpander({
                            scroll: false,
                            scrollOffset: 0,
                            scrollTo: "panel",                  // panel or listitem
                            animationSpeed: 400,
                            animationEasing: "easeInOutExpo",
                            showNav: true, // Show Navigation
                            nextText: "<span></span>", // Next button text
                            prevText: "<span></span>", // Previous button text
                            closeText: "", // Close button text
                            onStart: function(){
                                //Gridder Inititialized
                            },
                            onContent: function(){
                                //Gridder Content Loaded
                            },
                            onClosed: function(){
                                //Gridder Closed
                            }
                        });

                    })(jQuery);
                } catch(e) {
                    //We can also throw from try block and catch it here
                    // No Error Show
                }


            } else {
                // console.log("Slider Loaded");

                try {
                    (function($) {

                        let $teamCarouselWrapper = $scope.find('.ma-el-team-members-slider-section').eq(0),
                            $carousel_nav = $teamCarouselWrapper.data("carousel-nav"),
                            $loop = ($teamCarouselWrapper.data("loop") !== undefined) ? $teamCarouselWrapper.data("loop") : false,
                            $slidesToShow = $teamCarouselWrapper.data("slidestoshow"),
                            $slidesToScroll = $teamCarouselWrapper.data("slidestoscroll"),
                            $autoPlay = ($teamCarouselWrapper.data("autoplay") !== undefined) ? $teamCarouselWrapper.data("autoplay") : false,
                            $autoplaySpeed = ($teamCarouselWrapper.data("autoplayspeed") !== undefined) ? $teamCarouselWrapper.data("autoplayspeed") : false,
                            $transitionSpeed = $teamCarouselWrapper.data("speed"),
                            $pauseOnHover = ($teamCarouselWrapper.data("pauseonhover") !== undefined) ? $teamCarouselWrapper.data("pauseonhover") : false


                        // Team Carousel
                        if ($carousel_nav == "arrows" ) {
                            var arrows = true;
                            var dots = false;
                        } else {
                            var arrows = false;
                            var dots = true;
                        }

                        $teamCarouselWrapper.slick({
                            infinite: $loop,
                            slidesToShow : $slidesToShow,
                            slidesToScroll: $slidesToScroll,
                            autoplay: $autoPlay,
                            autoplaySpeed: $autoplaySpeed,
                            speed: $transitionSpeed,
                            pauseOnHover: $pauseOnHover,
                            dots: dots,
                            arrows: arrows,
                            prevArrow: "<div class='ma-el-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
                            nextArrow: "<div class='ma-el-team-carousel-next'><i class='fa fa-angle-right'></i></div>",
                            rows: 0,
                            responsive: [
                                {
                                    breakpoint: 1024,
                                    settings: {
                                        slidesToShow: 3,
                                    }
                                },
                                {
                                    breakpoint: 576,
                                    settings: {
                                        slidesToShow: 2,
                                    }
                                }
                            ],
                        });

                    })(jQuery);
                } catch(e) {
                    //We can also throw from try block and catch it here
                    // No Error Show
                }

            }

            // else if ($team_preset == "-circle-animation"){
            //     if($ma_el_team_circle_image_animation == "animation_svg_04"){
            //
            //     }
            // }

        },


        MA_ParticlesBG: function ($scope, $) {

            // try {
            //     (function($scope, $) {

            if ($scope.hasClass('ma-el-particle-yes')) {
                let id = $scope.data('id');
                //console.lgo(id);
                let element_type = $scope.data('element_type');
                let pdata = $scope.data('ma-el-particle');
                let pdata_wrapper = $scope.find('.ma-el-particle-wrapper').data('ma-el-pdata');

                if (typeof pdata != 'undefined' && pdata != '') {
                    if ($scope.find('.ma-el-section-bs').length > 0) {
                        $scope.find('.ma-el-section-bs').after('<div class="ma-el-particle-wrapper"' +
                            ' id="ma-el-particle-' + id + '"></div>');
                        particlesJS('ma-el-particle-' + id, pdata);
                    } else {

                        if (element_type == 'column') {

                            $scope.find('.elementor-column-wrap').prepend('<div class="ma-el-particle-wrapper"' +
                                ' id="ma-el-particle-' + id + '"></div>');
                        } else {
                            $scope.prepend('<div class="ma-el-particle-wrapper" id="ma-el-particle-' + id + '"></div>');
                        }

                        particlesJS('ma-el-particle-' + id, pdata);
                    }


                } else if (typeof pdata_wrapper != 'undefined' && pdata_wrapper != '') {

                    // $scope.prepend('<div class="ma-el-particle-wrapper" id="ma-el-particle-'+ id +'"></div>');
                    //console.log('calling particle js else', JSON.parse(pdata_wrapper));
                    if (element_type == 'column') {
                        $scope.find('.elementor-column-wrap').prepend('<div class="ma-el-particle-wrapper"' +
                            ' id="ma-el-particle-' + id + '"></div>');
                    }
                    else{
                        $scope.prepend('<div class="ma-el-particle-wrapper" id="ma-el-particle-' + id + '"></div>');
                    }

                    particlesJS('ma-el-particle-' + id, JSON.parse(pdata_wrapper));
                }

            }
            //
            //     })(jQuery);
            // } catch(e) {
            //     //We can also throw from try block and catch it here
            //     // No Error Show
            // }



        },

        MA_BgSlider: function ($scope, $){
            var ma_el_slides = [];
            var ma_el_slides_json = [];
            var ma_el_transition;
            var ma_el_animation;
            var ma_el_custom_overlay;
            var ma_el_overlay;
            var ma_el_cover;
            var ma_el_delay;
            var ma_el_timer;
            var slider_wrapper = $scope.children('.ma-el-section-bs').children('.ma-el-section-bs-inner');

            if (slider_wrapper && slider_wrapper.data('ma-el-bg-slider')) {

                var slider_images = slider_wrapper.data('ma-el-bg-slider');
                ma_el_transition = slider_wrapper.data('ma-el-bg-slider-transition');
                ma_el_animation = slider_wrapper.data('ma-el-bg-slider-animation');
                ma_el_custom_overlay = slider_wrapper.data('ma-el-bg-custom-overlay');
                if (ma_el_custom_overlay == 'yes') {
                    ma_el_overlay = ma_el_editor.plugin_url + 'assets/lib/vegas/overlays/' + slider_wrapper.data('ma-el-bg-slider-overlay');
                } else {
                    if (slider_wrapper.data('ma-el-bg-slider-overlay')) {
                        ma_el_overlay = ma_el_editor.plugin_url + 'assets/lib/vegas/overlays/' + slider_wrapper.data('ma-el-bg-slider-overlay');
                    } else {
                        ma_el_overlay = ma_el_editor.plugin_url + 'assets/lib/vegas/overlays/' + slider_wrapper.data('ma-el-bg-slider-overlay');
                    }
                }

                ma_el_cover = slider_wrapper.data('ma-el-bg-slider-cover');
                ma_el_delay = slider_wrapper.data('ma-el-bs-slider-delay');
                ma_el_timer = slider_wrapper.data('ma-el-bs-slider-timer');

                if (typeof slider_images != 'undefined') {
                    ma_el_slides = slider_images.split(",");

                    jQuery.each(ma_el_slides, function (key, value) {
                        var slide = [];
                        slide.src = value;
                        ma_el_slides_json.push(slide);
                    });

                    slider_wrapper.vegas({
                        slides: ma_el_slides_json,
                        transition: ma_el_transition,
                        animation: ma_el_animation,
                        overlay: ma_el_overlay,
                        cover: ma_el_cover,
                        delay: ma_el_delay,
                        timer: ma_el_timer,
                        init: function () {
                            if (ma_el_custom_overlay == 'yes') {
                                var ob_vegas_overlay = slider_wrapper.children('.vegas-overlay');
                                ob_vegas_overlay.css('background-image', '');
                            }
                        }
                    });

                }
            }
        },

        MA_AnimatedGradient: function ($scope, $) {

            if ($scope.hasClass('ma-el-animated-gradient-yes')) {
                let id = $scope.data('id');
                let color = $scope.data('color');
                let angle = $scope.data('angle');
                let gradient_color = 'linear-gradient(' + angle + ',' + color + ')';
                let heading = $scope.find('.elementor-heading-title');
                $scope.css('background-image', gradient_color);

                if($scope.hasClass('elementor-element-edit-mode')){
                    color = $scope.find('.animated-gradient').data('color');
                    angle = $scope.find('.animated-gradient').data('angle');
                    let gradient_color_editor = 'linear-gradient(' + angle + ',' + color + ')';
                    // console.log(gradient_color_editor);
                    $scope.prepend('<div class="animated-gradient" style="background-image : ' + gradient_color_editor + ' "></div>');
                    //$scope.find('.animated-gradient').css('background-image', gradient_color_editor);
                    //$scope.find('.animated-gradient').css('background-color', 'red');
                }
                //$scope.css('position', 'relative');
                //$scope.css('background-color', 'black');

            }

        },



        MA_PiechartsHandlerOnScroll: function ($scope, $) {

            $scope.waypoint(function (direction) {

                Master_Addons.MA_PiechartsHandler($(this.element), $);

            }, {
                offset: (window.innerHeight || document.documentElement.clientHeight) - 100,
                triggerOnce: true
            });
        },

        MA_PiechartsHandler : function ($scope, $) {

            $scope.find('.ma-el-piechart .ma-el-percentage').each(function () {

                var track_color = $(this).data('track-color');
                var bar_color = $(this).data('bar-color');

                $(this).easyPieChart({
                    animate: 2000,
                    lineWidth: 10,
                    barColor: bar_color,
                    trackColor: track_color,
                    scaleColor: false,
                    lineCap: 'square',
                    size: 220

                });

            });

        },

        StatsBarHandler: function ($scope, $) {

            $scope.find('.ma-el-stats-bar-content').each(function () {

                var dataperc = $(this).data('perc');

                $(this).animate({"width": dataperc + "%"}, dataperc * 20);

            });

        },

        StatsBarHandlerOnScroll: function ($scope, $) {

            $scope.waypoint(function (direction) {

                Master_Addons.StatsBarHandler($(this.element), $);

            }, {
                offset: (window.innerHeight || document.documentElement.clientHeight) - 150,
                triggerOnce: true
            });

        },



        // Master Addons: Countdown Timer
        MA_CountdownTimer:function ($scope, $) {

            try {
                (function($) {


                    var $countdownTimerWrapper = $scope.find('[data-countdown]').eq(0);

                    if (typeof $countdownTimerWrapper !== 'undefined' && $countdownTimerWrapper !== null) {
                        var $this = $countdownTimerWrapper,
                            finalDate = $this.data('countdown'),
                            day = $this.data('day'),
                            hours = $this.data('hours'),
                            minutes = $this.data('minutes'),
                            seconds = $this.data('seconds');

                        $this.countdown(finalDate, function (event) {
                            $(this).html(event.strftime(' ' +
                                '<div class="ma-el-countdown-container"><span class="ma-el-countdown-count">%-D </span><span' +
                                ' class="ma-el-countdown-title">' + day + '</span></div>' +
                                '<div class="ma-el-countdown-container"><span class="ma-el-countdown-count">%H </span><span' +
                                ' class="ma-el-countdown-title">' + hours + '</span></div>' +
                                '<div class="ma-el-countdown-container"><span class="ma-el-countdown-count">%M </span><span' +
                                ' class="ma-el-countdown-title">' + minutes + '</span></div>' +
                                '<div class="ma-el-countdown-container"><span class="ma-el-countdown-count">%S </span><span' +
                                ' class="ma-el-countdown-title">' + seconds + '</span></div>'));
                        }).on('finish.countdown', function (event) {
                            $(this).html("<p class='message'>Hurrey! This is event day</p>");
                        });
                    }

                })(jQuery);
            } catch(e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }

        },

        /**
         * Fancybox popup
         */
        MA_Fancybox_Popup:function ($scope, $) {

            try {
                (function($) {
                    if ( $.isFunction($.fn.fancybox) ) {
                        $("[data-fancybox]").fancybox({});
                    }
                })(jQuery);
            } catch(e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }

        }


    };



    window.eeTooltips = function() {
        var self = this;

        self.remove = function( $scope ) {

            if ( $scope.length ) {
                // Remove just the tooltips within the scope
                var scopeId = $scope.data('id'),
                    $hotips = $( '.hotip-tooltip[data-target-id="' + scopeId + '"]' );
            } else {

                // Remove all tooltips on page
                $hotips = $( '.hotip-tooltip' );
            }

            if ( $hotips.length ) {
                $hotips.remove();
            }
        };
    };


    $(window).on('elementor/frontend/init', function () {
        if( elementorFrontend.isEditMode() ) {
            editMode = true;
        }

        //Global Scripts
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_AnimatedGradient);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_BgSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_ParticlesBG);

        //Element Scripts
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-headlines.default', Master_Addons.MA_Headlines);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-advanced-accordion.default', Master_Addons.MA_Accordion);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-tabs.default', Master_Addons.MA_Tabs);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-progressbar.default', Master_Addons.MA_ProgressBar);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-team-members-slider.default', Master_Addons.MA_TeamSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-image-carousel.default', Master_Addons.MA_Image_Carousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-blog-post.default', Master_Addons.MA_Blog);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-news-ticker.default', Master_Addons.MA_NewsTicker);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-table-of-contents.default', Master_Addons.MA_Table_Of_Contents);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-el-countdown-timer.default', Master_Addons.MA_CountdownTimer);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-piecharts.default', Master_Addons.MA_PiechartsHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-timeline.default', Master_Addons.MA_Timeline);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-image-filter-gallery.default', Master_Addons.MA_Image_Filter_Gallery);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-image-filter-gallery.default', Master_Addons.MA_Fancybox_Popup);


        if (elementorFrontend.isEditMode()) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-piecharts.default', Master_Addons.MA_PiechartsHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-progressbars.default', Master_Addons.StatsBarHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-news-ticker.default', Master_Addons.MA_NewsTicker);
        } else{
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-piecharts.default', Master_Addons.MA_PiechartsHandlerOnScroll);
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-progressbars.default', Master_Addons.StatsBarHandlerOnScroll);
        }




    });

})(jQuery);