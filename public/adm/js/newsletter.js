$(function(){
    $('.newsletter-send-form .set-sending').on('click', function(e){
        e.preventDefault();
        let form = $(this).parents('form.newsletter-send-form');
        let data = $(form).serializeArray();
        ajaxCall($(this).attr('href'), data, 'sentSendingResult', {target: form, data:data});
    });
    $('.newsletter-send-form button[type="submit"].send').on('click', function(e){
        e.preventDefault();
        let form = $(this).parents('form.newsletter-send-form');
        let data = $(form).serializeArray();
        ajaxCall($(form).data('check-url'), data, 'checkNewsletterSending', {target: form, data:data});
    });
    $('.newsletter-send-form .send-test').on('click', function(e){
        e.preventDefault();
        let form = $(this).parents('form.newsletter-send-form');
        let data = $(form).serializeArray();
        ajaxCall($(this).attr('href'), data, 'sendTestResult', {target: form, data:data});
    });
    $('.newsletters-list.list .in-progress .newsletter-progress-box').each(function(e){
        updateSendingStatusOnList(null, {target:this});
    });
});

function checkNewsletterSending(obj, params) {
    let content = '<p>' + obj.msg + '</p>';
    let buttons = {};
    if(obj.emails) {
        content += '<p>' + obj.msg2 + '</p>';
        content+= '<div class="newsletter-progress-box"><div class="progress-bar"><div class="bar"></div><span class="percentage">0%</span></div><div class="stats">0/' + obj.emails + '</div></div>';
        buttons.ok = {
            text: $(params.target).data('btn-ok'),
            btnClass: 'btn-blue',
            action: function(e) {
                this.$content.find('.newsletter-progress-box').addClass('show');
                params.modal = this;
                ajaxCall($(params.target).attr('action'), params.data, 'finalizeSending', params);
                setTimeout(function(){showSendingStatus(null, params);}, 2000);
                this.buttons.ok.hide();
                this.buttons.cancel.hide();
                return false;
            }
        };
    }
    buttons.cancel = {
        isHidden: !obj.emails ? true : false,
        text: $(params.target).data('btn-cancel')
    };
    buttons.close = {
        isHidden: obj.emails ? true : false,
        text: $(params.target).data('btn-close')
    };
    $.confirm({
        title: $(params.target).data('title'),
        content: content,
        backgroundDismiss: false,
        autoClose: obj.status ? 'cancel' : 'cancel|20000',
        type: obj.status ? 'blue': 'red',
        useBootstrap: false,
        buttons: buttons
    });
}

function showSendingStatus(obj, params) {
    if(!params.modal.$content.find('.newsletter-progress-box').hasClass('sent')) {
        if(obj) {
            if(obj.status) {
                params.modal.$content.find('.newsletter-progress-box .bar').css('width', (obj.count * 100 / obj.emails) + '%');
                params.modal.$content.find('.newsletter-progress-box .percentage').text((obj.count * 100 / obj.emails) + '%');
                params.modal.$content.find('.newsletter-progress-box .stats').text(obj.count + '/' + obj.emails);
                setTimeout(function(){showSendingStatus(null, params);}, 2000);
            } else {
                params.modal.$content.find('.newsletter-progress-box').addClass('sent');
            }
        } else {
            ajaxCall($(params.target).data('status-url'), {}, 'showSendingStatus', params, 'GET');
        }
    }
}

function finalizeSending(obj, params) {
    params.modal.$content.find('.newsletter-progress-box .bar').css('width', (obj.count * 100 / obj.emails) + '%');
    params.modal.$content.find('.newsletter-progress-box .percentage').text((obj.count * 100 / obj.emails) + '%');
    params.modal.$content.find('.newsletter-progress-box .stats').text(obj.count + '/' + obj.emails);
    setTimeout(function(){showSendingStatus(null, params);}, 2000);
    params.modal.buttons.close.show();
    params.modal.$content.find('.newsletter-progress-box').addClass('sent');
    let content = '<p>' + obj.msg + '</p>';
    $.confirm({
        title: $(params.target).data('title'),
        content: content,
        backgroundDismiss: true,
        autoClose: 'close',
        boxWidth: '400px',
        type: obj.status ? 'green': 'red',
        useBootstrap: false,
        buttons: {
            close: {
                text: $(params.target).data('btn-close'),
                action: function(e) {
                    params.modal.close();
                }
            }
        }
    });
}

function sendTestResult(obj, params) {
    let content = '<p>' + obj.msg + '</p>';
    if(obj.debug) {
        content += obj.debug;
    }
    $.confirm({
        title: $(params.target).data('title'),
        content: content,
        backgroundDismiss: true,
        autoClose: 'close',
        boxWidth: '400px',
        type: obj.status ? 'green': 'red',
        useBootstrap: false,
        buttons: {
            close: {
                text: $(params.target).data('btn-close')
            }
        }
    });
}

function sentSendingResult(obj, params) {
    let content = '<p>' + obj.msg + '</p>';
    if(obj.debug) {
        content += obj.debug;
    }
    $.confirm({
        title: $(params.target).data('title'),
        content: content,
        backgroundDismiss: true,
        autoClose: 'close',
        boxWidth: '400px',
        type: obj.status ? 'green': 'red',
        useBootstrap: false,
        buttons: {
            close: {
                text: $(params.target).data('btn-close')
            }
        }
    });
}

function updateSendingStatusOnList(obj, params) {
    if(obj) {
        if(obj.status) {
            $(params.target).find('.bar').css('width', Math.round(obj.count * 100 / obj.emails) + '%');
            $(params.target).find('.percentage').text(Math.round(obj.count * 100 / obj.emails) + '%');
            //$(params.target).find('.stats').text(obj.count + '/' + obj.emails);
            if(obj.history_status == 'set' || obj.history_status == 'sending') {
                updateSendingStatusOnList(null, params);
            }
        } else {
            $(params.target).addClass('sent');
            $(params.target).parent().find('.cancel-btn').remove();
        }
    } else {
        setTimeout(function(){ajaxCall($(params.target).data('status-url'), {}, 'updateSendingStatusOnList', params, 'GET');}, 30000);
    }
}