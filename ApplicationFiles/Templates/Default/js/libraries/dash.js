(function () {
    "use strict";

    // Variables
    //
    var dnl = $(".dash-navbar-left"),
              dnlBtnToggle = $(".dnl-btn-toggle"),
              dnlBtnCollapse = $(".dnl-btn-collapse"),
              contentWrap = $(".content-wrap"),
              contentWrapEffect = contentWrap.data("effect");

    // Functions
    //
    function dnlShow() {
        $("html").addClass(contentWrapEffect);
        dnl.addClass("dnl-show").removeClass("dnl-hide");
        contentWrap.addClass(contentWrapEffect);
        dnlBtnToggle.find("span").removeClass("fa-bars").addClass("fa-arrow-left");
    }

    function dnlHide() {
        $("html").removeClass(contentWrapEffect);
        dnl.removeClass("dnl-show").addClass("dnl-hide");
        contentWrap.removeClass(contentWrapEffect);
        dnlBtnToggle.find("span").removeClass("fa-arrow-left").addClass("fa-bars");
    }

    // Toggle the edge navbar left
    //
    dnl.addClass("dnl-hide");
    dnlBtnToggle.click(function () {
        if (dnl.hasClass("dnl-hide")) {
            dnlShow();
        } else {
            dnlHide();
        }
    });

    // Collapse the dash navbar left subnav
    //
    dnlBtnCollapse.click(function (e) {
        e.preventDefault();
        if (dnl.hasClass("dnl-collapsed")) {
            dnl.removeClass("dnl-collapsed");
            contentWrap.removeClass("dnl-collapsed");
            $(this).find(".dnl-link-icon").removeClass("fa-arrow-right").addClass("fa-arrow-left");
        } else {
            dnl.addClass("dnl-collapsed");
            contentWrap.addClass("dnl-collapsed");
            $(this).find(".dnl-link-icon").removeClass("fa-arrow-left").addClass("fa-arrow-right");
        }
    });

    // Swipe plugin for handling touch events
    $("#TopNav").swipe({
        //Generic swipe handler for all directions
        swipeRight: function (event, direction, distance, duration, fingerCount) {
            dnlShow();
        },
        swipeLeft: function (event, direction, distance, duration, fingerCount) {
            dnlHide();
        },
        //Default is 75px, set to 0 for demo so any distance triggers swipe
        threshold: 75
    });

})();