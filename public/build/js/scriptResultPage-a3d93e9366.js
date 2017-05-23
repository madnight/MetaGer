$(document).ready(function () {
  activateJSOnlyContent();
  createCustomFocuses();
  var focus = $('#foki > li.active > a').attr('aria-controls');
  var custom = $('#foki > li.active').hasClass('custom-focus-tab-selector');
  getDocumentReadyForUse(focus, custom);
  botProtection();
  new Results();      // Adds the saved Results if they are present
});

function activateJSOnlyContent () {
  $('#searchplugin').removeClass('hide');
  $('.options').removeClass('hide');
}

function tabs () {
  $('#foki > li.tab-selector > a').each(function () {
    if ($(this).attr('target') != '_blank') {
      $(this).attr('href', '#' + $(this).attr('aria-controls'));
      $(this).attr('role', 'tab');
      $(this).attr('data-toggle', 'tab');
    }
  });
  $('#foki > li.tab-selector > a').off();
  $('#foki > li.tab-selector > a').on('show.bs.tab', function (e) {
    var fokus = $(this).attr('aria-controls');
    var link = $('#' + fokus + 'TabSelector a').attr('data-href');
    if ($('#' + fokus + 'TabSelector').attr('data-loaded') != '1') {
      $.get(link, function (data) {
        $('#' + fokus + 'TabSelector').attr('data-loaded', '1');
        $('#' + fokus).html(data);
        $('input[name=focus]').val($('#foki li.active a').attr('aria-controls'));
        getDocumentReadyForUse(fokus);
      });
    } else {
      getDocumentReadyForUse(fokus);
    }
  });
}

function getDocumentReadyForUse (fokus, custom = false) {
  clickLog();
  popovers();
  if (fokus === 'bilder') imageLoader();
  if (custom) initialLoadContent(fokus);
  // pagination()
  tabs();
  theme();
  fokiChanger();
  pluginInfo();
  productWidget();
  $('iframe:not(.resized)').iFrameResize();
  $('iframe').addClass('resized');
}

function pluginInfo () {
  if (localStorage) {
    if (localStorage.getItem('pluginInfo') == 'off') $('#searchplugin').css('display', 'none');
    $('#searchplugin').on('close.bs.alert', function () {
      $.get('/pluginClose');
      localStorage.setItem('pluginInfo', 'off');
    });
    $('#searchplugin a.btn').click(function () {
      $.get('/pluginInstall');
    });
  }
}



function theme () {
  if (localStorage) {
    var theme = localStorage.getItem('theme');
    if (theme != null) {
      if ((theme.match(/,/g) || []).length != 3) {
        localStorage.removeItem('theme');
      } else {
        theme = theme.split(',');
        $('#theme').attr('href', '/css/theme.css.php?r=' + theme[0] + '&g=' + theme[1] + '&b=' + theme[2] + '&a=' + theme[3]);
      }
    }
  }
}

function clickLog () {
  $('.result a.title, .result div.link-link a').off();
  $('.result a.title, .result div.link-link a').click(function () {
    $.get('/clickstats', {
      i: $('meta[name=p]').attr('content'),
      s: $(this).attr('data-hoster'),
      q: $('meta[name=q]').attr('content'),
      p: $(this).attr('data-count'),
      url: $(this).attr('href')
    });
  });
}

function botProtection () {
  if ($('meta[name=pqr]').length > 0) {
    var link = atob($('meta[name=pqr]').attr('content'));
    var hash = $('meta[name=pq]').attr('content');
    document.location.href = link + '&bot=' + hash;
  }
}

function popovers () {
  $('[data-toggle=popover]').each(function (e) {
    $(this).popover('destroy');
    $(this).popover({
      // html          :   true,
      // title         :   "<i class="fa fa-cog" aria-hidden="true"></i> Optionen",
      content: $(this).parent().find('.content').html()
    });
  });
}

function pagination () {
  $('.pagination li:not(.active) > a').attr('href', '#');
  $('.pagination li.disabled > a').removeAttr('href');
  $('.pagination li:not(.active) > a').off();
  $('.pagination li:not(.active) > a').click(paginationHandler);
}

function paginationHandler () {
  var link = $(this).attr('data-href');
  if (link.length == 0) {
    return;
  }
  var tabPane = $('.tab-pane.active');
  $(tabPane).html('<div class="loader"><img src="/img/ajax-loader.gif" alt="" /></div>');
  $.get(link, function (data) {
    $(tabPane).html(data);
    $('.pagination li:not(.active) > a').attr('href', '#');
    $('.pagination li.disabled > a').removeAttr('href');
    $('.pagination li:not(.active) > a').off();
    $('.pagination li:not(.active) > a').click(paginationHandler);
    getDocumentReadyForUse();
  });
}

