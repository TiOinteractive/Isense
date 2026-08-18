/* Slider galeria home */
/*
if($('.home-gallery-slider .gallery-list').length) {
    $(document).ready(function () {
        $('.home-gallery-slider .gallery-list').slick({
            dots: true,
            autoplay: true,
            prevArrow: $('.home-gallery-slider .prev'),
            nextArrow: $('.home-gallery-slider .next')
        });
    });
}

if($('.slider-list').length) {
    $(document).ready(function () {
        $('.slider-list').slick({
            autoplay: true,
            pauseOnFocus: false,
            pauseOnHover: false,
            autoplaySpeed: 5000,
            prevArrow: $('.slider-list .prev'),
            nextArrow: $('.slider-list .next')
        });

        $(".slider-list").on('afterChange', function (event, slick, currentSlide, nextSlide) {
            $('.slider-5 .carousel-indicators li').removeClass('active');
            $('.slider-5 .carousel-indicators li[data-slide-to="' + currentSlide + '"]').addClass('active');
            slick.slickSetOption("autoplay", true);
        });
        $('.carousel-indicators li').click(function () {
            var nr = $(this).attr('data-slide-to');
            $('.slider-list').slick('slickGoTo', nr);
        });

    });	
}	
*/
