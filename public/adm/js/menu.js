$(function(){
    $('.available-elements .module .header .expand').on('click', function(){
        if($(this).parents('.module').hasClass('minimized')) {
            $('.available-elements .module').addClass('minimized');
            $(this).parents('.module').removeClass('minimized');
        } else {
            $(this).parents('.module').addClass('minimized');
        }
    });
    
    $(document).on('click', '.menu-elements .menu-item .header .expand', function(){
        if($(this).parents('.menu-item').hasClass('minimized')) {
            $(this).parents('.menu-item').removeClass('minimized');
        } else {
            $(this).parents('.menu-item').addClass('minimized');
        }
    });
    
    $(document).on('click', '.menu-elements .menu-item .header .delete-menu-item', function(e){
        e.preventDefault();
        let item = this;
        $.confirm({
            title: $(item).data('title'),
            content: $(item).data('message'),
            type: 'red',
            autoClose: 'cancelAction|10000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(item).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        $(item).parents('.menu-item').remove();
                    }
                },
                cancelAction: {
                    text: $(item).data('btn-cancel')
                }
            }
        });
    });
    
    $('.available-elements .add-to-menu-btn').on('click', function(e){
        e.preventDefault();
        let data = {elements:{}};
        $(this).parents('.available-elements-list').find('.element input').each(function() {
            let value = $(this).val();
            if(($(this).attr('type') == 'checkbox' && $(this).prop('checked')) || ($(this).attr('type') == 'text' && value.length) || ($(this).attr('type') == 'hidden')) {
                let name = $(this).attr('name');
                name = name.substring(name.indexOf('[') + 1, name.indexOf(']'));
                if(typeof data.elements[name] == 'undefined') {
                    data.elements[name] = [];
                }
                data.elements[name].push($(this).val());
            }
        });
        ajaxCall($(this).attr('href'), data, 'addToMenu', {module:$(this).parents('.module')});
    });
    
    
    $(".menu-elements .menu-item-group").each(function(){
        $(this).sortable({
            handle: '.menu-item',
            placeholder: "menu-item-group minimized state-highlight",
            start: function(event, ui) {
            },
            sort: function (event, ui) {
                let left = ui.position.left - 10;
                if(left > 30) {
                    $('.menu-elements-list .state-highlight').css('margin-left', $(this).hasClass('menu-elements-list') ? 30 : 60);
                } else if(left > 0 && left < 30) {
                    $('.menu-elements-list .state-highlight').css('margin-left', $(this).hasClass('menu-elements-list') ? 0 : 30);
                } else {
                    $('.menu-elements-list .state-highlight').css('margin-left', 0);
                }
            },
            stop: function (event, ui) {
                $(ui.item).removeAttr('style');
                let left = ui.position.left - 10;
                if(left >= 30) {
                    $(ui.item).appendTo($(ui.item).prev('.menu-item-group'));
                } else if (left > 0 && left < 30) {
                    
                } else {
                    if(!$(this).hasClass('menu-elements-list')) {
                        $(ui.item).insertAfter($(ui.item).parent('.menu-item-group'));
                    }
                }
                fixMenuStructure();
            }
        });
    });
});


function addToMenu(obj, params) {
    if(obj.status) {
        $('.menu-form .menu-elements-list').append(obj.html);
        $(params.module).find('input:checked').prop('checked', false);
        fixMenuStructure();
    }
}

function fixMenuStructure(el) {
    $('.menu-form .menu-elements-list>.menu-item-group').each(function (no, item) {
        fixMenuGroupStructure(item, no);
    });
}

function fixMenuGroupStructure(group, no) {
    let parent_id = 0;
    if($(group).parent('.menu-item-group').length && !$(group).parent('.menu-item-group').hasClass('menu-elements-list')) {
        parent_id = $(group).parent('.menu-item-group').find('.menu-item:first .id-field').val();
    }
    $(group).find('.order-field').val(no);
    $(group).find('.parent-field').val(parent_id);
    $(group).children('.menu-item-group').each(function(no2, item2){
        fixMenuGroupStructure(item2, no2);
    });
}
