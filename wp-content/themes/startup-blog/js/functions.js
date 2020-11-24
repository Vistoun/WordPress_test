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