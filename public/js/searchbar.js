$(function () {
  loadLocalStorage();
  setSearchbarActionListeners();
});

/**
 * Loads the user theme and stored settings from local storage
 */
function loadLocalStorage () {
  if (localStorage) {
    setSettings();
  }
}

function setSearchbarActionListeners () {
  $('#toggleOptBtn').click(toggleOptionsDialog);
}

function setSettings () {
  var acceptedParams = ['autocomplete', 'key', 'lang', 'newtab', 'sprueche'];
  for (var key in localStorage) {
    var value = localStorage.getItem(key);
    var accepted = false;
    for (var i in acceptedParams) {
      if (key === 'param_' + acceptedParams[i]) {
        accepted = true;
      }
    }
    if (accepted) {
      key = key.substring(6);
      // Check for existing hidden fields for this key
      var existing = $('.search-hidden input[name="' + key + '"]');
      if (existing.length === 0) {
        // if none exist, create a new one
        $('.search-hidden').append('<input type="hidden" name="' + key + '" value="' + value + '">');
      }
    }
  }
  // Change the request method to the given parameter
  var requestMethod = localStorage.getItem('request');
  if (requestMethod !== null && (requestMethod === 'GET' || requestMethod === 'POST')) {
    $('#searchForm').attr('method', requestMethod);
  }
}

function toggleOptionsDialog () {
  var btnMode = $('#toggleOptBtn').attr('data-mode');
  if (btnMode == 'o') {
    openOptionsDialog();
  } else {
    closeOptionsDialog();
  }
}

function openOptionsDialog () {
  $('#toggleOptBtn').html('<i class="fa fa-chevron-up" aria-hidden="true"></i>');
  $('#toggleOptBtn').attr('data-mode', 'c');
  $('.search-option-frame').removeClass('hide');
}

function closeOptionsDialog () {
  $('#toggleOptBtn').html('<i class="fa fa-sliders" aria-hidden="true"></i>');
  $('#toggleOptBtn').attr('data-mode', 'o');
  $('.search-option-frame').addClass('hide');
}

$(function () {
  setFocusCreatorActionListeners();
  loadInitialCustomFocuses();
  loadInitialSelectedFocus();
  focusChanged();
});

/**
 * Sets all action listeners for this page
 */
function setFocusCreatorActionListeners () {
  $('.focusCheckbox').click(checkboxCheckListener);
  $('#addFocusBtn').click(() => showFocusCreateDialog(''));
  $('#editFocusBtn').click(editCurrentFocus);
  $('.save-focus-btn').click(saveFocus);
  $('.delete-focus-btn').click(deleteFocus);
  $('#focus-select').change(focusChanged);
  // Save Focus on clicking enter while in the focus name input
  $('#focus-name').keyup(function (event) {
    if (event.keyCode == 13) {
      saveFocus();
    }
  });
  $('#create-focus-modal').on('shown.bs.modal', function () {
    $('#focus-name').focus();
  });
}
/**
 * Loads all the custom focuses stored in local storage
 */
function loadInitialCustomFocuses () {
  for (var key in localStorage) {
    if (key.startsWith('focus_')) {
      var focus = loadFocusById(key);
      addFocus(focus.name);
    }
  }
}

function loadInitialSelectedFocus () {
  setFocus(getFocusInUrl());
}

/**
 * Shows the focus create dialog
 * If an id is given it will try to load a focus for the given id
 */
function showFocusCreateDialog (id) {
  if (id === undefined) {
    id = '';
  }
  document.getElementById('original-id').value = id;
  $('#create-focus-modal').modal('show');
  var storedFocus = loadFocusById(id);
  var focus = {};
  // Try to load a focus for the given id
  $('#focus-name').val('');
  uncheckAll();
  if (storedFocus !== null) {
    try {
      focus = JSON.parse(localStorage.getItem(id));
      $('#focus-name').val(focus.name);
      for (var key in focus) {
        if (key.startsWith('engine_')) {
          $('.focusCheckbox[name=' + key + ']').prop('checked', true);
        }
      }
    } catch (ex) {
      console.error(ex);
    }
  }
  toggleDeleteButton();
}

