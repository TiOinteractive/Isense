$(document).ready(function(){
      if($('#ajax_params_value').length) {
         refresh_ajax_params_value('#ajax_params_value');
	  }	 
      if($('.add-cat-parameter').length) {
           $('.add-cat-parameter').on('click', function (e) {
              e.preventDefault();
			  var selected=$('.cat-param-list input').serialize();
		      ajaxCall($(this).attr('href'), {selected:selected}, 'addCatParameter',{ok:$(this).data('ok'),cancel:$(this).data('cancel')});
		   });
      } 
		$(".order-sortable.cat-param-list .order-list").sortable({
			placeholder: "ui-state-highlight",
			helper: 'clone'
		});
		
		$('.remove-param').on('click', function (e) {
              e.preventDefault();
			  $(this).parent().parent().remove();
		});

		$(".order-sortable .order-list").sortable({
			placeholder: "ui-state-highlight",
			helper: 'clone',
			stop: function (event, ui) {
			  $('.order-group-inside .list-row').addClass('full');
			  $('.order-group-inside .order-group:last-child .list-row:last-child').removeClass('full');
			  formData = $('#order-form').serialize();
			  if(formData) {
				ajaxCall($('#order-form').attr('action'), {data:formData}, 'saveOrder');
			  }
			}			
		});

		$(".order-sortable .order-list .order-group-inside").sortable({
			placeholder: "ui-state-highlight",
			helper: 'clone',
			stop: function (event, ui) {
			  $('.order-group-inside .list-row').addClass('full');
			  $('.order-group-inside .order-group:last-child .list-row:last-child').removeClass('full');
			  formData = $('#order-form').serialize();
			  ajaxCall($('#order-form').attr('action'), {data:formData}, 'saveOrder');
			  
			}			
		});
		
		$('.add-additional-categories').on('click', function(e){
        e.preventDefault();
        let file_btn = this;
		let new_id = makeId(15);
        let div = $('<div></div>').addClass('jc-additional-categories');
		var cats=$('.additional-categories .additional-list input').serializeArray();
        $.confirm({
            title: $(file_btn).data('title'),
            content: div,
            boxWidth: '80%',
            backgroundDismiss: true,
            useBootstrap: false,
            buttons: {
                Ok: {
                    text: $(file_btn).data('btn-ok'),
                    btnClass: 'btn-success',
                    action: function (e) {
                        let data = $(div).find('form.form').serializeArray();
                        ajaxCall($(div).find('form.form').attr('action'), data, 'rSaveAdditionalCategories', {title:$(file_btn).data('title'), btn_close:$(file_btn).data('btn-close')});
                    }
                },
                cancel: {
                    text: $(file_btn).data('btn-cancel')
                }
            }
        });
        ajaxCall($(file_btn).attr('href'),cats, 'showAdditionalCategories', {target: div});
    });
	
	$(document).on('click', '.list .comment_show', function () {
		if($(this).find('.all').hasClass('hide')) {
			$(this).find('.truncate').addClass('hide');
			$(this).find('.all').removeClass('hide');	
		}
        else {
          $(this).find('.all').addClass('hide');
		  $(this).find('.truncate').removeClass('hide');

        } 			
	});	
	$('.add-restaurant-cuisine').on('click', function(e){
        e.preventDefault();
        let file_btn = this;
		let new_id = makeId(15);
        let div = $('<div></div>').addClass('jc-additional-categories');
		var cats=$('.cuisine-type .additional-list input').serializeArray();
        $.confirm({
            title: $(file_btn).data('title'),
            content: div,
            boxWidth: '80%',
            backgroundDismiss: true,
            useBootstrap: false,
            buttons: {
                Ok: {
                    text: $(file_btn).data('btn-ok'),
                    btnClass: 'btn-success',
                    action: function (e) {
                        let data = $(div).find('form.form').serializeArray();
                        ajaxCall($(div).find('form.form').attr('action'), data, 'rSaveCuisine', {title:$(file_btn).data('title'), btn_close:$(file_btn).data('btn-close')});
                    }
                },
                cancel: {
                    text: $(file_btn).data('btn-cancel')
                }
            }
        });
        ajaxCall($(file_btn).attr('href'),cats, 'showAdditionalCategories', {target: div});
    });
	
	
	$('.edit-comment-btn').on('click', function(e){
        e.preventDefault();
        let file_btn = this;
		let new_id = makeId(15);
        let div = $('<div></div>').addClass('jc-comment-edit');
        $.confirm({
            title: $(file_btn).data('title'),
            content: div,
            boxWidth: '80%',
            backgroundDismiss: true,
            useBootstrap: false,
            buttons: {
                Ok: {
                    text: $(file_btn).data('btn-ok'),
                    btnClass: 'btn-success',
                    action: function (e) {
                        let data = $(div).find('form.form').serializeArray();
                        ajaxCall($(div).find('form.form').attr('action'), data, 'SaveComment', {title:$(file_btn).data('title'), btn_close:$(file_btn).data('btn-close')});
                    }
                },
                cancel: {
                    text: $(file_btn).data('btn-cancel')
                }
            }
        });
        ajaxCall($(file_btn).attr('href'),{}, 'showAdditionalCategories', {target: div});
    });
	
	$('.restaurant-form .add-restaurant-news').on('click', function(e){
        e.preventDefault();
        let file_btn = this;
		let new_id = makeId(15);
		var selected=$('.restaurant-form .additional-news .news-box input').serializeArray();
        let div = $('<div></div>').addClass('jc-flavornews');
         $.confirm({
            title: $(file_btn).data('title'),
            content: div,
            boxWidth: '80%',
            backgroundDismiss: true,
            useBootstrap: false,
            buttons: {
                Ok: {
                    text: $(file_btn).data('btn-ok'),
                    btnClass: 'btn-success',
                    action: function (e) {
                        let data = $(div).find('form.filters-results').serializeArray();
                        ajaxCall($(div).find('form.filters-results').attr('action'), data, 'flavorAddNews', {title:$(file_btn).data('title'), btn_close:$(file_btn).data('btn-close')});
                    }
                },
                cancel: {
                    text: $(file_btn).data('btn-cancel')
                }
            }
        });
        ajaxCall($(file_btn).attr('href'), selected, 'showRelatedNewsModal', {target: div});
    });		
	
	
		
		
    });
	
	function flavorAddNews(obj, params) {
    if(obj.status) {
        $('.restaurant-form .additional-news .news-box').append(obj.html);
    }
    $.confirm({
        title: params.title,
        content: obj.msg,
        backgroundDismiss: true,
        useBootstrap: false,
        type: obj.result ? 'green' : 'red',
        buttons: {
            Ok: {
                text: params.btn_close,
            },
        }
    });
}

