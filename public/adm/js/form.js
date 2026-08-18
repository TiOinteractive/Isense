$(function(){
    $('.form .add-field').on('click', function(e){
        e.preventDefault();
        let no = 0;
        $('.form .form-fields-box .form-group').each(function(){
            let n = parseInt($(this).data('no'));
            if(n >= no) {
                no = n + 1;
            }
        });
        let data = {no: no};
        ajaxCall($(this).attr('href'), data, 'formAddField');
    });
    $(document).on('click', '.form .delete-field', function(e){
        e.preventDefault();
        let field = this;
        $.confirm({
            title: $(field).data('title'),
            content: $(field).data('message'),
            type: 'red',
            autoClose: 'cancelAction|8000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(field).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        $(field).parents('.form-group').remove();
                    }
                },
                cancelAction: {
                    text: $(field).data('btn-cancel')
                }
            }
        });
    });
});

function formAddField(obj) {
    if(obj.status) {
        $('.form .form-fields-box').append(obj.html);
        fixOrder($('.form .order-box'));
        $('html, body').animate({
            scrollTop: $('.form .form-fields-box .form-group').last().offset().top - 20
        }, 500);
    }
}