!function(a,b,c,d){a.throttle=function(a,b,c,e){function f(){function f(){h=+new Date,c.apply(j,l)}function i(){g=d}var j=this,k=+new Date-h,l=arguments;e&&!g&&f(),g&&clearTimeout(g),e===d&&k>a?f():!0!==b&&(g=setTimeout(e?i:f,e===d?a-k:a))}var g,h=0;return"boolean"!=typeof b&&(e=c,c=b,b=d),f},a.debounce=function(b,c,e){return e===d?a.throttle(b,c,!1):a.throttle(b,e,!1!==c)}}(jQuery,window,document);
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
    }
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});