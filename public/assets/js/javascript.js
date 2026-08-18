function ajaxCall(url, data, callback, callback_params) {
    $.ajax({
        dataType: 'json',
        method: 'POST',
        url: url,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data: data,
    })
    .done(function (response) {
        console.log("success");
        if (response.html) {
            response.html = decodeURIComponent(atob(response.html)).replace(/\+/g, ' ');
        }
        if(callback) {
            window[callback](response, callback_params);
        }
        if(response.callback) {
            window[response.callback](response, callback_params);
        }
    })
    .fail(function (e, r, t) {
        console.log("error");
        console.log(e);
        console.log(r);
        console.log(t);
    })
    .always(function () {
        console.log("complete");
    });
}

$(function(){
    
    $(document).on('click', '.show-password', function(e){
        e.preventDefault();
        let type = $(this).siblings('input').attr('type');
        if(type == 'password') {
            $(this).siblings('input').attr('type', 'text');
            $(this).text($(this).data('hide'));
        } else {
            $(this).siblings('input').attr('type', 'password');
            $(this).text($(this).data('show'));
        }
    });
    
    $('.cookies-box .close').on('click', function(){
        let waznosc = new Date();
        waznosc.setMonth(waznosc.getMonth()+6);
        document.cookie = "CookiesInfo=closed; expires=" + waznosc.toGMTString() + ";domain=." + window.location.host + ";path=/";
        $(this).parents('.cookies-box').fadeOut();
    });
    
    $('header .navbar-toggle').on('click', function () {
        var target = $(this).attr('data-target');
        if ($(target).hasClass('open')) {
            $(target).removeClass('open');
			$('header.main-header.fixed').css('height','auto');
        } else {
            $(target).addClass('open');
			$('header.main-header.fixed').css('height','100vh');
        }
    });
    $('header #navbar #close').on('click', function () {
        $('header #navbar').removeClass('open');
    });
    
    $('.newsletter-subscribe').on('click', function(e){
        e.preventDefault();
        ajaxCall($(this).attr('href'), {}, 'showNewsletterForm', {target: this});
    });
    
    if($('.footer-flashdata').length) {
        $('.footer-flashdata').each(function(index, item){
            $.confirm({
                title: $(item).data('title'),
                content: item,
                autoClose: 'close|10000',
                useBootstrap: false,
                closeIcon: true,
                type: $(item).hasClass('success') ? 'green' : 'red',
                boxWidth: '500px',
                buttons: {
                    close: {
                        text: $(item).data('close'),
                        action: function () {},
                    }
                }
            });
        });
    };
    
    $(document).on('submit', '.comments-form', function(e){
        e.preventDefault();
        if(!$(this).hasClass('block')) {
            $(this).removeClass('error success');
            $(this).find('.error').removeClass('error');
            $(this).find('.alert').remove();
            $(this).find('.form-result').remove();
            let data = $(this).serializeArray();
            $(this).addClass('sending block');
            ajaxCall($(this).attr('action'), data, 'commentsCallback', {target: this});
        }
    });
    $(document).on('click', '.comments-list .comment-bottom .reply', function(e){
        e.preventDefault();
        ajaxCall($(this).attr('href'), {}, 'commentsCallback', {target: this});
    });
    $(document).on('click', '.comments-list .childs-comments-btn', function(e){
        e.preventDefault();
        let btn = this;
        if($(btn).closest('.comment').find('.comment-childs-box').hasClass('expand')) {
            $(btn).closest('.comment').find('.comment-childs-box').height($(btn).closest('.comment').find('.comment-childs-box .comment-childs-cont').height());
            $(btn).closest('.comment').find('.comment-childs-box').height(0);
            setTimeout(function(){
                $(btn).closest('.comment').find('.comment-childs-box').removeClass('expand').removeAttr('style');
            }, 400);
            $(btn).find('span').text($(btn).data('expand'));
        } else {
            $(btn).closest('.comment').find('.comment-childs-box').height($(btn).closest('.comment').find('.comment-childs-box .comment-childs-cont').height());
            setTimeout(function(){
                $(btn).closest('.comment').find('.comment-childs-box').addClass('expand').removeAttr('style');
            }, 400);
            $(btn).find('span').text($(btn).data('collapse'));
        }
    });
    $(document).on('click', '.comments-list .more-comments', function(e){
        e.preventDefault();
        $(this).prepend('<span class="loader"></span>');
        ajaxCall($(this).attr('href'), {count: $(this).closest('.comments-list').find('.comment.lvl-0').length}, 'commentsCallback', {target: this});
    });
    
    $(document).on('click', '.log-in-ajax', function(e){
        e.preventDefault();
        ajaxCall($(this).attr('href'), {action: 'form'}, 'loginCallback', {target: this});
    });
    $(document).on('submit', '.jconfirm .login-form form', function(e){
        if(!$(this).hasClass('sending')) {
            $(this).addClass('sending');
            $(this).find('.field.error').removeClass('error');
            $(this).find('.alert').remove();
            e.preventDefault();
            let data = $(this).serializeArray();
            ajaxCall($(this).attr('action'), data, 'loginCallback', {target: this});
        }
    });
    
    $('.social-share-buttons a.open').on('click', function(e){
        e.preventDefault();
        window.open($(this).attr('href'), 'sharer', 'toolbar=0,status=0,width=648,height=395');
    });
    $('.social-share-buttons a.copy').on('click', function(e){
        e.preventDefault();
        navigator.clipboard.writeText($(this).attr('href'));
        let span = $('<span></span>').addClass('social-button-info').html('&#10003;');
        $(this).append(span);
        setTimeout(function(){$(span).remove();}, 2000);
    });
	
	var top = 10;
	if($(window).scrollTop()>=top) {
		$('header.main-header').addClass('fixed');
		$('header.main-header-flavor').addClass('fixed');
		$('body').css('padding-top','150px');
	}
    else {
      $('header.main-header').removeClass('fixed');
	  $('header.main-header-flavor').removeClass('fixed');
	  $('body').removeAttr('style');
    }		
	$(window).scroll(function () { 
		var y = $(this).scrollTop();
		if (y >= top) {
		  $('header.main-header').addClass('fixed');
		  $('header.main-header-flavor').addClass('fixed');
		  $('body').css('padding-top','150px');
		} else {
		  $('header.main-header').removeClass('fixed');
		  $('header.main-header-flavor').removeClass('fixed');
	      $('body').removeAttr('style');
		}
	});
	
	
	$(".main-menu .menu a").mouseenter(function() {
	  if(!$(this).parent().hasClass("active")) {	
		$('.main-menu .menu-item.active span.name').css('background','none');
	  }	
	}).mouseleave(function() {
		$('.main-menu .menu-item.active span.name').removeAttr('style');
	});
	
	 $('.main-header .select_lang').on('click', function(e){
	    if($(this).hasClass('open')) {
			$(this).removeClass('open');
		}
        else {
          $(this).addClass('open');
        }  			
	 });
	 
	 $(document).on('click', function (e) {
         
		 
		 if ($(e.target).closest(".main-header .select_lang").length === 0) {
        $(".main-header .select_lang").removeClass('open');
    }
	});
	 
    //setTimeout(checkAds(), 500);
    //setTimeout(checkAds(), 1000);
    //setTimeout(checkAds(), 2000);
    //setTimeout(checkAds(), 8000);
});

