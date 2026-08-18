$( document ).ready(function() {	
	if($('.rating .rate_lokal').length>0) {
		$('.rating .rate_lokal').each(function() {
			var rate=$(this).attr('data-rate');
			$(this).raty({half:true,readOnly: true,score: rate,size:25,hints:['', '', '', '', '', ''],starHalf:'/assets/gfx/star-half-big.png',starOff:'/assets/gfx/star-off-big.png',
			starOn:'/assets/gfx/star-on-big.png'});
			
		});	
	}	
	$('#flavors .sort_select select').on( "change", function() {
		var value=$(this).val();
		var name=$(this).attr('name');
	    $('#flavors .filters input[name="'+name+'"]').val(value);
	    if($('#leftParameters form#filters').length>0) {
		$('#leftParameters form#filters').submit(); 
		 }
		 else { 	 
			$('#flavors form#filters').submit();	
		 }
	
	});
	
	if($('#flavors #restaurant-single .show_other_cuisine').length>0) {
		$('#flavors #restaurant-single .show_other_cuisine').on( "click", function() {
			$('#flavors #restaurant-single span.hide').removeClass('hide');
			$('#flavors #restaurant-single .show_other_cuisine').remove();
		})
	}	
	
	if($('.flavor-carousel').length>0) {
		$('.flavor-carousel .list').slick({
		  infinite: true,
		  slidesToShow: 4,
		  slidesToScroll:1,
		  autoplay: true,
          pauseOnFocus: false,
          pauseOnHover: false,
          autoplaySpeed: 5000,
		  prevArrow:'<div class="prev-btn controls trans400"><i class="fa-solid fa-chevron-left"></i></div>',
		  nextArrow:'<div class="next-btn controls trans400"><i class="fa-solid fa-chevron-right"></i></div>',
		  responsive: [
			{
			  breakpoint: 1024,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 600,
			  settings: {
				slidesToShow: 2,
				slidesToScroll: 1
			  }
		   }]
	
		});
	}
	
	
	if($('.shop-list #product_lists').length>0) {
		$('.shop-list #product_lists').slick({
		  infinite: true,
		  slidesToShow: 4,
		  slidesToScroll:1,
		  autoplay: false,
          pauseOnFocus: false,
          pauseOnHover: false,
          autoplaySpeed: 5000,
		  prevArrow:'<div class="prev-btn controls trans400"><i class="fa-solid fa-chevron-left"></i></div>',
		  nextArrow:'<div class="next-btn controls trans400"><i class="fa-solid fa-chevron-right"></i></div>',
		  responsive: [
			{
			  breakpoint: 1024,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 600,
			  settings: {
				slidesToShow: 2,
				slidesToScroll: 1
			  }
		   }]
	
		});
	}

	if($('.entertainment-slider-full').length>0) {
		$('.entertainment-slider-full .list').slick({
			 infinite: true,
			 slidesToShow:1,
			 arrows:true,
			 dots:true,
             prevArrow : '<button type="button" class="slick-prev"><svg viewBox="0 0 96 96"><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg></button>',
            nextArrow : '<button type="button" class="slick-next"><svg viewBox="0 0 96 96"><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg></button>',
		     slidesToScroll:1,
		      autoplay:true,
			
			
		});
    }		
	
	
	
	if($('.home-list.cinema-repertoire-list').length>0) {
		$('.home-list.cinema-repertoire-list .list').slick({
			 infinite: true,
			 slidesToShow:5,
			 arrows:true,
			 dots:false,
            prevArrow : '<button type="button" class="slick-prev"><svg viewBox="0 0 96 96"><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg></button>',
            nextArrow : '<button type="button" class="slick-next"><svg viewBox="0 0 96 96"><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg></button>',
		     slidesToScroll:5,
		      autoplay:false,
			 responsive: [
			{
			  breakpoint: 1024,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
				centerMode: true,
				centerPadding: '35px',
			  }
			},
			{
			  breakpoint: 600,
			  settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
				centerMode: true,
				centerPadding: '35px',
			  }
		   }]
			
		});
    }		
	
	if($('.home-list.soon_in_cinema').length>0) {
		$('.home-list.soon_in_cinema .list').slick({
			 infinite: true,
			 slidesToShow:5,
			 arrows:true,
			 dots:false,
            prevArrow : '<button type="button" class="slick-prev"><svg viewBox="0 0 96 96"><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg></button>',
            nextArrow : '<button type="button" class="slick-next"><svg viewBox="0 0 96 96"><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg></button>',
		     slidesToScroll:5,
		      autoplay:false,
			 responsive: [
			{
			  breakpoint: 1024,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
				centerMode: true,
				centerPadding: '35px',
			  }
			},
			{
			  breakpoint: 600,
			  settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
				centerMode: true,
				centerPadding: '35px',
			  }
		   }]
			
		});
    }	
	
	
	if($('.section-2.home-list.event-calendar-list').length>0) {
	$('.section-2.home-list.event-calendar-list .list').slick({
			 infinite: true,
			 slidesToShow:5,
			 arrows:true,
			 dots:false,
			 rows: 2,
            prevArrow : '<button type="button" class="slick-prev"><svg viewBox="0 0 96 96"><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg></button>',
            nextArrow : '<button type="button" class="slick-next"><svg viewBox="0 0 96 96"><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg></button>',
		     slidesToScroll:5,
		      autoplay:false,
			 responsive: [
			{
			  breakpoint: 1024,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 3
			  }
			},
			{
			  breakpoint: 600,
			  settings: {
				slidesToShow: 2,
				slidesToScroll: 2
			  }
		   }]
			
		});
	}
	
	
	
	if($('.section-28.home-list.event-calendar-list').length>0) {
	$('.section-28.home-list.event-calendar-list .list').slick({
			 infinite: true,
			 slidesToShow:5,
			 arrows:true,
			 dots:false,
			 rows: 2,
            prevArrow : '<button type="button" class="slick-prev"><svg viewBox="0 0 96 96"><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg></button>',
            nextArrow : '<button type="button" class="slick-next"><svg viewBox="0 0 96 96"><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg></button>',
		     slidesToScroll:5,
		      autoplay:false,
			 responsive: [
			{
			  breakpoint: 1024,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 3
			  }
			},
			{
			  breakpoint: 600,
			  settings: {
				slidesToShow: 2,
				slidesToScroll: 2
			  }
		   }]
			
		});
	}
	
	
	
	
	
	
	
	
	
	if($('.mobile #gallery_home .list').length>0) {
		$('.mobile #gallery_home .list').slick({
		  infinite: true,
		  slidesToShow:1,
		  slidesToScroll:1,
		  autoplay: false,
          pauseOnFocus: false,
          pauseOnHover: false,
          autoplaySpeed: 5000,
		  arrows:false,
		  centerMode: true,
		  centerPadding: '45px',
		});
	}	
	
	
	if($('.mobile .event-cinema-section .movies-list-main').length>0) {
		$('.mobile .event-cinema-section .movies-list-main').slick({
		  infinite: true,
		  slidesToShow:2,
		  slidesToScroll:1,
		  autoplay: false,
          pauseOnFocus: false,
          pauseOnHover: false,
          autoplaySpeed: 5000,
		  arrows:false,
		  centerMode: true,
		  centerPadding: '35px',
		});
	}	
	
	
	
	if($('#home-w-obiektywie').length>0) {
		$('#home-w-obiektywie .list').slick({
		autoplay:false,
		arrows:false,
		pauseOnHover:false,
		autoplaySpeed: 6000,
		speed:800
		});
		$('#home-w-obiektywie .list').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('#home-w-obiektywie .list-thumbs li span').stop();
			$('#home-w-obiektywie .list-thumbs li span').css('width',0);
			$('#home-w-obiektywie .list-thumbs li').eq(nextSlide).find('span').animate({ width:'100%' }, 7000, function() {});	
		});
		
		
		$('#home-w-obiektywie .list-thumbs li').on( "click", function() {
		  $('#home-w-obiektywie .list-thumbs li span').stop();	
		  $('#home-w-obiektywie .list-thumbs li span').css('width',0);
		  $('#home-w-obiektywie .list-thumbs li').removeClass('active');
		  $('#home-w-obiektywie .list').slick('slickGoTo', $(this).index());
		  $(this).addClass('active');
		  $(this).find('span').animate({ width:'100%' }, 7000, function() {}); 
		});
		
			var offset=$('#home-w-obiektywie').offset().top;
			if(($(window).scrollTop()+100)>=offset) {
			    $('#home-w-obiektywie').addClass('start');
				$('#home-w-obiektywie .list-thumbs li').eq(0).find('span').animate({ width:'100%' }, 7000, function() {});	
				setTimeout(
					  function() 
					  {
						$('#home-w-obiektywie .list').slick('slickSetOption', 'autoplay', true,true);
					  }, 2000);
			}
			$(window).scroll(function(){
			  if (!$('#home-w-obiektywie').hasClass("start")) {
			         if(($(window).scrollTop()+100)>=offset) {
			    $('#home-w-obiektywie').addClass('start');
				$('#home-w-obiektywie .list-thumbs li').eq(0).find('span').animate({ width:'100%' }, 7000, function() {});	
					setTimeout(
					  function() 
					  {
						$('#home-w-obiektywie .list').slick('slickSetOption', 'autoplay', true,true);
					  }, 2000);
				}
			  }
			});
	}	
	
		if($('#flavors .ranking .list .list-row').length>0) {
			$('#flavors .ranking .list .order').on( "click", function() {
				var type=$(this).attr('data-order');
				if($(this).hasClass("desc")) {
					var sort=type+'_asc';	
				}
                else {
                   var sort=type+'_desc';
                }					
				$('#flavors .filters input#type_order').val(sort);
				$('#flavors .filters form').submit();
			});	
	    }
		
		
		if($('#flavors_page .sort_select .view').length>0) {
			
			$('#flavors_page .sort_select .view svg').on( "click", function() {
			   var type=$(this).parent().attr('class');
			   if(type=="view_2") {
				   $('#flavors .filters input[name="view"]').val(2);
			   }
               else {
                  $('#flavors .filters input[name="view"]').val(1);
               }	
				if($('#leftParameters form#filters').length>0) {
					$('#leftParameters form#filters').submit(); 
				 }
				 else { 	 
					$('#flavors form#filters').submit();	
				 }
		    });
		}
		
		
		if($('#restaurant-single #map').length>0) {
		   var lat=parseFloat($('#restaurant-single #map').attr('data-lat'));
		   var lng=parseFloat($('#restaurant-single #map').attr('data-long'));
		   var marker_url = ($('#restaurant-single #map').attr('data-marker'));
		   mapStart(lat,lng,marker_url);
		}
		
		
		if($('#flavors #restaurants_map').length>0) {
          mapAll();
		}	
		
		
		$('#flavors #menu h3').on( "click", function() {
		   if($(window).width()<1100) {
		     if($('#flavors #menu #cats').hasClass('open')) {
				 $('#flavors #menu #cats').removeClass('open');
				 $('#flavors #menu .fa-solid').addClass('fa-chevron-right');
			 }
             else {
                   $('#flavors #menu #cats').addClass('open');
                   $('#flavors #menu .fa-solid').removeClass('fa-chevron-right');
                   $('#flavors #menu .fa-solid').addClass('fa-chevron-down');
             }				 
		   }
		});
		
		$('#flavors #cuisineMenu h3').on( "click", function() {
		   if($(window).width()<1100) {
		     if($('#flavors #cuisineMenu .inside').hasClass('open')) {
				 $('#flavors #cuisineMenu .inside').removeClass('open');
				 $('#flavors #cuisineMenu .fa-solid').addClass('fa-chevron-right');
			 }
             else {
                   $('#flavors #cuisineMenu .inside').addClass('open');
                   $('#flavors #cuisineMenu .fa-solid').removeClass('fa-chevron-right');
                   $('#flavors #cuisineMenu .fa-solid').addClass('fa-chevron-down');
             }				 
		   }
		});
		
		
		if($('#mobile-flavor-head .navbar-fluid').length>0) {
		
		
		$(window).bind('scroll', function () {
			if ($(window).scrollTop() > 80) {
				$('#mobile-flavor-head .navbar-fluid').addClass('fluid');
			} else {
				$('#mobile-flavor-head .navbar-fluid').removeClass('fluid');
			}
			});
		}
		
		if($('.weather-widget').length>0) {
			(function(d,w,t,k){function l(){if(typeof(w._MIOB_)=='undefined'){w._MIOB_={};}var m=w._MIOB_[t]=k;var s=d.createElement('script');m.p=('https:'==d.location.protocol?'https:':'http:');s.type='text/javascript';s.async=true;s.src=m.p+'//info.meteocast.net/mt/'+m.t+'.js';d.body.appendChild(s);}if(d.readyState=='complete')l();else{if(w.attachEvent)w.attachEvent('onload',l);else w.addEventListener('load',l,false);}})(document,window,'00a83db024f2673db2e476454b419ee3',{t:'current',sw:{"iorder":"3","tblank":1},css:['{p}//info.meteocast.net/mt/{t}.css','{p}//info.meteocast.net/cs/de6b0c7b9713e550b6376d46562a974fec5c8094.css'],source:'meteocast'});
		}	
  
		$('#mobile-flavor-head .navbar-toggle').on( "click", function() {
			if($('#mobile-flavor-head .mobile-menu').hasClass('open')) {
				 $('#mobile-flavor-head .mobile-menu').removeClass('open');
				 $('#mobile-flavor-head .navbar-toggle').removeClass('open');
				 $('body,html').removeAttr('style');
			}
			else {
				 $('#mobile-flavor-head .mobile-menu').addClass('open');
				 $('#mobile-flavor-head .navbar-toggle').addClass('open');
			}			
		});	
		
		$('#flavors #leftParameters #filters .parameter h4').on( "click", function() {
			if($(this).parent().hasClass('open')) {
				  $(this).parent().removeClass('open');
				  $(this).find('.fa-solid').removeClass('fa-chevron-down');
				  $(this).find('.fa-solid').addClass('fa-chevron-right');
			}
            else {
              $(this).parent().addClass('open');
              $(this).find('.fa-solid').removeClass('fa-chevron-right');
			  $(this).find('.fa-solid').addClass('fa-chevron-down');
            }				
			
			
		});		
	if($('.place-single .place_address').length>0) {
		var address= $('.place-single .place_address').html();
		geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': address}, function(results, status) {
		
			if(results[0].geometry.location.lat()) {
			  var lat=results[0].geometry.location.lat();
			  var lng=results[0].geometry.location.lng();
			  var alink='https://www.google.pl/maps/place/'+lat+'+'+lng;
			  address='<a href="'+alink+'" target="_blank">'+address+'</a>';
			  $('.place-single .place_address').html(address);
			  
			}
		

		});
	}	
	if($('#photo #other_photo .item.active').length>0) {
	  if($('#photo #other_photo .item.active').index()>0) {
		   $('html, body').animate({ scrollTop: $('#main_photo').offset().top-300}, 80); 
	  }	  
    }
		
});	

