$.fn.TiO_lightbox = function (options) {
	var settings = {
	loader:true,	
    count:true,
	show_slideshow:true,
	thumbs:true,
	show_toolbar:true,
	time_slideshow:4000,
	rel:'lightbox'
	
  };	
  
  var slides = new Array();
  var slideIndex=new Array();
  $.extend(settings,options);
	$('a[rel="'+settings.rel+'"]').each(function( index ) {
	 if($(this).attr('data-caption')) {
       var caption=$(this).attr('data-caption');
     }		 
	 else if($(this).attr('title')) {
       var caption=$(this).attr('title');
     }	 
	 else if($(this).find('img').attr('alt')) {
       var caption=$(this).find('img').attr('alt');
     }	

     if($(this).attr('data-thumb')) {
       var thumb_url=$(this).attr('data-thumb');
     }	 
	   
	 var slide=new Array();
	 slide['width']=$(this).width();
	 slide['height']=$(this).height();
	 slide['photo_url']=$(this).attr('href'); 
	 slideIndex.push(slide['photo_url']);
	 var ext = $(this).attr('href').split('.').pop();
	 var ext2=$(this).attr('href').substring(0, 16);
	 if(ext2=="https://youtu.be" || ext2=="https://www.yout") {ext='youtube';
		var video_url=slide['photo_url'].split('/');
		slide['youtube_url']=video_url.pop();
		slide['thumb']='https://img.youtube.com/vi/'+slide['youtube_url']+'/maxresdefault.jpg';
	 }
	 else { 
		 if(thumb_url) {
			slide['thumb']=thumb_url; 
		 }
		 else { 	 
			slide['thumb']=$(this).find('img').attr('src');
		 }
	 }
	 slide['caption']=caption;
	 slide['type']=ext;
	 slides.push(slide); 
	});
	$('a[rel="'+settings.rel+'"]').on('click',function(event) {
		event.preventDefault();
		var posX = $(this).offset().left+$(this).outerWidth(),posY = ($(this).offset().top-$(window).scrollTop()+($(this).outerHeight()/2));
		var active=slideIndex.indexOf($(this).attr('href'));
		startTiO_lightbox(settings,slides,(active+1),posX,posY); 
				
	});		
}	


