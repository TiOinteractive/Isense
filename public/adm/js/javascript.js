$(function () {
    /* Left menu toogle start */
        $('.left-menu-toogle').on('click', function(){
		var waznosc = new Date();
		var time = waznosc.getTime();
		var expireTime = time + 5184000;
		waznosc.setTime(expireTime);
        if($('.main-content').hasClass('toogle-left')) {
            $('.main-content').removeClass('toogle-left');
            $(this).removeClass('toogle');
			document.cookie = "LeftMenuM=show; expires=" + waznosc.toGMTString() + ";domain=." + window.location.host + ";path=/";
        } else {
            $('.main-content').addClass('toogle-left');
            $(this).addClass('toogle');
			document.cookie = "LeftMenuM=hide; expires=" + waznosc.toGMTString() + ";domain=." + window.location.host + ";path=/";
        }
    });
    /* Left menu toogle end */
    
	/* Order start */
    initializeSortable();
    /* Order end */
	
    /* Order start */
    $(".order-box").sortable({
        placeholder: "order-item state-highlight",
        stop: function (event, ui) {
            fixOrder(this);
        }
    });
    /* Order end */

    /* Group start */
    $(document).on('click', '.form-group .form-group-head .expand', function () {
        if ($(this).parents('.form-group').hasClass('minimized')) {
            $(this).parents('.form-group').removeClass('minimized');
        } else {
            $(this).parents('.form-group').addClass('minimized');
        }
    });
    /* Group end */

    /* Tabs start */
    $(document).on('click', '.tabs .tabs-head div.tab', function () {
        $(this).parents('.tabs-head').find('.tab.active').removeClass('active');
        $(this).parents('.tabs').find('.tabs-content .tab-item.active').removeClass('active');
        $(this).addClass('active');
        $(this).parents('.tabs').find('.tabs-content .tab-item').eq($(this).index()).addClass('active');
    });
    /* Tabs end */

    /* Radio select start */
        $(document).on('change', '.radio-select-box .option input', function(){
           let active_item = $(this).val();
           $(this).parent().siblings('.option.active').removeClass('active');
           $(this).parent().addClass('active');
           $(this).parents('.radio-select-box').find('.radio-select-item.active').removeClass('active');
           $(this).parents('.radio-select-box').find('.radio-select-item.' + active_item).addClass('active');
        });
    /* Radio select end */
    
    /* List start */
    $('.list .list-row .list-remove-btn').on('click', function (e) {
        e.preventDefault();
        let btn = this;
        $.confirm({
            title: $(btn).data('title'),
            content: $(btn).data('message'),
            type: 'red',
            autoClose: 'cancel|10000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(btn).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        ajaxCall($(btn).attr('href'), {}, 'listRowRemove', {target: $(btn).parents('.list-row')});
                    }
                },
                cancel: {
                    text: $(btn).data('btn-cancel')
                }
            }
        });
    });
    $('.list .list-row .list-block-btn').on('click', function (e) {
        e.preventDefault();
        let btn = this;
        $.confirm({
            title: $(btn).data('title'),
            content: $(btn).data('message'),
            type: 'red',
            autoClose: 'cancel|10000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(btn).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        ajaxCall($(btn).attr('href'), {}, 'listRowBlock', {target: $(btn).parents('.list-row')});
                    }
                },
                cancel: {
                    text: $(btn).data('btn-cancel')
                }
            }
        });
    });
    $('.list .list-row .list-preview-btn').on('click', function (e) {
        e.preventDefault();
        ajaxCall($(this).attr('href'), {}, 'listRowPreview', {target: $(this)});
    });
    $('.list .list-row .list-publish-btn').on('click', function (e) {
        e.preventDefault();
        ajaxCall($(this).attr('href'), {}, 'listRowPublish', {target: $(this).parents('.list-col')});
    });
    $('.list .list-row .list-home-btn').on('click', function (e) {
        e.preventDefault();
        ajaxCall($(this).attr('href'), {}, 'listRowHome', {target: $(this).parents('.list-col')});
    });
	$('.list .list-row .ajax_select').on('change', function (e) {
        e.preventDefault();
        ajaxCall($(this).data('url'), {value:$(this).val()}, 'listRowHome', {target: $(this).parents('.list-col')});
    });
	
	
	
    $('.list .list-row .list-newsletter-btn').on('click', function (e) {
        e.preventDefault();
        ajaxCall($(this).attr('href'), {}, 'listRowNewsletter', {target: $(this).parents('.list-col')});
    });
    $('.newsletter-options .clear').on('click', function (e) {
        e.preventDefault();
        ajaxCall($(this).attr('href'), {}, 'listRowNewsletterClear', {target: $(this).parents('.list-col')});
    });
    $('.list .list-row .list-chart-btn').on('click', function (e) {
        e.preventDefault();
        ajaxCall($(this).attr('href'), {}, 'listChart', {target: $(this).parents('.list-col')});
    });
    $('.order-select').on('change', function () {
        let sort = $(this).val();
        $('.order-select').val(sort);
        $('form.filters input[name="order"]').val(sort);
        if ($('form.filters').length) {
            $('form.filters').submit();
        } else {

        }
    });
	
	$('.on-page-select').on('change', function () {
        let no = $(this).val();
        $('.on-page-select').val(no);
        $('form.filters input[name="on_page"]').val(no);
        if ($('form.filters').length) {
            $('form.filters').submit();
        }
    });
	
	
    $(".list-order-box").sortable({
        placeholder: "list-row state-highlight",
        stop: function (event, ui) {
            let direction = 'asc';
            if ($('.order-select').length) {
                let tmp = $('.order-select').val()
                direction = tmp.substring(tmp.indexOf(';') + 1)
            }
            let old_pos = parseInt($(ui.item).find('.list-col.order').text());
            let new_pos = 1;
            if (direction == 'desc' && $(ui.item).next().length) {
                new_pos = parseInt($(ui.item).next().find('.list-col.order').text());
            }
            if ($(ui.item).prev().length) {
                new_pos = parseInt($(ui.item).prev().find('.list-col.order').text());
                if (direction == 'desc') {
                    --new_pos;
                }
                if (old_pos > new_pos) {
                    ++new_pos;
                }
            }

            let data = {
                old_pos: old_pos,
                new_pos: new_pos
            }
            ajaxCall($(this).data('url'), data, 'fixListOrder', {target: ui.item, direction: direction});
        }
    });
    $('form.filters button.ajax').on('click', function(){
        
    });
    /* List end */
	
	
	$("#order-place-form").sortable({
			placeholder: "ui-state-highlight",
			helper: 'clone',
			stop: function (event, ui) {
			   formData = $('#order-place-form').serialize();
			   ajaxCall($('#order-place-form').attr('action'), {data:formData}, 'savePlaceOrder');
			}			
		});
		
	
	
	function savePlaceOrder(obj) {
 let div = $('<div></div>').addClass('list-row-result').addClass(obj.result ? 'success' : 'error');
        div.append($('<span></span>').text(obj.msg));
        $("#order-form").append(div);
        setTimeout(function () {
            div.remove();
        }, 3000);
}	
	
	
	
	
	$('.list .list-row .list-col[data-order]').on('click', function(){
        let field = $(this).data('order');
        let direction = '';
        if($(this).hasClass('asc')) {
            $(this).removeClass('asc').addClass('desc');
            direction = 'desc';
        } else if($(this).hasClass('desc')) {
            $(this).removeClass('desc');
        } else {
            $(this).addClass('asc');
            direction = 'asc';
        }
        $('form.filters input[name="order"]').val(direction ? field + ',' + direction : '');
        $('form.filters').submit();
    });
    

    /* Links start */
    $('.link-box .link-name').on('change', function () {
        let synchronize = $(this).closest('form').find('.link-synchronize').length ? $(this).closest('form').find('.link-synchronize').prop('checked') : true;
        let redirect = $(this).closest('form').find('.link-redirect').length ? $(this).closest('form').find('.link-redirect').prop('checked') : false;
        if(synchronize && !redirect) {
            let name = $(this).val();
            let id_link = $(this).closest('.link-box').find('.link-id').val();
            let id_lang = $(this).closest('.link-box').find('.link-id-lang').val();
            let direct_link = $(this).closest('.link-box').find('.link-direct-links').length ? $(this).parents('.link-box').find('.link-direct-links').val() : '';
            let id_page = $(this).closest('form').find('.link-page-id').val();
            let module = $(this).closest('form').find('.link-module').val();
            $(this).closest('.link-box').find('.link-field').siblings('span.warning').remove();
            ajaxCall($('#admin-panel-slug').val() + '/links/check' + (id_link ? '/' + id_link : ''), {name: name, id_lang: id_lang, id_page: id_page, module: module, direct_link: direct_link}, 'changeLink', {target: $(this).closest('.link-box')});
        }
    });
    $('.link-box .link-field:not(:disabled)').on('change', function () {
        let redirect = $(this).closest('form').find('.link-redirect').length ? $(this).closest('form').find('.link-redirect').prop('checked') : false;
        if(!redirect) {
            let link = $(this).val();
            let id_link = $(this).closest('.link-box').find('.link-id').val();
            let id_lang = $(this).closest('.link-box').find('.link-id-lang').val();
            let direct_link = $(this).closest('.link-box').find('.link-direct-links').length ? $(this).parents('.link-box').find('.link-direct-links').val() : '';
            let id_page = $(this).closest('form').find('.link-page-id').val();
            let module = $(this).closest('form').find('.link-module').val();
            $(this).closest('.link-box').find('.link-field').siblings('span.warning').remove();
            ajaxCall($('#admin-panel-slug').val() + '/links/check' + (id_link ? '/' + id_link : ''), {id_lang: id_lang, id_page: id_page, module: module, direct_link: direct_link, link: link}, 'changeLink', {target: $(this).closest('.link-box')});
        }
    });
    $('.link-box .link-redirect').on('change', function () {
        let redirect = $(this).prop('checked');
        let synchronize = $(this).closest('form').find('.link-synchronize').prop('checked');
        if(redirect) {
            $(this).closest('.link-box').find('.link-synchronize').attr('disabled', 'disabled');
            $(this).closest('.link-box').find('.link-field').removeAttr('readonly');
            if($(this).data('link')) {
                $(this).closest('.link-box').find('.link-field').val($(this).data('link'));
            }
        } else {
            $(this).closest('.link-box').find('.link-synchronize').removeAttr('disabled');
            if(synchronize) {
                $(this).closest('.link-box').find('.link-name').change();
                $(this).closest('.link-box').find('.link-field').attr('readonly', 'readonly');
            } else {
                $(this).closest('.link-box').find('.link-field').removeAttr('readonly');
            }
        }
    });
    $('.link-box .link-synchronize').on('change', function () {
        let synchronize = $(this).prop('checked');
        if(synchronize) {
            $(this).closest('.link-box').find('.link-field').attr('readonly', 'readonly');
            $(this).closest('.link-box').find('.link-name').change();
        } else {
            $(this).closest('.link-box').find('.link-field').removeAttr('readonly');
            if($(this).data('link')) {
                $(this).closest('.link-box').find('.link-field').val($(this).data('link'));
            }
        }
    });
    $('.link-page-id').on('change', function () {
        let lang_links = [];
        let id_page = $(this).val();
        $('.link-box').each(function () {
            let name = $(this).find('.link-name').val();
            let id_link = $(this).find('.link-id').val();
            let id_lang = $(this).find('.link-id-lang').val();
            let direct_link = $(this).find('.link-direct-links').length ? $(this).find('.link-direct-links').val() : '';
            lang_links.push({name: name, id_lang: id_lang, id_link: id_link, direct_link: direct_link});
        });
        let module = $(this).parents('form').find('.link-module').val();
        $('.link-box .link-field').siblings('span.warning').remove();
        ajaxCall($('#admin-panel-slug').val() + '/links/check-all', {id_page: id_page, lang_links: lang_links, module: module}, 'changeAllLinks');
    });
    /* Links end */

    /* File menager start */
    $(document).on('click', 'a.file-menager', function (e) {
        e.preventDefault();
        let file_btn = this;
        let new_id = makeId(15);
        let div = $('<div></div>').addClass('jc-file-menager-content');
        let multi = typeof $(file_btn).data('multi') == 'undefined' || $(file_btn).data('multi') == false || $(file_btn).data('multi') == 'false' ? 0 : $(file_btn).data('multi');
        let key_name = typeof $(file_btn).data('key') == 'undefined' ? 'file' : $(file_btn).data('key');
        let type = typeof $(file_btn).data('type') == 'undefined' ? '' : $(file_btn).data('type');
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
                        let data = {
                            files: [],
                            name: $(file_btn).data('field-name'),
                            key_name: key_name
                        };
                        $(this.$body).find('form.form-list .files-list input.file-input').each(function () {
                            if ($(this).prop('checked')) {
                                data.files.push($(this).val());
                            }
                        });
                        ajaxCall($(this.$body).find('form.form-list').attr('action'), data, 'addFile', {target: file_btn, multi: multi});
                    }
                },
                cancel: {
                    text: $(file_btn).data('btn-cancel')
                }
            }
        });
        ajaxCall($(file_btn).attr('href'), {type: type, multi: multi}, 'addFiles', {target: div});
    });

    $(document).on('click', '.files-list .remove-file', function (e) {
        e.preventDefault();
        let file = this;
        $.confirm({
            title: $(file).data('title'),
            content: $(file).data('message'),
            type: 'red',
            autoClose: 'cancelAction|8000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(file).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        $(file).parents('.file').parents('.file-box').remove();
                    }
                },
                cancelAction: {
                    text: $(file).data('btn-cancel')
                }
            }
        });
    });
    /* File menager end */

    /* Alert start */
    $(document).on('click', '.alert-box .close', function () {
        $(this).parents('.alert-box').remove();
    });
    /* Alert end */

    /* Wyswig editor start */
    initializeWyswig();
    /* Wyswig editor end */

    /* Date Range Picker start */
    initializeDataPicker();
    /* Date Range Picker end */
    
    
    /* Page content start */
    $('.edit-element-handler').on('change', function(e){
        e.preventDefault();
        let name = $(this).find('option:selected').text();
        let link = $(this).find('option:selected').data('link');
        if(typeof link == 'undefined') {
            link = '';
        }
        $(this).siblings('.edit-element-link').attr('href', link).find('strong').text(name);
    });
    $('.edit-sidebar-handler').on('change', function(e){
        e.preventDefault();
        let name = $(this).find('option:selected').text();
        let link = $(this).find('option:selected').data('link');
        if(typeof link == 'undefined') {
            link = '';
        }
        $(this).siblings('.edit-sidebar-link').attr('href', link).find('strong').text(name);
    });
    $('.page-content-name').on('change', function(){
        ajaxCall($(this).data('url'), {name: $(this).val()}, 'pageContentName', {target: this});
    });
    $('.form .expand').on('click', function(){
        if($(this).hasClass('rotate')) {
            $(this).removeClass('rotate');
            $(this).closest('.form').find('.expand-content').removeClass('hidden');
        } else {
            $(this).addClass('rotate');
            $(this).closest('.form').find('.expand-content').addClass('hidden');
        }
    });
    
    /* Page content end */
	
    /* Sidebar content start */
    $('.sidebar-content-name').on('change', function(){
        ajaxCall($(this).data('url'), {name: $(this).val()}, 'sidebarContentName', {target: this});
    });
    /* Sidebar content end */
    
    
	
	

    $('.add-bigbox').on('click', function(e){
        e.preventDefault();
        let file_btn = this;
        let new_id = makeId(15);
        let div = $('<div></div>').addClass('jc-related-products-content');
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
                        ajaxCall($(div).find('form.filters-results').attr('action'), data, 'BigBoxAddNews', {title:$(file_btn).data('title'), btn_close:$(file_btn).data('btn-close'),box:$(file_btn).attr('id')});
                    }
                },
                cancel: {
                    text: $(file_btn).data('btn-cancel')
                }
            }
        });
        ajaxCall($(file_btn).attr('href'), {}, 'showBigBoxModal', {target: div});
    });
	if($('.tags_input').length) {
	
	   $('.tags_input').each(function() {
		   if($(this).data('url')) {
			$(this).tagsInput({
				'autocomplete_url': $(this).data('url'),
				'width':'100%',
				'height':'auto',
				'minChars':3,
				'defaultText':'',
				'placeholderColor' : '#000000'
			});
		   }
		   else {
			   $(this).tagsInput({
				'width':'100%',
				'height':'auto',
				'defaultText':'',
				'placeholderColor' : '#000000'
			});
		   }	   
	
	   });
	
	}
	
		/* Crop image start */
	
	
	$(document).on('click', 'a.file-crop', function (e) {
        e.preventDefault();
        let file_btn = this;
        let new_id = makeId(25);
        let div = $('<div></div>').addClass('jc-crop-content');
        let title =$(file_btn).data('title');
		let file_path =$(file_btn).data('file-path');
		let file_id =$(file_btn).data('file-id');
		let btn_ok =$(file_btn).data('btn-ok');
		let btn_cancel =$(file_btn).data('btn-cancel');
		let btn_choose =$(file_btn).data('choose');
		let label_width =$(file_btn).data('width');
		let label_height =$(file_btn).data('height');
		let field_name =$(file_btn).data('field');
        $.confirm({
            title: title,
            content: div,
            boxWidth: '80%',
            backgroundDismiss: false,
            useBootstrap: false,
            buttons: {
                Ok: {
                    text: btn_ok,
                    btnClass: 'btn-success',
                    action: function (e) {
                  
					   let data = {
                            file_path: file_path,
                            file_id: file_id,
							field_name:field_name,
							width:$('#crop-modal #output_width').val(),
							height:$('#crop-modal #output_height').val(),
							x:$('#crop-modal #output_x').val(),
							y:$('#crop-modal #output_y').val()
                        };
                       
                        ajaxCall($(this.$body).find('#crop-modal form').attr('action'), data, 'saveCrop', {target: file_btn});
					
                    }
                },
                cancel: {
                    text: btn_cancel
                }
            }
        });
        ajaxCall($(file_btn).attr('href'), {title:title,file_path:file_path,file_id:file_id,btn_choose:btn_choose,label_width:label_width,label_height:label_height}, 'cropFile', {target: div});
    });
	
	 $(document).on('click', '.files-list .remove-crop-file', function (e) {
        e.preventDefault();
        let file = this;
        $.confirm({
            title: $(file).data('title'),
            content: $(file).data('message'),
            type: 'red',
            autoClose: 'cancelAction|8000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(file).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        $(file).parents('.crop_save').remove();
                    }
                },
                cancelAction: {
                    text: $(file).data('btn-cancel')
                }
            }
        });
    });
	/* Crop image end */	
	
	
		$('.edit-tag-btn').on('click', function(e){
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
                        ajaxCall($(div).find('form.form').attr('action'), data, 'SaveTag', {title:$(file_btn).data('title'), btn_close:$(file_btn).data('btn-close')});
                    }
                },
                cancel: {
                    text: $(file_btn).data('btn-cancel')
                }
            }
        });
        ajaxCall($(file_btn).attr('href'),{}, 'cropFile', {target: div});
    });

    /* Scroll list search start */
    $(document).on('input', '.scroll-list-search', function () {
        let phrase = $(this).val().trim().toLowerCase();
        let list = $(this).closest('.form-field').find('.scroll-list');
        list.find('.form-col').each(function () {
            let name = $(this).find('label').text().trim().toLowerCase();
            $(this).toggle(phrase === '' || name.indexOf(phrase) !== -1);
        });
    });
    /* Scroll list search end */
})


