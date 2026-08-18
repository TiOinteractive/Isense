$(function(){
    $(document).on('mouseenter', '.event-calendar .days .day a', function(e){
        e.preventDefault();
        $(this).data('title', $(this).attr('title'));
        $(this).attr('title', '');
        let count = $(this).closest('.day').data('count');
        if(count) {
            $(this).after($('<div></div>').addClass('hover').text(count));
        }
    }).on('mouseleave', '.event-calendar .days .day a', function(e) {
        $(this).attr('title', $(this).data('title'));
        $(this).closest('.day').find('.hover').remove();
    });
    $(document).on('click', '.event-calendar .header .arrow', function(e){
        e.preventDefault();
        if(!$(this).hasClass('disabled') && !$(this).closest('.event-calendar-box').hasClass('loading')) {
            $(this).closest('.event-calendar-box').append($('<div></div>').addClass('loader'));
            $(this).closest('.event-calendar-box').addClass('loading');
            ajaxCall($(this).attr('href'), {action: 'calendar', content: $(this).closest('.calendar-box').find('.page-content').val(), date: $(this).data('month'), sidebar: $(this).closest('.sidebar').length}, 'eventCalendarReload', {target:$(this).closest('.calendar-box')});
        }
    });
    $('.event-types-filter-box .type').on('click', function(e){
        e.preventDefault();
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('.events-form input[name="t"]').val('');
        } else {
            $(this).siblings('.active').removeClass('active');
            $(this).addClass('active');
            $('.events-form input[name="t"]').val($(this).data('slug'));
        }
       $('.events-form').submit();
    });
    $('.events-form .search-carousel .day .day-date, .events-form .search-carousel .day .header .day-name, .events-form .search-carousel .day .header .day-weekend').on('click', function(e){
        $(this).parent().siblings('.active').removeClass('active');
        $(this).parent().addClass('active');
        $(this).closest('form').find('input[name="d"]').val($(this).data('date'));
		$(this).closest('form').find('input[name="s"]').val('');
        $(this).closest('form').submit();
    });
    $('.events-form .field').on('change', function(e){
        e.preventDefault();
        eventsForm($(this).closest('form'));
    });
    $('.events-form').on('submit', function(e){
        e.preventDefault();
        eventsForm(this);
    });
    $(document).on('click', '.search-engine-2 .event-calendar-box .day a', function(e){
        e.preventDefault();
        $(this).closest('.search-top').find('input[name="d"]').val($(this).data('date'));
        eventsForm($(this).closest('form'));
    })
    if($('.search-carousel .carousel-wrap').length) {
        $('.search-carousel .carousel-wrap').slick({
            dots: false,
            infinite: false,
            speed: 600,
            slidesToShow: 7,
            slidesToScroll: 7,
            initialSlide: 0 && $('.search-carousel .carousel-wrap .day.active').length ? $('.search-carousel .carousel-wrap .day.active').index() : 0,
            prevArrow : '<button type="button" class="slick-prev"><svg viewBox="0 0 96 96"><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg></button>',
            nextArrow : '<button type="button" class="slick-next"><svg viewBox="0 0 96 96"><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg></button>',
            responsive: [
              {
                breakpoint: 1024,
                settings: {
                  slidesToShow: 6,
                  slidesToScroll: 6,
                  infinite: true,
                  dots: true
                }
              },
              {
                breakpoint: 600,
                settings: {
                  slidesToShow: 5,
                  slidesToScroll: 5
                }
              },
              {
                breakpoint: 480,
                settings: {
                  slidesToShow: 4,
                  slidesToScroll: 4
                }
              }
            ]
        });
        var slide_active=$('.search-carousel .carousel-wrap .day.active').index();
        if(slide_active) {
            $('.search-carousel .carousel-wrap').slick('slickGoTo', slide_active);
        }
    };
	
	    if($('.search-carousel .cinema-carousel-wrap').length) {
			
			
			
			
        $('.search-carousel .cinema-carousel-wrap').slick({
            dots: false,
            infinite: false,
            speed: 600,
            slidesToShow: 5,
            slidesToScroll: 5,
            prevArrow : '<button type="button" class="slick-prev"><svg viewBox="0 0 96 96"><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg></button>',
            nextArrow : '<button type="button" class="slick-next"><svg viewBox="0 0 96 96"><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg></button>',
            responsive: [
              {
                breakpoint: 1024,
                settings: {
                  slidesToShow: 4,
                  slidesToScroll: 4,
                  infinite: true,
                  dots: true
                }
              },
              {
                breakpoint: 600,
                settings: {
                  slidesToShow: 3,
                  slidesToScroll: 3
                }
              },
              {
                breakpoint: 480,
                settings: {
                  slidesToShow: 3,
                  slidesToScroll: 3
                }
              }
            ]
        });
		 var slide_active=$('.search-carousel .cinema-carousel-wrap .day.active').index();
		$('.search-carousel .cinema-carousel-wrap').slick('slickGoTo', slide_active);
		 
		
		
    };
	
	$('.mobile .search-engine-2 .search-top .filter-box.filter-cat .inside .choose-cat').on('click', function(e){
		if($('.mobile .search-engine-2 .search-top .filter-box.filter-cat .list').hasClass('open')) {
			$('.mobile .search-engine-2 .search-top .filter-box.filter-cat .list').removeClass('open');	
			$('.mobile .search-engine-2 .search-top .filter-box.filter-cat .fa-solid').removeClass('fa-chevron-down').addClass('fa-chevron-right');
		}
        else {
           $('.mobile .search-engine-2 .search-top .filter-box.filter-cat .list').addClass('open');
		   $('.mobile .search-engine-2 .search-top .filter-box.filter-cat .fa-solid').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        } 			
	});	
	$('.mobile .search-engine-2 .search-top .filter-box.filter-cat .list .type').on('click', function(e){
	   var slug=$(this).attr('data-slug');
	   if(slug=='all') {
		  $('form.events-form input.cat-type').val(''); 
		  $('.mobile .search-engine-2 .search-top .filter-box.filter-cat .inside .choose-cat span').html('Kategorie');
	   }
        else {	   
	       $('form.events-form input.cat-type').val(slug);
		   var name=$(this).find('.name').html();
		   $('.mobile .search-engine-2 .search-top .filter-box.filter-cat .inside .choose-cat span').html(name);	   
		} 
		$('.mobile .search-engine-2 .search-top .filter-box.filter-cat .list').removeClass('open');	
		$('.mobile .search-engine-2 .search-top .filter-box.filter-cat .fa-solid').removeClass('fa-chevron-down').addClass('fa-chevron-right');	
        $('.search-engine-2 form').submit();		
	});
	
	$('.search-engine-2 .search-top .filter-box.filter-date button').on('click', function(e){
		e.preventDefault();
		if($('.search-engine-2 .search-top .filter-box.filter-date .calendar-box').is(':visible')) {
			$('.search-engine-2 .search-top .filter-box.filter-date .calendar-box').hide();
		}
        else {		
		$('.search-engine-2 .search-top .filter-box.filter-date .calendar-box').show();
		}
	});	
	$('.search-engine-2 .search-top .filter-box.filter-date input').on('click', function(e){
	  e.preventDefault();
	  if($('.search-engine-2 .search-top .filter-box.filter-date .calendar-box').is(':visible')) {
			$('.search-engine-2 .search-top .filter-box.filter-date .calendar-box').hide();
		}
        else {		
		$('.search-engine-2 .search-top .filter-box.filter-date .calendar-box').show();
		}
	});
});


function eventsForm(form) {
    let url = $(form).attr('action');
    let data = $(form).serializeArray();
    let values = '';
    $.each(data, function(key, obj){
        if(obj.value) {
            values += '/' + obj.name + '/' + obj.value;
        }
    });
    if(values) {
        url += '/g' + values;
    }
    window.location.href = url;
}

function eventCalendarReload(obj, params) {
    console.log(params);
    $(params.target).find('.event-calendar-box').removeClass('loading');
    $(params.target).find('.event-calendar-box').html(obj.html);
}