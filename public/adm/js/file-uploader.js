$(function () {

    $('.fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        autoUpload: true,
        maxChunkSize: false,
        uploadTemplateId: '',
        downloadTemplateId: null,
        formData: {}
    }).bind('fileuploadsubmit', function (e, data) {
        data.formData = {
            type: $(this).data('type'),
            field: $(this).data('field'),
            multi: typeof $(this).attr('multiple') == 'undefined' ? 0 : 1,
			choose_main: typeof $(this).data('main') == 'undefined' ? 0 : 1,
			crop: typeof $(this).data('crop') == 'undefined' ? 0 : 1,
            option: typeof $(this).data('option') == 'undefined' ? '' : $(this).data('option'),
            module: typeof $(this).data('module') == 'undefined' ? '' : $(this).data('module'),
        };
    }).on('fileuploaddone', function (e, data) {
        if (data.result.success) {
            if (data.formData.multi) {
                $(this).parent().siblings('.files-list').append(decodeURIComponent(atob(data.result.html)).replace(/\+/g, ' '));
            } else {
                $(this).parent().siblings('.files-list').html(decodeURIComponent(atob(data.result.html)).replace(/\+/g, ' '));
            }
            initializeWyswig('.files-list .file-box:last');
        } else {
            let file_box = $('<div></div>').addClass('file-box alert-box error');
            file_box.append($('<p></p>').text(data.result.files.name + ': ' + data.result.files.error));
            let btn = $('<button></button>').addClass('close');
            btn.append($('<i></i>').addClass('fas fa-times'));
            file_box.append(btn);
            $(this).parent().siblings('.files-list').append(file_box);
        }
    });

    $(document).on('click', '.file-menager-remove-btn', function (e) {
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
                        let data = {
                            count: $(file).parents('.form-list').find('.files-list .file-box').length,
                            filters: {}
                        };
                        $(file).parents('.file-menager').find('.filters select, .filters input[type="text"], .filters input[type="hidden"]').each(function(){
                            data.filters[$(this).attr('name')] = $(this).val();
                        });
                        ajaxCall($(file).attr('href'), data, 'fileDeleted', {target:$(file).parents('.file-box')});
                        
                    }
                },
                cancelAction: {
                    text: $(file).data('btn-cancel')
                }
            }
        });
    });


});


function fileDeleted(obj, params) {
    let target = $(params.target);
    let div = $('<div></div>').addClass('alert-box').addClass(obj.success ? 'success' : 'error');
    div.append($('<span></span>').text(obj.msg));
    $(target).parents('.form-list').prepend(div);
    if(obj.success) {
        $(target).parents('.form-list').find('.count-box').find('p').text(obj.count_info ? obj.count_info : '');
        if(obj.count && obj.count_all && obj.count>=obj.count_all) {
            $(target).parents('.form-list').find('.count-box .load-more').addClass('hidden');
        } else {
            $(target).parents('.form-list').find('.count-box .load-more').removeClass('hidden');
        }
        $(target).remove();
    }
    setTimeout(function () {
        $(div).remove();
    }, 3000);
}



