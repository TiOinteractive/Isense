$(function(){
    $('.survey-form .add-option').on('click', function(e){
        e.preventDefault();
        let no = 0;
        $('.survey-form .options-box .form-group').each(function(){
            let n = parseInt($(this).data('no'));
            if(n >= no) {
                no = n + 1;
            }
        });
        let data = {no: no};
        ajaxCall($(this).attr('href'), data, 'surveyAddOption');
    });
    
    $(document).on('click', '.survey-form .delete-option', function(e){
        e.preventDefault();
        let slide = this;
        $.confirm({
            title: $(slide).data('title'),
            content: $(slide).data('message'),
            type: 'red',
            autoClose: 'cancelAction|8000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(slide).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        $(slide).parents('.form-group').remove();
                    }
                },
                cancelAction: {
                    text: $(slide).data('btn-cancel')
                }
            }
        });
    });
    
});

function surveyAddOption(obj) {
    if(obj.status) {
        $('.survey-form .options-box').append(obj.html);
        fixOrder($('.survey-form .order-box'));
        $('html, body').animate({
            scrollTop: $('.survey-form .options-box .form-group').last().offset().top - 20
        }, 500);
    }
}