$(function(){
    $('.add-module-element').on('click', function(e){
        e.preventDefault();
        let no = 0;
        $('.page-config-form .module-list .module').each(function(){
            let n = parseInt($(this).data('no'));
            if(n >= no) {
                no = n + 1;
            }
        });
        let data = {no: no};
        ajaxCall($(this).attr('href'), data, 'addModuleElement', {target:$(this).parent().find('.module-list')});
    });
    $(document).on('click', '.delete-module-element', function(e){
        e.preventDefault();
        let item = this;
        ajaxCall($(item).attr('href'), {action: 'check'}, 'moduleDeleteConfirm', {target: item, url: $(item).attr('href')});
    });
});

function moduleDeleteConfirm(obj, params) {
    let buttons = {};
    if(obj.status) {
        buttons = {
            delete: {
                text: $(params.target).data('btn-ok'),
                btnClass: 'btn-red',
                action: function () {
                    ajaxCall(params.url, {action: 'delete'}, 'moduleDeleteStatus', {target:params.target});
                }
            },
            cancelAction: {
                text: $(params.target).data('btn-cancel')
            }
        };
    } else {
        buttons = {
            cancelAction: {
                text: $(params.target).data('btn-close')
            }
        };
    }
    $.confirm({
        title: $(params.target).data('title'),
        content: obj.message,
        type: 'red',
        autoClose: 'cancelAction|10000',
        boxWidth: '500px',
        useBootstrap: false,
        buttons: buttons
    });
}

function moduleDeleteStatus(obj, params) {
    $.alert({
        title: $(params.target).data('title'),
        content: obj.message,
        type: obj.status ? 'green' : 'red',
        autoClose: 'cancelAction|10000',
        boxWidth: '500px',
        useBootstrap: false,
        buttons: {
            cancelAction: {
                text: $(params.target).data('btn-close')
            }
		}
    });
	if(obj.status) {
		$(params.target).parents('.module').remove();
	}
}

function addModuleElement(obj, params) {
    if(obj.status) {
        $(params.target).append(obj.html);
        fixOrder(params.target);
    }
}