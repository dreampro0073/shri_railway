$(document).ready(function(){
	$(".home-slider").owlCarousel({
		loop: true,
		nav: true,
		dots: true,
		autoplay: true,
		autoplayTimeout: 5000,
		navText: ['', ''],
		items: 1,
        smartSpeed:800,
        animateIn: 'fadeIn', // add this
        animateOut: 'fadeOut' // and this
	});
	// $(".tours-slider").owlCarousel({
	// 	loop: true,
	// 	nav: true,
	// 	dots: true,
	// 	margin:16,
	// 	autoplay: true,
	// 	autoplayTimeout: 5000,
	// 	navText: ['', ''],
	// 	items: 3,
	// });
	$(".testimonials-slider").owlCarousel({
		loop: true,
		nav: true,
		dots: true,
		margin:16,
		autoplay: true,
		autoplayTimeout: 5000,
		navText: ['', ''],
		items: 3,

		responsive:{
		    0: {
	          	items: 1
	    	},
		    768: {
		        items: 2
		    },
		    1200: {
		        items: 3
		    },
		      
		}
	});
});

$(document).on("click", ".toggle-menu", function () {
    $(this).toggleClass("open-menu");
    $("header ul.menu").slideToggle();
});

$("a.scroll-link").click(function (e) {
    e.preventDefault();

    var hash = $(this).attr("href");
    var target = $(hash);

    if (hash && target.length) {
        $('html, body').animate({
            scrollTop: target.offset().top - 30
        }, 800);
    }
    $("header ul.menu").slideUp();
    $(".toggle-menu").toggleClass("open-menu");
});
