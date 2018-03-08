$(document).ready(function () {
  activateJSOnlyContent();
  createCustomFocuses();
  var focus = $('#foki > li.active > a').attr('aria-controls');
  var custom = $('#foki > li.active').hasClass('custom-focus-tab-selector');
  getDocumentReadyForUse(focus, custom);
  botProtection();
  new Results().updateResultPageInterface(); // Adds the saved Results if they are present
  if (document.location.href.indexOf('focus=container') !== -1) {
    $($('#foki > li#savedFokiTabSelector').get(0)).find('>a').tab('show');
  }

  if (localStorage.hasOwnProperty('param_sprueche')) {
    var sprueche = localStorage.getItem('param_sprueche') === 'on'; // check for sprueche local storage parameter
  } else {
    var sprueche = getURLParameter('sprueche', 'on') === 'on'; // load the sprueche url parameter
  }

  var search = getMetaTag('q') || '';
  var locale = getMetaTag('l') || 'de';
  loadQuicktips(search, locale, sprueche); // load the quicktips
});

/*
function readLocaleFromUrl (defaultLocale) {
  return location.pathname.substr(1, location.pathname.indexOf('/meta', 0) - 1) || 'de'
}
*/

function getURLParameter (name, defaultValue) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || defaultValue;
}

function getMetaTag (name) {
  if (typeof $('meta[name="' + name + '"')[0] !== 'undefined') {
    return $('meta[name="' + name + '"')[0].content || null;
  } else {
    return null;
  }
}