function startTiO_lightbox(settings,slides,active,posX,posY) {
	$('#TiOLB').remove();
	$('body').addClass('swipe');
	$('body').append('<div id="TiOLB" data-active="'+active+'"><div class="slide_bg"></div></div>');
	$('#TiOLB').append('<div class="slide_cont"></div>');
	$("html,body").css("overflow", "hidden");
		
	
	if(slides.length>1) {
	    $('#TiOLB .slide_cont').append('<div class="controls"></div>');	
		if(active>1) {
          $('#TiOLB .slide_cont .controls').append('<button class="btn btn_left" title="Poprzedni"><div class="btn_inside"><svg viewBox="0 0 512 512"><path d="M213.7,256L213.7,256L213.7,256L380.9,81.9c4.2-4.3,4.1-11.4-0.2-15.8l-29.9-30.6c-4.3-4.4-11.3-4.5-15.5-0.2L131.1,247.9  c-2.2,2.2-3.2,5.2-3,8.1c-0.1,3,0.9,5.9,3,8.1l204.2,212.7c4.2,4.3,11.2,4.2,15.5-0.2l29.9-30.6c4.3-4.4,4.4-11.5,0.2-15.8  L213.7,256z"/></svg></div></button>');
		}
		else {
         $('#TiOLB .slide_cont .controls').append('<button class="btn btn_left wylacz" title="Poprzedni"><div class="btn_inside"><svg viewBox="0 0 512 512"><path d="M213.7,256L213.7,256L213.7,256L380.9,81.9c4.2-4.3,4.1-11.4-0.2-15.8l-29.9-30.6c-4.3-4.4-11.3-4.5-15.5-0.2L131.1,247.9  c-2.2,2.2-3.2,5.2-3,8.1c-0.1,3,0.9,5.9,3,8.1l204.2,212.7c4.2,4.3,11.2,4.2,15.5-0.2l29.9-30.6c4.3-4.4,4.4-11.5,0.2-15.8  L213.7,256z"/></svg></div></button>');
		}		
		if((active+1)<=slides.length) {
		 $('#TiOLB .slide_cont .controls').append('<button class="btn btn_right" title="Następny"><div class="btn_inside"><svg viewBox="0 0 512 512"><path d="M298.3,256L298.3,256L298.3,256L131.1,81.9c-4.2-4.3-4.1-11.4,0.2-15.8l29.9-30.6c4.3-4.4,11.3-4.5,15.5-0.2l204.2,212.7  c2.2,2.2,3.2,5.2,3,8.1c0.1,3-0.9,5.9-3,8.1L176.7,476.8c-4.2,4.3-11.2,4.2-15.5-0.2L131.3,446c-4.3-4.4-4.4-11.5-0.2-15.8  L298.3,256z"/></svg></div></button>');
		}
        else {
         $('#TiOLB .slide_cont .controls').append('<button class="btn btn_right wylacz" title="Następny"><div class="btn_inside"><svg viewBox="0 0 512 512"><path d="M298.3,256L298.3,256L298.3,256L131.1,81.9c-4.2-4.3-4.1-11.4,0.2-15.8l29.9-30.6c4.3-4.4,11.3-4.5,15.5-0.2l204.2,212.7  c2.2,2.2,3.2,5.2,3,8.1c0.1,3-0.9,5.9-3,8.1L176.7,476.8c-4.2,4.3-11.2,4.2-15.5-0.2L131.3,446c-4.3-4.4-4.4-11.5-0.2-15.8  L298.3,256z"/></svg></div></button>');
        }				
	}	
	$('#TiOLB .slide_cont').append('<div class="slide_caption"><div class="inside_caption">'+slides[(active-1)].caption+'</div></div>');
	
	if(settings.count==true) {
		$('#TiOLB .slide_caption').append('<div class="counter">Zdj. <span>'+active+'</span>/<span>'+(slides.length)+'</span></div>');	
	}
	
	
	
	if(settings.loader==true) {
	setTimeout(function(){	$('#TiOLB').append('<div class="loader"><div class="load-ico"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>')}, 800);
	}
	if(settings.thumbs==true && slides.length>1) {
	  /*
	  $('#TiOLB .slide_cont .slide_btns').append('<a href="#" class="btn btn_thumbs" title="Pokaż miniaturki"><svg enable-background="new 0 0 64 64" height="64px" version="1.1" viewBox="0 0 64 64" width="64px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="grid"/><g id="Layer_2"/><g id="Layer_3"/><g id="Layer_4"/><g id="Layer_5"/><g id="Layer_6"/><g id="Layer_7"/><g id="Layer_8"/><g id="Layer_9"><g><g><path d="M49.268,62.591H14.732V1.409h34.535V62.591z M16.732,60.591h30.535V3.409H16.732V60.591z"/></g><g><g><path d="M41.495,37.85h-18.99v-11.7h18.99V37.85z M24.505,35.85h14.99v-7.7h-14.99V35.85z"/></g><g><path d="M41.495,17.7h-18.99V6h18.99V17.7z M24.505,15.7h14.99V8h-14.99V15.7z"/></g><g><path d="M41.495,58h-18.99V46.3h18.99V58z M24.505,56h14.99v-7.7h-14.99V56z"/></g></g><g><g><rect height="4.188" width="2" x="18.5" y="2.906"/></g><g><rect height="4.188" width="2" x="43.5" y="2.906"/></g><g><rect height="4.188" width="2" x="18.5" y="8.906"/></g><g><rect height="4.188" width="2" x="43.5" y="8.906"/></g><g><rect height="4.188" width="2" x="18.5" y="14.906"/></g><g><rect height="4.188" width="2" x="43.5" y="14.906"/></g><g><rect height="4.188" width="2" x="18.5" y="20.906"/></g><g><rect height="4.188" width="2" x="43.5" y="20.906"/></g><g><rect height="4.188" width="2" x="18.5" y="26.906"/></g><g><rect height="4.188" width="2" x="43.5" y="26.906"/></g><g><rect height="4.188" width="2" x="18.5" y="32.906"/></g><g><rect height="4.188" width="2" x="43.5" y="32.906"/></g><g><rect height="4.188" width="2" x="18.5" y="38.906"/></g><g><rect height="4.188" width="2" x="43.5" y="38.906"/></g><g><rect height="4.188" width="2" x="18.5" y="44.906"/></g><g><rect height="4.188" width="2" x="43.5" y="44.906"/></g><g><rect height="4.188" width="2" x="18.5" y="50.906"/></g><g><rect height="4.188" width="2" x="43.5" y="50.906"/></g><g><rect height="4.188" width="2" x="18.5" y="56.906"/></g><g><rect height="4.188" width="2" x="43.5" y="56.906"/></g></g></g></g><g id="Layer_10"/><g id="Layer_37"/><g id="Layer_11"/><g id="Layer_12"/><g id="Layer_13"/><g id="Layer_14"/><g id="Layer_16"/><g id="Layer_17"/><g id="Layer_18"/><g id="Layer_19"/><g id="Layer_20"/><g id="Layer_21"/><g id="Layer_22"/><g id="Layer_23"/><g id="Layer_24"/><g id="Layer_25"/></svg></a>');	
	  */
	}	
	/*
	$('#TiOLB .slide_cont .slide_btns').append('<a href="#" class="btn btn_play" title="Włącz pokaz slajdów"><svg height="512px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M405.2,232.9L126.8,67.2c-3.4-2-6.9-3.2-10.9-3.2c-10.9,0-19.8,9-19.8,20H96v344h0.1c0,11,8.9,20,19.8,20  c4.1,0,7.5-1.4,11.2-3.4l278.1-165.5c6.6-5.5,10.8-13.8,10.8-23.1C416,246.7,411.8,238.5,405.2,232.9z"/></svg></a>');
	*/
	$('#TiOLB .slide_cont').append('<div class="slide_btns"><a href="#" class="btn btn_close" title="Zamknij"><svg style="enable-background:new 0 0 512 512;" viewBox="0 0 512 512"><path d="M437.5,386.6L306.9,256l130.6-130.6c14.1-14.1,14.1-36.8,0-50.9c-14.1-14.1-36.8-14.1-50.9,0L256,205.1L125.4,74.5  c-14.1-14.1-36.8-14.1-50.9,0c-14.1,14.1-14.1,36.8,0,50.9L205.1,256L74.5,386.6c-14.1,14.1-14.1,36.8,0,50.9  c14.1,14.1,36.8,14.1,50.9,0L256,306.9l130.6,130.6c14.1,14.1,36.8,14.1,50.9,0C451.5,423.4,451.5,400.6,437.5,386.6z"/></svg></a></div>');
	if(settings.show_toolbar==true) {
		$('#TiOLB .slide_cont .slide_btns').append('<a href="#" class="btn btn_fullscreen" title="Tryb pełnoekranowy"><svg viewBox="0 0 24 24"><path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"/></svg></a>');
	}
	$('#TiOLB .slide_cont .slide_btns').append('<a href="#" class="btn btn_zoom" title="Powiększ"><svg style="enable-background:new 0 0 64 64;" viewBox="0 0 64 64"><g><g><path d="M34.422,28.788H18.389c-1.526,0-2.763-1.237-2.763-2.763c0-1.526,1.237-2.763,2.763-2.763h16.033    c1.526,0,2.763,1.237,2.763,2.763C37.185,27.551,35.948,28.788,34.422,28.788z" style="fill:#acacac;"/></g><g><path d="M26.406,36.805c-1.526,0-2.763-1.237-2.763-2.763V18.009c0-1.526,1.237-2.763,2.763-2.763    c1.526,0,2.763,1.237,2.763,2.763v16.033C29.168,35.568,27.932,36.805,26.406,36.805z" style="fill:#acacac;"/></g><g><path d="M26.406,48.276c-12.069,0-21.888-9.819-21.888-21.888S14.337,4.5,26.406,4.5    s21.888,9.819,21.888,21.888S38.474,48.276,26.406,48.276z M26.406,10.026c-9.022,0-16.362,7.34-16.362,16.362    s7.34,16.362,16.362,16.362s16.362-7.34,16.362-16.362S35.428,10.026,26.406,10.026z" style="fill:#acacac;"/></g><g><path d="M51.005,59.5c-2.264,0-4.393-0.882-5.994-2.483L33.295,45.301    c-0.622-0.622-0.911-1.503-0.777-2.372c0.133-0.869,0.673-1.623,1.452-2.031c2.973-1.552,5.368-3.94,6.925-6.905    c0.408-0.778,1.162-1.315,2.031-1.447c0.868-0.132,1.748,0.157,2.369,0.778l11.705,11.705c1.601,1.601,2.483,3.73,2.483,5.994    c0,2.264-0.882,4.393-2.483,5.994l0,0c0,0,0,0,0,0C55.398,58.618,53.269,59.5,51.005,59.5z M39.637,43.828l9.281,9.281    c0.557,0.557,1.298,0.864,2.087,0.864c0.788,0,1.529-0.307,2.086-0.864c0,0,0,0,0,0c0.557-0.557,0.864-1.298,0.864-2.087    c0-0.788-0.307-1.529-0.864-2.087l-9.276-9.276C42.618,41.234,41.215,42.634,39.637,43.828z" style="fill:#acacac;"/></g></g></svg></a>'); 

	$('#TiOLB .slide_cont').append('<div class="slide_inside"></div>');
	var h=$(window).height()-120;
    if($(this).width()>800) {var w=$(this).width()-200;} else {var w=$(this).width();}
	if(slides[(active-1)].type=="html") {
		
		
	}	
	else if(slides[(active-1)].type=="youtube") {
		
	$("#TiOLB .slide_cont .slide_inside").css("left",""+(posX-(slides[(active-1)].width/2))+"px");
	$("#TiOLB .slide_cont .slide_inside").css("top",""+(posY)+"px");
	$("#TiOLB .slide_cont .slide_inside").addClass('youtube');

	$('#TiOLB .slide_cont .slide_inside').append('<div class="youtube-iframe" style="position: relative;width: 100%;height: 0;padding-bottom: 56.25%;"><iframe src="https://www.youtube.com/embed/'+slides[(active-1)]['youtube_url']+'?autoplay=1" title="'+slides[(active-1)].caption+'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>');	
	$("#TiOLB .slide_cont .slide_inside iframe").on('load',function() {
			var img_height=slides[(active-1)].height;
			var img_width=slides[(active-1)].width;
			var ratio=img_width/img_height;
			var new_height=h;
            var new_width=h*ratio;
			if(new_width>w) {
                 var new_width=w;
            }				
			if(new_height>h) {
                 var new_height=h;
            }
		     $("#TiOLB .slide_cont .slide_inside").css("width", new_width);
			 $("#TiOLB .slide_caption").css("width", new_width);
			 $("#TiOLB .slide_cont .slide_inside").css("height", new_height);
			 $("#TiOLB .slide_cont .slide_inside").css('left','50%');
			 $("#TiOLB .slide_cont .slide_inside").css('top','10px');
			 $('#TiOLB').addClass('open');
			 $('#TiOLB .loader').remove();
			  resize_TiO_lightbox();
		});		
	}	
	else {
		$("#TiOLB .slide_cont .slide_inside").css("left",""+(posX-(slides[(active-1)].width/2))+"px");
	    $("#TiOLB .slide_cont .slide_inside").css("top",""+(posY)+"px");
		$('#TiOLB .slide_cont .slide_inside').append('<img class="slide-img" src="'+slides[(active-1)].photo_url+'">');
		$("#TiOLB .slide_cont .slide_inside .slide-img").on('load',function() {
			var img_height=slides[(active-1)].height;
			var img_width=slides[(active-1)].width;
			var ratio=img_width/img_height;
			var new_height=h;
            var new_width=h*ratio;
			if(new_width>w) {
                 var new_width=w;
            }				
			if(new_height>h) {
                 var new_height=h;
            }
		     $("#TiOLB .slide_cont .slide_inside").css("width", new_width);
			 $("#TiOLB .slide_caption").css("width", new_width);
			 $("#TiOLB .slide_cont .slide_inside").css("height", new_height);
			 $("#TiOLB .slide_cont .slide_inside").css('left','50%');
			 $("#TiOLB .slide_cont .slide_inside").css('top','10px');
			 $('#TiOLB').addClass('open');
			 $('#TiOLB .loader').remove();
			  resize_TiO_lightbox();
		});
		
	}	
	
	$('#TiOLB').addClass('show_thumbs');	
	  $('#TiOLB').prepend('<div class="thumbs"></div>');
	  if(slides) {
		  var active_thumb='';
		  for (var i = 0; i < slides.length; i++) {
			  if((i+1)==$('#TiOLB').attr('data-active')) {
				active_thumb='active';  
			  }	
              else {
               active_thumb='';
              }				 		  
		      $('#TiOLB .thumbs').append('<div class="thumb thumb_'+(i+1)+' '+active_thumb+'"><img src="'+slides[i].thumb+'" thumb-id="'+(i+1)+'" /></div>');
		  }
		  
          var active_id=$('#TiOLB .thumbs .active img').attr('thumb-id');
          var przesun=$('#TiOLB .thumbs .thumb_'+(active_id-1)+'').width();
		  if(($('#TiOLB .thumbs .active').position().left+$('#TiOLB .thumbs .active').width()+przesun)>=$(window).width()) {  
			$('#TiOLB .thumbs').css('-webkit-transform','translateX(-'+($('#TiOLB .thumbs').width()-$(window).width()+przesun)+'px)');   
		  }	  
		  
		 
		  
		  var spr=($(window).width()/slides.length);
		  var single_thumb_width=$('#TiOLB .thumbs .thumb').width();
		  if(spr>single_thumb_width) { $('#TiOLB .thumbs').css('justify-content','center');}
		  else {$('#TiOLB .thumbs').css('justify-content','flex-start');}
		  
		  
		  	$('#TiOLB .thumbs img').on('click',function(event) {
	          var active_th=$(this).attr('thumb-id');
			  $('#TiOLB').attr('data-active',active_th);
		      show_TiO_lightbox(slides,(active_th));
			  	if(active_th==1 || active_th<1) {
					$('#TiOLB .controls .btn_left').addClass('wylacz');
				}	
				else {
				  $('#TiOLB .controls .btn_left').removeClass('wylacz');	
				}
				if((active_th)==slides.length) {
					$('#TiOLB .controls .btn_right').addClass('wylacz');
				}	
				else {
					$('#TiOLB .controls .btn_right').removeClass('wylacz');
				}	
	        });	 
	  }	
	
	
	$(window).on('resize',function() {
       resize_TiO_lightbox();
	});
	
	

	$('#TiOLB .slide_bg').on('click',function(event) {
		$('#TiOLB .loader').remove();	
		  setTimeout(function(){
		  $('#TiOLB').remove();
		  $("html,body").css("overflow", "");
		  $(document).off();
		  $(window).unbind();
		  $("html,body").css("overflow-x", "hidden");
		  $('body').removeClass('zoom');
		  }, 100);
	});	
	
	
	$('#TiOLB .btn_fullscreen').on('click',function(event) {
		event.preventDefault();
		var elem = document.getElementById("TiOLB"); 
		if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) { /* Safari */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE11 */
    elem.msRequestFullscreen();
  }
		
	});	
	

	
	$('#TiOLB .btn_thumbs').on('click',function(event) {
	event.preventDefault();
	
	if($('#TiOLB').hasClass('show_thumbs')) {
	  $('#TiOLB').removeClass('show_thumbs');
      $('#TiOLB .thumbs').remove(); 
	}	
	else {
	  $('#TiOLB').addClass('show_thumbs');	
	  $('#TiOLB').prepend('<div class="thumbs"></div>');
	  if(slides) {
		  var active_thumb='';
		  for (var i = 0; i < slides.length; i++) {
			  if((i+1)==$('#TiOLB').attr('data-active')) {
				active_thumb='active';  
			  }	
              else {
               active_thumb='';
              }				 		  
		      $('#TiOLB .thumbs').append('<div class="thumb thumb_'+(i+1)+' '+active_thumb+'"><img src="'+slides[i].thumb+'" thumb-id="'+(i+1)+'" /></div>');
		  }
		  
          var active_id=$('#TiOLB .thumbs .active img').attr('thumb-id');
          var przesun=$('#TiOLB .thumbs .thumb_'+(active_id-1)+'').width();
		  var spr=($(window).width()/slides.length);
		  var single_thumb_width=$('#TiOLB .thumbs .thumb').width();
		  if(($('#TiOLB .thumbs .active').position().left+$('#TiOLB .thumbs .active').width()+przesun)>=$(window).width() && spr<single_thumb_width) {  
			$('#TiOLB .thumbs').css('-webkit-transform','translateX(-'+($('#TiOLB .thumbs').width()-$(window).width()+przesun)+'px)');   
		  }	  
		  
		  	$('#TiOLB .thumbs img').on('click',function(event) {
	          var active_th=$(this).attr('thumb-id');
			  $('#TiOLB').attr('data-active',active_th);
		      show_TiO_lightbox(slides,(active_th));
			  	if(active_th==1 || active_th<1) {
					$('#TiOLB .controls .btn_left').addClass('wylacz');
				}	
				else {
				  $('#TiOLB .controls .btn_left').removeClass('wylacz');	
				}
				if((active_th)==slides.length) {
					$('#TiOLB .controls .btn_right').addClass('wylacz');
				}	
				else {
					$('#TiOLB .controls .btn_right').removeClass('wylacz');
				}	
	        });	 
	  }	
	}	
	});



    $('body.swipe #TiOLB .slide_inside').swipe( {
	   swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
			if($('body').hasClass('swipe') && !$('#TiOLB .slide_btns .btn_play').hasClass('btn_pause')) {
			if (direction == "left" && !$('#TiOLB .btn.btn_right').hasClass('wylacz')) {
			  next_TiO_lightbox(slides);		
			}
			if (direction == "right" && !$('#TiOLB .btn.btn_left').hasClass('wylacz')) {
			  prev_TiO_lightbox(slides);	
			}			
			if (direction == "down" && !$('#TiOLB .btn.btn_right').hasClass('wylacz')) {
				 next_TiO_lightbox(slides);	
			}
			if (direction == "up" && !$('#TiOLB .btn.btn_left').hasClass('wylacz')) {
			  prev_TiO_lightbox(slides);	
			}
			}
		},
	   threshold:20
	});	


	$('#TiOLB .btn_zoom').on('click',function(event) { 
	event.preventDefault();
	  if($('body').hasClass('zoom')) {
		$('body').removeClass('zoom');  
		$('body').addClass('swipe');
		$('#TiOLB .slide_inside .slide-img').css('top','');
		$('#TiOLB .slide_inside').removeClass('zoo-item');
		$('body #TiOLB .slide_inside').swipe("destroy");
		$('body #TiOLB .slide_btns a.btn_zoom').html('<svg style="enable-background:new 0 0 64 64;" viewBox="0 0 64 64"><g><g><path d="M34.422,28.788H18.389c-1.526,0-2.763-1.237-2.763-2.763c0-1.526,1.237-2.763,2.763-2.763h16.033    c1.526,0,2.763,1.237,2.763,2.763C37.185,27.551,35.948,28.788,34.422,28.788z" style="fill:#acacac;"/></g><g><path d="M26.406,36.805c-1.526,0-2.763-1.237-2.763-2.763V18.009c0-1.526,1.237-2.763,2.763-2.763    c1.526,0,2.763,1.237,2.763,2.763v16.033C29.168,35.568,27.932,36.805,26.406,36.805z" style="fill:#acacac;"/></g><g><path d="M26.406,48.276c-12.069,0-21.888-9.819-21.888-21.888S14.337,4.5,26.406,4.5    s21.888,9.819,21.888,21.888S38.474,48.276,26.406,48.276z M26.406,10.026c-9.022,0-16.362,7.34-16.362,16.362    s7.34,16.362,16.362,16.362s16.362-7.34,16.362-16.362S35.428,10.026,26.406,10.026z" style="fill:#acacac;"/></g><g><path d="M51.005,59.5c-2.264,0-4.393-0.882-5.994-2.483L33.295,45.301    c-0.622-0.622-0.911-1.503-0.777-2.372c0.133-0.869,0.673-1.623,1.452-2.031c2.973-1.552,5.368-3.94,6.925-6.905    c0.408-0.778,1.162-1.315,2.031-1.447c0.868-0.132,1.748,0.157,2.369,0.778l11.705,11.705c1.601,1.601,2.483,3.73,2.483,5.994    c0,2.264-0.882,4.393-2.483,5.994l0,0c0,0,0,0,0,0C55.398,58.618,53.269,59.5,51.005,59.5z M39.637,43.828l9.281,9.281    c0.557,0.557,1.298,0.864,2.087,0.864c0.788,0,1.529-0.307,2.086-0.864c0,0,0,0,0,0c0.557-0.557,0.864-1.298,0.864-2.087    c0-0.788-0.307-1.529-0.864-2.087l-9.276-9.276C42.618,41.234,41.215,42.634,39.637,43.828z" style="fill:#acacac;"/></g></g></svg>');
		$('body.swipe #TiOLB .slide_inside').swipe( {
	   swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
			if($('body').hasClass('swipe')) {
			if (direction == "left" && !$('#TiOLB .btn.btn_right').hasClass('wylacz')) {
			  next_TiO_lightbox(slides);		
			}
			if (direction == "right" && !$('#TiOLB .btn.btn_left').hasClass('wylacz')) {
			  prev_TiO_lightbox(slides);	
			}			
			if (direction == "down" && !$('#TiOLB .btn.btn_right').hasClass('wylacz')) {
				 next_TiO_lightbox(slides);	
			}
			if (direction == "up" && !$('#TiOLB .btn.btn_left').hasClass('wylacz')) {
			  prev_TiO_lightbox(slides);	
			}
			}
		},
	   threshold:20
	});	
	  }
	  else {  
	    $('body').removeClass('swipe');
		$('body').addClass('zoom');
		$('body #TiOLB .slide_btns a.btn_zoom').html('<svg style="enable-background:new 0 0 64 64;" viewBox="0 0 64 64"><g><g><path d="M34.424,28.79H18.39c-1.526,0-2.763-1.237-2.763-2.763c0-1.526,1.237-2.763,2.763-2.763h16.034    c1.526,0,2.763,1.237,2.763,2.763C37.187,27.553,35.95,28.79,34.424,28.79z" style="fill:#acacac;"/></g><g><path d="M26.407,48.279c-12.07,0-21.889-9.82-21.889-21.889S14.337,4.5,26.407,4.5    s21.889,9.82,21.889,21.889S38.477,48.279,26.407,48.279z M26.407,10.026c-9.023,0-16.363,7.34-16.363,16.363    s7.34,16.363,16.363,16.363s16.363-7.34,16.363-16.363S35.43,10.026,26.407,10.026z" style="fill:#acacac;"/></g><g><path d="M51.008,59.5c-2.171,0-4.342-0.826-5.994-2.479L33.297,45.304    c-0.622-0.622-0.911-1.503-0.778-2.372c0.133-0.87,0.673-1.624,1.452-2.031c2.973-1.553,5.368-3.94,6.925-6.906    c0.408-0.778,1.162-1.315,2.031-1.447c0.868-0.132,1.748,0.157,2.369,0.778l11.706,11.706c3.305,3.305,3.305,8.684,0,11.989    c0,0,0,0,0,0C55.35,58.674,53.179,59.5,51.008,59.5z M55.049,55.067h0.002H55.049z M39.64,43.831l9.282,9.282    c1.151,1.151,3.023,1.151,4.174,0c1.151-1.151,1.151-3.023,0-4.174l-9.277-9.277C42.621,41.237,41.217,42.637,39.64,43.831z" style="fill:#acacac;"/></g></g></svg>');
		$('#TiOLB .slide_inside').addClass('zoo-item');
		$('#TiOLB .slide_inside .slide-img').css('top','100%');
		var dod=0;
		$('body.zoom #TiOLB .slide_inside').on('mousemove',function(event){
		if($('body').hasClass('zoom')) {
			pozX=event.pageX-$('body.zoom #TiOLB .slide_inside').offset().left;
			pozY=event.pageY-$('body.zoom #TiOLB .slide_inside').offset().top;
			imgX=$('body.zoom #TiOLB .slide_inside .slide-img').width();
			 imgY=$('body.zoom #TiOLB .slide_inside .slide-img').height();
             $('#TiOLB .slide_inside').scrollLeft(pozX);
			 var hD=$('body.zoom #TiOLB .slide_inside').height();
			 if(pozY<(0.3*hD)) {
              	$('#TiOLB .slide_inside img').css('top','100%');	
				
			 }	 
			 else if(pozY>0.7*hD) {
				$('#TiOLB .slide_inside img').css('top','-100%'); 
			 }	
             else {
               $('#TiOLB .slide_inside img').css('top','0%');

			 }
		}			 
		});	
		$('body.zoom #TiOLB .slide_inside').swipe( {
		    swipeStatus:function(event, phase, direction, distance,duration) {
			if(phase!='cancel' && phase!='end') { 
			if(event.touches) {
             pozX=event.touches[0].pageX-$('body.zoom #TiOLB .slide_inside').offset().left;
			 pozY=event.touches[0].pageY-$('body.zoom #TiOLB .slide_inside').offset().top;
            }
            else {			
			 pozX=event.pageX-$('body.zoom #TiOLB .slide_inside').offset().left;
			 pozY=event.pageY-$('body.zoom #TiOLB .slide_inside').offset().top;
			} 
			 
			 imgX=$('body.zoom #TiOLB .slide_inside .slide-img').width();
			 imgY=$('body.zoom #TiOLB .slide_inside .slide-img').height();
             $('#TiOLB .slide_inside').scrollLeft(pozX);
			 var hD=$('body.zoom #TiOLB .slide_inside').height();
			 if(pozY<(0.3*hD)) {
              	$('#TiOLB .slide_inside img').css('top','100%');	
			 }	 
			 else if(pozY>0.7*hD) {
				$('#TiOLB .slide_inside img').css('top','-100%'); 
			 }	
             else {
               $('#TiOLB .slide_inside img').css('top','0%');
			 }				  
            } 		 
		   },
		   threshold:0,
		   triggerOnTouchEnd:true
		});
	  }
	});	

	$('#TiOLB .btn_close').on('click',function(event) {
	event.preventDefault();
	    $('#TiOLB .loader').remove();	
		  setTimeout(function(){
		  $('#TiOLB').remove();
		  $("html,body").css("overflow", "");
		  $("html,body").css("overflow-x", "hidden");
		  }, 100);
		  $(document).off();
		  $(window).unbind();
		  $('#TiOLB .zoo-item').remove();
          $('#TiOLB .zoo-img').remove();
	      $('body').removeClass('zoom');
		  $('body.swipe #TiOLB .slide_inside').swipe( {
	   swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
			if($('body').hasClass('swipe')) {
			if (direction == "left" && !$('#TiOLB .btn.btn_right').hasClass('wylacz')) {
			  next_TiO_lightbox(slides);		
			}
			if (direction == "right" && !$('#TiOLB .btn.btn_left').hasClass('wylacz')) {
			  prev_TiO_lightbox(slides);	
			}			
			if (direction == "down" && !$('#TiOLB .btn.btn_right').hasClass('wylacz')) {
				 next_TiO_lightbox(slides);	
			}
			if (direction == "up" && !$('#TiOLB .btn.btn_left').hasClass('wylacz')) {
			  prev_TiO_lightbox(slides);	
			}
			}
		},
	   threshold:20
	});	
	});

	
	$('#TiOLB .controls .btn_right').on('click',function(event) {
	event.preventDefault();
	if(!$(event.target).hasClass('wylacz') && !$(event.target).parent().hasClass('wylacz') && !$(event.target).parent().parent().hasClass('wylacz')) {	
	 next_TiO_lightbox(slides);
	}
	});
	
	
	
	$('#TiOLB .controls .btn_left').on('click',function(event) {
	event.preventDefault();
	if(!$(event.target).hasClass('wylacz') && !$(event.target).parent().hasClass('wylacz') && !$(event.target).parent().parent().hasClass('wylacz')) {
	prev_TiO_lightbox(slides);
	}
	});
	
	$(document).on('keyup', function(e) {
		if($('#TiOLB .controls').length>0) {
		if(e.keyCode==27) {
			clearInterval(slideShow); 
			$('#TiOLB .loader').remove();	
		  setTimeout(function(){
		  $('#TiOLB').remove();
		  $("html,body").css("overflow", "");
		  $("html,body").css("overflow-x", "hidden");
		  }, 100);	
          $('#TiOLB .zoo-img').remove();
		  $('body').removeClass('zoom');
		}	
		else if((e.keyCode==39 || e.keyCode==40) && !$('#TiOLB .controls .btn_right').hasClass('wylacz')) {
		 next_TiO_lightbox(slides);	
		}
       else if((e.keyCode==37 || e.keyCode==38) && !$('#TiOLB .controls .btn_left').hasClass('wylacz')) {	  
		 prev_TiO_lightbox(slides);	
		}
		}	
	});	
	
	$(window).bind('mousewheel DOMMouseScroll', function(event){
	if($('#TiOLB .controls').length>0) {
    if (event.originalEvent.wheelDelta > 0 || event.originalEvent.detail < 0) {
		if(!$('#TiOLB .controls .btn_left').hasClass('wylacz')) {
        prev_TiO_lightbox(slides);	
		}
    }
    else  {
		if(!$('#TiOLB .controls .btn_right').hasClass('wylacz')) {
        next_TiO_lightbox(slides);
		}
    }
	}
   });
	
	var slideShow='';
	$('#TiOLB .slide_btns .btn_play').on('click',function(event) {
	  event.preventDefault();
	  if($(this).hasClass('btn_pause')) {
		$(this).removeClass('btn_pause');  
		$('#TiOLB .slide_btns .btn_play').html('<svg height="512px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M405.2,232.9L126.8,67.2c-3.4-2-6.9-3.2-10.9-3.2c-10.9,0-19.8,9-19.8,20H96v344h0.1c0,11,8.9,20,19.8,20  c4.1,0,7.5-1.4,11.2-3.4l278.1-165.5c6.6-5.5,10.8-13.8,10.8-23.1C416,246.7,411.8,238.5,405.2,232.9z"/></svg>');	
          $('#TiOLB .slide_cont .slideshow_status').remove();
		  clearInterval(slideShow); 
	  }
      else {
       $(this).addClass('btn_pause');
		$('#TiOLB .slide_cont').append('<div class="slideshow_status"><div class="procent"></div></div>');
       $('#TiOLB .slide_btns .btn_play').html('<svg enable-background="new 0 0 23 29" height="29px" id="Layer_1" version="1.1" viewBox="0 0 23 29" width="23px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><rect height="29" width="9"/><rect height="29" width="9" x="14"/></g></svg>');
       $('#TiOLB .slide_cont .slideshow_status .procent').css('width','0%');
	   $('#TiOLB .slide_cont .slideshow_status .procent').animate({ width:'100%'},settings.time_slideshow);               
					var active=parseInt($('#TiOLB').attr('data-active'));
					var show=active;
					var clear='no';
					slideShow =setInterval(function () {
					$('#TiOLB .slide_cont .slideshow_status .procent').css('width','0%');
					$('#TiOLB .slide_cont .slideshow_status .procent').animate({ width:'100%'},(settings.time_slideshow-200));  	
					show += 1;		
					if((active+1)==show && clear=="yes") {
                      clearInterval(slideShow); 
					  $('#TiOLB .slide_btns .btn_play').removeClass('btn_pause');
					  $('#TiOLB .slide_btns .btn_play').html('<svg height="512px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M405.2,232.9L126.8,67.2c-3.4-2-6.9-3.2-10.9-3.2c-10.9,0-19.8,9-19.8,20H96v344h0.1c0,11,8.9,20,19.8,20  c4.1,0,7.5-1.4,11.2-3.4l278.1-165.5c6.6-5.5,10.8-13.8,10.8-23.1C416,246.7,411.8,238.5,405.2,232.9z"/></svg>');	
					  $('#TiOLB .slide_cont .slideshow_status').remove();
                    }
					else if((active+1)==show && clear=="no") {
					 clear="yes";	
					}	
					
					if(show==slides.length) {
						$('#TiOLB .controls .btn_right').addClass('wylacz');
					}	
					else {
						$('#TiOLB .controls .btn_right').removeClass('wylacz');
					}	
					
					if(show==1) {
						$('#TiOLB .controls .btn_left').addClass('wylacz');
					}	
					else {
						$('#TiOLB .controls .btn_left').removeClass('wylacz');	
					}
					
					
                    if((show-1)==slides.length) { 
					  show=1;
					  $('#TiOLB').attr('data-active',1); 
					  show_TiO_lightbox(slides,1);
					  $('#TiOLB .controls .btn_left').addClass('wylacz');
					}
					else {
					  $('#TiOLB').attr('data-active',show); 
					  show_TiO_lightbox(slides,show);
                    }
					}, settings.time_slideshow);
	 }		  
	});	
}