function SaveTag(obj,params) {
	$('.list .row-tag'+obj.id+' .tag').html(obj.all);
	let span = $('<span></span>').addClass('list-col-result').text(obj.msg);
        $('.list .row-tag'+obj.id+' .tag').append(span);
        setTimeout(function () {
            $(span).remove();
        }, 3000);
}	




function cropFile(obj, params) {
    $(params.target).html(obj.html);
}

function saveCrop(obj,params) {
    $(params.target).siblings('.list').html(obj.html);
}	


function showBigBoxModal(obj, params) {
		$(params.target).html(obj.html);
		 initializeDataPicker();
		$(params.target).find('form.filters').on('submit', function(e){
			e.preventDefault();
			let data = $(this).serializeArray();
			ajaxCall($(this).attr('action'), data, 'showBigBox', params);
		});
}	
	
function showBigBox(obj, params) {
    $(params.target).find('form.filters-results').html(obj.html);
}

function BigBoxAddNews(obj, params) {
    if(obj.status) {
        $('.'+params.box).html(obj.html);
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


function ajaxCall(url, data, callback, callback_params, method) {
    $.ajax({
        dataType: 'json',
        method: method ? method : 'POST',
        url: url,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data: data,
    })
            .done(function (response) {
                if (response.html) {
                    response.html = decodeURIComponent(atob(response.html)).toString().replace(/\+/g, ' ');
                }
                window[callback](response, callback_params);
            })
            .fail(function (e, r, t) {
                console.log("error");
                console.log(e);
                console.log(r);
                console.log(t);
            })
            .always(function () {
            });
}

function fixOrder(el) {
    $(el).find('.order-item').each(function (no, item) {
        $(this).find('.order-field').val(no);
    });
}

function fixListOrder(obj, params) {
    if (obj.status) {
        let div = $('<div></div>').addClass('list-row-result').addClass(obj.result ? 'success' : 'error');
        div.append($('<span></span>').text(obj.msg));
        $(params.target).parent().append(div);
        setTimeout(function () {
            div.remove();
        }, 3000);
        if (obj.result) {
            $(params.target).parent().find('.list-row').each(function () {
                let pos = parseInt($(this).find('.list-col.order').text());
                if (params.direction == 'desc') {
                    if (obj.old_pos < obj.new_pos && pos >= obj.new_pos && pos < obj.old_pos) {
                        $(this).find('.list-col.order').text(pos + 1);
                    } else if (obj.old_pos > obj.new_pos && pos > obj.old_pos && pos <= obj.new_pos) {
                        $(this).find('.list-col.order').text(pos - 1);
                    }
                } else {
                    if (obj.old_pos > obj.new_pos && pos >= obj.new_pos && pos < obj.old_pos) {
                        $(this).find('.list-col.order').text(pos + 1);
                    } else if (obj.old_pos < obj.new_pos && pos > obj.old_pos && pos <= obj.new_pos) {
                        $(this).find('.list-col.order').text(pos - 1);
                    }
                }
            });
            $(params.target).find('.list-col.order').text(obj.new_pos);
        }
    }
}

function initializeSortable() {
    $(".order-box").sortable({
        placeholder: "order-item state-highlight",
        stop: function (event, ui) {
            fixOrder(this);
        }
    });
}


function AjaxSaveForm(el) {
    formData = $(el).serialize();
    url = $("#FormAdmin").attr('action');
    $.ajax({
        dataType: 'json',
        method: "POST",
        url: url,
        data: formData,
        success: function (returnData) {
            if (returnData.response && returnData.response.redirect) {
                window.location = returnData.response.redirect;
            } else if (returnData.response && returnData.response.error) {
                $('.ajax-cont').replaceWith(returnData.response.error);
            } else {
                $('.ajax-cont').replaceWith(returnData.form);
            }
        },
        error: function (q,w,e) {
            console.log(q);
            console.log(w);
            console.log(e);
        }
        
    });
}

function AjaxCheckbox(url) {
    $.ajax({
        dataType: 'json',
        method: "POST",
        url: url,
        success: function (returnData) {
            if (returnData.response == true) {
                $('.ajax-cont').replaceWith(returnData.form);
            }
        }
    });
}

function confirm_delete(id, url, title, message, cancel) {
    $.confirm({
        title: title,
        content: message,
        type: 'red',
        autoClose: 'cancelAction|8000',
        boxWidth: '500px',
        useBootstrap: false,
        buttons: {
            deleteUser: {
                text: title,
                btnClass: 'btn-red',
                action: function () {
                    AjaxCheckbox(url + id);
                }
            },
            cancelAction: {
                text: cancel
            }
        }
    });

}

function addFiles(obj, params) {
    $(params.target).html(obj.html);
    initializeFileUploader();
    initializeDataPicker(params.target);
}

function addFile(obj, params) {
    if (params.multi == false) {
        $(params.target).siblings('.files-list').html(obj.html);
    } else {
        $(params.target).siblings('.files-list').prepend(obj.html);
    }
}

function makeId(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function listRowRemove(obj, params) {
    if (obj.status) {
        if (obj.alert) {
            $.alert({
                title: $(params.target).find('.list-remove-btn').data('title'),
                content: obj.msg,
                type: obj.result ? 'green' : 'red',
                autoClose: 'cancelAction|10000',
                boxWidth: '500px',
                useBootstrap: false,
                buttons: {
                    cancelAction: {
                        text: $(params.target).find('.list-remove-btn').data('btn-cancel')
                    }
                }
            });
        } else {
            if(obj.preview) {
                if(obj.change) {
                    let span = $('<span></span>').addClass('list-col-result').text(obj.msg);
                    let span2 = $('<span></span>').addClass('comment-result').text(obj.msg);
                    $(params.target).find('.preview-remove-btn').closest('.comment-bottom').prepend(span2);
                    $('.list .list-row.list-row-' + obj.id + ' .list-col .list-remove-btn').parent().append(span);
                    if(obj.result) {
                        $(params.target).find('.preview-remove-btn').remove();
                        $(params.target).find('.comment-content span.content').css('text-decoration', 'line-through');
                        $('.list .list-row.list-row-' + obj.id + ' .list-col .list-remove-btn').remove();
                        $('.list .list-row.list-row-' + obj.id + ' .list-col span.content').css('text-decoration', 'line-through');
                    }
                    setTimeout(function () {
                        $(span2).remove();
                        $(span).remove();
                    }, 3000);
                    
                }
            } else {
                if(obj.cancel) {
                    let div = $('<div></div>').addClass('list-row-result').addClass(obj.result ? 'success' : 'error');
                    div.append($('<span></span>').text(obj.msg));
                    $(params.target).append(div);
                    if(obj.result) {
                        $(params.target).find('.list-remove-btn.cancel-btn').parent().text(obj.msg2);
                    }
                    $(params.target).find('.list-remove-btn.cancel-btn').remove();
                    setTimeout(function () {
                        $(div).remove();
                    }, 3000);
                } else if(obj.change) {
                    let span = $('<span></span>').addClass('list-col-result').text(obj.msg);
                    $(params.target).find('.list-remove-btn').after(span);
                    if(obj.result) {
                        $(params.target).find('.list-remove-btn').remove();
                        $(params.target).find('span.content').css('text-decoration', 'line-through');
                    }
                    setTimeout(function () {
                        $(span).remove();
                    }, 3000);
                } else {
                    let div = $('<div></div>').addClass('list-row-result').addClass(obj.result ? 'success' : 'error');
                    div.append($('<span></span>').text(obj.msg));
                    $(params.target).append(div);
                    setTimeout(function () {
                        if (obj.result) {
                            $(params.target).parent().find('.list-sub-row.list-parent-row-' + obj.id).remove();
                            $(params.target).remove();
                        } else {
                            $(div).remove();
                        }
                    }, 3000);
                }
            }
        }
    }
}

function listRowBlock(obj, params) {
    if (obj.status) {
        if(obj.preview) {
            let span = $('<span></span>').addClass('list-col-result').text(obj.msg);
            let span2 = $('<span></span>').addClass('comment-result').text(obj.msg);
            $(params.target).find('.comment-cont.user-' + obj.id_user + ' .comment-bottom').prepend(span2);
            $('.list .list-row.user-' + obj.id_user + ' .list-col .list-block-btn').parent().append(span);
            if(obj.result) {
                $(params.target).parent().find('.comment-cont.user-' + obj.id_user + ' .comment-bottom .preview-block-btn').remove();
                $('.list .list-row.user-' + obj.id_user + ' .list-col .list-block-btn').remove();
            }
            setTimeout(function () {
                $(params.target).parent().find('.comment-cont.user-' + obj.id_user + ' .comment-bottom .comment-result').remove();
                $('.list .list-row.user-' + obj.id_user + ' .list-col .list-col-result').remove();
            }, 3000);
        } else {
            let span = $('<span></span>').addClass('list-col-result').text(obj.msg);
            $(params.target).parent().find('.list-row.user-' + obj.id_user + ' .list-col .list-block-btn').parent().append(span);
            if(obj.result) {
                $(params.target).parent().find('.list-row.user-' + obj.id_user + ' .list-col .list-block-btn').remove();
            }
            setTimeout(function () {
                $(params.target).parent().find('.list-row.user-' + obj.id_user + ' .list-col .list-col-result').remove();
            }, 3000);
        }
    }
}

function listRowPreview(obj, params) {
    if (obj.status) {
        $.alert({
            title: $(params.target).data('title'),
            content: obj.html,
            type: 'dark',
            boxWidth: '830px',
            useBootstrap: false,
            onContentReady: function() {
                initializePreviewFunctions(this);
            },
            buttons: {
                cancelAction: {
                    text: $(params.target).data('btn-close')
                }
            }
        });
    }
}

function listRowPublish(obj, params) {
    $(params.target).find('.list-col-result').remove();
    if (obj.status) {
        let span = $('<span></span>').addClass('list-col-result').text(obj.msg);
        $(params.target).append(span);
        $(params.target).find('.list-publish-btn i').removeClass(obj.publish ? 'fa-regular fa-square' : 'fa-solid fa-square-check').addClass(obj.publish ? 'fa-solid fa-square-check' : 'fa-regular fa-square');
        setTimeout(function () {
            $(span).remove();
        }, 3000);
    }
}

function listRowHome(obj, params) {
    $(params.target).find('.list-col-result').remove();
    if (obj.status) {
        let span = $('<span></span>').addClass('list-col-result').text(obj.msg);
        $(params.target).append(span);
        $(params.target).find('.list-home-btn i').removeClass(obj.home ? 'fa-toggle-off' : 'fa-toggle-on').addClass(obj.home ? 'fa-toggle-on' : 'fa-toggle-off');
        setTimeout(function () {
            $(span).remove();
        }, 3000);
    }
}

function listRowNewsletter(obj, params) {
    $(params.target).find('.list-col-result').remove();
    if (obj.status) {
        let span = $('<span></span>').addClass('list-col-result').text(obj.msg);
        $(params.target).append(span);
        $(params.target).find('.list-newsletter-btn i').removeClass(obj.newsletter ? 'fa-toggle-off' : 'fa-toggle-on').addClass(obj.newsletter ? 'fa-toggle-on' : 'fa-toggle-off');
        $('.newsletter-options .newsletter-count strong').text(obj.count);
        setTimeout(function () {
            $(span).remove();
        }, 3000);
    }
}

function listRowNewsletterClear(obj, params) {
    if (obj.status) {
        $('.newsletter-options .newsletter-count strong').text(obj.count);
        $('.list .list-col-result').remove();
        $('.list .list-newsletter-btn i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
    }
}

function listChart(obj, params) {
    $.confirm({
        title: obj.title,
        content: obj.html,
        type: 'orange',
        boxWidth: '500px',
        useBootstrap: false,
        onContentReady: function(data, status, xhr){
            new ApexCharts(document.querySelector(obj.target), {
                chart: {
                  type: 'donut'
                },
                series: obj.data.series,
                labels: obj.data.labels,
                plotOptions: {
                       pie: {
                           donut: {
                               labels: {
                                   show: true,
                                   name: {
                                     show: true
                                   },
                                   value: {
                                     show: true
                                   },
                                   total: {
                                       show: true,
                                       color: '#000',
                                       label: obj.total
                                   }
                               }
                           }
                       }
                    }
              }).render();
        },
        buttons: {
            close: {
                text: obj.close
            }
        }
    });
}

function changeLink(obj, params) {
    if (obj.status) {
        $(params.target).find('.link-field').val(obj.link);
        if(obj.error) {
            $(params.target).find('.link-field').after($('<span></span>').addClass('warning').html(obj.error));
        }
    }
}

function changeAllLinks(obj) {
    if (obj.status) {
        $.each(obj.lang_links, function (i, l) {
            $('.link-box.lang-' + l.id_lang).find('.link-field').val(l.link);
            if(l.error) {
                $('.link-box.lang-' + l.id_lang).find('.link-field').after($('<span></span>').addClass('warning').html(l.error));
            }
        });
    }
}


function show_hide(check, hide) {
    if ($(check).is(':checked')) {
        $(hide).show();
    } else {
        $(hide).hide();
    }

}

function initializeDataPicker(box) {
      let daterangpicker_locale = {
        format: "DD.MM.YYYY",
        separator: " - ",
        applyLabel: lang.Apply,
        cancelLabel: lang.Cancel,
        fromLabel: lang.From,
        toLabel: lang.To,
        customRangeLabel: lang.Custom,
        weekLabel: lang.WeekShort,
        daysOfWeek: [
            lang.daysShort[6],
            lang.daysShort[0],
            lang.daysShort[1],
            lang.daysShort[2],
            lang.daysShort[3],
            lang.daysShort[4],
            lang.daysShort[5]
        ],
        monthNames: lang.months,
        firstDay: 1
    };
    let target_range = $('.datepicker-range');
    let target_date = $('.datepicker-date');
    let target_time = $('.datepicker-time');
    if(box) {
        target_range = $(box).find('.datepicker-range');
        target_date = $(box).find('.datepicker-date');
        target_time = $(box).find('.datepicker-time');
    }
    if (target_range.hasClass('time')) {
        daterangpicker_locale.format = "DD.MM.YYYY HH:mm",
        target_range.daterangepicker({
            autoApply: true,
            locale: daterangpicker_locale,
            autoUpdateInput: false,
            timePickerSeconds: false,
            timePicker24Hour: true,
            timePicker: true,
            showDropdowns: true,
        }).on('apply.daterangepicker', function (ev, picker) {
		    if(picker.startDate.format('DD.MM.YYYY')==picker.endDate.format('DD.MM.YYYY')) {
				$(this).val(picker.startDate.format('DD.MM.YYYY'));	
			}
            else { 				
				$(this).val(picker.startDate.format('DD.MM.YYYY') + ' - ' + picker.endDate.format('DD.MM.YYYY'));
			}
            $(this).trigger('change');
        }).on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $(this).trigger('change');
        });
    } else {
        if (target_range.length) {
            target_range.daterangepicker({
                autoApply: true,
                locale: daterangpicker_locale,
                autoUpdateInput: false,
                showDropdowns: true,
            }).on('apply.daterangepicker', function (ev, picker) {
                if(picker.startDate.format('DD.MM.YYYY')==picker.endDate.format('DD.MM.YYYY')) {
					$(this).val(picker.startDate.format('DD.MM.YYYY'));	
				}
                else { 				
				$(this).val(picker.startDate.format('DD.MM.YYYY') + ' - ' + picker.endDate.format('DD.MM.YYYY'));
				}
                $(this).trigger('change');
            }).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                $(this).trigger('change');
            });
        }
    }
    if(target_date.length) {
        if($(target_date).hasClass('time')) {
            daterangpicker_locale.format = "DD.MM.YYYY HH:mm";
        }
        target_date.daterangepicker({
            autoApply: true,
            locale: daterangpicker_locale,
            autoUpdateInput: false,
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: $(target_date).hasClass('time'),
            timePicker24Hour: true,
            timePickerSeconds: false,
        }).on('apply.daterangepicker', function (ev, picker) {
            if($(this).hasClass('time')) {
                $(this).val(picker.startDate.format('DD.MM.YYYY HH:mm'));
            } else {
                $(this).val(picker.startDate.format('DD.MM.YYYY'));
            }
            $(this).trigger('change');
        }).on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $(this).trigger('change');
        });
    }
    if(target_time.length) {
        daterangpicker_locale.format = "HH:mm",
        target_time.daterangepicker({
            autoApply: true,
            locale: daterangpicker_locale,
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            timePickerSeconds: false,
            singleDatePicker: true,
        }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find(".calendar-table").hide();
            picker.container.find('.calendar-time select').on('change', function(){
                setTimeout(function(){picker.container.find('.applyBtn').trigger('click');}, 50);
            });
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('HH:mm'));
            $(this).trigger('change');
        }).on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $(this).trigger('change');
        });
    }

    target_timedate = $('.datetimepicker-date');
    if (target_timedate.length) {
        daterangpicker_locale.format = "DD.MM.YYYY HH:mm",
                target_timedate.daterangepicker({
                    autoApply: true,
                    locale: daterangpicker_locale,
                    autoUpdateInput: false,
                    singleDatePicker: true,
                    showDropdowns: true,
                    timePicker24Hour: true,
                    timePicker: true,
                }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD.MM.YYYY HH:mm'));
            $(this).trigger('change');
        }).on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $(this).trigger('change');
        });
    }	
}	

