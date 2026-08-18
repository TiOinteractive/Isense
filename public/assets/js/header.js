$(function () {
    $('.navbar-toggle').on("click", function () {
        var offset = $('.top-header .logo').offset();
        var wh = $('.top-header .logo').width();
        if (offset) {
            var przesun = $(window).width() - (offset.left + wh + 30);
        }
        $('.dropdown-menu').addClass('open');
        if ($(window).width() < 800 || $('body').hasClass('mobile')) {
            $('.dropdown-menu #white').css('width', '100%');
            $('.dropdown-menu #white').animate({right: '0px'}, 1200);
        } else {
            $('.dropdown-menu #black').css('width', (offset.left + wh + 50) + 'px');
            $('.dropdown-menu #white').css('width', przesun);
            $('.dropdown-menu #black').animate({left: '0px'}, 1200);
            $('.dropdown-menu #white').animate({right: '0px'}, 1200);
        }
    });
    $('.navbar-toggle-close').on("click", function () {
        $('.dropdown-menu #black').animate({left: '-100%'}, 1200);
        $('.dropdown-menu #white').animate({right: '-100%'}, 1200, function () {
            $('body,html').css('overflow-y', 'auto');
            $('.dropdown-menu').removeClass('open');
			$('header.main-header.fixed').css('height','auto');
        });
    });


    $('.mobile .drop-mobile .has-submenu h3 span').on("click", function (event) {
        event.preventDefault();
        if ($(this).parent().parent().hasClass('open')) {
            $('.mobile .drop-mobile .item').removeClass('open');
            $('.mobile .drop-mobile .has-submenu h3 .fa-solid').removeClass('fa-chevron-down').addClass('fa-chevron-right');
        } else {
            $('.mobile .drop-mobile .item').removeClass('open');
            $('.mobile .drop-mobile .has-submenu h3 .fa-solid').removeClass('fa-chevron-down').addClass('fa-chevron-right');
            $(this).parent().parent().addClass('open');
            $(this).parent().parent().find('.fa-solid').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        }
    });


    $('.navbar-toggle-mobile').on("click", function () {
        if ($(this).hasClass('open')) {
            $('.drop-mobile .item').removeClass('open');
            $(this).removeClass('open');
            $('.drop-mobile').removeClass('open');
			$('.main-header').css('height','');
        } else {
			$('.mobile .drop-mobile .has-submenu h3 .fa-solid').removeClass('fa-chevron-down').addClass('fa-chevron-right');
            $(this).addClass('open');
            $('.drop-mobile').addClass('open');
			$('.main-header').css('height','100vh');
        }


    });
});

function SendNewsletterForm() {
	var url=$('a.newsletter-subscribe').attr('href');
	var email=$('#newsletter-foot input[name="email"]').val();
	ajaxCall(url, {}, 'showNewsletterForm', {target: $('a.newsletter-subscribe'),email:email});
}	
