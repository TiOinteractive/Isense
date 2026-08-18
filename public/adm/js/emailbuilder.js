
function loadModule(module) {
    let module_conf = getModuleConfigFromHtml(module);
    ajaxCall($(module).closest('form').attr('action'), {action: 'config', module: $(module).data('module'), id: $(module).data('id'), data: module_conf.data, conf: module_conf.conf}, 'showModuleConfigForm', {item: module});
}

function getModuleConfigFromHtml(module) {
    let data = {};
    $(module).find('[class*="conf-"]').each(function (k, item) {
        let name = '';
        let classes = this.className.split(' ');
        $.each(classes, function (k, c) {
            if (c.substring(0, 5) == 'conf-') {
                name = c.substring(5);
                return false;
            }
        });
        switch ($(this).prop("tagName")) {
            case 'a':
            case 'A':
                data[name + '_href'] = $(this).attr('href');
                data[name + '_title'] = $(this).attr('title');
                data[name + '_text'] = $(this).text();
                break;
            case 'img':
            case 'IMG':
                data[name + '_src'] = $(this).attr('src');
                data[name + '_alt'] = $(this).attr('alt');
                break;
            case 'td':
            case 'TD':
            case 'span':
            case 'SPAN':
                data[name + '_text'] = $(this).text();
                break;
        }
    });
    let conf = {};
    $(module).find('[data-conf]').each(function (k, item) {
        let tmp_conf = $(this).data('conf');
        $.each(tmp_conf, function (k, i) {
            conf[k] = i;
        })
    });
    return {data: data, conf: conf};
}

function dropModule(obj, params) {
    if (obj.id) {
        $('#builder .module[data-id="' + obj.id + '"]').html(obj.html);
    } else {
        if($('#builder .module-target').length) {
            $('#builder .module-target').before(obj.html);
        } else {
            $(obj.html).appendTo($("#builder"));
        }
    }
    $('#builder .module-target').remove();
}

function showModuleConfigForm(obj, params) {
    $('.email-builder-sidebar .module-config-box').remove();
    $('.email-builder-sidebar').addClass('config');
    $('.email-builder-sidebar .email-builder-sidebar-cont').append(obj.html);
    initializeDataPicker($('.email-builder-sidebar .module-config-box.' + obj.module + '-' + obj.id));
}

function addNewsletterFile(obj, params) {
    if (params.multi == false) {
        $(params.target).closest('.photo').find('img').remove();
        $(params.target).closest('.photo').prepend(obj.html);
        $(params.target).closest('.photo').find('input[name="photo_src"]').val(obj.path).trigger('change');
    } else {
        //$(params.target).siblings('.files-list').prepend(obj.html);
    }
}

function newsletterSaved(obj, params) {
    if (obj.status) {
        window.location = obj.redirect;
    } else {
        let errors_list = '';
        if (obj.errors) {
            errors_list += '<ul>';
            $.each(obj.errors, function (n, v) {
                params.modal.$content.find('input[name="' + n + '"]').parent().addClass('error');
                errors_list += '<li>' + v + '</li>';
            });
            errors_list += '</ul>';
        }
        params.modal.$content.append('<div class="result alert-box ' + (obj.status ? 'success' : 'error') + '"><p>' + obj.message + '</p>' + errors_list + '</div>');
    }
}

function newsletterTemplateSaved(obj, params) {
    let errors_list = '';
    if (obj.errors) {
        errors_list += '<ul>';
        $.each(obj.errors, function (n, v) {
            params.modal.$content.find('input[name="' + n + '"]').parent().addClass('error');
            errors_list += '<li>' + v + '</li>';
        });
        errors_list += '</ul>';
    }
    if (obj.status) {
        params.modal.buttons.cancel.hide();
        params.modal.buttons.delete.hide();
        params.modal.buttons.close.show();
        $(params.target).closest('.templates-list').find('.list').html(obj.html);
    }
    params.modal.$content.append('<div class="result alert-box ' + (obj.status ? 'success' : 'error') + '"><p>' + obj.message + '</p>' + errors_list + '</div>');
}

