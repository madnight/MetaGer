$(document).ready(function() {
    if (location.href.indexOf("#plugin-modal") > -1) {
        $("#plugin-modal").modal("show");
    }
    $("button").popover();
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
        if (localStorage.getItem("pers") && !isUseOnce()) {
            setSettings();
        }
    }
    setActionListeners();
    loadInitialCustomFocuses()
});

function setActionListeners() {
    $("button").on("shown.bs.popover", function() {
        $("#color-chooser a").click(function() {
            var theme = $(this).attr("data-rgba");
            if (localStorage) {
                localStorage.setItem("theme", theme);
                location.href = "/";
            }
        });
    });
    $("#mobileFoki").change(function() {
        var focus = $("#mobileFoki > option:selected").val();
        if (focus == "angepasst") {
            window.location = "./settings/";
        } else {
            window.location = "./?focus=" + focus;
            //$("#mobileFoki > option:selected").val());
        }
    });
    if ($("fieldset#foki.mobile").length) {
        $("fieldset#foki.mobile label#anpassen-label").click(function() {
            window.location = "./settings/";
        });
    }
    $("#addFocusBtn").click(function() {
        showFocusCreateDialog("")
    });
    $("#save-focus-btn").click(saveFocus);
    $("#delete-focus-btn").click(deleteFocus);
}