function prev_TiO_lightbox(slides) {
  if(!$('body').hasClass('zoom')) {	
	$('#TiOLB .loader').remove();
	$('#TiOLB .controls .btn_right').removeClass('wylacz');
	var active=parseInt($('#TiOLB').attr('data-active'));
	if(active>1) {
		$('#TiOLB').attr('data-active',(active-1));
	}
	if(active==2) {
		$('#TiOLB .controls .btn_left').addClass('wylacz');
	}	
	else {
	  $('#TiOLB .controls .btn_left').removeClass('wylacz');	
	}	

show_TiO_lightbox(slides,(active-1));
  }	
}	


function next_TiO_lightbox(slides) {
	  if(!$('body').hasClass('zoom')) {
	$('#TiOLB .loader').remove();
	$('#TiOLB .controls .btn_left').removeClass('wylacz');	
	var active=parseInt($('#TiOLB').attr('data-active'));
	if((active+1)<=slides.length) {
		$('#TiOLB').attr('data-active',(active+1));
	}
	if((active+1)==slides.length) {
		$('#TiOLB .controls .btn_right').addClass('wylacz');
	}	
	else {
		$('#TiOLB .controls .btn_right').removeClass('wylacz');
	}	

show_TiO_lightbox(slides,(active+1));
	  } 
}	