/**
 * Shows the focus create dialog for a given id
 */
function showFocusEditDialog (id) {
  showFocusCreateDialog(id);
}

function getCurrentFocus () {
  return $("#foki > div.active").attr("id");
}

/**
 * Shows an edit dialog for the current selected focus
 */
function editCurrentFocus () {
  var currentFocus = getCurrentFocus();
  showFocusEditDialog(currentFocus);
}

/**
 * Shows/Hides the delete button if (no) checkboxes are selected
 */
function toggleDeleteButton () {
  if (atLeastOneChecked()) {
    $('.delete-focus-btn').show();
  } else {
    $('.delete-focus-btn').hide();
  }
}

/**
 * Save the current Focus
 * Listens for save button
 */
function saveFocus () {
  /* Vorprüfungen */
  // Falls keine Suchmaschine ausgewählt wurde
  if (!atLeastOneChecked()) {
    switch (document.documentElement.lang) {
      case 'en':
        alert('Please select at least 1 search engine.');
        break;
      case 'es':
        alert('Por favor, seleccione al menos un motor de búsqueda.');
        break;
      default:
        alert('Bitte mindestens 1 Suchmaschine auswählen.');
        break;
    }
    return;
  }
  // Falls der Name zu kurz ist oder ungültige Zeichen enthält
  var name = document.getElementById('focus-name').value;
  if (!isValidName(name)) {
    switch (document.documentElement.lang) {
      case 'en':
        alert('No characters other than a-z, A-Z, 0-9, ä, ö, ü, ß, -, _ allowed, at least 1 character');
        break;
      case 'es':
        alert('Por favor, introduzca un nombre válido');
        break;
      default:
        alert('Bitte gültigen Namen eingeben:\n* Keine Sonderzeichen\n* Mindestens 1 Buchstabe\n');
        break;
    }
    return;
  }
  // Liest die original-id des aktuellen fokus-dialogs (gesetzt wenn man einen Fokus bearbeitet)
  var oldId = document.getElementById('original-id').value;
  var id = getIdFromName(name);
  var overwrite = true;
  // Wenn bereits ein Fokus mit dem Namen existiert, man diesen aber nicht editiert sondern gerade einen Neuen erstellt
  if (alreadyInUse(name) && oldId !== id) {
    // Fragt den Nutzer ob er den Fokus überschreiben möchte
    if (!confirm('Name bereits genutzt\nüberschreiben?')) {
      // Falls nicht wird das Speichern abgebrochen
      return;
    }
    // Ansonsten wird der andere Fokus gelöscht
    deleteFocusById(id);
  }
  /* Fokus speichern */
  var focus = {};
  // Ausgewählte Suchmaschinen lesen und zu Fokus hinzufügen
  $('input[type=checkbox]:checked').each(function (el) {
    focus[$(this).attr('name')] = $(this).val();
  });

  // Name setzen
  focus['name'] = name;
  // Alte Version des Fokus löschen (aus localStorage und von der Webseite, falls eine existiert)
  if (oldId !== '') {
    localStorage.removeItem(oldId);
    removeFocusById(oldId);
  }
  // Neue Version des Fokus hinzufügen (zu localStorage und der Webseite)
  localStorage.setItem(id, JSON.stringify(focus));
  addFocus(name);
  setFocus(id);
  // Fokus-Formular verbergen
  $('#create-focus-modal').modal('hide');
}

/**
 * Delete current Focus
 * Listens for delete button
 */
function deleteFocusById (id) {
  localStorage.removeItem(id);
  removeFocusById(id);
  $('#focus-select').change();
}

/**
 * Delete current Focus
 * Listens for delete button
 */
function deleteFocus () {
  var oldId = document.getElementById('original-id').value;
  deleteFocusById(oldId);
  $('#create-focus-modal').modal('hide');
  $('#focus-select').change();
}

/**
 * Is the name valid (in terms of characters)?
 */
