$(function(){
    $('.slider-form .add-slide').on('click', function(e){
        e.preventDefault();
        let no = 0;
        $('.slider-form .slides-box .form-group').each(function(){
            let n = parseInt($(this).data('no'));
            if(n >= no) {
                no = n + 1;
            }
        });
        let data = {no: no};
        ajaxCall($(this).attr('href'), data, 'sliderAddSlide');
    });
    
    $(document).on('click', '.slider-form .delete-slide', function(e){
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

function sliderAddSlide(obj) {
    if(obj.status) {
        $('.slider-form .slides-box').append(obj.html);
        fixOrder($('.slider-form .order-box'));
        $('html, body').animate({
            scrollTop: $('.slider-form .slides-box .form-group').last().offset().top - 20
        }, 500);
    }
}