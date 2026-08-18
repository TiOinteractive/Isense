$(function () {

$('.news-form .add-related-gallery').on('click', function(e){
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
                        ajaxCall($(div).find('form.filters-results').attr('action'), data, 'AddRelatedGallery', {title:$(file_btn).data('title'), btn_close:$(file_btn).data('btn-close')});
                    }
                },
                cancel: {
                    text: $(file_btn).data('btn-cancel')
                }
            }
        });
        ajaxCall($(file_btn).attr('href'), {}, 'showRelatedGalleryModal', {target: div});
    });

$('.news-form .list-remove-related').on('click', function(e){
	e.preventDefault();
	 $(this).parent().parent().remove();
});	



$('.link-category-id').on('change', function () {
        let lang_links = [];
        let id_page = $(this).val();
        $('.link-box').each(function () {
            let name = $(this).find('.category-name').val();
            let id_link = $(this).find('.link-id').val();
            let id_lang = $(this).find('.link-id-lang').val();
            let direct_link = $(this).find('.link-direct-links').length ? $(this).find('.link-direct-links').val() : '';
            lang_links.push({name: name, id_lang: id_lang, id_link: id_link, direct_link: direct_link});
        });
        $('.link-box .link-field').siblings('span.warning').remove();
        ajaxCall($('#admin-panel-slug').val() + '/foto/categorylink', {id_page: id_page, lang_links: lang_links}, 'changeAllLinks');
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


 $('.list .list-row .file-category-id').on('change', function (e) {
        e.preventDefault();
        ajaxCall($(this).data('url'), {cat:$(this).val()}, 'listRowPublish', {target: $(this).parents('.list-col')});
 });




$('.link-box .category-name').on('change', function () {
		let lang_links = [];
        let name = $(this).val();
        let id_link = $(this).parents('.link-box').find('.link-id').val();
        let id_lang = $(this).parents('.link-box').find('.link-id-lang').val();
        let id_page = $(this).parents('form').find('.link-page-id').val();
		let re_id = $(this).parents('form').find('.link-category-id').val();
		lang_links.push({name: name, id_lang: id_lang, id_link: id_link});
        $(this).parents('.link-box').find('.link-field').siblings('span.warning').remove();
        ajaxCall($('#admin-panel-slug').val() + '/foto/categorylinkcheck',{id_page: id_page,re_id:re_id, lang_links: lang_links}, 'changeLink', {target: $(this).parents('.link-box')});
    });
	
	
});	
	
function saveOrder(obj) {

 let div = $('<div></div>').addClass('list-row-result').addClass(obj.result ? 'success' : 'error');
        div.append($('<span></span>').text(obj.msg));
        $("#order-form .list").append(div);
        setTimeout(function () {
            div.remove();
        }, 3000);
}	

function showRelatedGalleryModal(obj, params) {
    $(params.target).html(obj.html);
    $(params.target).find('form.filters').on('submit', function(e){
        e.preventDefault();
        let data = $(this).serialize();
		var selected=$('.news-form .related-box input').serialize();
        ajaxCall($(this).attr('action'), {data:data,selected:selected}, 'showRelatedGallery', params);
    });
}

function showRelatedGallery(obj, params) {
    $(params.target).find('form.filters-results').html(obj.html);
}

function AddRelatedGallery(obj, params) {
    if(obj.status) {
        $('.related-box').append(obj.html);
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