function setSettings() {
    if (canCustomSearch()) {
        $("#foki input[type=radio]#angepasst").attr("checked", true);
        $("#foki label#anpassen-label").removeClass("hide");
        $("#foki button#reset-settings-btn").removeClass("hide");
        $("#searchForm").append("<input type=\"hidden\" name=\"canCustomSearch\" value=\"true\">");
    }
    for (var i = 0; i < localStorage.length; i++) {
        var key = localStorage.key(i);
        var value = localStorage.getItem(key);
        if (key.startsWith("param_") && !key.endsWith("lang") && !key.endsWith('autocomplete')) {
            key = key.substring(key.indexOf("param_") + 6);
            $("#searchForm").append("<input type=\"hidden\" name=\"" + key + "\" value=\"" + value + "\">");
        }
    }
    if (localStorage.getItem("param_lang") !== null) {
        var value = localStorage.getItem("param_lang");
        // Change the value of the lang input field to the given parameter
        $("input[name=lang]").val(value);
    }
    if (localStorage.getItem("param_autocomplete") !== null) {
        var value = localStorage.getItem("param_autocomplete");
        // Change the value of the lang input field to the given parameter
        $("input[name=eingabe]").attr("autocomplete", value);
    }
    if ($("fieldset#foki.mobile").length) {
        $("fieldset.mobile input#bilder").val("angepasst");
        $("fieldset.mobile input#bilder").prop("checked", true);
        $("fieldset.mobile input#bilder").attr("id", "angepasst");
        $("fieldset.mobile label#bilder-label").attr("id", "anpassen-label");
        $("fieldset.mobile label#anpassen-label").attr("for", "angepasst");
        $("fieldset.mobile label#anpassen-label span.glyphicon").attr("class", "glyphicon glyphicon-cog");
        $("fieldset.mobile label#anpassen-label span.content").html("angepasst");
    }
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
// Opera 8.0+
var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
// Firefox 1.0+
var isFirefox = typeof InstallTrigger !== 'undefined';
// At least Safari 3+: "[object HTMLElementConstructor]"
var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
// Internet Explorer 6-11
var isIE = /*@cc_on!@*/ false || !!document.documentMode;
// Edge 20+
var isEdge = !isIE && !!window.StyleMedia;
// Chrome 1+
var isChrome = !!window.chrome && !!window.chrome.webstore;
// Blink engine detection
var isBlink = (isChrome || isOpera) && !!window.CSS;
// Prüft, ob der URL-Parameter "usage" auf "once" gesetzt ist.
function isUseOnce() {
    var url = document.location.search;
    var pos = url.indexOf("usage=");
    if (pos >= 0 && url.substring(pos + 6, pos + 11) == "once") return true;
    return false;
}

function softResetOptions() {
    // localStorage.removeItem("pers");
    localStorage.removeItem("focus");
    var keys = [];
    for (var i = 0; i < localStorage.length; i++) {
        var key = localStorage.key(i)
        keys.push(key);
    }
    var metaParams = ["param_sprueche", "param_maps", "param_newtab", "param_lang", "param_autocomplete"];
    for (var i = 0; i < keys.length; i++) {
        var key = keys[i];
        if (key.startsWith("param_" || key.startsWith("focus"))) {
            if (metaParams.indexOf(key) === -1) {
                localStorage.removeItem(key);
            }
        }
    }
}

function canCustomSearch() {
    if (localStorage.key("focus") === "eigene") {
        return true;
    }
    var metaParams = ["param_sprueche", "param_maps", "param_newtab", "param_lang", "param_autocomplete"];
    var keys = [];
    for (var i = 0; i < localStorage.length; i++) {
        var key = localStorage.key(i)
        keys.push(key);
    }
    for (var i = 0; i < keys.length; i++) {
        var key = keys[i];
        if (key.startsWith("param_")) {
            if (metaParams.indexOf(key) === -1) {
                return true;
            }
        }
    }
    return false;
}

function loadInitialCustomFocuses() {
    for (var key in localStorage) {
        if (key.startsWith("focus_")) {
            var focus = loadFocusById(key);
            addFocus(focus.name);
        }
    }
}

function showFocusCreateDialog(id = "") {
    document.getElementById("original-id").value = id;
    $("#create-focus-modal").modal("show");
    var storedFocus = loadFocusById(id);
    var focus = {};
    // Try to load a focus for the given id
    $("#focus-name").val("");
    uncheckAll()
    if (storedFocus !== null) {
        try {
            focus = JSON.parse(localStorage.getItem(id));
            $("#focus-name").val(focus.name);
            for (var key in focus) {
                if (key.startsWith("engine_")) {
                    $(".focusCheckbox[name=" + key + "]").prop('checked', true);
                }
            }
        } catch (ex) {
            console.error(ex);
        }
    }
}

function showFocusEditDialog(id) {
    showFocusCreateDialog(id);
}

function saveFocus() {
    var focus = {};
    var name = document.getElementById("focus-name").value;
    var id = getIdFromName(name);
    var oldId = document.getElementById("original-id").value;
    $("input[type=checkbox]:checked").each(function(el) {
        focus[$(this).attr("name")] = $(this).val();
    });
    focus["name"] = name;
    if (localStorage.getItem(id) === null) {
        addFocus(name);
    }
    if (oldId !== "") {
        localStorage.removeItem(oldId);
        removeFocusById(oldId);
    }
    localStorage.setItem(id, JSON.stringify(focus));
    $("#create-focus-modal").modal("hide");
}

function deleteFocus() {
    var oldId = document.getElementById("original-id").value;
    localStorage.removeItem(oldId);
    removeFocusById(oldId);
    $("#create-focus-modal").modal("hide");
}
/*
<input id="NAME" class="hide" type="radio" name="focus" value="NAME" form="searchForm" checked required>
<label id="NAME-label" for="NAME">
    <span class="glyphicon glyphicon-star"></span>
    <span class="content">NAME</span>
    <button class="btn btn-default">
        <span class="glyphicon glyphicon-pencil"></span>
    </button>
</label>
*/
function addFocus(name) {
    var id = getIdFromName(name);
    var foki = document.getElementById("foki");
    // create <div> to wrap all Elements
    var wrapper = document.createElement("div");
    wrapper.classList.add("focus");
    // create <input>
    var newFocus = document.createElement("input");
    newFocus.id = id;
    newFocus.classList.add("focus-radio");
    newFocus.classList.add("custom-focus");
    newFocus.classList.add("hide");
    newFocus.type = "radio";
    newFocus.name = "focus";
    newFocus.value = name;
    newFocus.setAttribute("Form", "searchForm");
    newFocus.checked = true;
    newFocus.required = true;
    // create <label>
    var newFocusLabel = document.createElement("label");
    newFocusLabel.id = id + "-label";
    newFocusLabel.classList.add("focus-label");
    newFocusLabel.classList.add("custom-focus-label");
    newFocusLabel.htmlFor = id;
    // create glyphicon
    var newFocusGlyphicon = document.createElement("span");
    newFocusGlyphicon.classList.add("glyphicon");
    newFocusGlyphicon.classList.add("glyphicon-star");
    // create content
    var newFocusContent = document.createElement("span");
    newFocusGlyphicon.classList.add("content");
    newFocusContent.textContent = name;
    // create edit button
    var newFocusEditLink = document.createElement("a");
    newFocusEditLink.classList.add("focus-edit");
    newFocusEditLink.classList.add("custom-focus-edit");
    newFocusEditLink.classList.add("mutelink");
    newFocusEditLink.href = "#";
    newFocusEditLink.onclick = function() {
        showFocusEditDialog(id);
    }
    var newFocusEditLinkGlyphicon = document.createElement("span");
    newFocusEditLinkGlyphicon.classList.add("glyphicon");
    newFocusEditLinkGlyphicon.classList.add("glyphicon-pencil");
    // add new elements
    var addFocusBtn = document.getElementById("addFocusBtn");
    foki.insertBefore(wrapper, addFocusBtn);
    wrapper.appendChild(newFocus);
    wrapper.appendChild(newFocusLabel);
    newFocusLabel.appendChild(newFocusGlyphicon);
    newFocusLabel.appendChild(newFocusContent);
    wrapper.appendChild(newFocusEditLink);
    newFocusEditLink.appendChild(newFocusEditLinkGlyphicon);
}

function removeFocus(name) {
    removeFocusById(getIdFromName(name));
}

function removeFocusById(id) {
    var focusRadio = document.getElementById(id);
    var focus = focusRadio.parentNode;
    var parent = focus.parentNode;
    parent.removeChild(focus);
}

function getIdFromName(name) {
    return "focus_" + name.split(" ").join("_").toLowerCase();
}

function loadFocusById(id) {
    return JSON.parse(localStorage.getItem(id));
}

function uncheckAll() {
    $(".focusCheckbox").prop("checked", false);
}