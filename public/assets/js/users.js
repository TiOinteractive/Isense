$(function(){
    $('.data-copies .file.html a').on('click', function(e){
        e.preventDefault();
        let url = $(this).attr('href');
        ajaxCall(url, {aa:'aa'}, 'showCopyData', {target: this});
    });
});

function showCopyData(obj, params) {
    let modal = $.confirm({
        title: $(params.target).data('title'),
        content: obj.html,
        type: 'orange',
        boxWidth: '500px',
        useBootstrap: false,
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            close: {
                text: $(params.target).data('close'),
                action: function() {

                }
            },
        }
    });
}

function onSignIn(response) {
  console.log(onSignIn);
}