function activateJSOnlyContent () {
  $('#searchplugin').removeClass('hide');
  $('.js-only').removeClass('js-only');
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

function getDocumentReadyForUse (fokus, custom) {
  if (typeof custom == 'undefined') custom = false;
  activateJSOnlyContent();
  clickLog();
  popovers();
  if (fokus === 'bilder') imageLoader();
  if (custom) initialLoadContent(fokus);
  // pagination()
  tabs();
  theme();
  fokiChanger();
  pluginInfo();
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
function addFocus (focus, active) {
  if (typeof active == 'undefined') active = false;
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
function addTab (focus, active) {
  if (typeof active == 'undefined') active = false;
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

function resultSaver (index) {
  var title = $('div.tab-pane.active .result[data-count=' + index + '] a.title').html();
  var link = $('div.tab-pane.active .result[data-count=' + index + '] a.title').attr('href');
  var anzeigeLink = $('div.tab-pane.active .result[data-count=' + index + '] div.link-link > a').html();
  var gefVon = $('div.tab-pane.active .result[data-count=' + index + '] span.hoster').html();
  var hoster = $('div.tab-pane.active .result[data-count=' + index + '] a.title').attr('data-hoster');
  var anonym = $('div.tab-pane.active .result[data-count=' + index + '] a.proxy').attr('href');
  var description = $('div.tab-pane.active .result[data-count=' + index + '] div.description').html();
  var color = $('div.tab-pane.active .result[data-count=' + index + '] div.number').css('color');
  var rank = parseFloat($('div.tab-pane.active .result[data-count=' + index + ']').attr('data-rank'));
  new Result(title, link, anzeigeLink, gefVon, hoster, anonym, description, color, rank, undefined);
  var to = $('#savedFokiTabSelector').length ? $('#savedFokiTabSelector') : $('#foki');
  $('div.tab-pane.active .result[data-count=' + index + ']').transfer({
    to: to,
    duration: 1000
  });
  new Results().updateResultPageInterface();
}

function loadQuicktips (search, locale, sprueche) {
  var blacklist = [];
  if (!sprueche) {
    blacklist.push('sprueche');
  }
  getQuicktips(search, locale, blacklist, createQuicktips);
}

const QUICKTIP_SERVER = 'https://quicktips.metager3.de';
// const QUICKTIP_SERVER = 'http://localhost:63825'

/**
 * Requests quicktips from the quicktip server and passes them to the loadedHandler
 * 
 * @param {String} search search term
 * @param {String} locale 2 letter locale identifier
 * @param {Array<String>} blacklist excluded loaders
 * @param {Function} loadedHandler handler for loaded quicktips
 */
function getQuicktips (search, locale, blacklist, loadedHandler) {
  var getString = QUICKTIP_SERVER + '/quicktips.xml?search=' + search + '&locale=' + locale;
  blacklist.forEach(function (value) {
    getString += '&loader_' + value + '=false';
  });
  $.get(getString, function (data, status) {
    if (status === 'success') {
      var quicktips = $(data).children('feed').children('entry').map(function () {
        return quicktip = {
          type: $(this).children('mg\\:type').text(),
          title: $(this).children('title').text(),
          summary: $(this).children('content').text(),
          url: $(this).children('link').attr('href'),
          gefVon: $(this).children('mg\\:gefVon').text(),
          score: $(this).children('relevance\\:score').text(),
          details: $(this).children('mg\\:details').children('entry').map(function () {
            return {
              title: $(this).children('title').text(),
              text: $(this).children('text').text(),
              url: $(this).children('url').text()
            };
          }).toArray()
        };
      }).toArray();
      loadedHandler(quicktips);
    } else {
      console.error('Loading quicktips failed with status ' + status);
    }
  }, 'xml');
}

/**
 * <div id="quicktips">
 *   <div class="quicktip" type="TYPE">
 *     <details>
 *       <summary>
 *         <h1><a href="URL">TITLE</a></h1>
 *         <p>SUMMARY</p>
 *       </summary>
 *       <div class="quicktip-detail">
 *         <h2><a href="DETAILURL">DETAILTITLE</a></h1>
 *         <p>DETAILSUMMARY</p>
 *       </div>
 *       <div class="quicktip-detail">
 *         ...
 *       </div>
 *       ...
 *     </details>
 *     <span>GEFVON
 *   </div>
 * </div>
 * 
 * @param {Object} quicktips 
 */
function createQuicktips (quicktips, sprueche) {
  var quicktipsDiv = $('#quicktips');
  quicktips.sort(function (a, b) {
    return b.score - a.score;
  }).forEach(function (quicktip) {
    var mainElem;
    if (quicktip.details.length > 0) {
      mainElem = $('<details>');
      var summaryElem = $('<summary class="quicktip-summary">');
      var headlineElem = $('<h1>');
      if (quicktip.url.length > 0) {
        headlineElem.append('<a href=' + quicktip.url + '>' + quicktip.title + '</a>');
      } else {
        headlineElem.text(quicktip.title);
      }
      headlineElem.append('<i class="quicktip-extender fa fa-chevron-circle-down" aria-hidden="true"></i>');
      summaryElem
        .append(headlineElem)
        .append('<p>' + quicktip.summary + '</p>');
      mainElem.append(summaryElem);
      quicktip.details.forEach(function (detail) {
        var detailElem = $('<div class="quicktip-detail">');
        var detailHeadlineElem = $('<h2>');
        if (detail.url.length > 0) {
          detailHeadlineElem.append('<a href=' + detail.url + '>' + detail.title + '</a>');
        } else {
          detailHeadlineElem.text(detail.title);
        }
        detailElem
          .append(detailHeadlineElem)
          .append('<p>' + detail.text + '</p>');
        mainElem.append(detailElem);
      });
    } else {
      mainElem = $('<div class="quicktip-summary">');
      var headlineElem = $('<h1>');
      if (quicktip.url.length > 0) {
        headlineElem.append('<a href=' + quicktip.url + '>' + quicktip.title + '</a>');
      } else {
        headlineElem.text(quicktip.title);
      }
      mainElem
        .append(headlineElem)
        .append('<p>' + quicktip.summary + '</p>');
    }
    var quicktipDiv = $('<div class="quicktip" type="' + quicktip.type + '">');
    quicktipDiv
      .append(mainElem)
      .append('<span class="gefVon">' + quicktip.gefVon + '</span>');
    quicktipsDiv.append(quicktipDiv);
  });
}