function show_TiO_lightbox(slides,active) {
		$('#TiOLB .thumbs .thumb').removeClass('active');
	    $('#TiOLB .thumb_'+active).addClass('active');
	    
		
		if($('#TiOLB .slide_inside').hasClass('youtube')) {
			$('#TiOLB .slide_inside').removeClass('youtube');
			$('#TiOLB .slide_inside').html('<img class="slide-img" src="">');
		}	
		
		if(slides && slides[(active-1)] && slides[(active-1)].type == 'youtube') {
		  $('#TiOLB .slide_cont .slide_inside').html('<div class="youtube-iframe" style="position: relative;width: 100%;height: 0;top:0px;padding-bottom: 56.25%;"><iframe src="https://www.youtube.com/embed/'+slides[(active-1)]['youtube_url']+'?autoplay=1" title="'+slides[(active-1)].caption+'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>');	
          $('#TiOLB .slide_cont .slide_inside').addClass('youtube');
		  $('#TiOLB .slide_cont .counter').html('Zdj. <span>'+active+'</span>/<span>'+(slides.length)+'</span>');
          $('#TiOLB .slide_cont .slide_caption .inside_caption').html(slides[(active-1)].caption); 		  
		  resize_TiO_lightbox();	
		}	
		
		else {
		/*
		$('#TiOLB .slide_inside .slide-img').fadeOut(300, function() {
		$('#TiOLB .slide_cont .counter').html('Zdj. <span>'+active+'</span>/<span>'+(slides.length)+'</span>');		 
		$('#TiOLB .slide_inside .slide-img').attr('src',slides[(active-1)].photo_url);
		$("#TiOLB .slide_cont .slide_inside .slide-img").on('load',function() {
		resize_TiO_lightbox();
		});
		
		
		
		$('#TiOLB .slide_cont .slide_caption .inside_caption').html(slides[(active-1)].caption);	
		$('#TiOLB .slide_inside .slide-img').delay(200).fadeTo(200,1);
		 });
		 */
		 
		 $('#TiOLB .slide_cont .counter').html('Zdj. <span>'+active+'</span>/<span>'+(slides.length)+'</span>');		 
		$('#TiOLB .slide_inside .slide-img').attr('src',slides[(active-1)].photo_url);
		$("#TiOLB .slide_cont .slide_inside .slide-img").on('load',function() {
		resize_TiO_lightbox();
		});
		 $('#TiOLB .slide_cont .slide_caption .inside_caption').html(slides[(active-1)].caption);	
		 
		 
		} 
}	

