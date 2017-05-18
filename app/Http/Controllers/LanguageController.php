<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LanguageObject;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LanguageController extends Controller
{
    public function createOverview(Request $request)
    {
        $languageFilePath = resource_path() . "/lang/";
        $files            = scandir($languageFilePath);
        $dirs             = [];
        foreach ($files as $file) {
            if (is_dir($languageFilePath . $file) && $file !== "." && $file !== "..") {
                $dirs[] = $file;
            }

        }
        # Im Array "$dirs" haben wir nun alle Verzeichnisse mit dem entsprechenden Sprachkürzel
        # Alle von uns bislang unterstützen Sprachen sind hier eingetragen.
        $langTexts = [];
        $sum       = [];
        foreach ($dirs as $dir) {
            # Wir überprüfen nun für jede Datei die Anzahl der vorhandenen Übersetzungen
            $di                           = new RecursiveDirectoryIterator($languageFilePath . $dir);
            $langTexts[$dir]["textCount"] = 0;
            $langTexts[$dir]["fileCount"] = 0;
            foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
                if (!$this->endsWith($filename, ".")) {
                    $langTexts[$dir]["fileCount"] += 1;
                    $tmp = include $filename;
                    foreach ($tmp as $key => $value) {
                        $sum = array_merge($sum, $this->getValues([$key => $value], basename($filename)));
                        $langTexts[$dir]["textCount"] += count($this->getValues([$key => $value]));
                    }

                }

            }
        }
        $deComplete = $langTexts["de"]["textCount"] === count($sum) ? true : false;
        return view('languages.overview')
            ->with('title', trans('titles.languages'))
            ->with('langTexts', $langTexts)
            ->with('sum', $sum)
            ->with('deComplete', $deComplete);
    }

    public function createEditPage($from, $to, $exclude = "", $email = "")
    {
        $languageFilePath = resource_path() . "/lang/";
        $files            = scandir($languageFilePath);
        $dirs             = [];
        foreach ($files as $file) {
            if (is_dir($languageFilePath . $file) && $file !== "." && $file !== "..") {
                $dirs[$file] = $file;
            }

        }
        # Abbruchbedingungen:
        if ($from === "" || $to === "" || ($from !== "de" && $from !== "all") || ($from === "all" && $to !== "de") && !array_has($dirs, $to)) {
            return redirect(url('languages'));
        }

        $texts = [];

        $langTexts = [];
        $sum       = [];
        $filePath  = [];
        foreach ($dirs as $dir) {
            if ($from !== "all" && $dir !== $to && $dir !== $from) {
                continue;
            }

            # Wir überprüfen nun für jede Datei die Anzahl der vorhandenen Übersetzungen
            $di              = new RecursiveDirectoryIterator($languageFilePath . $dir);
            $langTexts[$dir] = 0;
            foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
                if (!$this->endsWith($filename, ".")) {
                    $tmp = include $filename;
                    foreach ($tmp as $key => $value) {
                        $sum                                    = array_merge($sum, $this->getValues([$key => $value], basename($filename)));
                        $texts[basename($filename)][$key][$dir] = $value;
                        $langTexts[$dir] += count($this->getValues([$key => $value]));
                    }
                    $filePath[basename($filename)] = preg_replace("/lang\/.*?\//si", "lang/$to/", substr($filename, strpos($filename, "lang")));
                }

            }
        }

        $langs = [];
        $fn    = "";
        $t     = [];
        $ex    = ['files' => [], 'new' => 0];
        if ($exclude !== "") {
            try {
                $ex = unserialize(base64_decode($exclude));
            } catch (\ErrorException $e) {
                $ex = ['files' => [], 'new' => 0];
            }
        }

        foreach ($texts as $filename => $text) {
            $has = false;
            foreach ($ex['files'] as $file) {
                if ($file === $filename) {
                    $has = true;
                }
            }
            if ($has) {
                continue;
            }
            while ($this->hasToMuchDimensions($text)) {
                $text = $this->deMultidimensionalizeArray($text);
            }
            # Hier können wir später die bereits bearbeiteten Dateien ausschließen.
            foreach ($text as $textname => $languages) {
                if ($languages === "") {
                    continue;
                }

                $complete = true;
                foreach ($languages as $lang => $value) {
                    if ($lang !== $to) {
                        $langs = array_add($langs, $lang, $lang);
                    }
                    if (!isset($languages[$to]) && isset($languages[$lang])) {
                        $complete = false;
                    }

                }
                if (!isset($languages[$to])) {
                    $fn = $filePath[$filename];
                    $t  = $text;
                    break 2;
                }
            }
        }
        $t = $this->htmlEscape($t, $to);
        $t = $this->createHints($t, $to);

        return view('languages.edit')
            ->with('texts', $t)             //Array mit vorhandenen Übersetzungen der Datei $fn in beiden Sprachen
            ->with('filename', $fn)         //Pfad zur angezeigten Datei
            ->with('title', trans('titles.languages.edit')) 
            ->with('langs', $langs)         //Ausgangssprache (1 Element)
            ->with('to', $to)               //zu bearbeitende Sprache
            ->with('langTexts', $langTexts) //Anzahl der vorhandenen Übersetzungen
            ->with('sum', $sum)             //Alle vorhandenen Texte (in allen Dateien) in beiden Sprachen in einem Array
            ->with('new', $ex["new"])       //
            ->with('email', $email);        //Email-Adresse des Benutzers
    }

    public function createSynopticEditPage(Request $request, $exclude = "") 
    {
        $languageFilePath = resource_path() . "/lang/";
        $languageFolders  = scandir($languageFilePath); 
        #Enthält zu jeder Sprache ein Objekt mit allen Daten
        $languageObjects  = [];
        $to = [];

        #Instanziiere LanguageObject
        foreach ($languageFolders as $folder) {
            if (is_dir($languageFilePath . $folder) && $folder !== "." && $folder !== "..") {
                $languageObjects[$folder] = new LanguageObject($folder, $languageFilePath.$folder);
            }
        }

        #Speichere Daten in LanguageObject
        foreach ($languageObjects as $folder => $languageObject) {
            $to[] = $folder;
            $di = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($languageObject->filePath));
            foreach($di as $filename => $file) {
                if(!$this->endsWith($filename, ".")) {
                    $tmp = include $filename;
                    foreach ($tmp as $key => $value) {
                        $languageObject->saveData(basename($filename), $key, $value);
                    }
                }
            }
        }

        #Dekodieren ausgeschlossener Dateien anhand des URL-Parameters
        $ex    = ['files' => [], 'new' => 0];
        if ($exclude !== "") {
            try {
                $ex = unserialize(base64_decode($exclude));
            } catch (\ErrorException $e) {
                $ex = ['files' => [], 'new' => 0];
            }
        }

        $fn = "";

        #Wähle die erste Datei aus, welche nicht ausgeschlossen worden ist
        foreach($languageObjects as $folder => $languageObject) {
            foreach($languageObject->stringMap as $languageFileName => $languageFile) {
                foreach($ex['files'] as $file) {
                    if($file === $languageFileName) {
                        continue 2;
                    }
                }
                $fn = $languageFileName;
                break 2;            
            }
        }

        if($fn === "") {
            //Alles bearbeitet -> zeige entsprechende Nachricht
        }

        $snippets = [];

        #Speichere den Inhalt der ausgewählten Datei in allen Sprachen in $snippets ab
        foreach($languageObjects as $folder => $languageObject) {
            foreach($languageObject->stringMap as $languageFileName => $languageFile) {
                if($languageFileName === $fn) {
                    foreach($languageFile as $key => $value) {
                        $snippets[$key][$languageObject->language] = $value;      
                    }
                    continue 2;
                }
            }
        }

        #Fülle $snippets auf mit leeren Einträgen für übrige Sprachen
        foreach($to as $t) {
            foreach($snippets as $key => $langArray) {
                if(!isset($langArray[$t])) {
                    $snippets[$key][$t] = "";
                }
            }
        }

        return view('languages.synoptic')
            ->with('to', $to)           #Alle vorhandenen Sprachen
            ->with('texts', $snippets)         #Array mit Sprachsnippets
            ->with('filename', $fn)     #Name der Datei
            ->with('title', trans('titles.languages.edit'));
    }

    private function htmlEscape($t, $to)
    {
        foreach ($t as $key => $langTexts) {
            if ($langTexts !== "") {
                foreach ($langTexts as $lang => $text) {
                    if ($lang !== $to) {
                        $t[$key][$lang] = htmlspecialchars($text);
                    }
                }
            }
        }
        return $t;
    }

    private function createHints($t, $to)
    {
        foreach ($t as $key => $langTexts) {
            if ($langTexts !== "") {
                foreach ($langTexts as $lang => $text) {
                    if ($lang !== $to) {
                        if (preg_match("/:\w+/si", $text)) {
                            $t[$key][$lang] = preg_replace("/(:\w+)/si", "<a class=\"text-danger hint\" data-toggle=\"tooltip\" data-trigger=\"hover\" data-placement=\"auto\" title=\"Dies ist ein Variablenname. Er wird dort, wo der Text verwendet wird durch einen dynamischen Wert ersetzt. In der Übersetzung sollte dieser deshalb auch so wie er ist in den Satz integriert werden.\" data-container=\"body\" >$1</a>", $text);
                        }
                        if (preg_match("/&lt;.*?&gt;/si", $text)) {
                            $t[$key][$lang] = preg_replace("/(&lt;.*?&gt;)/si", "<a class=\"text-danger hint\" data-toggle=\"tooltip\" data-trigger=\"hover\" data-placement=\"auto\" title=\"Dies ist ein sogenanntes HTML-Tag. Wenn Sie sich das zutrauen, bauen Sie diese HTML Tags gerne so wie sie sind in Ihre Übersetzung ein. Achten Sie hierbei darauf, dass der Text zwischen den Tags auch bei der Übersetzung an der logisch gleichen Stelle von den Tags umfasst ist.\" data-container=\"body\" >$1</a>", $text);
                        }

                    }
                }
            }
        }
        return $t;
    }

    private function getValues($values, $prefix = "")
    {
        $return = [];
        if (!is_array($values)) {
            return $return;
        } else {
            foreach ($values as $key => $value) {
                if (is_array($value)) {
                    $return = array_merge($return, $this->getValues($value, $prefix . $key));
                } elseif (is_string($value)) {
                    $return[$prefix . $key] = $value;
                }
            }
        }

        return $return;
    }

    private function hasToMuchDimensions($t)
    {
        foreach ($t as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $lang => $val) {
                    if (is_array($val)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function deMultidimensionalizeArray($t)
    {
        # Es gibt noch Besonderheiten in den Texten
        # Es kann sein, dass statt einem String ein Array aus Strings als Werte existieren.
        # Diese müssen aufgelöst werden:
        $tmp = [];
        foreach ($t as $key => $value) {
            $isArray = false;
            if (is_array($value)) {
                foreach ($value as $lang => $val) {
                    if (is_array($val)) {
                        $isArray = true;
                    }

                }
            } else {
                $tmp[$key] = $value;
                continue;
            }
            if (!$isArray) {
                $tmp[$key] = $value;
            } else {
                $tmp[$key] = "";
                foreach ($value as $lang => $val) {
                    if (is_array($val)) {
                        foreach ($val as $key2 => $val) {
                            $tmp["\t" . $key . "#" . $key2][$lang] = $val;
                        }

                    }
                }
            }

        }
        return $tmp;
    }

    public function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public function endsWith($haystack, $needle)
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }
}
