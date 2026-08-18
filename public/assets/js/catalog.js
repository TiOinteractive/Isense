
$(function(){ 
    $('.catalog-form .field').on('change', function(e){
        e.preventDefault();
       if($('body.mobile').length>0) {
		  var spr=$(this).parent().hasClass('filter-cat');
		  if(spr) {
			$('.catalog-form').attr('action',$(this).val());
		  }
		  $('.catalog-form').submit();
	   }
       else {	   
		catalogForm($(this).closest('form'));
	   }	
    });
    $('.catalog-form').on('submit', function(e){
        e.preventDefault();
        catalogForm($(this));
    });
});

function catalogForm(form) {
    let url = $(form).attr('action');
    let data = $(form).serializeArray();
    let values = '';
    $.each(data, function(key, obj){
        if(obj.value) {
            values += '/' + obj.name + '/' + obj.value;
        }
    });
    if(values) {
        url += '/g' + values;
    }
    window.location.href = url;
}