function filterLetter(letter) {
	 $('#flavors .filters input[name="letter"]').val(letter);
	 if($('#leftParameters form#filters').length>0) {
		$('#leftParameters form#filters').submit(); 
	 }
     else { 	 
		$('#flavors form#filters').submit();	
	 }
}	

function clearLetter() {
	$('#flavors .filters input[name="letter"]').val('');
	if($('#leftParameters form#filters').length>0) {
		$('#leftParameters form#filters').submit(); 
	 }
     else { 	 
		$('#flavors form#filters').submit();	
	 }	
}	

function clearParameter(id_param,id_value) {
$('#flavors form#filters input#p_'+id_param+'_'+id_value).attr('checked',false);	
	if($('#leftParameters form#filters').length>0) {
		$('#leftParameters form#filters').submit(); 
	 }
     else { 	 
		$('#flavors form#filters').submit();	
	 }	
}	

function clearAllParameters() {
	$('#flavors form#filters input').attr('checked',false);
	$('#flavors .filters input[name="letter"]').val('');
	if($('#leftParameters form#filters').length>0) {
		$('#leftParameters form#filters').submit(); 
	 }
     else { 	 
		$('#flavors form#filters').submit();	
	 }
}	

function RscrollTo(where) {
	if($(where).offset()) {
		$('html, body').animate({scrollTop : $(where).offset().top},400);
	}
}	

