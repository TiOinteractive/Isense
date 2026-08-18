$(function(){
    $('.banner-count-clicks').on('click', function(e){
        //e.preventDefault();
        ajaxCall(window.location.pathname, {action: 'click', content: $(this).closest('.banner-section').data('page-cont'), url: $(this).attr('href'), id: $(this).data('id')}, 'eventCalendarReload', {target:$(this).closest('.calendar-box')});
    });
});