function resize_TiO_lightbox() {
	    if($(window).width()>770) {
		$("#TiOLB .slide_cont .slide_inside").css("width", ($(window).width()-100)+'px');
		$("#TiOLB .slide_caption").css("width", ($(window).width()-100)+'px');
	    $("#TiOLB .slide_cont .slide_inside").css("height", ($(window).height()-120)+'px');	
		}
		else {
		$("#TiOLB .slide_cont .slide_inside").css("width", ($(window).width()-20)+'px');
		$("#TiOLB .slide_caption").css("width",($(window).width()-20)+'px');
	    $("#TiOLB .slide_cont .slide_inside").css("height", ($(window).height()-120)+'px');	
		}	
		if($("#TiOLB .slide_cont .slide_inside").hasClass('youtube')) {
			var img_width=$("#TiOLB .slide_cont .slide_inside iframe").width();
			var img_height=$("#TiOLB .slide_cont .slide_inside iframe").height();	
		}	
		else {
			var img_width=$("#TiOLB .slide_cont .slide_inside .slide-img").width();
			var img_height=$("#TiOLB .slide_cont .slide_inside .slide-img").height();
		}
	
		var ratio=img_width/img_height;
		var h=img_height;
		if(h>($(window).height()-110)) {
		h=$(window).height()-110;
img_height=h;		
		}	
		if($(window).width()>770) {var w=$(window).width()-100;$("#TiOLB .slide_cont .slide_inside").css('top',(($(window).height()-120-img_height)/2)+'px');}
		else {var w=$(window).width()-20;$("#TiOLB .slide_cont .slide_inside").css('top',(($(window).height()-120-img_height)/2)+'px');}
		
			var new_height=h;
            var new_width=h*ratio;
			if(new_width>w) {
                 var new_width=w;
            }				
			if(new_height>h) {
                 var new_height=h;
            }

		$("#TiOLB .slide_cont .slide_inside").css("width", new_width);
		$("#TiOLB .slide_caption").css("width",new_width);
	    $("#TiOLB .slide_cont .slide_inside").css("height", new_height);
		
		
		 $("#TiOLB .slide_cont .slide_inside").css('transform','scale(1) translate(-50%,0%)');
		 if($('#TiOLB').hasClass('show_thumbs')) {
		var spr=($(window).width()/$('#TiOLB .thumbs .thumb').length);
		var single_thumb_width=$('#TiOLB .thumbs .thumb').width();
		  if(spr>single_thumb_width) { $('#TiOLB .thumbs').css('justify-content','center');}
		  else {$('#TiOLB .thumbs').css('justify-content','flex-start');}
		   
		   if(spr<single_thumb_width) { 
           var active_id=$('#TiOLB .thumbs .active img').attr('thumb-id');
		   var ile_thumb=$('#TiOLB .thumbs .thumb').length;
		   if(active_id<2) {
			  $('#TiOLB .thumbs').css('-webkit-transform','translateX(0px)');  
		   }	   
		   else if(active_id>ile_thumb) {
            $('#TiOLB .thumbs').css('-webkit-transform','translateX(-'+($('#TiOLB .thumbs').width()-$(window).width())+'px)');
		   } 	
             else if(active_id!=ile_thumb) {
			  var pos=($('#TiOLB .thumbs .active').offset().left-$('#TiOLB .thumbs').offset().left)-($(window).width()/2)+($('#TiOLB .thumbs .active').width()/2);
			  if(pos>0) {
			  $('#TiOLB .thumbs').css('-webkit-transform','translateX(-'+pos+'px)');
			  }
			  else {
			  $('#TiOLB .thumbs').css('-webkit-transform','translateX(0px)');
			  }			  
			 }
		   }
		   else {
			 $('#TiOLB .thumbs').css('-webkit-transform','translateX(0px)');  
		   }	   
        }
		
		
		 
}	