function removeRelatedNews(id) {
	var title=$('.additional-news .list-row-'+id+' .list-remove-btn-news').data('title');
	var msg=$('.additional-news .list-row-'+id+' .list-remove-btn-news').data('message');
	var btn_ok=$('.additional-news .list-row-'+id+' .list-remove-btn-news').data('btn-ok');
	var btn_cancel=$('.additional-news .list-row-'+id+' .list-remove-btn-news').data('btn-cancel');
    $.confirm({
        title: title,
        content: msg,
        backgroundDismiss: true,
        useBootstrap: false,
		buttons: {
			Ok: {
                    text: btn_ok,
                    btnClass: 'btn-success',
                    action: function (e) {
                        $('.additional-news .list-row-'+id).remove(); 
                    }
                },
                cancel: {
                    text: btn_cancel
                }
		}	
	});
}	
	
	
function showRelatedNewsModal(obj, params) {
    $(params.target).html(obj.html);
    $(params.target).find('form.filters').on('submit', function(e){
        e.preventDefault();
        let data = $(this).serializeArray();
        ajaxCall($(this).attr('action'), data, 'showRelatedNews', params);
    });
}	

function showRelatedNews(obj, params) {
    $(params.target).find('form.filters-results').html(obj.html);
}
	


function showAdditionalCategories(obj, params) {	
	 $(params.target).html(obj.html);
}	

function rSaveAdditionalCategories(obj,params) {
       $('.additional-categories .additional-list').html(obj.html);
}

function rSaveCuisine(obj,params) {
       $('.cuisine-type .additional-list').html(obj.html);
}


