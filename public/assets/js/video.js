let players = [];
$(function () {
    /* YouTube start */
    if ($('.video-external.youtube').length) {
        let tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        let firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        
    }
    /* YouTube end */
    
    /* Viemo start */
    if ($('.video-external.vimeo').length) {
        $('.video-external.vimeo').each(function() {
            let url = $(this).data('url');
            let auto = $(this).hasClass('auto');
            let loop = $(this).hasClass('loop');
            let mute = $(this).hasClass('mute');
            let controls = $(this).hasClass('controls');
            let player = this;
            if(url) {
                $.ajax({
                    url: "https://vimeo.com/api/oembed.json?url=" + url + "&controls=" + (controls ? "true" : "false") + (auto ? "&autoplay=true&muted=true" : "") + (loop ? "&loop=true" : "") + (loop ? "&muted=true" : ""),
                    type: 'GET',
                    crossDomain: true,
                    dataType: 'jsonp',
                    success: function (response) {
                        $(player).html(response.html);
                    },
                    error: function (err) {
                        console.log(err)
                    }
                });
            }
        });
    }
    /* Viemo end */
    
    /* Dailymotion start */
    if ($('.video-external.dailymotion').length) {
        $('.video-external.dailymotion').each(function() {
            let url = $(this).data('url');
            let auto = $(this).hasClass('auto');
            let player = this;
            if(url) {
                $.ajax({
                    url: "https://www.dailymotion.com/services/oembed?url=" + url + "&autoplay=true",
                    type: 'GET',
                    crossDomain: true,
                    dataType: 'jsonp',
                    success: function (out) {
                        $(player).html(out.html);
                    },
                    error: function (err) {
                        console.log(err)
                    }
                });
            }
        });
    }
    /* Dailymotion end */
});

function onYouTubeIframeAPIReady() {
    $('.video-external.youtube').each(function () {
        let ext_id = $(this).data('id');
        let loop = $(this).hasClass('loop');
        let auto = $(this).hasClass('auto');
        let mute = $(this).hasClass('mute');
        let controls = $(this).hasClass('controls');
        let id = $(this).attr('id');
        players[ext_id] = new YT.Player(id, {
            videoId: ext_id,
            playerVars: { 
                'autoplay': auto ? 1 : 0, 
                'controls': controls ? 1 : 0 ,
                'loop': loop ? 1 : 0 ,
                'rel' : 0,
                'showinfo' : 0,
            },
            events: {
                'onReady': function(event) {
                    if(mute) {
                        players[ext_id].mute();
                    }
                    if(auto) {
                        players[ext_id].mute();
                        players[ext_id].playVideo();
                    }
                    $('#' + id).parents('.item.video-item').addClass('ready');
                },
                'onStateChange': function(event) {
                    if(loop && event.data == YT.PlayerState.ENDED) {
                        players[ext_id].seekTo(0);
                        players[ext_id].playVideo();
                    }
                }
            }
        });
    });
}