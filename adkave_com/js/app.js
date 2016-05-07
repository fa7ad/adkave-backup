/*!
 * NogorAds Homepage (http://nogorads.com)
 * @author: Fahad Hossain(http://thewebaholic.ml)
 * @license: AGPLv3
 * @uses 
 *  Freelancer (http://startbootstrap.com) | @license Apache v2.0 (http://www.apache.org/licenses/LICENSE-2.0.)
 */
(function() {
    // Reset form on reload
    $(window).load(function(){
        $('form').trigger('reset');
    });

    // Emulate Parallax
    (function() {
        $(window).on('scroll load', function() {
            var h_offset = Math.floor($('nav').offset().top / 33);
            $('header').css('background-position', 'center calc(' + h_offset + 'px - 10vh)').css('background-position', 'center -moz-calc(' + h_offset + 'px - 10vh)');
        });
    })(jQuery, window, document);

    //nav colors
    (function($) {
        $(window).on('scroll load', function() {
            if (
                $('nav').offset().top <= $('#contact').offset().top - 50
            ) {
                $('nav').css('background-color', 'rgba(10,10,10,0.2)');
            } else {
                $('nav').css('background-color', '');
            }
        });
    })(jQuery, window);

    // jQuery for page scrolling feature - requires jQuery Easing plugin
    $(function() {
        $(window).bind('scroll load', function() {
            var top_off = $('nav').offset().top;
            var height = Math.floor(window.innerHeight * 0.2);
            if (top_off <= height) {
                $('.scroll-top.page-scroll').css('opacity', '0');
            } else if (top_off >= height && top_off <= window.innerHeight) {
                var fade = top_off / window.innerHeight;
                $('.scroll-top.page-scroll').css('opacity', fade);
            }
        });
        $('.page-scroll a, a[href^="#"]').bind('click', function(event) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: ($($anchor.attr('href')).offset().top - 35)
            }, 1500, 'easeInOutExpo');
            event.preventDefault();
        });
    });

    // Floating label headings for the contact form
    $(function() {
        $("body").on("input propertychange", ".floating-label-form-group", function(e) {
            $(this).toggleClass("floating-label-form-group-with-value", !!$(e.target).val());
        }).on("focus", ".floating-label-form-group", function() {
            $(this).addClass("floating-label-form-group-with-focus");
        }).on("blur", ".floating-label-form-group", function() {
            $(this).removeClass("floating-label-form-group-with-focus");
        });
    });

    // Highlight the top nav as scrolling occurs
    $('body').scrollspy({
        target: '.navbar-fixed-top'
    })

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a').click(function() {
        $('.navbar-toggle:visible').click();
    });

    // Hides and unhides form content
    $("[name=name]").on("blur", function() {
        if ($('[name=name]').val().length > 3) {
            $('input[name=name]').closest('.row').slideUp(500);
            $('#offer-row').fadeIn();
        }
    });
    $("[name=company]").on("blur", function() {
        if ($('[name=company]').val().length > 2) {
            $('input[name=company]').closest('.row').slideUp(500);
            $('#budget-row').fadeIn();
        }
    });
    $("[name=designation]").on("blur", function() {
        if ($('[name=designation]').val().length > 2) {
            $('input[name=designation]').closest('.row').slideUp(500);
            $('#email-row').fadeIn();
        }
    });
})(jQuery, window);
