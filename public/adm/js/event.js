$(function(){
    $('.add-event-hour').on('click', function(e){
        e.preventDefault();
        ajaxCall($(this).attr('href'), {}, 'addEventHour', {target: this});
    });

    $(document).on('click', '.delete-event-hour', function(e){
        e.preventDefault();
        $(this).closest('.event-hour').remove();
    });
});

function addEventHour(obj, params) {
    $(params.target).before(obj.html);
    initializeDataPicker($(params.target).prev());
}