function checkAds() {
    
	
	/*
	$('.aa-zone .aa-zone-container').each(function(){
        console.log(this);
        console.log($(this).height());
        if($(this).height() > 25) {
            $(this).removeClass('hidden');
        } else {
            $(this).addClass('hidden');
        }
    });
	*/
	
	
	
}
	
function showNewsletterForm(obj, params) {
    if(obj.html) {
        $.confirm({
            title: $(params.target).data('title'),
            content: obj.html,
            type: 'orange',
            boxWidth: '500px',
			animation: 'none',
			animationBounce:1,
            useBootstrap: false,
            closeIcon: true,
			onContentReady: function () {
				if(params.email) {
	               $('.newsletter-box form.newsletter-form input[name="email"]').val(params.email);
				}
			},
            backgroundDismiss: true,
            buttons: {
                close: {
                    text: $(params.target).data('close'),
					isHidden: true,
                    action: function() {

                    }
                },
            }
        });
    } else {
        if(obj.errors) {
            $.each(obj.errors, function(index, text){
                $(params.target).find('input[name="' + index + '"]').closest('.field').addClass('error');
                $(params.target).find('input[name="' + index + '"]').parent().after('<div class="alert alert-error">' + text + '</div>');
            });
        }
    }
}

function commentsCallback(response, params) {
    switch(response.action) {
        case 'list':
            $('.comments-list').html(response.html);
            break;
        case 'form':
            $.confirm({
                title: response.lang.title,
                content: response.html,
                type: 'orange',
                boxWidth: '800px',
                useBootstrap: false,
                closeIcon: true,
                backgroundDismiss: false,
                buttons: {
                    close: {
                        text: response.lang.close,
                        action: function() {

                        }
                    },
                }
            });
            break;
        case 'more':
            $(params.target).parent().before(response.html);
            $(params.target).find('.loader').remove();
            if($(params.target).closest('.comments-list').find('.comment.lvl-0').length >= response.count) {
                $(params.target).closest('.comments-list').find('.more-comments-box').remove();
            }
            break;
        default:
            $(params.target).removeClass('sending');
            setTimeout(function(){$(params.target).removeClass('block');}, 600);
            if(response.result) {
                $(params.target).addClass('success');
                $(params.target).find("input[type=text]:not([readonly]), textarea, select").val('');
                $(params.target).find("input[type=checkbox]").prop(false);
                ajaxCall($(params.target).attr('action') + '/list', {}, 'commentsCallback', {target: this});
            } else {
                $(params.target).addClass('error');
            }
            if(response.msg) {
                let div = $('<div></div>').addClass('field');
                div.append($('<div></div>').addClass('result alert alert-' + (response.result ? 'success' : 'error')).text(response.msg));
                $(params.target).prepend(div);
            }
            if(response.errors) {
                $.each(response.errors, function(label, error){
                    $(params.target).find('input[name="' + label + '"],textarea[name="' + label + '"]').closest('.field').addClass('error');
                    $(params.target).find('input[name="' + label + '"],textarea[name="' + label + '"]').closest('.field').append($('<div></div>').addClass('alert alert-error').text(error));
                })
            }
            break;
    }
}
	
function loginCallback(obj, params) {
    switch(obj.action) {
        case 'form':
            $.confirm({
                title: obj.lang.title,
                content: obj.html,
                type: 'orange',
                boxWidth: '700px',
                useBootstrap: false,
                closeIcon: true,
                backgroundDismiss: false,
                buttons: {
                    close: {
                        text: obj.lang.close,
                        action: function() {
                            
                        }
                    },
                }
            });
            break;
        default:
            $(params.target).removeClass('sending');
            if(obj.result) {
                location.reload();
            } else {
                if(obj.errors) {
                    $.each(obj.errors, function(index, value){
                        if(index == 'result') {
                            $(params.target).prepend($('<div></div>').addClass('result alert alert-error').text(value));
                        } else {
                            $(params.target).find('input[name="' + index + '"]').closest('.field').addClass('error');
                            $(params.target).find('input[name="' + index + '"]').parent().after($('<div></div>').addClass('alert alert-error').text(value));
                        }
                    })
                }
            }
            break;
    }
}

function updateViews() {
    setTimeout(function(){
        ajaxCall(window.location, {action: 'views'}, 'commentsCallback', {target: this});
    }, 2000);
}

function CountNews(id) {
	ajaxCall('/count-news', {action:'count',id:id}, '', {});
}	