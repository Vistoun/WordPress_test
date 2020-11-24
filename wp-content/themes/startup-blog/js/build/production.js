/*global jQuery */
/*jshint browser:true */
/*!
 * FitVids 1.1
 *
 * Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
 * Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
 * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
 *
 */

;(function( $ ){

    'use strict';

    $.fn.fitVids = function( options ) {
        var settings = {
            customSelector: null,
            ignore: null
        };

        if(!document.getElementById('fit-vids-style')) {
            // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
            var head = document.head || document.getElementsByTagName('head')[0];
            var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
            var div = document.createElement("div");
            div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
            head.appendChild(div.childNodes[1]);
        }

        if ( options ) {
            $.extend( settings, options );
        }

        return this.each(function(){
            var selectors = [
                'iframe[src*="player.vimeo.com"]',
                'iframe[src*="youtube.com"]',
                'iframe[src*="youtube-nocookie.com"]',
                'iframe[src*="kickstarter.com"][src*="video.html"]',
                'object',
                'embed'
            ];

            if (settings.customSelector) {
                selectors.push(settings.customSelector);
            }

            var ignoreList = '.fitvidsignore';

            if(settings.ignore) {
                ignoreList = ignoreList + ', ' + settings.ignore;
            }

            var $allVideos = $(this).find(selectors.join(','));
            $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
            $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

            $allVideos.each(function(){
                var $this = $(this);
                if($this.parents(ignoreList).length > 0) {
                    return; // Disable FitVids on this video.
                }
                if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
                if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
                {
                    $this.attr('height', 9);
                    $this.attr('width', 16);
                }
                var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
                    width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
                    aspectRatio = height / width;
                if(!$this.attr('id')){
                    var videoID = 'fitvid' + Math.floor(Math.random()*999999);
                    $this.attr('id', videoID);
                }
                $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
                $this.removeAttr('height').removeAttr('width');
            });
        });
    };
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );
jQuery(document).ready(function($){

    var menuPrimaryContainer = $('#menu-primary-container');
    var menuPrimary = $('#menu-primary');
    var menuPrimaryItems = $('#menu-primary-items');
    var menuSecondaryContainer = $('#menu-secondary-container');
    var menuSecondary = $('#menu-secondary');
    var menuSecondaryItems = $('#menu-secondary-items');
    var toggleNavigation = $('#toggle-navigation');
    var toggleNavigationSecondary = $('#toggle-navigation-secondary');
    var menuLink = $('.menu-item').children('a');
    var slider = $('#bb-slide-list');
    var sliderContainer = $('#bb-slider');
    const sliderTime = objectL10n.sliderTime === '' ? 5 : objectL10n.sliderTime;

    $('.post-content').fitVids({
        customSelector: 'iframe[src*="dailymotion.com"], iframe[src*="slideshare.net"], iframe[src*="animoto.com"], iframe[src*="blip.tv"], iframe[src*="funnyordie.com"], iframe[src*="hulu.com"], iframe[src*="ted.com"], iframe[src*="wordpress.tv"]'
    });

    objectFitAdjustment();
    $(window).on('resize', function(){
        objectFitAdjustment();
    });
    // Jetpack infinite scroll event that reloads posts.
    $( document.body ).on( 'post-load', function () {
        objectFitAdjustment();
    } );

    toggleNavigation.on('click', function() {

        if( menuPrimaryContainer.hasClass('open') ) {
            menuPrimaryContainer.removeClass('open');
            $(this).removeClass('open');

            menuPrimaryContainer.css('max-height','');

            // change screen reader text
            $(this).children('span').text(objectL10n.openMenu);

            // change aria text
            $(this).attr('aria-expanded', 'false');

        } else {
            if ( menuPrimaryItems.length == 0 ) {
                menuPrimaryItems = $('.menu-unset');
            }
            menuPrimaryContainer.addClass('open');
            $(this).addClass('open');
            var maxHeight = 0;
            menuPrimaryItems.find('li').each(function(){
                maxHeight += 60;
            });
            maxHeight += 36;
            menuPrimaryContainer.css('max-height', maxHeight );

            // change screen reader text
            $(this).children('span').text(objectL10n.closeMenu);

            // change aria text
            $(this).attr('aria-expanded', 'true');
        }
    });

    toggleNavigationSecondary.on('click', function() {

        if( menuSecondaryContainer.hasClass('open') ) {
            menuSecondaryContainer.removeClass('open');
            $(this).removeClass('open');

            menuSecondaryContainer.css('max-height', 0 );

            // change screen reader text
            $(this).children('.screen-reader-text').text(objectL10n.openMenu);

            // change aria text
            $(this).attr('aria-expanded', 'false');

        } else {
            menuSecondaryContainer.addClass('open');
            $(this).addClass('open');
            var maxHeight = 0;
            menuSecondaryItems.find('li').each(function(){
                maxHeight += 60;
            });
            maxHeight += 36;
            menuSecondaryContainer.css('max-height', maxHeight );

            // change screen reader text
            $(this).children('.screen-reader-text').text(objectL10n.closeMenu);

            // change aria text
            $(this).attr('aria-expanded', 'true');
        }
    });
    
    sliderContainer.css('min-height', sliderContainer.find('.slide.current').find('.content-container').outerHeight() + 60);

    var autoRotation = autoRotateSlider;
    if ( objectL10n.autoRotateSlider == 'yes' ) {
        var autoRotationID = setInterval( autoRotation, sliderTime + '000' );
    }

    $('.slide-nav').on('click', function(e) {
        e.preventDefault();

        // Don't do anything if there's only one slide
        if ( slider.find('.slide').length == 1 ) { return; }
        
        var current = slider.find('.current');
        current.removeClass('current');
        var currentDot = $('#dot-navigation').children('.current');
        currentDot.removeClass('current');
        if ( 
            ($(this).hasClass('left') && !$('body').hasClass('rtl') ) 
            || (($(this).hasClass('right') && $('body').hasClass('rtl') ) )
        ) {
            if( current.prev().length ) {
                current.prev().addClass('current');
                currentDot.prev().addClass('current');
            } else {
                current.siblings(":last").addClass('current');
                currentDot.siblings(":last").addClass('current');
            }
        } else {
            if( current.next().length ) {
                current.next().addClass('current');
                currentDot.next().addClass('current');
            } else {
                current.siblings(":first").addClass('current');
                currentDot.siblings(":first").addClass('current');
            }
        }
        current = slider.find('.current');
        sliderContainer.css('min-height', current.find('.content-container').outerHeight() + 60);

        if ( objectL10n.autoRotateSlider == 'yes' ) {
            clearInterval(autoRotationID);
            autoRotationID = setInterval( autoRotation, sliderTime + '000' );
        }
    });
    
    $('.dot').on('click', function(e) {
        e.preventDefault();
        var currentSlide = slider.find('.current').removeClass('current');
        var currentDot = $('#dot-navigation').children('.current').removeClass('current');
        $(this).addClass('current');
        var slideNumber = $(this).index() + 1;
        currentSlide = slider.find('.slide-' + slideNumber);
        currentSlide.addClass('current');
        sliderContainer.css('min-height', currentSlide.find('.content-container').outerHeight() + 60);
    });

    function autoRotateSlider() {
        var current = slider.find('.current');
        current.removeClass('current');
        var currentDot = $('#dot-navigation').children('.current');
        currentDot.removeClass('current');
        if (current.next().length) {
            current.next().addClass('current');
            currentDot.next().addClass('current');
        } else {
            current.siblings(":first").addClass('current');
            currentDot.siblings(":first").addClass('current');
        }
    }

    // mimic cover positioning without using cover
    function objectFitAdjustment() {

        // if the object-fit property is not supported
        if( !('object-fit' in document.body.style) ) {

            $('.featured-image').each(function () {

                if ( !$(this).parent().parent('.post').hasClass('ratio-natural') ) {

                    var image = $(this).children('img').add($(this).children('a').children('img'));

                    // don't process images twice (relevant when using infinite scroll)
                    if ( image.hasClass('no-object-fit') ) {
                        return;
                    }

                    image.addClass('no-object-fit');

                    // if the image is not wide enough to fill the space
                    if (image.outerWidth() < $(this).outerWidth()) {

                        image.css({
                            'width': '100%',
                            'min-width': '100%',
                            'max-width': '100%',
                            'height': 'auto',
                            'min-height': '100%',
                            'max-height': 'none'
                        });
                    }
                    // if the image is not tall enough to fill the space
                    if (image.outerHeight() < $(this).outerHeight()) {

                        image.css({
                            'height': '100%',
                            'min-height': '100%',
                            'max-height': '100%',
                            'width': 'auto',
                            'min-width': '100%',
                            'max-width': 'none'
                        });
                    }
                }
            });
        }
    }
    
    // ===== Scroll to Top ==== //

    if ( $('#scroll-to-top').length !== 0 ) {
        $(window).on( 'scroll', function() {
            if ($(this).scrollTop() >= 200) {        // If page is scrolled more than 50px
                $('#scroll-to-top').addClass('visible');    // Fade in the arrow
            } else {
                $('#scroll-to-top').removeClass('visible');   // Else fade out the arrow
            }
        });
        $('#scroll-to-top').click(function(e) {      // When arrow is clicked
            $('body,html').animate({
                scrollTop : 0                       // Scroll to top of body
            }, 600);
            $(this).blur();
        });
    }
});

/* fix for skip-to-content link bug in Chrome & IE9 */
window.addEventListener("hashchange", function(event) {

    var element = document.getElementById(location.hash.substring(1));

    if (element) {

        if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
            element.tabIndex = -1;
        }

        element.focus();
    }

}, false);