function showRestaurantMenu() {
	$( ".menu a:first-child" ).trigger( "click" );
}	

function mapStart(lat,lng,marker_url) {
	if(!lat) var lat=50.040947;
    if(!lng) var lng=21.999281;
    var styles = [
        {stylers: [{ hue: "#5B4224" }]},
        {featureType: "road",elementType: "geometry",stylers: [{ hue: "#ff6600" },{ lightness: 0 }]},
        {featureType: "water",stylers: [{ hue: "#6199DF" },{ lightness: 0 },{ visibility: "simplified" }]}
    ];
    var styledMap = new google.maps.StyledMapType(styles);
    var wspolrzedne = new google.maps.LatLng(lat+0.002,lng);
    var otionsMap = {
        scrollwheel: false,
        zoom: 14,
        center: wspolrzedne,
        mapTypeControl:false
    };
    mapa = new google.maps.Map(document.getElementById("map"), otionsMap);
    mapa.mapTypes.set('map_style', styledMap);
    mapa.setMapTypeId('map_style'); 
	ajaxCall(marker_url, {action: 'marker', lat: lat, lng:lng}, 'addMarker', {});
}	

function addMarker(obj, params) {
	var dymek = new google.maps.InfoWindow();	
	var opcjeMarkera = {  
		position: new google.maps.LatLng(obj.lat,obj.lng),  
		map: mapa,
        icon: '/image/r/60/60/'+obj.ico,
	}
	var marker = new google.maps.Marker(opcjeMarkera);
	marker.txt='<div style="width:220px;min-height:70px">'+obj.html+'</div>';
	google.maps.event.addListener(marker,"click",function(){
		dymek.setContent(marker.txt);
		dymek.open(mapa,marker);
	});
	google.maps.event.trigger(marker,'click');
}