function ShowHideMenu(id,level) {
	var waznosc = new Date();
	var time = waznosc.getTime();
	var expireTime = time + 5184000;
	waznosc.setTime(expireTime);
	if($('.left-cont .re-'+id+'.level-'+(level+1)+'.hide').length>0) {
		$('.left-cont .re-'+id+'.level-'+(level+1)+'.hide').removeClass('hide');
		$('.left-cont .list-row-'+id+' .fa-solid').removeClass('fa-chevron-down');
		$('.left-cont .list-row-'+id+' .fa-solid').addClass('fa-chevron-up');
		document.cookie = "LeftMenu_"+id+"=show; expires=" + waznosc.toGMTString() + ";domain=." + window.location.host + ";path=/";
	}
    else {
        $('.left-cont .re-'+id+'.level-'+(level+1)).addClass('hide');
		$('.left-cont .list-row-'+id+' .fa-solid').removeClass('fa-chevron-up');
		$('.left-cont .list-row-'+id+' .fa-solid').addClass('fa-chevron-down');
		document.cookie = "LeftMenu_"+id+"=hide; expires=" + waznosc.toGMTString() + ";domain=." + window.location.host + ";path=/";
    }		
}

function pageContentName(obj, params) {
    $(params.target).parent().addClass(obj.status ? 'success' : 'error');
    setTimeout(function(){$(params.target).parent().removeClass(obj.status ? 'success' : error);}, 1000);
    if(!$(params.target).val()) {
        $(params.target).val($(params.target).attr('placeholder'));
    }
}

