var AppConfig = {};
AppConfig.VALIDATION_ERROR_MESSAGE = 'Please fix the highlighted validation errors';
AppConfig.VALIDATION_INPUT_ERROR_ANIMATION = 'wobble';
AppConfig.VALIDATION_ERROR_ANIMATION = 'fadeInLeftBig';
var GCurrenURL = window.location.href;
var currentUrl = '';
var parts = window.location.search.substr(1).split("&");
var $_GET = {};
var initSelect2Ajax, init_decimal_inputs;
for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split("=");
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]) || "";
    if ($_GET[decodeURIComponent(temp[0])] == "undefined") $_GET[decodeURIComponent(temp[0])] = "";
}
const set_cookie = (cname, cvalue, seconds) => {
  var d = new Date();
  d.setTime(d.getTime() + (seconds * 1000));
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=Lax;";
}
const get_cookie = (cookiename, default_value = '') => {
  var cookiestring = RegExp("" + cookiename + "[^;]+").exec(document.cookie);
  var data = decodeURIComponent(!!cookiestring ? cookiestring.toString().replace(/^[^=]+./, "") : "");
  return data ? data : default_value;
}
setTimeout(() => {
    if (document.getElementById('HiddenAppBaseURL')) AppConfig.BASE_URL = document.getElementById('HiddenAppBaseURL').value;
}, 100);
function redirect(url, showloader = true) {
    if (showloader) show_loader();
    window.location.href = url;
}
function change_url(url) {
    window.history.pushState({}, "", url);
}
function add_param_to_url(key, value, url = '') {
    if (url == '') url = window.location.href;
    const NewURL = new URL(url);
    NewURL.searchParams.set(key, value);
    return NewURL;
}
const show_loader = () => {
	//document.getElementById('theme-loader').classList.remove('d-none');
};
const hide_loader = (delay = false) => {
    var delayTimeout = delay ? 10000 : 100;
    //setTimeout(() => document.getElementById('theme-loader').classList.add('d-none'), delayTimeout);
};
function unnotify() {
    $('[data-growl="container"]').remove();
}
var notify_timeout = null;
function notify(title, message, from, align, icon, type, animIn, animOut) {
    unnotify();
    clearTimeout(notify_timeout);
	notify_timeout = setTimeout( ()=> $._notify(title, message, from, align, icon, type, animIn, animOut), 500);
}
function decode_html_entities(text) {
    var entities = [ ['amp', '&'], ['apos', '\''], ['#x27', '\''], ['#x2F', '/'], ['#39', '\''], ['#47', '/'], ['lt', '<'], ['gt', '>'], ['nbsp', ' '], ['quot', '"'], ['\quot', '"'] ];
    for (var i = 0, max = entities.length; i < max; ++i) text = text.replace(new RegExp('&' + entities[i][0] + ';', 'g'), entities[i][1]);
    return text;
}

const to_time = (value)=>{
    const h = Math.floor(value / 3600);
    const m = Math.floor((value % 3600) / 60);
    const s = Math.floor(value % 60);

    let parts = [];
    if (h > 0) parts.push(`${h}h`);
    if (m > 0) parts.push(`${m}m`);
    if (s > 0 || parts.length === 0) parts.push(`${s}s`);

    return parts.join(' ');
}

window.addEventListener("pageshow", function (event) {
    var historyTraversal = event.persisted ||
        (typeof window.performance != "undefined" &&
            window.performance.navigation.type === 2);
    if (historyTraversal) {
        // Handle page restore.
        window.location.reload();
    }
});
$(document).ready(function() {
    hide_loader();

    $('body').tooltip({
        html: true,
        trigger: 'hover',
        selector: '.bs-tt'
    });
	
    $._notify = function(obj){
        var default_obj = {
        type: 'danger',
        title: 'Test Title',
        icon: 'fa fa-exclamation-triangle',
        message: '',
        from: 'bottom',
        align: 'right',
        delay: false,
        animIn: 'fadeInRight',
        animOut: 'fadeOutRight',
        mouse_over: false,
        allow_dismiss: true,
        url: ''
        };
        if (obj.hasOwnProperty('type') && obj.type == 'success' && !obj.hasOwnProperty('icon')) {
            obj.icon = 'fa fa-check-circle';
        }
        for(key in obj) default_obj[key] = obj[key];
        $.growl({
            icon: 'me-2 ' + default_obj.icon,
            title: default_obj.title,
            message: default_obj.message,
            url: default_obj.url
        },{
            element: 'body',
            type: default_obj.type,
            allow_dismiss: default_obj.allow_dismiss,
            placement: {
                from: default_obj.from,
                align: default_obj.align
            },
            offset: {
                x: 30,
                y: 30
            },
            spacing: 10,
            z_index: 999999,
            delay: default_obj.delay,
            timer: 1000,
            url_target: '_blank',
            mouse_over: default_obj.mouse_over,
            animate: {
                enter: 'animated ' + default_obj.animIn,
                exit: 'animated ' + default_obj.animOut
            },
            icon_type: 'class',
            template: '<div data-growl="container" class="alert" role="alert">' +
            '<button type="button" class="close mt-1 ml-1 text-white" data-growl="dismiss">' +
            '<i class="fa fa-times"></i>' +
            '<span class="sr-only">Close</span>' +
            '</button>' +
            '<span data-growl="icon"></span>' +
            '<span data-growl="title"></span>' +
            '<span data-growl="message"></span>' +
            '<a href="#" data-growl="url"></a>' +
            '</div>'
        });
    };

    $(document).on('click', '.confirm-action', function () {
        var t_dataset = this.dataset;
        var $this = $(this);
        var options = {
            title: 'Are you sure?',
            text: 'This action cannot be undone',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Proceed',
            cancelButtonText: 'Not Now'
        };
        if (t_dataset.title) {
            options.title = t_dataset.title;
        }
        if (t_dataset.text) {
            options.text = t_dataset.text;
        }
        if (t_dataset.type) {
            options.type = t_dataset.type;
        }
        if (t_dataset.yes_text) {
            options.confirmButtonText = t_dataset.yes_text;
        }
        if (t_dataset.no_text) {
            options.cancelButtonText = t_dataset.no_text;
        }
        options.customClass = {
            popup: "swal2-large-popup",
            title: "swal2-large-title",
            confirmButton: "swal2-large-button",
            cancelButton: "swal2-large-button",
        };
        Swal.fire(options).then((result) => {
            if (result.isConfirmed) {
                if(t_dataset.action == 'submit'){
                    show_loader();
                    $(`#${t_dataset.element_id}`)[0].submit();
                }else if(t_dataset.action == 'link'){
                    show_loader();
                    window.location.href = t_dataset.url;
                }else{
                    $(`#${t_dataset.element_id}`).trigger("lms.action.confirmed");
                }
            }
        });
    });

    $('.submitForm').submit(function (e) {
        e.preventDefault();
        show_loader();
        $('.submitForm button[type="submit"]').prop('disabled', true).html('<i class="fa fa-circle-notch fa-spin"></i> Please wait...');
        this.submit();
    });
});