function Mapclear(type) {
   if(type=="all") {
		$('#flavors form#filters select').val('');
		$('#flavors form#filters input').val('');
   }	   
	if(type=="type") {
		$('#flavors form#filters select[name="type"]').val('');
   }
   if(type=="cuisine") {
		$('#flavors form#filters select[name="cuisine"]').val('');
   }
   $('#flavors form#filters').submit(); 
}	


function mapAll() {
	
	var lat=50.040947;
    var lng=21.999281;
    var styles = [
        {stylers: [{ hue: "#5B4224" }]},
        {featureType: "road",elementType: "geometry",stylers: [{ hue: "#ff6600" },{ lightness: 0 }]},
        {featureType: "water",stylers: [{ hue: "#6199DF" },{ lightness: 0 },{ visibility: "simplified" }]}
    ];
    var styledMap = new google.maps.StyledMapType(styles);
    var wspolrzedne = new google.maps.LatLng(lat+0.002,lng);
    var otionsMap = {
        scrollwheel: false,
        zoom: 14,
        center: wspolrzedne,
        mapTypeControl:false
    };
    mapa = new google.maps.Map(document.getElementById("map"), otionsMap);
    mapa.mapTypes.set('map_style', styledMap);
    mapa.setMapTypeId('map_style'); 
	var dymek = new google.maps.InfoWindow();
	$( "#restaurants_map .markers div" ).each(function() {
	        var lat=$(this).attr('data-lat');
	        var lng=$(this).attr('data-long');
			var icon=$(this).attr('data-ico');
			var url=$(this).attr('data-marker');
			var marker_id=$(this).attr('data-id');
			var opcjeMarkera = {  
				position: new google.maps.LatLng(lat,lng),  
				map: mapa,
				icon: icon,
			}
			var marker = new google.maps.Marker(opcjeMarkera);
			marker.txt='<div style="width:220px;min-height:70px"></div>';
			google.maps.event.addListener(marker,"click",function(){
				ajaxCall(url, {action: 'marker', lat: lat, lng:lng}, 'addMarkerMap', {dymek:dymek,marker:marker,mapa:mapa});
			});
	});
}	