// wait to see if a touch event is fired
var hasTouch;
window.addEventListener('touchstart', setHasTouch, false );

// require a double-click on parent dropdown items
function setHasTouch() {

    hasTouch = true;

    // Remove event listener once fired
    window.removeEventListener('touchstart', setHasTouch);

    // get the width of the window
    var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0],
        x = w.innerWidth || e.clientWidth || g.clientWidth;


    // don't require double clicks for the toggle menu
    if (x > 799) {
        enableTouchDropdown();
    }
}

// require a second click to visit parent navigation items
function enableTouchDropdown(){

    // get all the parent menu items
    var menuParents = document.getElementsByClassName('menu-item-has-children');

    // add a 'closed' class to each and add an event listener to them
    for (i = 0; i < menuParents.length; i++) {
        menuParents[i].className = menuParents[i].className + " closed";
        menuParents[i].addEventListener('click', openDropdown);
    }
}

// check if an element has a class
function hasClass(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}

// open the dropdown without visiting parent link
function openDropdown(e){

    // if has 'closed' class...
    if(hasClass(this, 'closed')){
        // prevent link from being visited
        e.preventDefault();
        // remove 'closed' class to enable link
        this.className = this.className.replace('closed', '');
        // add 'open' close
        this.className = this.className + ' open';
    }
}