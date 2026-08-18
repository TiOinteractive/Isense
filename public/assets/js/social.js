(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk')
);

$(function(){
    $(document).on('click', '.fb-login-btn', function(){
        let button = this;
        FB.login(function (response) {
            if (response.status == 'connected' && response.authResponse) {
                FB.api('/me', {fields: 'email,last_name,first_name'}, function (response2) {
                    ajaxCall($(button).closest('.login-form').find('form').attr('action'), {action: 'facebook', access_token: response.authResponse.accessToken, user: response2}, 'socialCallback', {target: $(button).closest('.login-form').find('form')});
                });
            } else {
                console.log('User cancelled login or did not fully authorize.');
            }
        }, {
            scope: 'public_profile,email',
            return_scopes: true
        });
    });
});

window.fbAsyncInit = function () {
    FB.init({
        appId: '916589709881553',
        cookie: true,
        xfbml: true,
        version: 'v19.0'
    });
};
  
function socialCallback(obj, params) {
    if(obj.result) {
        let redirect = $(params.target).find('input[name="return"]').val();
        if(redirect) {
            location.href = redirect;
        } else {
            location.reload();
        }
    }
}

function goggleLogin(response) {
    ajaxCall('/logowanie', {action: 'google', credential: response.credential}, 'socialCallback', {target: ''});
}