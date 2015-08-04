$(function() {
	$('.blink').focus(function() {
        if(this.title==this.value) {
            this.value = '';
        }
    }).blur(function(){
        if(this.value=='') {
            this.value = this.title;
        }
    });
    
	$('#slider .slider-items ul').jcarousel({
		'scroll': 1,
		'auto': 3,
		'wrap': 'both',
        initCallback: mycarousel_initCallback,
        itemVisibleInCallback: {
			onAfterAnimation: function(c, o, i, s) {
				jQuery('.slider-nav li a').removeClass('active');
				jQuery('.slider-nav li:eq('+ (i-1) +') a').addClass('active');
			}
		}
	});
	
	Cufon.replace('.slider-items h2', {
		color: '-linear-gradient(#c7c7c7, #000000)'
	});
	
	if ($.browser.msie && $.browser.version == 6) {
		DD_belatedPNG.fix('#wrapper, #header, h1#logo a, #search form, #search .search-button, #search .advanced, #navigation, #navigation li a, #main, #slider .slider-items, .more, #slider .jcarousel-next, #slider .jcarousel-prev, #slider .slider-nav li a, #slider .slider-nav li a span, #slider .slider-nav li .slide-sep, #content .top, #content .inner, #products, #products .product .img-box .box-frame, .pr-price, #footer, #footer #bottom-logo a');
	}
	
});

function mycarousel_initCallback(carousel) {

    jQuery('.slider-nav a').bind('click', function() {
        carousel.scroll(jQuery.jcarousel.intval((jQuery(this).parent().index() + 1)));
        return false;
    });
    
    $('#slider .jcarousel-prev, #slider .jcarousel-next').hover(function(){
    	$(this).addClass('carousel-active');
    }, function(){
    	$(this).removeClass('carousel-active');
    });

};