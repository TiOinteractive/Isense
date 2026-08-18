$(function () {
    $(document).on('submit', 'form.newsletter-form', function (e) {
        e.preventDefault();
        if(!$(this).hasClass('block')) {
            $(this).removeClass('error success');
            $(this).find('.error').removeClass('error');
            $(this).find('.alert').remove();
            $(this).find('.form-result').remove();
            let data = $(this).serializeArray();
            $(this).addClass('sending block');
            ajaxCall($(this).attr('action'), data, '', {target: this});
        }
    });
    
    if($('.newsletter-popup').length) {
        $('.newsletter-popup').each(function(i, content){
            $.confirm({
                title: $(content).data('title'),
                content: content,
                autoClose: 'cancel|10000',
                useBootstrap: false,
                buttons: {
                    cancel: {
                        text: $(content).data('btn'),
                        action: function () {},
                    }
                }
            });
        });
    }
});

function newsletterCallback(response, params) {
    $(params.target).removeClass('sending');
    setTimeout(function(){$(params.target).removeClass('block');}, 600);
    if(response.result) {
        $(params.target).addClass('success');
        $(params.target).find("input[type=text], textarea, select").val('');
        $(params.target).find("input[type=checkbox]").prop(false);
    } else {
        $(params.target).addClass('error');
    }
    if(response.msg) {
        let div = $('<div></div>').addClass('field');
        div.append($('<div></div>').addClass('result alert alert-' + (response.result ? 'success' : 'error')).text(response.msg));
        $(params.target).prepend(div);
    }
    if(response.errors) {
        $.each(response.errors, function(label, error){
            $(params.target).find('input[name="' + label + '"],textarea[name="' + label + '"]').closest('.field').addClass('error');
            $(params.target).find('input[name="' + label + '"],textarea[name="' + label + '"]').closest('.field').append($('<div></div>').addClass('alert alert-error').text(error));
        })
    }
}