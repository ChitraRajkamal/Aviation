var AppConfig = {};
AppConfig.VALIDATION_ERROR_MESSAGE = 'Please fix the highlighted validation errors';
AppConfig.VALIDATION_INPUT_ERROR_ANIMATION = 'wobble';
AppConfig.VALIDATION_ERROR_ANIMATION = 'fadeInLeftBig';
var GCurrenURL = window.location.href;
var currentUrl = '';
AppConfig.IS_SKELETON_LESS = GCurrenURL.toLowerCase().includes('_skeletonless_');
AppConfig.SKELETON_LESS_ERROR_MSG_DELAY = 3000;
var parts = window.location.search.substr(1).split("&");
var $_GET = {};
var _IntroJs = {};
var initSelect2Ajax, init_decimal_inputs;
for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split("=");
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]) || "";
    if ($_GET[decodeURIComponent(temp[0])] == "undefined") $_GET[decodeURIComponent(temp[0])] = "";
}
setTimeout(() => {
    if (document.getElementById('HiddenAppBaseURL')) AppConfig.BASE_URL = document.getElementById('HiddenAppBaseURL').value;
    if (document.getElementById('PreviewImagePlaceholder')) AppConfig.PREVIEW_IMAGE_PLACEHOLDER = document.getElementById('PreviewImagePlaceholder').value;
}, 100);
function set_cookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function get_cookie(cookiename) {
    var cookiestring = RegExp("" + cookiename + "[^;]+").exec(document.cookie);
    return decodeURIComponent(!!cookiestring ? cookiestring.toString().replace(/^[^=]+./, "") : "");
}
function redirect(url, showloader = true) {
    if (showloader) show_loader();
    window.location.href = url;
}
function redirect_if_not_iframe() {
    if (window === window.parent) {
        show_loader();
        setTimeout(() => window.location.href = AppConfig.BASE_URL, 100);
    }
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
	document.getElementById('theme-loader').classList.remove('d-none');
};
const hide_loader = (delay = false) => {
    var delayTimeout = delay ? 10000 : 100;
    setTimeout(() => document.getElementById('theme-loader').classList.add('d-none'), delayTimeout);
};
const convert_bytes_to_mb = (value) => {
    return value > 0 ? (value/(1024*1024)) : 0;
};
const show_two_decimal = (value) => isNaN(value) ? value : (Math.round(value * 100) / 100).toFixed(2);
const show_zero_if_negative = (value) => (value > 0 ? value : 0);
const get_percentage = (dividend, divisor) => ((dividend * 100) / divisor);
const get_value_from_percentage = (value, percentage) => ((value * percentage) / 100);
const get_inclusive_value_from_percentage = (value, percentage) => (value * percentage / (100 + percentage));
function unnotify() {
    $('[data-growl="container"]').remove();
}
var notify_timeout = null;
function notify(title, message, from, align, icon, type, animIn, animOut) {
    unnotify();
    clearTimeout(notify_timeout);
	notify_timeout = setTimeout( ()=> $._notify(title, message, from, align, icon, type, animIn, animOut), 500);
}
function set_active_menu(url = '') {
    setTimeout(() => {
        currentUrl = AppConfig.BASE_URL + '/' + url;
        $(document).trigger('set_active_menu')
    }, 500);
}
function decode_html_entities(text) {
    var entities = [ ['amp', '&'], ['apos', '\''], ['#x27', '\''], ['#x2F', '/'], ['#39', '\''], ['#47', '/'], ['lt', '<'], ['gt', '>'], ['nbsp', ' '], ['quot', '"'], ['\quot', '"'] ];
    for (var i = 0, max = entities.length; i < max; ++i) text = text.replace(new RegExp('&' + entities[i][0] + ';', 'g'), entities[i][1]);
    return text;
}
function get_standard_date(input_date) { // MM/DD/YYYY
    const yyyy = input_date.getFullYear();
    let mm = input_date.getMonth() + 1; // Months start at 0!
    let dd = input_date.getDate();

    mm = mm.toString().padStart(2, '0');
    dd = dd.toString().padStart(2, '0');

    return mm + '/' + dd + '/' + yyyy;
}
function random_string(length) {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567899876543210zyxwvutsrqponmlkjihgfedcbaZYXWVUTSRQPONMLKJIHGFEDCBA';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
    }
    return result;
}
String.prototype.customTrimLeft = function(charlist) {
	if (charlist === undefined)
	charlist = "\s";
	return this.replace(new RegExp("^[" + charlist + "]+"), "");
};
String.prototype.customTrimRight = function(charlist) {
	if (charlist === undefined)
	charlist = "\s";
	return this.replace(new RegExp("[" + charlist + "]+$"), "");
};

