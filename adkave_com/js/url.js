/*!
 * Hash URL Modal Handler
 * @author: Fahad Hossain(http://fa7ad.github.io/)
 * @license: AGPLv3
 */
(function($) {
    if (window.location.hash !== "" && window.location.hash.slice(1) !== "") {
        var url = window.location.hash.slice(1);
        if (($("#" + url).length > 0) && ($("a[href$=" + url + "]").length > 0)) {
            $("a[href$=" + url + "]").click();
            if ((url.indexOf("odal") > 0)) {
                $("#" + url).on('hidden.bs.modal', function() {
                    $('html, body').delay(1000).animate({
                        scrollTop: 0
                    }, 1000, 'easeInOutExpo');
                });
                $("#" + url).off('hidden.bs.modal');
            };
        } else {
            console.log("Error! There is no '" + window.location.hash + "' in this DOM")
        }
    } else {
        console.log("There is no # in this URL");
        console.log("Or there is nothing following the #");
    }
})(jQuery, window);
