/**
 * Reviews JS
 */
if (typeof (jQuery) != 'undefined') {

    (function ($) {
        "use strict";

        $(function () {

            var LAE_Tabs_Mgr = {

                init: function () {

                    var self = this;

                    if ($('.lae-tabs').length === 0) return; // no tabs here

                    /* Triggered when someone pastes a URL with #tab-link into browser address bar and there is a browser refresh. */
                    self.initHash();

                    /* Triggered when an internal link is clicked which points to a tab - eg. a primary menu item which links to a tab */
                    self.initAnchor();

                    /*
                    Triggered when someone pastes a URL with #tab-link into browser address bar and there is NO browser refresh.
                    Only the hash part of the URL changed and hence browser window was not refreshed.
                    */
                    $(window).on("hashchange.lae.tabs", function () {
                        self.initHash();
                    });

                },

                initAnchor: function () {

                    var self = this;

                    $('a[href*="#"]').not('.lae-tab-label').click(function (event) {

                        var hash = $(this).attr('href').split('#').pop();

                        if ('' !== hash) {

                            var $element = $('#' + hash);

                            if ($element.length > 0) {

                                if ($element.hasClass('lae-tab-pane')) {

                                    // Do not allow the anchor to navigate to the tab - we will smooth scroll to the same
                                    event.preventDefault();

                                    self.displayTab($element);
                                }
                            }
                        }

                    });

                },

                initHash: function () {

                    var self = this;

                    var hash = window.location.hash.replace('#', '').split('/').shift();

                    if ('' !== hash) {

                        var $element = $('#' + hash);

                        if ($element.length > 0) {

                            if ($element.hasClass('lae-tab-pane')) {

                                setTimeout(function () {

                                    self.displayTab($element);

                                }, 100);
                            }
                        }
                    }

                },

                displayTab: function ($tabPane) {

                    var index, offset, speed, $tabs, $mobileMenu;

                    offset = .2;

                    speed = 300;

                    $tabs = $tabPane.closest('.lae-tabs');

                    $mobileMenu = $tabs.find('.lae-tab-mobile-menu');

                    // opens the mobile menu
                    $mobileMenu.trigger('click');

                    index = $tabs.find('.lae-tab-pane').index($tabPane);

                    var $tabNav = $tabs.find('.lae-tab-nav > .lae-tab').eq(index);

                    // closes the mobile menu after selecting the required tab
                    $tabNav.trigger('click');

                    $("html, body").animate({
                        scrollTop: Math.round($tabs.offset().top - $(window).height() * offset)
                    }, speed);
                }

            };

            var LAE_Accordion_Mgr = {

                init: function () {

                    var self = this;

                    /* Triggered when someone pastes a URL with #accordion-link into browser address bar and there is a browser refresh. */
                    self.initHash();

                    /* Triggered when an internal link is clicked which points to a accordion - eg. a primary menu item which links to an accordion */
                    self.initAnchor();

                    /*
                    Triggered when someone pastes a URL with #accordion-link into browser address bar and there is NO browser refresh.
                    Only the hash part of the URL changed and hence browser window was not refreshed.
                    */
                    jQuery(window).on("hashchange.lae.accordion", function () {
                        self.initHash();
                    });

                },

                initAnchor: function () {

                    var self = this;

                    jQuery('a[href*="#"]').click(function (event) {

                        var hash = jQuery(this).attr('href').split('#').pop();

                        if ('' !== hash) {

                            var $element = jQuery('#' + hash);

                            if ($element.length > 0) {

                                if ($element.hasClass('lae-panel')) {

                                    // Do not allow the anchor to navigate to the tab - we will smooth scroll to the same
                                    event.preventDefault();

                                    self.displayPanel($element);
                                }
                            }
                        }

                    });

                },

                initHash: function () {

                    var self = this;

                    var hash, $element;

                    hash = window.location.hash.replace('#', '').split('/').shift();

                    if ('' !== hash) {

                        $element = jQuery('#' + hash);

                        if ($element.length > 0) {

                            if ($element.hasClass('lae-panel')) {

                                setTimeout(function () {

                                    self.displayPanel($element);

                                }, 100);
                            }
                        }
                    }

                },

                displayPanel: function ($panel) {

                    var self = this;

                    var offset, speed;

                    offset = .2;

                    speed = 300;

                    // Only trigger click if the panel is not already open. Do not close the same if already open
                    if (!$panel.hasClass('lae-active')) {

                        var $panelLabel = $panel.find('.lae-panel-title').eq(0);

                        $panelLabel.trigger('click');

                    }

                    // Delay the scrolling to enable click action to be complete ensuring all elements are in place
                    setTimeout(function () {

                        jQuery("html, body").animate({
                            scrollTop: $panel.offset().top - jQuery(window).height() * offset
                        }, speed);

                    }, 300);

                }

            };

            LAE_Tabs_Mgr.init();

            LAE_Accordion_Mgr.init();

        });

    }(jQuery));

}