function sidebarContentName(obj, params) {
    $(params.target).parent().addClass(obj.status ? 'success' : 'error');
    setTimeout(function(){$(params.target).parent().removeClass(obj.status ? 'success' : error);}, 1000);
    if(!$(params.target).val()) {
        $(params.target).val($(params.target).attr('placeholder'));
    }
}

function initializeWyswig(box) {
    let wyswig = $('.wyswig-textarea');
    if(box) {
        wyswig = $(box).find('.wyswig-textarea');
    }
    wyswig.each(function (index, item) {
        var editor = new Jodit(this, {
            defaultActionOnPaste: "insert_clear_html",
            askBeforePasteFromWord: false,
            askBeforePasteHTML: false,
            minHeight: 200,
            height: 400,
            imageDefaultWidth:'',
            toolbarButtonSize: "small",
            allowResizeX: false,
            allowResizeY: true,
            spellcheck: true,
            uploader: {
                url: $('#admin-panel-slug').val() + '/filebrowser?action=fileUpload'
            },
            filebrowser: {
                ajax: {
                    url: $('#admin-panel-slug').val() + '/filebrowser'
                }
            }
        });
    });
}

function initializePreviewFunctions(confirm) {
    $(confirm.$body).find('.preview-block-btn').on('click', function (e) {
        e.preventDefault();
        let btn = this;
        $.confirm({
            title: $(btn).data('title'),
            content: $(btn).data('message'),
            type: 'red',
            autoClose: 'cancel|10000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(btn).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        ajaxCall($(btn).attr('href'), {preview: 1}, 'listRowBlock', {target: $(btn).parents('.jconfirm-content')});
                    }
                },
                cancel: {
                    text: $(btn).data('btn-cancel')
                }
            }
        });
    });
    $(confirm.$body).find('.preview-remove-btn').on('click', function (e) {
        e.preventDefault();
        let btn = this;
        $.confirm({
            title: $(btn).data('title'),
            content: $(btn).data('message'),
            type: 'red',
            autoClose: 'cancel|10000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(btn).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        ajaxCall($(btn).attr('href'), {preview: 1}, 'listRowRemove', {target: $(btn).closest('.comment-cont')});
                    }
                },
                cancel: {
                    text: $(btn).data('btn-cancel')
                }
            }
        });
    });	
}