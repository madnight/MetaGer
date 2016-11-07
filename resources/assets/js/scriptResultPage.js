$(document).ready(function() {
    getDocumentReadyForUse($("#foki > li.active > a").attr("aria-controls"));
    $('iframe').iFrameResize({
        'autoResize': true,
        'heightCalculationMethod': 'documentElementScroll'
    });
    botProtection();
});

function tabs() {
    //return;
    $("#foki > li.tab-selector > a").each(function() {
        $(this).attr("href", "#" + $(this).attr("aria-controls"));
        $(this).attr("role", "tab");
        $(this).attr("data-toggle", "tab");
    });
    $("#foki > li.tab-selector > a").off();
    $("#foki > li.tab-selector > a").on("show.bs.tab", function(e) {
        var fokus = $(this).attr("aria-controls");
        var link = $("#" + fokus + "TabSelector a").attr("data-href");
        if ($("#" + fokus + "TabSelector").attr("data-loaded") != "1") {
            $.get(link, function(data) {
                $("#" + fokus + "TabSelector").attr("data-loaded", "1");
                $("#" + fokus).html(data);
                $("input[name=focus]").val($("#foki li.active a").attr("aria-controls"));
                getDocumentReadyForUse(fokus);
            });
        } else {
            getDocumentReadyForUse(fokus);
        }
    });
}

function getDocumentReadyForUse(fokus) {
    clickLog();
    popovers();
    if (fokus === "bilder") imageLoader();
    //pagination();
    tabs();
    theme();
    fokiChanger();
    pluginInfo();
    productWidget();
}

function pluginInfo() {
    if (localStorage) {
        if (localStorage.getItem('pluginInfo') == "off") $("#searchplugin").css("display", "none");
        $("#searchplugin").on('close.bs.alert', function() {
            $.get('/pluginClose');
            localStorage.setItem('pluginInfo', 'off');
        });
        $("#searchplugin a.btn").click(function() {
            $.get('/pluginInstall');
        });
    }
}

function theme() {
    if (localStorage) {
        var theme = localStorage.getItem("theme");
        if (theme != null) {
            if ((theme.match(/,/g) || []).length != 3) {
                localStorage.removeItem("theme");
            } else {
                theme = theme.split(",");
                $("#theme").attr("href", "/css/theme.css.php?r=" + theme[0] + "&g=" + theme[1] + "&b=" + theme[2] + "&a=" + theme[3]);
            }
        }
    }
}

function clickLog() {
    $(".result a.title, .result div.link-link a").off();
    $(".result a.title, .result div.link-link a").click(function() {
        $.get("/clickstats", {
            i: $("meta[name=p]").attr("content"),
            s: $(this).attr("data-hoster"),
            q: $("meta[name=q]").attr("content"),
            p: $(this).attr("data-count"),
            url: $(this).attr("href")
        });
    });
}

function popovers() {
    $("[data-toggle=popover]").each(function(e) {
        $(this).popover("destroy");
        $(this).popover({
            //html          :   true,
            //title         :   "<span class='glyphicon glyphicon-cog'></span> Optionen",
            content: $(this).parent().find(".content").html()
        });
    });
}

function pagination() {
    $(".pagination li:not(.active) > a").attr("href", "#");
    $(".pagination li.disabled > a").removeAttr("href");
    $(".pagination li:not(.active) > a").off();
    $(".pagination li:not(.active) > a").click(paginationHandler);
}

function paginationHandler() {
    var link = $(this).attr("data-href");
    if (link.length == 0) {
        return;
    }
    var tabPane = $(".tab-pane.active");
    $(tabPane).html("<div class=\"loader\"><img src=\"/img/ajax-loader.gif\" alt=\"\" /></div>");
    $.get(link, function(data) {
        $(tabPane).html(data);
        $(".pagination li:not(.active) > a").attr("href", "#");
        $(".pagination li.disabled > a").removeAttr("href");
        $(".pagination li:not(.active) > a").off();
        $(".pagination li:not(.active) > a").click(paginationHandler);
        getDocumentReadyForUse();
    });
}

function imageLoader() {
    if (typeof $("#container").masonry == "undefined") {
        return;
    }
    var $grid = $("#container").masonry({
        columnWidth: 150,
        itemSelector: '.item',
        gutter: 10,
        isFitWidth: true
    });
    $grid.imagesLoaded().progress(function(instance, image) {
        $grid.masonry('layout');
    });
}