function initializeFileUploader() {
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: $(this).attr('action'),
        autoUpload: true,
        maxChunkSize: false,
        uploadTemplateId: '',
        downloadTemplateId: null,
        uploadTemplate: function (o) {
            var rows = $();
            $.each(o.files, function (index, file) {
                var row = $('<div class="template-upload file">' +
                        '<div class="file-preview"><span class="preview"></span></div>' +
                        '<div class="file-name"><p class="name"></p>' +
                        '<div class="error"></div>' +
                        '</div>' +
                        '<div class="file-size"><p class="size"></p>' +
                        '<div class="progress"></div>' +
                        '</div>' +
                        '<div class="file-buttons">' +
                        (!index && !o.options.autoUpload ?
                                '<button class="start" disabled><i class="fas fa-upload"></i></button>' : '') +
                        (!index ? '<button class="cancel"><i class="fas fa-times"></i></button>' : '') +
                        '</div>' +
                        '</div>');
                row.find('.name').text(file.name);
                row.find('.size').text(o.formatFileSize(file.size));
                if (file.error) {
                    row.find('.error').text(file.error);
                }
                rows = rows.add(row);
            });
            return rows;
        },
        downloadTemplate: function (o) {
            var rows = $();
            $.each(o.files, function (index, file) {
                var row = $('<div class="template-download file">' +
                        '<div class="file-preview"><span class="preview"></span></div>' +
                        '<div div class="file-name"><p class="name"></p>' +
                        (file.error ? '<div class="error"></div>' : '') +
                        '</div>' +
                        '<div class="file-size"><span class="size"></span></div>' +
                        '<div class="file-buttons"><button class="delete"><i class="fas fa-times"></i></button></div>' +
                        '</div>');
                row.find('.size').text(o.formatFileSize(file.size));
                if (file.error) {
                    row.find('.name').text(file.name);
                    row.find('.error').text(file.error);
                } else {
                    row.find('.name').append($('<a></a>').text(file.name).attr('title', file.name).attr('target', '_blank'));
                    if (file.thumbnailUrl) {
                        row.find('.preview').append(
                                file.type == 'image' ? $('<a></a>').append($('<img>').prop('src', file.thumbnailUrl)) : $('<span></span>').addClass('ext').text(file.ext)
                                );
                    }
                    row.find('a')
                            .attr('data-gallery', '')
                            .prop('href', file.url);
                    row.find('button.delete')
                            .attr('data-type', file.delete_type)
                            .attr('data-url', file.delete_url);
                }
                rows = rows.add(row);
            });
            return rows;
        }
    })
    .bind('fileuploadsubmit', function (e, data) {
        data.formData = {
            multi: typeof $(this).data('multi') == 'undefined' ? 0 : $(this).data('multi'),
            list_type: $(this).parents('.file-menager').find('.filters select[name="type"]').val(),
            count: $('.file-menager .form-list .files-list .file-box').length,
        };
        $('.file-menager .filters').find('select, input[type="text"], input[type="hidden"]').each(function(){
            data.formData['filter_' + $(this).attr('name')] = $(this).val();
        });
    })
    .on('fileuploaddone', function (e, data) {
        if (data.result.success) {
            $('.file-menager .count-box').find('p').text(data.result.data.count_info ? data.result.data.count_info : '');
            $('.file-menager .files-list').prepend(decodeURIComponent(atob(data.result.html)).replace(/\+/g, ' '));
        }
    })
    .on('fileuploadprogress', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
    });
    
    $('#form-list-filters').on('submit', function(e){
        e.preventDefault();
        let filters = {};
        $(this).find('select, input[type="text"], input[type="hidden"]').each(function(){
            filters[$(this).attr('name')] = $(this).val();
        });
        ajaxCall($(this).attr('action'), {filters:filters}, 'filterFiles', {target: $(this).parents('.file-menager').find('.form-list .files-list')});
    });
    $('#form-list-filters .filter').find('select, input[type="text"]').on('change', function(){
        $('#form-list-filters').submit();
    });
    
    $('.file-menager .load-more').on('click', function(e){
        e.preventDefault();
        let data = {
            count: $(this).parents('.file-menager').find('.form-list .files-list .file-box').length,
            filters: {}
        };
        $(this).parents('.file-menager').find('.filters select, .filters input[type="text"], .filters input[type="hidden"]').each(function(){
            data.filters[$(this).attr('name')] = $(this).val();
        });
        ajaxCall($(this).attr('href'), data, 'loadMore', {target: $(this).parents('.file-menager').find('.form-list .files-list')});
    });
}

function filterFiles(obj, params) {
    $(params.target).html(obj.html);
    $(params.target).siblings('.count-box').find('p').text(obj.count_info ? obj.count_info : '');
    if(obj.count && obj.count_all && obj.count>=obj.count_all) {
        $(params.target).parents('.file-menager').find('.count-box .load-more').addClass('hidden');
    } else {
        $(params.target).parents('.file-menager').find('.count-box .load-more').removeClass('hidden');
    }
}

function loadMore(obj, params) {
    $(params.target).append(obj.html);
    $(params.target).siblings('.count-box').find('p').text(obj.count_info ? obj.count_info : '');
    if(obj.count && obj.count_all && obj.count>=obj.count_all) {
        $(params.target).parents('.file-menager').find('.count-box .load-more').addClass('hidden');
    } else {
        $(params.target).parents('.file-menager').find('.count-box .load-more').removeClass('hidden');
    }
}