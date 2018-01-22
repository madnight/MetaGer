// Speichert die Übersetzungen
var translations = {
    'de': {
        'key': 'Bitte mindestens 1 Suchmaschine auswählen.'
    },

    'en': {
        'key' : 'Please select at least 1 search engine.'
    },

    'es': {
        'key': 'Por favor, seleccione al menos un motor de búsqueda.'
    }
}

/**
 * Übersetzt den gegebenen Schlüssel in der gegebenen Sprache
 * @param {string} key Zu übersetzender Schlüssel
 * @param {string} lang Zu verwendende Sprache
 */
function t(key, lang) {
    if (translations[lang] && translations[lang][key]) {
        return translations[lang][key];
    } else {
        return '';
    }
}

/**
 * Übersetzt den gegebenen Schlüssel in der aktuellen Sprache des HTML-Dokuments (<html lang="...">)
 * @param {string} key Zu übersetzender Schlüssel
 */
function t(key) {
    var lang = $('html').attr('lang');
    return t(key, lang);
}
