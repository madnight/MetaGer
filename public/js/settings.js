$(document).ready(function () {
  // Wenn LocalStorage verfügbar ist, geben wir die Möglichkeit die Einstellungen dort zu speichern
  tickOptions();
  if (localStorage) {
    $('#save').removeClass('hidden');
    $('#save').click(function () {
      localStorage.setItem('pers', true);
      $('input[type=checkbox]:checked, input[type=hidden]').each(function () {
        localStorage.setItem($(this).attr('name'), $(this).val());
      });
      $('select').each(function () {
        localStorage.setItem($(this).attr('name'), $(this).val());
      });
      $('input[type=text]').each(function () {
        localStorage.setItem($(this).attr('name'), $(this).val());
      });
      document.location.href = $('#save').attr('data-href');
    });
  }
  $('.checker').click(function () {
    var selector = '.' + $(this).attr('data-type');
    if ($(selector + ' input:checked').length) {
      $(selector + ' input').prop('checked', false);
    } else {
      $(selector + ' input').prop('checked', true);
    }
  });
  $('#unten').click(function () {
    $('#settings-form').append('<input type="hidden" name="usage" value="once">');
      alert(t('saved-settings'));
  });
  $('#plugin').click(function () {
    $('form').attr('action', $('#save').attr('data-href') + '#plugin-modal');
    alert(t('generated-plugin'));
  });
  $('#settings-focus').val('angepasst');
});

function tickOptions () {
  if (localStorage && localStorage.getItem('pers')) {
    for (var i = 0; i < localStorage.length; i++) {
      var key = localStorage.key(i);
      var value = localStorage.getItem(key);
      if (key.startsWith('param_')) {
        if ($('input[name=' + key + ']').length) {
          $('input[name=' + key + ']').attr('checked', '');
        } else {
          $('select[name=' + key + '] > option[value=' + value + ']').attr('selected', true);
        }
      }
    }
  } else {
    $('div.web input').attr('checked', true);
  }
}

function getLanguage () {
  var metaData = document.getElementsByTagName('meta');
  for (var m in metaData) {
    if (metaData[m]['httpEquiv'] == 'language') {
      return metaData[m]['content'];
    }
  }
}