function addMarkerMap(obj,params) {
	params.dymek.setContent('<div style="width:220px;min-height:70px">'+obj.html+'</div>');
	params.dymek.open(params.mapa,params.marker);
}


function RateRestaurant(el,id_restaurant,id_comment='') {
	var url=$(el).attr('href');
	ajaxCall(url, {action: 'rate', id_restaurant:id_restaurant,id_comment:id_comment,url:url}, 'showRateModal', {});
}	

function showRateModal(obj, params) {
	
	  if($(window).width()>800) {
		width='500px';
      }
      else {
       width='98%';
      }		  
	
	
	  if(obj.response_status) {
		  $.confirm({
                title: obj.title,
                content: obj.html,
                useBootstrap: false,
                closeIcon: true,
                type: 'default',
                boxWidth:width,
				titleClass:'rateModal',
                buttons: {
					send: {
						text: 'OK',
						btnClass: 'btn-green',
                        action: function () {},
					}
                }
            }); 
	  }
	  else {
		  if(obj.session_user) {
		  $.confirm({
                title: obj.title,
                content: obj.html,
                useBootstrap: false,
                closeIcon: true,
                type: 'default',
                boxWidth:width,
				titleClass:'rateModal',
                buttons: {
					send: {
						text: 'Dodaj opinię',
						btnClass: 'btn-green',
                        action: function () {
							var data_form=$('form#formRateModal').serialize();
							ajaxCall(obj.url, {action: 'rate', id_restaurant:obj.id_restaurant,id_comment:obj.id_comment,url:obj.url,data_form:data_form}, 'showRateModal', {});
						},
					},	
                    close: {
                        text: 'anuluj',
                        action: function () {},
                    }
                }
            });
		  }
		  else {
			  $.confirm({
                title: obj.title,
                content: obj.html,
                useBootstrap: false,
                closeIcon: true,
                type: 'red',
                boxWidth:width,
				titleClass:'rateModal',
                buttons: {
					send: {
						text: 'Przejdź do logowania',
						btnClass: 'btn-green',
                        action: function () {
							$('header.main-header-flavor .login a').click();
						},
					},	
                    close: {
                        text: 'anuluj',
                        action: function () {},
                    }
                }
            }); 
		  }	  
	  }	  
}	

