(function($) {
  $(window).load(function() {
    $(".product-image").click(function() {
      window.location = $(this).attr("data-href");
    });
    $(".flexslider").flexslider({
      animation: "slide",
      controlNav: "thumbnails"
    });
  });
})(jQuery);

