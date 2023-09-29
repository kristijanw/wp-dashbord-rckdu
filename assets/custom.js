(function ($) {

    $(window).on('load', function () {
        $('.pro-loader').hide();
    });


}) (jQuery);



function updateURL(key, val){
    var url = window.location.href;
    var reExp = new RegExp("[\?|\&]"+key + "=[0-9a-zA-Z\_\+\-\|\.\,\;]*");

    if(reExp.test(url)) {
        var reExp = new RegExp("[\?&]" + key + "=([^&#]*)");
        var delimiter = reExp.exec(url)[0].charAt(0);
        url = url.replace(reExp, delimiter + key + "=" + val);
    } else {
        var newParam = key + "=" + val;

        if( url.indexOf('?') == -1 ){
            url += '?';
        }

        if(url.indexOf('#') > -1){
            var urlparts = url.split('#');
            url = urlparts[0] +  "&" + newParam +  (urlparts[1] ?  "#" +urlparts[1] : '');
        } else {
            url += "&" + newParam;
        }
    }

    window.location = url;
    // window.history.pushState(null, document.title, url);
}