function eliminateHost(host) {
    $(".result:not(.ad)").each(function(e) {
        var host2 = $(this).find(".link-link > a").attr("data-host");
        if (host2.indexOf(host) === 0) {
            $(this).css("display", "none");
        }
    });
}

function fokiChanger() {
    $("#fokiChanger ul > li").click(function() {
        document.location.href = $(this).attr("data-href");
    });
}
// Polyfill for form attribute
(function($) {
    /**
     * polyfill for html5 form attr
     */
    // detect if browser supports this
    var sampleElement = $('[form]').get(0);
    var isIE11 = !(window.ActiveXObject) && "ActiveXObject" in window;
    if (sampleElement && window.HTMLFormElement && sampleElement.form instanceof HTMLFormElement && !isIE11) {
        // browser supports it, no need to fix
        return;
    }
    /**
     * Append a field to a form
     *
     */
    $.fn.appendField = function(data) {
        // for form only
        if (!this.is('form')) return;
        // wrap data
        if (!$.isArray(data) && data.name && data.value) {
            data = [data];
        }
        var $form = this;
        // attach new params
        $.each(data, function(i, item) {
            $('<input/>').attr('type', 'hidden').attr('name', item.name).val(item.value).appendTo($form);
        });
        return $form;
    };
    /**
     * Find all input fields with form attribute point to jQuery object
     * 
     */
    $('form[id]').submit(function(e) {
        var $form = $(this);
        // serialize data
        var data = $('[form=' + $form.attr('id') + ']').serializeArray();
        // append data to form
        $form.appendField(data);
    }).each(function() {
        var form = this,
            $form = $(form),
            $fields = $('[form=' + $form.attr('id') + ']');
        $fields.filter('button, input').filter('[type=reset],[type=submit]').click(function() {
            var type = this.type.toLowerCase();
            if (type === 'reset') {
                // reset form
                form.reset();
                // for elements outside form
                $fields.each(function() {
                    this.value = this.defaultValue;
                    this.checked = this.defaultChecked;
                }).filter('select').each(function() {
                    $(this).find('option').each(function() {
                        this.selected = this.defaultSelected;
                    });
                });
            } else if (type.match(/^submit|image$/i)) {
                $(form).appendField({
                    name: this.name,
                    value: this.value
                }).submit();
            }
        });
    });
})(jQuery);

function botProtection() {
    if ($("meta[name=pqr]").length > 0) {
        var link = atob($("meta[name=pqr]").attr("content"));
        var hash = $("meta[name=pq]").attr("content");
        document.location.href = link + "&bot=" + hash;
    }
}

function productWidget() {
    var isMobile = false; //initiate as false
    // device detection
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) isMobile = true;
    if (isMobile) {
        $('.lightSlider').lightSlider({
            item: 5,
            loop: false,
            slideMove: 2,
            easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
            speed: 600,
            pager: false,
            prevHtml: '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span>',
            nextHtml: '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span>',
            responsive: [{
                breakpoint: 1400,
                settings: {
                    item: 4,
                    slideMove: 2,
                }
            }, {
                breakpoint: 1200,
                settings: {
                    item: 3,
                    slideMove: 2,
                }
            }, {
                breakpoint: 980,
                settings: {
                    item: 4,
                    slideMove: 2,
                }
            }, {
                breakpoint: 720,
                settings: {
                    item: 3,
                    slideMove: 1
                }
            }, {
                breakpoint: 520,
                settings: {
                    item: 2,
                    slideMove: 1,
                }
            }, ]
        });
    } else {
        $('.lightSlider').lightSlider({
            item: 5,
            loop: false,
            slideMove: 2,
            easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
            speed: 600,
            pager: false,
            enableTouch: false,
            enableDrag: false,
            prevHtml: '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span>',
            nextHtml: '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span>',
            responsive: [{
                breakpoint: 1400,
                settings: {
                    item: 4,
                    slideMove: 2,
                }
            }, {
                breakpoint: 1200,
                settings: {
                    item: 3,
                    slideMove: 2,
                }
            }, {
                breakpoint: 980,
                settings: {
                    item: 4,
                    slideMove: 2,
                }
            }, {
                breakpoint: 720,
                settings: {
                    item: 3,
                    slideMove: 1
                }
            }, {
                breakpoint: 520,
                settings: {
                    item: 2,
                    slideMove: 1,
                }
            }, ]
        });
    }
    $(".lightSliderContainer").removeClass("hidden");
}