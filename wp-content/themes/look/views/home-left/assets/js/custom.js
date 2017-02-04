( function( $ ) {
    "use strict";

 	//Push menu
    $("#menu-toggle").click(function () {
        $('body').addClass('open');
        $('#menu-toggle').toggleClass('active');
	});
    $("#close-btn").click(function () {
        $('body').removeClass('open');
    });

	$(document).mouseup(function (e) {
        var container = $("body.open");

        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.removeClass('open');
        }
    });
    
})(jQuery);