function imageLoader () {
  if (typeof $('#container').masonry == 'undefined') {
    return;
  }
  var $grid = $('#container').masonry({
    columnWidth: 150,
    itemSelector: '.item',
    gutter: 10,
    isFitWidth: true
  });
  $grid.imagesLoaded().progress(function (instance, image) {
    $grid.masonry('layout');
  });
}

function eliminateHost (host) {
  $('.result:not(.ad)').each(function (e) {
    var host2 = $(this).find('.link-link > a').attr('data-host');
    if (host2.indexOf(host) === 0) {
      $(this).css('display', 'none');
    }
  });
}

function fokiChanger () {
  $('#fokiChanger ul > li').click(function () {
    document.location.href = $(this).attr('data-href');
  });
}
// Polyfill for form attribute
(function ($) {
  /**
   * polyfill for html5 form attr
   */
  // detect if browser supports this
  var sampleElement = $('[form]').get(0);
  var isIE11 = !(window.ActiveXObject) && 'ActiveXObject' in window;
  if (sampleElement && window.HTMLFormElement && sampleElement.form instanceof HTMLFormElement && !isIE11) {
    // browser supports it, no need to fix
    return;
  }
  /**
   * Append a field to a form
   *
   */
  $.fn.appendField = function (data) {
    // for form only
    if (!this.is('form')) return;
    // wrap data
    if (!$.isArray(data) && data.name && data.value) {
      data = [data];
    }
    var $form = this;
    // attach new params
    $.each(data, function (i, item) {
      $('<input/>').attr('type', 'hidden').attr('name', item.name).val(item.value).appendTo($form);
    });
    return $form;
  };
  /**
   * Find all input fields with form attribute point to jQuery object
   * 
   */
  $('form[id]').submit(function (e) {
    var $form = $(this);
    // serialize data
    var data = $('[form=' + $form.attr('id') + ']').serializeArray();
    // append data to form
    $form.appendField(data);
  }).each(function () {
    var form = this,
      $form = $(form),
      $fields = $('[form=' + $form.attr('id') + ']');
    $fields.filter('button, input').filter('[type=reset],[type=submit]').click(function () {
      var type = this.type.toLowerCase();
      if (type === 'reset') {
        // reset form
        form.reset();
        // for elements outside form
        $fields.each(function () {
          this.value = this.defaultValue;
          this.checked = this.defaultChecked;
        }).filter('select').each(function () {
          $(this).find('option').each(function () {
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

function productWidget () {
  var isMobile = false; // initiate as false
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
      prevHtml: '<i class="fa fa-chevron-left" aria-hidden="true"></i><span class="sr-only">Previous</span>',
      nextHtml: '<i class="fa fa-chevron-right" aria-hidden="true"></i><span class="sr-only">Next</span>',
      responsive: [{
        breakpoint: 1400,
        settings: {
          item: 4,
          slideMove: 2
        }
      }, {
        breakpoint: 1200,
        settings: {
          item: 3,
          slideMove: 2
        }
      }, {
        breakpoint: 980,
        settings: {
          item: 4,
          slideMove: 2
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
          slideMove: 1
        }
      } ]
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
      prevHtml: '<i class="fa fa-chevron-left" aria-hidden="true"></i><span class="sr-only">Previous</span>',
      nextHtml: '<i class="fa fa-chevron-right" aria-hidden="true"></i><span class="sr-only">Next</span>',
      responsive: [{
        breakpoint: 1400,
        settings: {
          item: 4,
          slideMove: 2
        }
      }, {
        breakpoint: 1200,
        settings: {
          item: 3,
          slideMove: 2
        }
      }, {
        breakpoint: 980,
        settings: {
          item: 4,
          slideMove: 2
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
          slideMove: 1
        }
      } ]
    });
  }
  $('.lightSliderContainer').removeClass('hidden');
}
/**
 * Creates focus tab and tab selector for every stored focus in local storage
 */
function createCustomFocuses () {
  for (var key in localStorage) {
    if (key.startsWith('focus_')) {
      var focus = loadFocusById(key);
      var active = false;
      if (getActiveFocusId() === getIdFromName(focus.name)) {
        active = true;
      }
      addFocus(focus, active);
      addTab(focus, active);
    }
  }
}
/**
 * Adds a focuses tab selector to the tab selector section
 * 
 * @if( $metager->getFokus() === "produktsuche" )
 *     <li id="produktsucheTabSelector" class="active tab-selector" role="presentation" data-loaded="1">
 *        <a aria-controls="produktsuche" data-href="#produktsuche" href="#produktsuche">
 *             <i class="fa fa-shopping-cart" aria-hidden="true"></i>
 *             <span class="hidden-xs">{{ trans('index.foki.produkte') }}</span>
 *         </a>
 *     </li>
 * @else
 *     <li id="produktsucheTabSelector" class="tab-selector" role="presentation" data-loaded="0">
 *         <a aria-controls="produktsuche" data-href="{!! $metager->generateSearchLink('produktsuche') !!}" href="{!! $metager->generateSearchLink('produktsuche', false) !!}">
 *             <i class="fa fa-shopping-cart" aria-hidden="true"></i>
 *             <span class="hidden-xs">{{ trans('index.foki.produkte') }}</span>
 *         </a>
 *     </li>
 * @endif
 */
function addFocus (focus, active = false) {
  var id = getIdFromName(focus.name);
  var foki = document.getElementById('foki');
  // create <input>
  var focusElement = document.createElement('li');
  focusElement.id = id + 'TabSelector';
  focusElement.classList.add('tab-selector');
  focusElement.classList.add('custom-focus-tab-selector');
  if (active) {
    focusElement.classList.add('active');
    focusElement.setAttribute('data-loaded', '1');
  } else {
    focusElement.setAttribute('data-loaded', '0');
  }
  focusElement.setAttribute('role', 'presentation');
  // create <a>
  var focusElementLink = document.createElement('a');
  focusElementLink.setAttribute('aria-controls', id);
  var searchLink = generateSearchLinkForFocus(focus);
  focusElementLink.setAttribute('data-href', searchLink);
  focusElementLink.setAttribute('href', searchLink);
  // create <a> icon
  var focusElementIcon = document.createElement('i');
  focusElementIcon.classList.add('fa');
  focusElementIcon.classList.add('fa-star');
  focusElementIcon.setAttribute('aria-hidden', 'true');
  // create <span> focusname
  var focusElementName = document.createElement('span');
  focusElementName.classList.add('hidden-xs');
  focusElementName.innerHTML = focus.name;
  // add new elements
  var mapsTabSelector = document.getElementById('mapsTabSelector');
  foki.insertBefore(focusElement, mapsTabSelector);
  focusElement.appendChild(focusElementLink);
  focusElementLink.appendChild(focusElementIcon);
  focusElementLink.appendChild(focusElementName);
}
/**
 * Adds a focuses tab to the tab section
 * 
 * @if( $metager->getFokus() === "produktsuche" )
 *     <div role="tabpanel" class="tab-pane active" id="produktsuche">
 *         <div class="row">
 *                 @yield('results')
 *         </div>
 *      </div>
 * @else
 *     <div role="tabpanel" class="tab-pane" id="produktsuche">
 *         <div class="loader">
 *             <img src="/img/ajax-loader.gif" alt="" />
 *         </div>
 *     </div>
 * @endif
 */
function addTab (focus, active = false) {
  var id = getIdFromName(focus.name);
  // create tab div
  var tabPane = document.createElement('div');
  tabPane.id = id;
  tabPane.classList.add('tab-pane');
  if (active) {
    tabPane.classList.add('active');
  }
  tabPane.setAttribute('role', 'tabpanel');
  // create row div
  var row = document.createElement('div');
  row.classList.add('loader');
  // create loader image
  var img = document.createElement('img');
  img.setAttribute('src', '/img/ajax-loader.gif');
  img.setAttribute('alt', '');
  row.appendChild(img);
  // add new elements
  var tabs = document.getElementById('main-content-tabs');
  tabs.appendChild(tabPane);
  tabPane.appendChild(row);
}
/**
 * Turns a name into an id
 * Converts special characters and spaces
 */
function getIdFromName (name) {
  return 'focus_' + name.split(' ').join('_').toLowerCase();
}
/**
 * Loads the focus object for the given id from local storage
 */
function loadFocusById (id) {
  return JSON.parse(localStorage.getItem(id));
}
/**
 * Gets the id of the currently active focus
 */
function getActiveFocusId () {
  var search = window.location.search;
  var from = search.indexOf('focus=') + 'focus='.length;
  var to = search.substring(from).indexOf('&') + from;
  if (to <= 0) {
    to = search.substring(from).length;
  }
  return search.substring(from, to);
}
/**
 * Turns the link of the current page into a search link for the given focus
 */
// TODO catch error if link is http://localhost:8000/meta/meta.ger3?
function generateSearchLinkForFocus (focus) {
  var link = document.location.href;
  // remove old engine settings
  // not yet tested, only for compability problems with old versions of bookmarks and plugins
  /*
  while (link.indexOf("engine_") !== -1) {
      var from = search.indexOf("engine_")
      var to = search.substring(from).indexOf("&") + from
      if (to === 0) {
          to = search.substring(from).length
      }
      link = link.substring(0, from) + link.substring(to)
  }
  */
  // add new engine settings
  for (var key in focus) {
    if (key.startsWith('engine_')) {
      var focusName = key.substring('engine_'.length);
      link += '&' + focusName + '=' + focus[key];
    }
  }
  link += '&out=results';
  link = replaceFocusInUrl(link);
  return link;
}
/**
 * Replaces the focus in a given url with the "angepasst" focus
 */
function replaceFocusInUrl (url) {
  var from = url.indexOf('focus=');
  var to = url.substring(from).indexOf('&') + from;
  if (to === 0) {
    to = url.substring(from).length;
  }
  url = url.substring(0, from) + url.substring(to);
  return url + '&focus=angepasst';
}
/**
 * Loads the content for a given fokus
 */
function initialLoadContent (fokus) {
  var link = $('#' + fokus + 'TabSelector a').attr('data-href');
  $.get(link, function (data) {
    $('#' + fokus).html(data);
    getDocumentReadyForUse(fokus);
  });
}

/*
 * This object gathers all stored Result Objects and can Update the Interface to show them.
*/
function Results(option){
  if(!localStorage) return;
  this.prefix = "result_";
  this.results = [];
  this.updateResults(option);
  this.updateInterface();
}

Results.prototype.updateResults = function(option){
  // Iterate over all Keys in the LocalStorage
  for(var i = 0; i < localStorage.length; i++){
    if(localStorage.key(i).indexOf(this.prefix) === 0){
      var key = localStorage.key(i);
      key = key.substr(this.prefix.length);
      var tmpResult = new Result(undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, key);
      tmpResult.setIndex(i);
      if(option !== undefined && option === "remove"){
        localStorage.removeItem(key);
      }else{
        this.results.push(tmpResult);
      }
    }
  }
}

Results.prototype.updateInterface = function(){
  if($("#savedFokiTabSelector").length === 0){
    var savedFoki = $('\
      <li id="savedFokiTabSelector" data-loaded="1" class="tab-selector" role="presentation">\
        <a aria-controls="savedFoki" href="#savedFoki" role="tab" data-toggle="tab">\
          <span class="glyphicon glyphicon-floppy-disk"></span> gespeicherte Ergebnisse\
        </a>\
      </li>\
      ');
    $("#foki").append(savedFoki);
  }
    if($("#savedFoki").length === 0){
      // Now append the Tab Panel
      var tabPanel = $('\
        <div role="tabpanel" class="tab-pane" id="savedFoki">\
        </div>\
        ');
      $("#main-content-tabs").append(tabPanel);
    }else{
      $("#savedFoki").html("");
      var tabPanel = $("#savedFoki");
    }
    if(this.results.length > 0) this.addToContainer(tabPanel);
}

Results.prototype.addToContainer = function(container){
  $.each(this.results, function(index, result){
    $(container).append(result.toHtml());
  });
  var options = $('\
      <div class="saver-options row">\
          <input class="form-control" type="text" placeholder="Filtern">\
          <button class="btn btn-danger btn-md"><span class="glyphicon glyphicon-trash"></span> Alle Ergebnisse aus dem Speicher entfernen</button>\
      </div>\
    ');
  $(container).prepend(options);

  $(options).find("button").click(function(){
      new Results("remove");
  });

  $(options).find("input").keyup(function(){
    var search = $(this).val();
    $("#savedFoki > div.result").each(function(index, value){
      var html = $(this).html();
      if(html.indexOf(search) === -1){
        $(value).addClass("hidden");
      }else{
        $(value).removeClass("hidden");
      }
    });
  });

}

function resultSaver(index) {
  var title = $(".result[data-count=" + index + "] a.title").html();
  var link = $(".result[data-count=" + index + "] a.title").attr("href");
  var anzeigeLink = $(".result[data-count=" + index + "] div.link-link > a").html();
  var gefVon = $(".result[data-count=" + index + "] span.hoster").html();
  var hoster =  $(".result[data-count=" + index + "] a.title").attr("data-hoster");
  var anonym = $(".result[data-count=" + index + "] a.proxy").attr("href");
  var description = $(".result[data-count=" + index + "] div.description").html();
  var color = $(".result[data-count=" + index + "] div.number").css("color");
  
  new Result(title, link, anzeigeLink, gefVon, hoster, anonym, description, color, undefined);
  new Results();
}

function Result(title, link, anzeigeLink, gefVon, hoster, anonym, description, color, hash){
  this.prefix = "result_";  // Präfix for the localStorage so we can find all Items

  if(hash !== null && hash !== undefined){
    this.hash = hash;
    this.load();
  }else{
    this.hash = MD5(title + link + anzeigeLink + gefVon + hoster + anonym + description);

    this.title = title;
    this.link = link;
    this.anzeigeLink = anzeigeLink;
    this.gefVon = gefVon;
    this.hoster = hoster;
    this.anonym = anonym;
    this.description = description;
    this.color = color;
    this.save();
  }
}

Result.prototype.load = function(){
  if(localStorage){
    var result = localStorage.getItem(this. prefix + this.hash);
    if(result === null) return false;
    result = b64DecodeUnicode(result);
    result = JSON.parse(result);
    this.title = result.title;
    this.link = result.link;
    this.anzeigeLink = result.anzeigeLink;
    this.gefVon = result.gefVon;
    this.hoster = result.hoster;
    this.anonym = result.anonym;
    this.description = result.description;
    this.color = result.color;
    return true;
  }else{
    return false;
  }
}

Result.prototype.save = function(){
  /*
  * This function will save the data of this Result to the LocalStorage
  */
  if(localStorage){

    var result = {
      title: this.title,
      link: this.link,
      anzeigeLink: this.anzeigeLink,
      gefVon: this.gefVon,
      hoster: this.hoster,
      anonym: this.anonym,
      description: this.description,
      color: this.color,
    };

    result = JSON.stringify(result);
    result = b64EncodeUnicode(result);

    localStorage.setItem(this.prefix + this.hash, result);

    return true;
  }else{
    return false;
  }
}

function b64EncodeUnicode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode('0x' + p1);
    }));
}

function b64DecodeUnicode(str) {
    return decodeURIComponent(Array.prototype.map.call(atob(str), function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}


Result.prototype.setIndex = function(index){
  this.index = index;
}

Result.prototype.remove = function(){
  localStorage.removeItem(this.prefix + this.hash);
  new Results();
}

Result.prototype.toHtml = function(){
  var result = $('\
    <div class="result row">\
      <div class="col-sm-1 glyphicon glyphicon-trash remover" title="Ergebnis aus dem Speicher löschen">\
      </div>\
      <div class="resultInformation col-xs-12 col-sm-11">\
        <div class="col-xs-10 col-sm-11" style="padding:0; ">\
          <p class="title">\
            <a class="title" href="' + this.link + '" target="_blank" data-hoster="' + this.hoster + '" data-count="1" rel="noopener">\
              ' + this.title + '\
            </a>\
          </p>\
          <div class="link">\
            <div>\
              <div class="link-link">\
                <a href="' + this.link + '" target="_blank" data-hoster="' + this.hoster + '" data-count="' + this.index + '" rel="noopener">\
                  ' + this.anzeigeLink + '\
                </a>\
            </div>\
          </div>\
          <span class="hoster">\
            ' + this.gefVon + '\
          </span>\
          <a class="proxy" onmouseover="$(this).popover(\'show\');" onmouseout="$(this).popover(\'hide\');" data-toggle="popover" data-placement="auto right" data-container="body" data-content="Der Link wird anonymisiert geöffnet. Ihre Daten werden nicht zum Zielserver übertragen. Möglicherweise funktionieren manche Webseiten nicht wie gewohnt." href="' + this.proxy + '" target="_blank" rel="noopener" data-original-title="" title="">\
            <img src="/img/proxyicon.png" alt="">\
            anonym öffnen\
          </a>\
        </div>\
      </div>\
      <div class="description">' + this.description + '</div>\
      </div>\
    </div>');
  $(result).find(".remover").click({caller: this}, function(event){
    event.data.caller.remove();
  });
  return result;
}