function newsletterTemplateLoaded(obj, params) {
    if (obj.status) {
        params.modal.buttons.cancel.hide();
        params.modal.buttons.load.hide();
        params.modal.buttons.close.show();
        $("#builder").html(obj.html);
    }
    params.modal.$content.append('<div class="result alert-box ' + (obj.status ? 'success' : 'error') + '"><p>' + obj.message + '</p></div>');
}

function newsletterTemplateDeleted(obj, params) {
    if (obj.status) {
        params.modal.buttons.cancel.hide();
        params.modal.buttons.delete.hide();
        params.modal.buttons.close.show();
        $(params.target).closest('.templates-list').find('.list').html(obj.html);
    }
    params.modal.$content.append('<div class="result alert-box ' + (obj.status ? 'success' : 'error') + '"><p>' + obj.message + '</p></div>');
}

$(function () {
    $(document).on('scroll', function (e) {
        let window_top = $(window).scrollTop();
        let window_height = $(window).height();
        let sidebar_offset = $('.email-builder-sidebar').offset();
        let sidebar_height = $('.email-builder-sidebar').height();
        let sideber_cont_height = $('.email-builder-sidebar-cont').height();
        if (window_height > sideber_cont_height) {
            if (window_top > sidebar_offset.top) {
                if (window_top + sideber_cont_height < sidebar_offset.top + sidebar_height) {
                    $('.email-builder-sidebar-cont').css('top', (window_top - sidebar_offset.top) + 'px');
                } else {
                    $('.email-builder-sidebar-cont').css('top', (sidebar_height - sideber_cont_height) + 'px');
                }
            } else {
                $('.email-builder-sidebar-cont').removeAttr('style');
            }
        } else {
            if (window_top + window_height > sidebar_offset.top + sideber_cont_height) {
                if (window_top + window_height < sidebar_offset.top + sidebar_height) {
                    $('.email-builder-sidebar-cont').css('top', (window_top + window_height - (sidebar_offset.top + sideber_cont_height)) + 'px');
                } else {
                    $('.email-builder-sidebar-cont').css('top', (sidebar_height - sideber_cont_height) + 'px');
                }
            } else {
                $('.email-builder-sidebar-cont').removeAttr('style');
            }
        }
    });
    let module_target_div = $('<div></div>').addClass('module-target');
    $("#modules .module-item").draggable({
        cancel: "a.ui-icon", // clicking an icon won't initiate dragging
        revert: "invalid", // when not dropped, the item will revert back to its initial position
        containment: "document",
        helper: "clone",
        cursor: "move",
        start: function(event, ui) {
            $(module_target_div).addClass('sortable-placeholder ui-sortable-placeholder');
        },
        drag: function(event, ui) {
            let item;
            let center = 0;
            let top = ui.offset.top - $('#builder').offset().top;
            if(top < 0) top = 0;
            $("#builder .module").each(function(i, it){
                let pos = $(it).position();
                let height = $(it).height();
                if(top >= pos.top && top <= pos.top + height) {
                    center = pos.top + height - (height / 2);
                    item = it;
                    return false;
                }
            });
            if(top < center) {
                $(item).before(module_target_div);
            } else {
                $(item).after(module_target_div);
            }
        },
        stop: function(event, ui) {
            $(module_target_div).removeClass('sortable-placeholder ui-sortable-placeholder');
        }
    });
    
    $("#builder").droppable({
        accept: "#modules > .module-item",
        classes: {
            "ui-droppable-active": "ui-state-highlight",
        },
        drop: function (event, ui) {
            ajaxCall($(this).closest('form').attr('action'), {action: 'builder', module: ui.draggable.data('module')}, 'dropModule', {item: ui.draggable});
        }
    });

    $("#builder").sortable({
        placeholder: "sortable-placeholder",
        handle: ".move"
    }).disableSelection();

    $(document).on('click', "#builder a", function (e) {
        e.preventDefault();
        window.open($(this).attr('href'), '_blank');
    });

    $(document).on('click', "#builder .module", function (e) {
        //e.preventDefault();
        //if(!$(e.target).closest('.settings').length && !$('.email-builder-sidebar .module-config-box.' + $(this).data('module') + '-' + $(this).data('id')).length) {
        //loadModule(this);
        //}
    });
    $(document).on('click', "#builder .module .settings .remove", function (e) {
        e.preventDefault();
        let module = $(this).closest('.module').data('module');
        let id = $(this).closest('.module').data('id');
        if ($('.email-builder-sidebar .module-config-box.' + module + '-' + id).length) {
            $('.email-builder-sidebar .module-config-box.' + module + '-' + id).remove();
            $('.email-builder-sidebar').removeClass('config');
        }
        $(this).closest('.module').remove();
    });
    $(document).on('click', '#builder .module .settings .configuration', function (e) {
        e.preventDefault();
        if (!$('.email-builder-sidebar .module-config-box.' + $(this).closest('.module').data('module') + '-' + $(this).closest('.module').data('id')).length) {
            loadModule($(this).closest('.module'));
        }
    });
    $(document).on('mouseenter', "#builder .module", function (e) {
        let settings_div = $('<div></div>').addClass('settings');
        settings_div.append($('<div></div>').addClass('configuration').html('<svg viewBox="0 0 24 24"><path d="M12.0124 2.25C12.7464 2.25846 13.4775 2.34326 14.1939 2.50304C14.5067 2.57279 14.7406 2.83351 14.7761 3.15196L14.9463 4.67881C15.0233 5.37986 15.6152 5.91084 16.3209 5.91158C16.5105 5.91188 16.6982 5.87238 16.8734 5.79483L18.2741 5.17956C18.5654 5.05159 18.9057 5.12136 19.1232 5.35362C20.1354 6.43464 20.8892 7.73115 21.3279 9.14558C21.4225 9.45058 21.3137 9.78203 21.0566 9.9715L19.8151 10.8866C19.461 11.1468 19.2518 11.56 19.2518 11.9995C19.2518 12.4389 19.461 12.8521 19.8159 13.1129L21.0585 14.0283C21.3156 14.2177 21.4246 14.5492 21.3299 14.8543C20.8914 16.2685 20.138 17.5649 19.1264 18.6461C18.9091 18.8783 18.569 18.9483 18.2777 18.8206L16.8714 18.2045C16.4691 18.0284 16.007 18.0542 15.6268 18.274C15.2466 18.4937 14.9935 18.8812 14.9452 19.3177L14.7761 20.8444C14.7413 21.1592 14.5124 21.4182 14.2043 21.4915C12.7558 21.8361 11.2467 21.8361 9.79828 21.4915C9.49015 21.4182 9.26129 21.1592 9.22643 20.8444L9.0576 19.32C9.00802 18.8843 8.75459 18.498 8.37467 18.279C7.99475 18.06 7.53345 18.0343 7.13244 18.2094L5.72582 18.8256C5.43446 18.9533 5.09428 18.8833 4.87703 18.6509C3.86487 17.5685 3.11144 16.2705 2.67344 14.8548C2.57911 14.5499 2.68811 14.2186 2.94509 14.0293L4.18842 13.1133C4.54256 12.8531 4.75172 12.4399 4.75172 12.0005C4.75172 11.561 4.54256 11.1478 4.18796 10.8873L2.94541 9.97285C2.68804 9.78345 2.57894 9.45178 2.67361 9.14658C3.11236 7.73215 3.86619 6.43564 4.87837 5.35462C5.09584 5.12236 5.43618 5.05259 5.72749 5.18056L7.12786 5.79572C7.53081 5.97256 7.99404 5.94585 8.37601 5.72269C8.75633 5.50209 9.00953 5.11422 9.05841 4.67764L9.22849 3.15196C9.26401 2.83335 9.49811 2.57254 9.81105 2.50294C10.5283 2.34342 11.2602 2.25865 12.0124 2.25ZM12.0126 3.7499C11.5586 3.75524 11.1058 3.79443 10.6581 3.86702L10.5491 4.84418C10.4473 5.75368 9.92028 6.56102 9.13066 7.01903C8.33622 7.48317 7.36761 7.53903 6.52483 7.16917L5.62654 6.77456C5.0546 7.46873 4.59938 8.25135 4.27877 9.09168L5.07656 9.67879C5.81537 10.2216 6.25172 11.0837 6.25172 12.0005C6.25172 12.9172 5.81537 13.7793 5.07734 14.3215L4.27829 14.9102C4.59863 15.752 5.05392 16.5361 5.62625 17.2316L6.53138 16.8351C7.36947 16.4692 8.33149 16.5227 9.12377 16.9794C9.91606 17.4361 10.4446 18.2417 10.5482 19.1526L10.6572 20.1365C11.5468 20.2878 12.4557 20.2878 13.3453 20.1365L13.4543 19.1527C13.5551 18.2421 14.083 17.4337 14.8762 16.9753C15.6695 16.5168 16.6334 16.463 17.473 16.8305L18.3775 17.2267C18.9493 16.5323 19.4044 15.7495 19.7249 14.909L18.927 14.3211C18.1882 13.7783 17.7518 12.9162 17.7518 11.9995C17.7518 11.0827 18.1882 10.2206 18.9261 9.67847L19.7229 9.09109C19.4023 8.25061 18.947 7.46784 18.375 6.77356L17.4785 7.16737C17.1132 7.32901 16.718 7.4122 16.3189 7.41158C14.8492 7.41004 13.6158 6.30355 13.4554 4.84383L13.3464 3.8667C12.9009 3.7942 12.4529 3.75512 12.0126 3.7499ZM11.9999 8.24995C14.071 8.24995 15.7499 9.92888 15.7499 12C15.7499 14.071 14.071 15.75 11.9999 15.75C9.92887 15.75 8.24994 14.071 8.24994 12C8.24994 9.92888 9.92887 8.24995 11.9999 8.24995ZM11.9999 9.74995C10.7573 9.74995 9.74994 10.7573 9.74994 12C9.74994 13.2426 10.7573 14.25 11.9999 14.25C13.2426 14.25 14.2499 13.2426 14.2499 12C14.2499 10.7573 13.2426 9.74995 11.9999 9.74995Z"/></svg>'));
        settings_div.append($('<div></div>').addClass('move').html('<svg viewBox="0 0 24 24"><path d="M15.2803 6.03033C14.9874 6.32322 14.5126 6.32322 14.2197 6.03033L12.75 4.56066L12.75 8.25C12.75 8.66421 12.4142 9 12 9C11.5858 9 11.25 8.66421 11.25 8.25L11.25 4.56066L9.78033 6.03033C9.48744 6.32322 9.01256 6.32322 8.71967 6.03033C8.42678 5.73744 8.42678 5.26256 8.71967 4.96967L11.4697 2.21967C11.6103 2.07902 11.8011 2 12 2C12.1989 2 12.3897 2.07902 12.5303 2.21967L15.2803 4.96967C15.5732 5.26256 15.5732 5.73744 15.2803 6.03033Z"/><path d="M6.03033 14.2197C6.32322 14.5126 6.32322 14.9874 6.03033 15.2803C5.73744 15.5732 5.26256 15.5732 4.96967 15.2803L2.21967 12.5303C2.07902 12.3897 2 12.1989 2 12C2 11.8011 2.07902 11.6103 2.21967 11.4697L4.96967 8.71967C5.26256 8.42678 5.73744 8.42678 6.03033 8.71967C6.32322 9.01256 6.32322 9.48744 6.03033 9.78033L4.56066 11.25H8.25C8.66421 11.25 9 11.5858 9 12C9 12.4142 8.66421 12.75 8.25 12.75H4.56066L6.03033 14.2197Z"/><path d="M17.9697 15.2803C17.6768 14.9874 17.6768 14.5126 17.9697 14.2197L19.4393 12.75H15.75C15.3358 12.75 15 12.4142 15 12C15 11.5858 15.3358 11.25 15.75 11.25H19.4393L17.9697 9.78033C17.6768 9.48744 17.6768 9.01256 17.9697 8.71967C18.2626 8.42678 18.7374 8.42678 19.0303 8.71967L21.7803 11.4697C21.921 11.6103 22 11.8011 22 12C22 12.1989 21.921 12.3897 21.7803 12.5303L19.0303 15.2803C18.7374 15.5732 18.2626 15.5732 17.9697 15.2803Z"/><path d="M15.2803 17.9697C14.9874 17.6768 14.5126 17.6768 14.2197 17.9697L12.75 19.4393L12.75 15.75C12.75 15.3358 12.4142 15 12 15C11.5858 15 11.25 15.3358 11.25 15.75L11.25 19.4393L9.78033 17.9697C9.48744 17.6768 9.01256 17.6768 8.71967 17.9697C8.42678 18.2626 8.42678 18.7374 8.71967 19.0303L11.4697 21.7803C11.6103 21.921 11.8011 22 12 22C12.1989 22 12.3897 21.921 12.5303 21.7803L15.2803 19.0303C15.5732 18.7374 15.5732 18.2626 15.2803 17.9697Z"/></svg>'));
        settings_div.append($('<div></div>').addClass('remove').html('<svg viewBox="0 0 24 24"><path d="M12,0A12,12,0,1,0,24,12,12.013,12.013,0,0,0,12,0Zm0,22A10,10,0,1,1,22,12,10.011,10.011,0,0,1,12,22Z"/><path d="M16.707,7.293a1,1,0,0,0-1.414,0L12,10.586,8.707,7.293A1,1,0,1,0,7.293,8.707L10.586,12,7.293,15.293a1,1,0,1,0,1.414,1.414L12,13.414l3.293,3.293a1,1,0,0,0,1.414-1.414L13.414,12l3.293-3.293A1,1,0,0,0,16.707,7.293Z"/></svg>'));
        $(this).append(settings_div);
    }).on('mouseleave', "#builder .module", function (e) {
        $(this).find('.settings').remove();
    });

    $(document).on('click', ".module-config-box .header .close", function (e) {
        e.preventDefault();
        $(this).closest('.email-builder-sidebar').removeClass('config');
        $(this).closest('.module-config-box').remove();
    });
    $(document).on('change', '.module-config-box .module-config', function (e) {
        e.preventDefault();
        let data = {};
        let form_data = $(this).closest('.module-config-box').find('.module-config').serializeArray();
        $.each(form_data, function (key, item) {
            data[item.name] = item.value;
        });
        if (!$('.email-builder-sidebar .module-config-box.' + $(this).data('module') + '-' + $(this).data('id')).length) {
            ajaxCall($(this).closest('form').attr('action'), {action: 'builder', module: $(this).closest('.module-config-box').data('module'), id: $(this).closest('.module-config-box').data('id'), data: data}, 'dropModule', {item: this});
        }
    });
    $(document).on('click', '.module-config-box .field-box .photo .remove', function (e) {
        e.preventDefault();
        $(this).closest('.photo').find('img').remove();
        $(this).closest('.photo').find('input[name="photo_src"]').val('').trigger('change');
        $(this).addClass('hidden');
        $(this).parent().find('.change').addClass('hidden');
        $(this).parent().find('.add').removeClass('hidden');
    });
    $(document).on('click', '.module-config-box .field-box .photo .add, .module-config-box .field-box .photo .change', function (e) {
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
                            key_name: key_name,
                            module: 'newsletter',
                            multi: multi
                        };
                        $(this.$body).find('form.form-list .files-list input.file-input').each(function () {
                            if ($(this).prop('checked')) {
                                data.files.push($(this).val());
                            }
                        });
                        ajaxCall($(this.$body).find('form.form-list').attr('action'), data, 'addNewsletterFile', {target: file_btn, multi: multi});
                    }
                },
                cancel: {
                    text: $(file_btn).data('btn-cancel')
                }
            }
        });
        ajaxCall($(file_btn).data('url'), {type: type, multi: multi}, 'addFiles', {target: div});
    });

    $('.email-builder-form').on('submit', function (e) {
        e.preventDefault();
        let form = this;
        $.confirm({
            title: $(form).data('title'),
            content: '<form>' +
                    '<div class="form-row">' +
                    '<label>' + $(form).data('message') + '</label>' +
                    '<input type="text" name="name" class="name" value="' + ($(form).find('input[name="newsletter_name"]').val()) + '" />' +
                    '</div>' +
                    '</form>',
            type: 'orange',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                delete: {
                    text: $(form).data('btn-ok'),
                    btnClass: 'btn-orange',
                    action: function () {
                        $(form).find('.email-builder .module-target').remove();
                        $(form).find('.email-builder .module .settings').remove();
                        let name = this.$content.find('.name').val();
                        let html = $(form).find('.email-builder').html();
                        html = btoa(encodeURIComponent(html));
                        this.$content.find('.result').remove();
                        this.$content.find('.error').removeClass('error');
                        ajaxCall($(form).data('action'), {name: name, html: html}, 'newsletterSaved', {target: form, modal: this});
                        return false;
                    }
                },
                cancel: {
                    text: $(form).data('btn-cancel')
                }
            }
        });
    });

    $('.save-template-btn').on('click', function (e) {
        e.preventDefault();
        let button = this;
        let options = '';
        $(this).closest('.templates-list').find('.template-item').each(function () {
            options += '<option value="' + $(this).data('template-id') + '">' + $(this).text() + '</option>';
        });
        $.confirm({
            title: $(button).data('title'),
            content: '<form>' +
                    '<div class="form-row">' +
                    '<label>' + $(button).data('message') + '</label>' +
                    '<select name="template" class="template">' +
                    '<option value="">' + '-- zapisz jako nowy --' + '</option>' +
                    options +
                    '</select>' +
                    '<input type="text" name="name" class="name" value="" />' +
                    '</div>' +
                    '</form>',
            type: 'orange',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                delete: {
                    text: $(button).data('btn-ok'),
                    btnClass: 'btn-orange',
                    action: function () {
                        let template = this.$content.find('.template').val();
                        let name = this.$content.find('.name').val();
                        let modules = [];
                        $('.email-builder-content .module').each(function (k, item) {
                            let module_conf = getModuleConfigFromHtml(this);
                            let module = {
                                module: $(this).data('module'),
                                id: $(this).data('id'),
                                data: module_conf.data,
                                conf: module_conf.conf,
                            };
                            modules.push(module);
                        });
                        this.$content.find('.result').remove();
                        this.$content.find('.error').removeClass('error');
                        ajaxCall($(button).attr('href'), {action: 'template-save', modules: modules, name: name, id: template}, 'newsletterTemplateSaved', {target: button, modal: this});
                        return false;
                    }
                },
                cancel: {
                    text: $(button).data('btn-cancel')
                },
                close: {
                    text: $(button).data('btn-close'),
                    isHidden: true,
                }
            }
        });
    });
    $(document).on('click', '#templates .template-item .name', function () {
        let item = $(this).parent();
        $.confirm({
            title: $(item).data('title'),
            content: $(item).data('message'),
            type: 'orange',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                load: {
                    text: $(item).data('btn-ok'),
                    btnClass: 'btn-orange',
                    action: function () {
                        ajaxCall($(item).closest('form').attr('action'), {action: 'template-load', id: $(item).data('template-id')}, 'newsletterTemplateLoaded', {target: item, modal: this});
                        return false;
                    }
                },
                cancel: {
                    text: $(item).data('btn-cancel')
                },
                close: {
                    text: $(item).data('btn-close'),
                    isHidden: true,
                }
            }
        });
    });

    $(document).on('click', '#templates .template-item .delete-template-btn', function (e) {
        e.preventDefault();
        let item = this;
        $.confirm({
            title: $(item).data('title'),
            content: $(item).data('message'),
            type: 'orange',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                delete: {
                    text: $(item).data('btn-ok'),
                    btnClass: 'btn-orange',
                    action: function () {
                        ajaxCall($(item).closest('form').attr('action'), {action: 'template-delete', id: $(item).closest('.template-item').data('template-id')}, 'newsletterTemplateDeleted', {target: item, modal: this});
                        return false;
                    }
                },
                cancel: {
                    text: $(item).data('btn-cancel')
                },
                close: {
                    text: $(item).data('btn-close'),
                    isHidden: true,
                }
            }
        });
    });
});