function SaveComment(obj,params) {
	$('.list .comment_'+obj.id+' .truncate').html(obj.truncate);
	$('.list .comment_'+obj.id+' .all').html(obj.all);
	let span = $('<span></span>').addClass('list-col-result').text(obj.msg);
        $('.list .comment_'+obj.id+' .comment_show').append(span);
        setTimeout(function () {
            $(span).remove();
        }, 3000);
}	


function addCatParameter(obj, params) {
       $.confirm({
        title:params.ok,
        content:obj.msg,
		closeIcon: true,	 
        buttons: {
            save: {
                text: params.ok,
                btnClass: 'btn-green',
                action: function(){
                   saveCatParameter();
                }
            },
            cancelAction: {
                    text:params.cancel
            }
        }
    });	
}

function saveCatParameter() {
	
	var ajax_url=$('#modal-value').attr('action');
	$.ajax({
        type: "POST",
        url: ajax_url,
        data: $('#modal-value').serialize(),
        success: function(data)
        {
		  $('.cat-param-list').append(data);
        }
    });	
}	

function refresh_ajax_params_value(id) {
	var ajax_url=$(id).attr('data-action');
	$.ajax({
            url: ajax_url,
            type: "GET",
            dataType: "html",
        }).done(function (data) {
			$(id).html(data);	
});	
}	

function save_ajax_params_value() {
	var ajax_url=$('#modal-value').attr('action');
	$.ajax({
        type: "POST",
        url: ajax_url,
        data: $('#modal-value').serialize(),
        success: function(data)
        {
		   $('#modal_response').remove();	   
		   if(data.length==2) {
			   $('.jconfirm-closeIcon').click();
		   }
           else { 		    
			$('<div id="modal_response"></div>').insertBefore('#modal-value');
		    $('#modal_response').html(data);
		   }
		   refresh_ajax_params_value('#ajax_params_value');
        }
    });
}	


function ParameterAddValue(url,title,txt_save,txt_cancel) {
	$.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			}).done(function (data) {
    $.confirm({
        title:title,
        content:data,
		closeIcon: true,	 
        buttons: {
            save: {
                text: txt_save,
                btnClass: 'btn-green',
                action: function(){
					save_ajax_params_value();
					refresh_ajax_params_value('#ajax_params_value');
					return false;
                }
            },
            cancelAction: {
                    text: txt_cancel
            }
        }
    });	
	});	
}	


function saveOrder(obj) {
 let div = $('<div></div>').addClass('list-row-result').addClass(obj.result ? 'success' : 'error');
        div.append($('<span></span>').text(obj.msg));
        $("#order-form .list").append(div);
        setTimeout(function () {
            div.remove();
        }, 3000);
}	

function selectRestaurantParameter(el,id_parameter) {
	var value=$('option:selected',el).text();
	if($(el).val()!=0) {
		$(el).parent().parent().find('input').val(value);
		$(el).parent().parent().find('input').attr('readonly',true);
	}
    else {
       $(el).parent().parent().find('input').attr('readonly',false);
    }		
}	

function addRestaurantParameter(el,id_parameter) {
	var row=$('.restaurant_parameters .param_'+id_parameter).first().html();
	$('<div class="flex param_'+id_parameter+'">'+row+'</div>').insertAfter($(el).parent().parent().parent());
	$('.restaurant_parameters .param_'+id_parameter+':last-child input').val('');
	$('.restaurant_parameters .param_'+id_parameter+':last-child input').attr('readonly',false);
	$('.restaurant_parameters .param_'+id_parameter+':last-child select').prop('selected',false);
	$('.restaurant_parameters .param_'+id_parameter+':last-child select option[value="0"]').prop('selected',true);
}	

function removeRestaurantParameter(el,id_parameter) {
	var count=$('.restaurant_parameters .param_'+id_parameter).length;
	if(count>1) {
		$(el).parent().parent().parent().remove();
	}
    else {
        $('.restaurant_parameters .param_'+id_parameter+' input').attr('readonly',false);
		$('.restaurant_parameters .param_'+id_parameter+' select option[value="0"]').prop('selected',true);
    }		
}	