window.addEventListener('message', (event) => {
    /*if (event.origin !== 'https://example.com') {
        console.warn('Origin not allowed:', event.origin);
        return;
    }*/
    if(event.data.closeModal){
        if($(`#${event.data.closeModal}`).length) $(`#${event.data.closeModal}`).modal('hide');
    }
});

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

    //Bootstrap Tooltip
    $('body').tooltip({
        html: true,
        trigger: 'hover',
        selector: '.bs-tt'
    });

    //This is to hide parent container tooltip when hovering on child which also have tooltip
    $('.bs-tt').hover(
        function () {
            if ($(this).parents('.parent-bs-tt').length > 0) $(this).parents('.parent-bs-tt').tooltip('hide');
        }, function () {
            if ($(this).parents('.parent-bs-tt').length > 0) $(this).parents('.parent-bs-tt').tooltip('show');
        }
    );

    if ($('.show_two_decimal_text').length > 0) {
        $('.show_two_decimal_text').each(function () {
            this.innerHTML = show_two_decimal(this.innerHTML);
        });
    }

    $('.closeModalFromIframe').click(function() {
        window.parent.postMessage({
            closeModal: this.dataset.modal
        }, '*');
    });

    /*$('[data-toggle="tooltip"], .bs-tt').on('shown.bs.tooltip', function () {
        $('.tooltip').addClass('animated fadeInDown');
    });*/

    //Bootstrap Selec2
    if ($('.select-two').length > 0) {
        $('.select-two.s2-allow-new-data').each(function () {
            var thisId = this.id;
            var c_select2_obj = {
                //minimumResultsForSearch: -1, // This is to remove search box
                //allowClear: true,
                language: {
                    noResults: function (e) {
                        if ($('#' + thisId).length > 0 && $('#' + thisId).prev('.btn-open-skeleton-less-form').length > 0) {
                            return `<div class="text-center">No results found.<br><b data-id="${thisId}" data-name="${event.target.value}" class="s2-open-btn-skeleton-less cursor-pointer text-primary text-underline">Create "${event.target.value}"</b><br>Shortcut : <b>[Ctrl + Enter]</b></div>`;
                        } else {
                            return 'No results found';
                        }
                    },
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
            };
            $(this).select2(c_select2_obj);
        });

        $(document).on('mouseenter', '.btn-add, .btn-remove', function () {
            $(this).tooltip('dispose').tooltip({
                trigger: 'hover',
                title: $(this).hasClass('btn-add') ? 'Add New Item' : 'Remove Item',
                placement: "top",
            });
            $(this).tooltip('show');
        });

        $(document).on('keyup', 'input.select2-search__field', function (e) {
            if (e.ctrlKey && e.keyCode == 13) {
                var ariaControlsId = $(this).attr('aria-controls');
                var elementId = ariaControlsId.split('-')[1] || '';
                if ($('#' + elementId).hasClass('s2-allow-new-data') && $(`#${ariaControlsId} .s2-open-btn-skeleton-less`).length > 0) {
                    $(`#${ariaControlsId} .s2-open-btn-skeleton-less`).trigger('click');
                }
            }
        });

        $(document).on('click', '.s2-open-btn-skeleton-less', function () {
            var thisId = $(this).data('id');
            if ($('#' + thisId).length > 0) {
                $('#' + thisId).select2("close");
                $('#' + thisId).prev('.btn-open-skeleton-less-form').trigger('click', [{
                    source: 'from_select2', //Don't change this because it is used in skl less
                    name: $(this).data('name')
                }]);
            }
        });

        $('.select-two:not(.s2-allow-new-data)').each(function () {
            var c_select2_obj = {};
            if ($(this).data('placeholder')) {
                c_select2_obj.placeholder = $(this).data('placeholder');
            }
            if ($(this).data('allow_clear')) {
                c_select2_obj.allowClear = $(this).data('allow_clear');
            }
            $(this).select2(c_select2_obj);
        });
        
        $('.select-two').on('focus', function () {
            $(this).select2('open');
        });
        $(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
            $(this).closest(".select2-container").siblings('select:enabled').select2('open');
        });
    }

    //Bootstrap Selec2 Ajax
    if ($('.select-two-ajax').length > 0) {
        initSelect2Ajax = (ths) => {
            var options = {};
            options.minimumInputLength = ths.data("min_input") ? ths.data("min_input") : 2;
            //options.maximumResultsForSearch = ths.data("max_result") ? ths.data("max_result") : 5;
            options.language = {
                searching: function () {
                    return "Fetching...";
                },
                noResults: function () {
                    return `No ${ths.data('object') ?? 'data'} found for the input "${select2AjaxQuery}"`;
                }
            };
            options.templateResult = select2TemplateResult;
            options.templateSelection = select2TemplateSelection;
            options.ajax = {
                url: ths.data("ajax"),
                type: "POST",
                data: function (term, page) {
                    ths.trigger("select_ajax_request_started");
                    select2AjaxQuery = term.term;
                    var q_obj = {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        query: term.term,
                        extra: true
                    };
                    /*if (ths.data('add_unit') == true) {
                        q_obj.unit = 1;
                    }*/
                    return q_obj;
                },
                dataType: 'JSON',
                delay: 500,
                processResults: function (data) {
                    ths.trigger("select_ajax_request_ended");
                    return { results: data.items };
                },
                cache: true
            };
            $(ths).select2(options);
        }
        function select2TemplateResult(item) {
            //return $('<span>' + item.text + '</span>'); //This is for html rendering
            return item.text;
        }
        function select2TemplateSelection(item) {
            if (typeof item.UnitName !== 'undefined') {
                $(item.element).attr("data-unit_name", item.UnitName);
                $(item.element).trigger('select2_apply_unit');
            }
            return (item.textSelection || item.text) || 'Search';
        }

        var select2AjaxQuery = '';
        $('.select-two-ajax').each(function () {
            initSelect2Ajax($(this));
        });
        
        $('.select-two-ajax').on('focus', function () {
            $(this).select2('open');
        });
    }    

    function initScrollToFixed() {
        if ($('.fixed-submit-container').length > 0) {
            $('.fixed-submit-container').scrollToFixed({
                bottom: 0,
                //limit: $('.fixed-submit-container').offset().top
            });
        }
    }
    initScrollToFixed();

    function refreshScrollToFixed() {
        $('.fixed-submit-container').trigger('detach.ScrollToFixed');
        initScrollToFixed();
    }

    //Show loader if inbuilt validation is successful
    $('.inbuilt-form').submit(function (e) {
        //console.log($(this).data('unobtrusiveValidation'));
        //console.log($(this).valid());
        //e.preventDefault();
        var validator = $(this).data('validator');
        //console.log(validator);
        $('.help-block.custom--element').children('.field-validation-error').children('span').text(''); // Workaround for Custom Field Elements
        if (validator != null && validator.errorList.length > 0) {
            //console.log(validator.errorList[0]);
            validator.errorList[0].element.focus();
            for (var objIndex in validator.errorList) {
                var invObj = validator.errorList[objIndex];
                if (invObj.element.id) {
                    $(invObj.element).next('.help-block').children('.field-validation-error').children(`span[id^=${invObj.element.id}]`).text(invObj.message);
                }
            }
            refreshScrollToFixed();
        }
        if ($(this).find('.input-validation-error').length == 0) {
            if ($(this).hasClass('show-loader')) show_loader();
        } else {
            notify({ title: AppConfig.VALIDATION_ERROR_MESSAGE, animIn: AppConfig.VALIDATION_ERROR_ANIMATION, delay: (AppConfig.IS_SKELETON_LESS ? AppConfig.SKELETON_LESS_ERROR_MSG_DELAY : false) });
            $('.input-validation-error').siblings('.help-block').addClass(`animated ${AppConfig.VALIDATION_INPUT_ERROR_ANIMATION}`);
            setTimeout(() => $('.help-block').removeClass(`animated ${AppConfig.VALIDATION_INPUT_ERROR_ANIMATION}`), 1000);
            setTimeout(() => {
                //$(this).find('.input-validation-error:first').focus();
            }, 500);
            refreshScrollToFixed();
        }
    });
    /*$('.inbuilt-form').on('invalid-form.validate', function (e) {
        var validator = $(this).data('validator');
        console.log(validator);
        if (validator.numberOfInvalids() > 0) {
            //console.log(validator.errorList[0]);
            validator.errorList[0].element.focus();
            for (var objIndex in validator.errorList) {
                var invObj = validator.errorList[objIndex];
                $(invObj.element).next('.help-block').children('.field-validation-error').children(`span[id^=${invObj.element.id}]`).text(invObj.message);
            }
        }
    });*/

    //Set active menu
    setTimeout(() => {
        currentUrl = location.href.customTrimRight('#').toLowerCase();
        currentUrl = currentUrl.split('?')[0];
        currentUrl = currentUrl.replace(/\/\d+(\/edit)?$/, '');
        if (currentUrl.includes('/create')) {
            currentUrl = currentUrl.split('/create')[0];
        } else if (currentUrl.includes('/create')) {
            currentUrl = currentUrl.split('/create')[0];
        } 
    }, 10);
    
    $(document).on('set_active_menu', function () {
        setTimeout(() => {
            $('.pcoded-submenu li.active').removeClass('active');
            $('.mCSB_container ul li a, .mCSB_container ul.pcoded-submenu li a').each(function () {
                if (currentUrl.toLowerCase() == $(this).prop('href').toLowerCase()) {
                    if ($(this).parent().parent().hasClass('pcoded-submenu')) {
                        $(this).parent().parent().parent().addClass('pcoded-trigger');
                    }
                    $(this).parent().addClass('active');
                    return;
                }
            });
        }, 10);
    });
    $(document).trigger('set_active_menu');

    if ($('.decimal_input, .decimal_input_1_to_100').length > 0) {
        $('.decimal_input').autoNumeric("init", {
            decimalCharacter: '.',
            currencySymbol: ' '
        });

        $('.decimal_input_1_to_100').autoNumeric("init", {
            decimalCharacter: '.',
            currencySymbol: ' ',
            vMax: '100.00',
            vMin: '0.00'
        });
    }

    $(document).on('focusin', '.decimal_input, .decimal_input_1_to_100', function () {
        if (this.getAttribute('readonly') == null) if (this.value == '0.00' || parseFloat(this.value || 0) == 0) this.value = '';
    });

    $(document).on('focusout', '.decimal_input, .decimal_input_1_to_100', function () {
        if (this.value == '') this.value = '0.00';
    });

    init_decimal_inputs = () => {
        $('input[class*=decimal_input').each(function () {
            if (parseFloat(this.value || 0) == 0) this.value = '0.00';
        });
    }

    init_decimal_inputs();

    $(document).on("click", ".g-link", function () {
        window.open(this.href, ($(this).prop('target') ?? '_blank'));
    });

    if ($('.bs-confirm-action').length > 0) {

        var g_confirm_modal_target;
        $(document).on("click", ".bs-confirm-action", function () {
            g_confirm_modal_target = $(this);
            show_confirm_modal();
        });
        function show_confirm_modal() {
            var gmtt = g_confirm_modal_target;
            if (gmtt.data('title')) $("#ConfirmModal .modal-title").html(gmtt.data('title'));
            if (gmtt.data('message')) $("#ConfirmModal .confirm-message").html(gmtt.data('message'));
            if (gmtt.data('yes')) $("#ConfirmModal .yes").html(gmtt.data('yes'));
            if (gmtt.data('no')) $("#ConfirmModal .no").html(gmtt.data('no'));
            var gmtt_bg = gmtt.data('bg');
            if (!gmtt_bg) gmtt_bg = 'bg-danger';
            $("#ConfirmModal .modal-header").prop('class', 'modal-header text-light ' + gmtt_bg);
            var gmtt_btn_bg = gmtt.data('btn_bg');
            if (!gmtt_btn_bg) gmtt_btn_bg = 'btn-danger';
            $("#ConfirmModal .yes").prop('class', 'btn yes ' + gmtt_btn_bg);
            $("#ConfirmModal").modal('show');
        }
        $('#ConfirmModal .yes').click(function () {
            $("#ConfirmModal").modal('hide');
            if (g_confirm_modal_target.data('load') == true) {
                if (g_confirm_modal_target.data('target') == "new_tab") {
                    window.open(g_confirm_modal_target.data('href'));
                } else {
                    show_loader();
                    location.href = g_confirm_modal_target.data('href');
                }
            } else {
                g_confirm_modal_target.trigger("custom_action_confirmed");
            }
        });
        $('#ConfirmModal .no').click(function () {
            g_confirm_modal_target.trigger("custom_action_cancelled");
        });
    }
    $(document).on('change', '.country_has_state', function () {
        var $this = $(this);
        $('.dynamic_state').empty().append('<option value="">--- Choose an option ---</option>');
        if ($('.state_has_city').length > 0) $('.state_has_city').trigger('change');
        if (this.value == '0' || this.value == '') return false;
        try {
            show_loader();
            $.post(AppConfig.BASE_URL + '/Ajax/GetStatesByCountryId', { Id: this.value }, function (result) {
                hide_loader();
                if (result.Status == "success") {
                    if (result.Data.length > 0) {
                        for (i = 0; i < result.Data.length; i++) {
                            $('.dynamic_state').append(`<option value="${result.Data[i].Id}">${result.Data[i].Text}</option>`);
                        }
                        if ($this.data('focus') !== false) $('.dynamic_state').focus();
                    }
                } else { 
                    notify({ title: result.Message, animIn: AppConfig.VALIDATION_ERROR_ANIMATION });
                }
            }, 'JSON');
        }
        catch (err) {

        }
        finally {
            hide_loader(true);
        }
    });

    $(document).on('change', '.state_has_city', function () {
        var $this = $(this);
        $('.dynamic_city').empty().append('<option value="">--- Choose an option ---</option>');
        if (this.value == '0' || this.value == '') return false;
        try {
            show_loader();
            $.post(AppConfig.BASE_URL + '/Ajax/GetCitiesByStateId', { Id: this.value }, function (result) {
                hide_loader();
                if (result.Status == 'error') {
                    notify({ title: result.Message });
                    return false;
                }
                if (result.Data.length > 0) {
                    for (i = 0; i < result.Data.length; i++) {
                        $('.dynamic_city').append(`<option value="${result.Data[i].Id}">${result.Data[i].Text}</option>`);
                    }
                    if ($this.data('focus') !== false) $('.dynamic_city').focus();
                }
            }, 'JSON');
        }
        catch (err) {

        }
        finally {
            hide_loader(true);
        }
    });

    $(document).on('keyup input', '.validate-number', function () {
        var $this = $(this);
        var CurrentValue = parseFloat(this.value || 0);
        if ($this.attr('data-minvalue') != undefined) {
            var MinValue = parseFloat($this.attr('data-minvalue') || 0);
            if (CurrentValue < MinValue) {
                notify({
                    title: $this.attr('data-message') ? $this.attr('data-message') : `Value must not be less than ${MinValue}`,
                    animIn: AppConfig.VALIDATION_ERROR_ANIMATION
                });
                this.value = MinValue;
            }
        }
        if ($this.attr('data-maxvalue') != undefined) {
            var MaxValue = parseFloat($this.attr('data-maxvalue') || 0);
            if (CurrentValue > MaxValue) {
                notify({
                    title: $this.attr('data-message') ? $this.attr('data-message') : `Value must not be greater than ${MaxValue}`,
                    animIn: AppConfig.VALIDATION_ERROR_ANIMATION
                });
                this.value = MaxValue;
            }
        }
    });

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

    //$('#ConfirmModal').modal('show');

    if ($('.g-datatable').length > 0) {
        $('.g-datatable').DataTable({
            "order": [],
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": true,
            "dom": '<"row top-filter"<"col-12 col-md-6 pl-3 pl-md-0 d-flex d-md-block"Bi><"col-12 col-md-6 pl-3 pl-md-0 pr-0 mt-2 d-flex d-md-block"f>>',
            "language": {
                "info": `<div class="row" style="line-height: 24px;">
                        <div class="col-12 col-md pr-md-1"><i class="fa fa-info-circle"></i> Current Page has _TOTAL_ records</div>
                        <div class="col-12 col-md px-0 d-none d-md-block"><b>|</b></div>
                        <div class="col-12 col-md pl-md-1"><i class="fa fa-info-circle"></i> Click on <b class="cursor-help dotted-border-bottom bs-tt" title="Click any one of the following column headings to sort by that">Column Heading</b> to sort</div>
                    </div>`,
            }
            /*"dom": '<"top"><"customdttoolbar text-right">ifrt<"bottom"><"clear">', //lfrtip
            initComplete: function () {
                if ($('#add-to-customdttoolbar').length > 0) {
                    $(".customdttoolbar").html($('#add-to-customdttoolbar').children());
                    $('#add-to-customdttoolbar').remove();
                }
            }*/
        });
        $('.dataTables_filter [type="search"]').prop('placeholder', 'Search');
    }

    //Show loader on click
    $('.sloc[href!="#"], .mCSB_container li:not(.pcoded-hasmenu) > a').click(function (e) {
        if (!e.ctrlKey) show_loader();
    });
    $(document).on('click', '.sloc[href!="#"]', function (e) {
        if (!e.ctrlKey) show_loader();
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
            icon: 'mr-2 ' + default_obj.icon,
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

    $(document).on("keypress", ".numbers_only", function (e) {
        if (String.fromCharCode(e.keyCode).match(/[^0-9]/g) && e.keyCode != 13 && e.keyCode != 9 && ($(this).data('exclude') == undefined || (!$(this).data('exclude').toString().split(',').includes(`${e.keyCode}`)) || this.value.toString().includes(':'))) return false;
    });

    if ($('.bs-validate-form').length > 0) {
        $(".bs-validate-form").each(function () {
            var ths = $(this);
            $(ths).submit(function (e, manual) {
                e.preventDefault();
                if (!ths[0].id) notify({ title: `One or more forms don't have ID attribute` });
                unnotify();
                $(ths).validator("validate");
                if ($("#" + ths[0].id + " .has-error:not(.d-none):visible").length == 0 && $("#" + ths[0].id + " button.disabled").length == 0) {
                    var CanSubmit = ths.data("submit");
                    if (CanSubmit == undefined) CanSubmit = true;
                    if (CanSubmit && manual == undefined) {
                        var CanLoad = ths.data("load");
                        if (CanLoad == undefined) CanLoad = true;
                        if (CanLoad) show_loader();
                        this.submit();
                    } else {
                        ths.trigger("forms_validated", [manual]);
                    }
                } else {
                    $("#" + ths[0].id + " .has-error:not(.d-none)").find('.help-block').addClass(`animated ${AppConfig.VALIDATION_INPUT_ERROR_ANIMATION}`);
                    setTimeout(() => $('.help-block').removeClass(`animated ${AppConfig.VALIDATION_INPUT_ERROR_ANIMATION}`), 1000);
                    $("#" + ths[0].id + " .has-error:not(.d-none)").each(function () {
                        $(this).find(".form-control").focus();
                        ths.trigger("forms_not_validated", [manual]);
                        notify({ title: AppConfig.VALIDATION_ERROR_MESSAGE, animIn: AppConfig.VALIDATION_ERROR_ANIMATION/*, delay: (AppConfig.IS_SKELETON_LESS ? AppConfig.SKELETON_LESS_ERROR_MSG_DELAY : false)*/ });
                        return false;
                    });
                    refreshScrollToFixed();
                }
            });
        });
    }

    if ($('[data-parsley-validate]').length > 0) {
        $('[data-parsley-validate]').parsley({
            excluded: 'input:hidden, input:disabled, .d-none input, .d-none select, .d-none textarea',
            // Custom error placement for Select2
            errorsContainer: function (ParsleyField) {
                if (ParsleyField.$element.hasClass('select-two')) {
                    // Place error messages after the Select2 container
                    return ParsleyField.$element.siblings('.select2-container');
                }
                return undefined;
            }
        });

        $('[data-parsley-validate]').submit(function (e) {
            e.preventDefault();
            if ($(this).parsley().isValid()) {
                $('#hf-save-and-new').val('');
                if($('#save-and-new, #hf-save-and-new').length == 2){
                    var clickedButton = $(e.originalEvent.submitter);
                    if(clickedButton.attr('id') == 'save-and-new'){
                        $('#hf-save-and-new').val('new');
                    }
                }
                if($(this).hasClass('manualSubmission')){
                    $(this).trigger('lms.form.validated');
                }else{
                    $('#save-and-new, .btn-submit').prop('disabled', true);
                    show_loader();
                    this.submit();
                }
            }
        });
    }

    if ($('.global_upload_file').length > 0) {
        $(document).on('change', '.global_upload_file', function () {
            var thisUpload = $(this);
            var fileName = thisUpload.val();
            if ($('#err_' + this.id).length > 0) $('#err_' + this.id).remove();
            if (fileName == '') return false;

            var fileMaxSize = thisUpload.data('max-size');
            var allowedExtensions = thisUpload.prop('accept') || '';
            if (allowedExtensions) {
                allowedExtensions = allowedExtensions.replace(/ /g, '').split(',');
            } else {
                allowedExtensions = [".jpg", ".png", ".jpeg", ".gif"];
            }
            allowedExtensions = allowedExtensions.map(d => d.substring(1));

            var fileSize = this.files[0].size;
            var fileExtension = (fileName.substr((fileName.lastIndexOf('.') + 1)) || '').toLowerCase();
            var imageData = new FormData();
            imageData.append('file', this.files[0]);
            imageData.append('type', (thisUpload.data('type') || 'Product'));
            imageData.append('max_size', fileMaxSize);
            imageData.append('allowed_extension', JSON.stringify(allowedExtensions));

            if (!allowedExtensions.includes(fileExtension)) {
                notify({ title: `Allowed formats are ${allowedExtensions.join(', ')}` });
                thisUpload.val('');
                return false;
            }
            if (fileSize > fileMaxSize) {
                notify({ title: `Maximum allowed size is ${fileMaxSize / (1024 * 1024)} MB` });
                thisUpload.val('');
                return false;
            }
            var progressElement = thisUpload.parent().next();
            progressElement.removeClass('d-none');
            try {
                //show_loader();
                progressElement.find('.progress-bar').css({ width: '0%' });
                $.ajax({
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseFloat(percentComplete * 100);
                                //console.log(percentComplete);
                                progressElement.find('.progress-bar').css({ width: `${percentComplete}%` });
                                if (percentComplete === 100) {
                                    setTimeout(() => progressElement.addClass('d-none'), 1000);
                                }
                            }
                        }, false);
                        return xhr;
                    },
                    url: AppConfig.BASE_URL + '/Ajax/UploadFile',
                    type: "POST",
                    data: imageData,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false
                }).done(function (responseData) {
                    //hide_loader();
                    thisUpload.val('');
                    if (responseData.Status == 'error') {
                        notify({ title: responseData.Message });
                    } else {
                        notify({ title: responseData.Message, type: 'success' });
                        $('#' + thisUpload.data('preview-id')).prop('src', responseData.FullPath);
                        $('#' + thisUpload.data('hidden-id')).val(responseData.Path);
                    }
                });
            }
            catch (err) {

            }
            finally {
                //hide_loader(true);
            }
        });

        $(document).on('click', '.global_remove_file', function () {
            var thisUpload = $(this);
            if ($('#' + thisUpload.data('hidden-id')).val() == '') {
                notify({ title: 'Please add a file first' });
                return false;
            }
            $('#' + thisUpload.data('preview-id')).prop('src', AppConfig.PREVIEW_IMAGE_PLACEHOLDER);
            $('#' + thisUpload.data('hidden-id')).val('');
            notify({ title: 'Please hit Submit button to save changes', type: 'inverse', icon: 'fa fa-info-circle', delay: 5000 });
        });        
    }

	/* Redirect with Page = 1 if Pgae is 0 */
    if (GCurrenURL.includes('Page=0')) {
        window.location.href = add_param_to_url('Page', '1');
    }
    /* Custom Pagination */    
    if ($('.custom-pagination-jump-to-page').length > 0) {
        var current_page_number = $('.custom-pagination').data('page');
        var total_pages = $('.custom-pagination-jump-to-page').prop('max') || 0;
        if (current_page_number > total_pages) {
            redirect(add_param_to_url('Page', '1'));
        } else {
            // Add page parameter if not available
            if (!GCurrenURL.includes('Page=') || GCurrenURL.includes('Page=0')) {
                change_url(add_param_to_url('Page', '1'));
            }

            $('.pagination-link').each(function () {
                this.href = location.href.replace(`Page=${current_page_number}`, `Page=${$(this).data('page')}`);
            }).addClass('sloc');

            $('.custom-pagination-jump-to-page').keyup(function (e) {
                if (e.keyCode == 13) {
                    unnotify();
                    var page = parseInt(this.value || 0);
                    var maxPage = parseInt(this.max || 0);
                    var currentPage = parseInt($(this).data('page') || 0);
                    if (page == 0) {
                        notify({ title: 'Please enter a valid page number', animIn: AppConfig.VALIDATION_ERROR_ANIMATION });
                    } else if (page > maxPage) {
                        notify({ title: `Page number should be less than or equal to ${maxPage}`, animIn: AppConfig.VALIDATION_ERROR_ANIMATION });
                    } else {
                        redirect(location.href.replace(`Page=${currentPage}`, `Page=${page}`));
                    }
                }
            });
        }
    }

    /* Filter Form */
    if ($('#filter-form').length > 0) {
        $('#filter-form').prepend('<input type="hidden" name="Filter" value="True" />');
    }

    /* Date Filter */
    if ($('.date-filter').length > 0) {
        $('.date-filter').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'MM/DD/YYYY',
                applyLabel: 'Add Filter',
                cancelLabel: 'Reset'
            },
            open: 'bottom',
            maxDate: new Date(),
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                'Last 3 Months': [moment().subtract(3, 'months'), moment()],
                'Last 6 Months': [moment().subtract(6, 'months'), moment()],
                'Last One Year': [moment().subtract(1, 'years'), moment()]
            },
        }, function (start, end, label) {
            //console.log(start, end, label);
            //f.start = start.format('YYYY-MM-DD');/
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(`${picker.startDate.format(picker.locale.format)} - ${picker.endDate.format(picker.locale.format)}`);
        });
    }

    if ($('.g-bucket-file').length > 0) {
        $(".g-bucket-file").click(function (e) {
            var $this = $(this);
            var ObjectName = $this.data('object');
            try {
                var OldHtml = $this.html();
                $this.html('<i class="fa fa-spin fa-spinner"></i> Preparing url...');
                $.post(`${AppConfig.BASE_URL}/Ajax/GetBucketFileUrl`, { ObjectName: ObjectName }, function (data) {
                    if ($this.hasClass('g-download')) {
                        $this.html(OldHtml);
                        $('#g-download-placeholder').prop('href', data);
                        setTimeout(() => $('#g-download-placeholder')[0].click(), 1000);
                    } else {
                        setTimeout(() => $this.html(OldHtml), 100);
                        setTimeout(() => window.open(data), 500);
                    }
                });
            }
            catch (err) {

            }
        });
    }

    setTimeout(() => {
        if ($('.g-bucket-image').length > 0) {
            $(".g-bucket-image").each(function (e) {
                var $this = $(this);
                try {
                    $this.prop('src', `${AppConfig.BASE_URL}/Images/loader.gif`);
                    $.post(`${AppConfig.BASE_URL}/Ajax/GetBucketFileUrl`, { ObjectName: $this.data('object') }, function (data) {
                        $this.prop('src', data);
                    });
                }
                catch (err) {

                }
            });
        }
    }, 200);

    setTimeout(() => {
        if ($('.g-empty').length > 0) {
            $(".g-empty").each(function (e) {
                var $this = $(this);
                if ($this.text() == '') $this.html($this.data('fallback') || '-');
            });
        }
        if ($('.card-details').length > 0) {
            $(".card-details .form-group > div:nth-child(2)").each(function (e) {
                var $this = $(this);
                if ($this.text() == '') $this.html($this.data('fallback') || '-');
            });
        }
    }, 200);
});
