$(function(){
    $('.map-form .add-marker').on('click', function(e){
        e.preventDefault();
        let no = 0;
        $('.map-form .markers-box .form-group').each(function(){
            let n = parseInt($(this).data('no'));
            if(n >= no) {
                no = n + 1;
            }
        });
        let data = {no: no};
        ajaxCall($(this).attr('href'), data, 'mapsAddMarker');
    });
    
    $(document).on('click', '.map-form .delete-marker', function(e){
        e.preventDefault();
        let marker = this;
        $.confirm({
            title: $(marker).data('title'),
            content: $(marker).data('message'),
            type: 'red',
            autoClose: 'cancelAction|8000',
            boxWidth: '500px',
            useBootstrap: false,
            buttons: {
                deleteUser: {
                    text: $(marker).data('btn-ok'),
                    btnClass: 'btn-red',
                    action: function () {
                        $(marker).parents('.form-group').remove();
                    }
                },
                cancelAction: {
                    text: $(marker).data('btn-cancel')
                }
            }
        });
    });
    
});

function mapsAddMarker(obj) {
    if(obj.status) {
        $('.map-form .markers-box').append(obj.html);
        $('html, body').animate({
            scrollTop: $('.map-form .markers-box .form-group').last().offset().top - 20
        }, 500);
    }
}