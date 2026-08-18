$(function () {
    $('form.form').on('submit', function (e) {
        e.preventDefault();
        if(!$(this).hasClass('block')) {
            $(this).removeClass('error success');
            $(this).find('.error').removeClass('error');
            $(this).find('.error-info').remove();
            $(this).find('.form-result').remove();
            let data = $(this).serializeArray();
            $(this).addClass('sending block');
            ajaxCall($(this).attr('action'), data);
        }
    });
});

function formCallback(response) {
    $('form.form-' + response.form).removeClass('sending');
    setTimeout(function(){$('.form-' + response.form).removeClass('block');}, 600);
    if(response.result) {
        $('.form-' + response.form).addClass('success');
        $('.form-' + response.form).find("input[type=text], textarea, select").val('');
        $('.form-' + response.form).find("input[type=checkbox]").prop(false);
    } else {
        $('.form-' + response.form).addClass('error');
    }
    if(response.msg) {
        $('.form-' + response.form).find('.field-box.submit').before($('<p></p>').addClass('form-result').text(response.msg));
    }
    if(response.errors) {
        $.each(response.errors, function(label, error){
            $('.form-' + response.form).find('input[name="' + label + '"],textarea[name="' + label + '"]').parent().addClass('error');
            $('.form-' + response.form).find('input[name="' + label + '"],textarea[name="' + label + '"]').after($('<span></span>').addClass('error-info').text(error));
        })
    }
}