function mouseoverRateModal(type,score) {
	 $('.modal-restaurant-rate #'+type).html(score+'/5');
    RateModalAll();
}	

function mouseoutRateModal(type) {
	if($('input[name="'+type+'"]').val()!=''){
        $('#'+type+'').html($('input[name="'+type+'"]').val()+'/5');
    }else{
        $('#'+type+'').html('Nie oceniono');
    }
	RateModalAll();
}	

function RateModalAll() {
	var suma=0;
    var i=0;
    $('.modal-restaurant-rate input[type="hidden"]').each(function(){
        if($(this).val()>0){
            suma=suma+parseFloat($(this).val());
            i++;
        }
    });
    if(i>0){
        var ocena=suma/i;
        ocena=ocena.toFixed(1);
        $('.modal-restaurant-rate span.rate_all').html(ocena+'/5');
    }else{
        $('.modal-restaurant-rate span.rate_all').html('Nie oceniono');
    }
	
}

function ShowMobileFParameters() {
  $('.mobile #flavors #leftParameters').addClass('open-mobile');
}

function ShowMobileFSort() {
	 $('.mobile #flavors .mobile-sort').addClass('open-mobile');
}	

function FlavorSortMobile(sort,type) {
	$('#flavors .filters input[name="t"]').val(type);
	$('#flavors .filters input[name="sort"]').val(sort);
	$('#flavors #leftParameters #filters').submit();
}	

function showSurveyResults(id) {
	var url=$('#SurveyForm_'+id).attr('action');
	ajaxCall(url, {action: 'results',id:id}, 'showSurvey', {});
}	

function showSurvey(obj,params) {
	$('#home-survey .options').html(obj.html);
}	

function voteSurvey(id) {
	var url=$('#SurveyForm_'+id).attr('action');
	var votes=$('#SurveyForm_'+id).serialize();
	ajaxCall(url, {action: 'vote',id:id,votes:votes}, 'showSurvey', {});	
}	


function showSurveyResultsList(id) {
	var url=$('#SurveyFormList_'+id).attr('action');
	ajaxCall(url, {action: 'results_list',id:id}, 'showSurveyList', {id:id});
}	

function showSurveyList(obj,params) {
	$('#SurveyFormList_'+params.id+' .options').html(obj.html);
}	

function voteSurveyList(id) {
	var url=$('#SurveyFormList_'+id).attr('action');
	var votes=$('#SurveyFormList_'+id).serialize();
	ajaxCall(url, {action: 'vote_list',id:id,votes:votes}, 'showSurveyList', {id:id});	
}	



function GoToPlaceEvents() {
	$('html, body').animate({
        scrollTop: $(".events").offset().top-180
    }, 1000);
}	

