function getWindowHeight() {
    var myWidth = 0, myHeight = 0;
    if( typeof( window.innerWidth ) == 'number' ) {
        //Non-IE
        myHeight = window.innerHeight;
    } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
        //IE 6+ in 'standards compliant mode'
        myHeight = document.documentElement.clientHeight;
    } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
        //IE 4 compatible
        myHeight = document.body.clientHeight;
    }

    return myHeight
}

(function($) {
    $(window).load(function() {
        if(!device.mobile() && !device.tablet()){
            $('section.parallax-box').each(function(){
                var $bgobj = $(this).find('.parallax-bg'),
                    window_height = parseInt(getWindowHeight()),
                    element_pos = $bgobj.offset(),
                    element_top = parseInt(element_pos.top),
                    //buffer = Math.floor(element_top / window_height);
                    buffer = Math.floor(element_top - window_height),
                    visible_scroll = parseInt($(window).scrollTop()) - buffer;
                if ( visible_scroll > 0 ) {
                    if ( window_height > element_top ) {
                        var yPos = -(visible_scroll / $bgobj.data('speed'));
                    } else {
                        var yPos = -($(window).scrollTop() / $bgobj.data('speed'));
                    }
                    //console.log(yPos);
                    var coords = 'center '+ yPos + 'px';
                    $bgobj.css({ backgroundPosition: coords });
                }
                //console.log(element_top);
                //console.log(window_height);
                $(window).scroll(function() {
                    var element_pos = $bgobj.offset(),
                        element_top = parseInt(element_pos.top),
                        //buffer = Math.floor(element_top / window_height);
                        buffer = Math.floor(element_top - window_height),
                        visible_scroll = parseInt($(window).scrollTop()) - buffer;
                   
                    //console.log($(window).scrollTop());
                    //console.log(element_top);
                    if ( visible_scroll > 0 ) {
                        if ( window_height > element_top ) {
                            var yPos = -(visible_scroll / $bgobj.data('speed'));
                        } else {
                            var yPos = -($(window).scrollTop() / $bgobj.data('speed'));
                        }
                        //console.log(yPos);
                        var coords = 'center '+ yPos + 'px';
                        $bgobj.css({ backgroundPosition: coords });
                    }
                });
            });
        }
    });
})(jQuery);