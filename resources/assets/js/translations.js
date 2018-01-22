// Speichert die Übersetzungen
var translations = {
    'de': {
        'select-engine': 'Bitte mindestens 1 Suchmaschine auswählen.',
        'select-valid-name': 'Bitte gültigen Namen eingeben:\n* Keine Sonderzeichen\n* Mindestens 1 Buchstabe\n',
        'confirm-overwrite-name': 'Name bereits genutzt.\nÜberschreiben?',
        'saved-settings': 'Auf der folgenden Startseite sind Ihre Einstellungen nun einmalig gespeichert. Nach Ihrer ersten Suche sind diese wieder verloren. Wenn Sie diese speichern möchten, können Sie sich allerdings ein Lesezeichen für die generierte Startseite einrichten.',
        'generated-plugin': 'Ihr Browserplugin mit den persönlichen Sucheinstellungen wurde generiert. Folgen Sie bitte der Anleitung auf der folgenden Seite um es zu installieren. Beachten Sie: Zuvor sollten Sie ein eventuell bereits installiertes MetaGer-Plugin entfernen.'
    },

    'en': {
        'select-engine' : 'Please select at least 1 search engine.',
        'select-valid-name': 'No characters other than a-z, A-Z, 0-9, ä, ö, ü, ß, -, _ allowed, at least 1 character',
        'confirm-overwrite-name': 'Name already in use.\nOverwrite?',
        'saved-settings': 'On the following startpage your settings are saved one-time. They will be lost after your first search. Though if you want to save them, you can create a bookmark for the generated startpage.',
        'generated-plugin': 'Your browser plugin with personal settings was generated. Please follow the instructions on the following page to install it. Notice that beforehand you might have to delete a former MetaGer plugin.'
    },

    'es': {
        'select-engine': 'Por favor, seleccione al menos un motor de búsqueda.',
        'select-valid-name': 'Por favor, introduzca un nombre válido constituido por letras y números.',
        'confirm-overwrite-name': 'Nombre ya ha sido elegido.\n¿Substituirlo?',
        // 'saved-settings': '',
        // 'generated-plugin': ''
    }
}

/**
 * Übersetzt den gegebenen Schlüssel in der gegebenen Sprache
 * Gibt standardmäßig deutsche Sprachstrings zurück, da davon ausgegangen werden kann, dass diese immer vorhanden sind
 * @param {string} key Zu übersetzender Schlüssel
 * @param {string} lang Zu verwendende Sprache
 */
function t(key, lang) {
    if (arguments.length == 1) {
        var lang = $('html').attr('lang');
        return translations[lang][key];
    } else if (arguments.length == 2 && translations[lang] && translations[lang][key]) {
        return translations[lang][key];
    } else {
        return translations.de[key];
    }
}