function isValidName (name) {
  // no Characters other then a-z, A-Z, 0-9, ä, ö, ü, ß, -, _ allowed
  // at least 1 character
  return /^[a-zA-Z0-9äöüß\-_ ]+$/.test(name);
}

/**
 * Is at least one focus selected?
 */
function atLeastOneChecked () {
  return $('.focusCheckbox:checked').length > 0;
}

/**
 * Is there already a focus with this name?
 */
function alreadyInUse (name) {
  return localStorage.hasOwnProperty(getIdFromName(name));
}

/**
 * Adds an option to the focus selector
 */
function addFocus (name) {
  var id = getIdFromName(name);
  var customFocus = $('<div id="' + id +'"><a href="#" target="_self">' + name + '</a><a class="edit-focus" data-id="' + id + '" href="#"><i class="fa fa-wrench"></i></div>');
  $(customFocus).find(".edit-focus").click(function(){
    showFocusEditDialog($(this).attr("data-id"));
  });
  $("#foki .search-option-frame").before(customFocus);
}

/**
 * Remove the focuses html-elements
 */
function removeFocus (name) {
  removeFocusById(getIdFromName(name));
}

/**
 * Remove the focuses html-elements
 */
function removeFocusById (id) {
  if (id == '') {
    return;
  }
  $('#focus-select option[value="' + id + '"]').remove();
}

/**
 * Turns a name into an id
 * Converts special characters and spaces
 */
function getIdFromName (name) {
  name = name.toLowerCase();
  name = name.split(' ').join('_');
  name = name.split('ä').join('ae');
  name = name.split('ö').join('oe');
  name = name.split('ü').join('ue');
  return 'focus_' + name;
}

/**
 * Loads the focus object for the given id from local storage
 */
function loadFocusById (id) {
  return JSON.parse(localStorage.getItem(id));
}

/**
 * Unchecks all focuses from the focus creator dialog
 */
function uncheckAll () {
  $('.focusCheckbox').prop('checked', false);
}

/**
 * Sets the selected focus to default
 */
function setFocusToDefault () {
  setFocus(DEFAULT_FOCUS);
}

/**
 * Sets the selected focus
 * @param {String} focusID The id of the focus, without #
 */
function setFocus (focusID) {
  $('#focus-select option[value="' + focusID + '"]').prop('selected', true);
  $('#focus-select').change();
}

function focusChanged () {
  var selectedFocus = getCurrentFocus();
  if (focusIsEditable(selectedFocus)) {
    enableEditFocusBtn();
  } else {
    disableEditFocusBtn();
  }
  loadFocusForSearch(selectedFocus);
}

function focusIsEditable (focus) {
  if (focus.startsWith('focus_')) {
    return true;
  } else {
    return false;
  }
}

function enableEditFocusBtn () {
  $('#editFocusBtn').removeClass('disabled').click(editCurrentFocus);
}

function disableEditFocusBtn () {
  $('#editFocusBtn').addClass('disabled').off('click');
}

function loadFocusForSearch (focus) {
  var focus = loadFocusById(focus);

  var url ="/meta/meta.ger3?eingabe=x&focus=";

  console.log(focus, url);

  clearCustomSearch();
  for (var key in focus) {
    if (key.startsWith('engine_') && focus[key] == 'on') {
      addSumaToCustomSearch(key);
    }
  }
}

function getFocusInUrl () {
  var url = window.location;
  var focReg = /focus=(focus_\w+)/.exec(url);
  if (focReg && focReg[1]) {
    return focReg[1];
  }
}

function checkboxCheckListener (event) {
  toggleDeleteButton();
  var elem = event.target;
  if (elem.name) {
    if (elem.checked) {
      setCheckedForAllWithName(elem.name, true);
    } else {
      setCheckedForAllWithName(elem.name, false);
    }
  }
}

function setCheckedForAllWithName (name, checked) {
  $('.focusCheckbox[name=' + name + ']').prop('checked', checked);
}

//# sourceMappingURL=searchbar.js.map
