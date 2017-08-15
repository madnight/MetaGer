<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LanguageObject;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class LanguageController extends Controller
{

    public function __construct() 
    {
       $this->languageFilePath = resource_path()."/lang/";
       $this->languages = array('de','en','fr','es','nd');
    }

    public function createOverview(Request $request)
    {
        $languageFolders  = scandir($this->languageFilePath);
        $dirs             = [];

        foreach ($languageFolders as $folder) {
            if (is_dir($this->languageFilePath . $folder) && $folder !== "." && $folder !== "..") {
                $dirs[] = $folder;
            }
        }
        # Im Array "$dirs" haben wir nun alle Verzeichnisse mit dem entsprechenden Sprachkürzel
        # Alle von uns bislang unterstützen Sprachen sind hier eingetragen.
        $langTexts = [];
        $sum       = [];
        foreach ($dirs as $dir) {
            # Wir überprüfen nun für jede Datei die Anzahl der vorhandenen Übersetzungen
            $di                           = new RecursiveDirectoryIterator($this->languageFilePath . $dir);
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
        $languageFolders  = scandir($this->languageFilePath);
        $dirs             = [];

        foreach ($languageFolders as $folder) {
            if (is_dir($this->languageFilePath . $folder) && $folder !== "." && $folder !== "..") {
                $dirs[$folder] = $folder;
            }
        }

        # Abbruchbedingungen:
        if (!in_array($to, $this->languages) || $from === "" || $to === "" || ($from !== "de" && $from !== "all") || ($from === "all" && $to !== "de") && !array_has($dirs, $to)) {
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
            $di              = new RecursiveDirectoryIterator($this->languageFilePath . $dir);
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
        $ex = $this->decodeExcludedFiles($exclude);

        foreach ($texts as $filename => $text) {
            foreach ($ex['files'] as $file) {
                if ($file === $filename) {
                    continue 2;
                }
            }
            while ($this->hasToMuchDimensions($text)) {
                $text = $this->deMultidimensionalizeArray($text);
            }
            # Hier können wir später die bereits bearbeiteten Dateien ausschließen.
            foreach ($text as $textname => $languages) {
                if ($languages === "") {
                    continue;
                }

                foreach ($languages as $lang => $value) {
                    if ($lang !== $to) {
                        $langs = array_add($langs, $lang, $lang);
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
        $languageFolders  = scandir($this->languageFilePath); 

        # Enthält zu jeder Sprache ein Objekt mit allen Daten
        $languageObjects  = [];

        # Alle vorhandenen Sprachen
        $to = [];

        # Dekodieren ausgeschlossener Dateien anhand des URL-Parameters
        $ex = $this->decodeExcludedFiles($exclude);

        # Instanziiere LanguageObject
        foreach ($languageFolders as $folder) {
            if (is_dir($this->languageFilePath . $folder) && $folder !== "." && $folder !== "..") {
                $languageObjects[$folder] = new LanguageObject($folder, $this->languageFilePath.$folder);
            }
        }
        $fileNames = [];
        # Speichere Daten in LanguageObject, überspringe ausgeschlossene Dateien
        foreach ($languageObjects as $folder => $languageObject) {
            $to[] = $folder;
            $di = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($languageObject->filePath));
            foreach($di as $filename => $file) {
                if(!$this->endsWith($filename, ".") && !in_array(basename($filename), $fileNames)) {
                    $fileNames[] = basename($filename);
                }
                foreach($ex['files'] as $file) {
                    if($file === basename($filename)) {
                        continue 2;
                    }
                }
                if(!$this->endsWith($filename, ".")) {
                    $tmp = include $filename;
                    foreach ($tmp as $key => $value) {
                        $languageObject->saveData(basename($filename), $key, $value);
                    }
                }
            }
        }

        $fn = "";

        # Wähle die erste, unbearbeitete Datei aus
        foreach($languageObjects as $folder => $languageObject) {
            foreach($languageObject->stringMap as $languageFileName => $languageFile) {
                $fn = $languageFileName;
                break 2;            
            }
        }

        $snippets = [];
        $changeTime = 0;
        $recentlyChangedFiles = [];

        # Speichere den Inhalt der ausgewählten Datei in allen Sprachen in $snippets ab
        foreach($languageObjects as $folder => $languageObject) {
            foreach($languageObject->stringMap as $languageFileName => $languageFile) {
                if($languageFileName === $fn) {
                    if($changeTime < filemtime($languageObject->filePath."/".$languageFileName)) {
                        unset($recentlyChangedFiles);
                        $changeTime = filemtime($languageObject->filePath."/".$languageFileName);
                        $recentlyChangedFiles[] = $languageObject->language; 
                    } else if($changeTime === filemtime($languageObject->filePath."/".$languageFileName)) {
                        $recentlyChangedFiles[] = $languageObject->language; 
                    }
                    foreach($languageFile as $key => $value) {
                        $snippets[$key][$languageObject->language] = $value;      
                    }
                    continue 2;
                }
            }
        }

        # Fülle $snippets auf mit leeren Einträgen für die restlichen Sprachen
        foreach($to as $t) {
            foreach($snippets as $key => $langArray) {
                if(!isset($langArray[$t])) {
                    $snippets[$key][$t] = "";
                }
            }
        }

        return view('languages.synoptic')
            ->with('to', $to)           # Alle vorhandenen Sprachen
            ->with('texts', $snippets)         # Array mit Sprachsnippets
            ->with('filename', $fn)     # Name der Datei
            ->with('recentlyChangedFiles', $recentlyChangedFiles)
            ->with('otherFiles', $fileNames) # Namen der restlichen Sprachdateien
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

    public function processSynopticPageInput(Request $request, $exclude = "") {

        $filename = $request->input('filename');

        # Identifizieren des gedrückten Buttons
        if(isset($request['nextpage'])) {

            # Leite weiter zur nächsten Seite
            $ex = [];

            if ($exclude !== "") {
                try {
                    $ex = unserialize(base64_decode($exclude));
                } catch (\ErrorException $e) {
                    $ex = [];
                }

                if (!isset($ex["files"])) {
                    $ex["files"] = [];
                }
            }
            if (!isset($ex["new"])) {
                $ex["new"] = 0;
            }
            $ex['files'][] = basename($filename);
            $ex = base64_encode(serialize($ex));

            return redirect(url('synoptic', ['exclude' => $ex]));

        } elseif(isset($request['download'])) {
        # Andernfalls auslesen, zippen und herunterladen der veränderten Dateien 
         
            $data = [];
            $new  = 0;
            $editedFiles = [];

            foreach ($request->all() as $key => $value) {

                if ($key === "filename" || $value === "") {
                    continue;
                }

                $key = base64_decode($key);

                # Pfad zur Datei anhand des Schlüsselnamens rekonstruieren (Schlüssel enthält Sprachkürzel)
                $langdir = $this->extractLanguage($key);
                $filepath = "lang/".$langdir."/".$filename;
     
                if (strpos($key, "_new_") === 0 && $value !== "" || MailController::isEdited($this->processKey($key), $value, $filepath)) {
                    $new++;
                    $editedFiles[$langdir] = $filepath;
                } 
            }     

            # Erneute Iteration über Request, damit Dateien mitsamt vorherigen Einträgen abgespeichert werden 
            foreach($request->all() as $key => $value) {

                if ($key === "filename" || $value === "") {
                    continue;
                }

                $key = base64_decode($key);

                # Pfad zur Datei anhand des Schlüsselnamens rekonstruieren (Schlüssel enthält Sprachkürzel)
                $langdir = $this->extractLanguage($key);

                # Überspringe Datei, falls diese nicht bearbeitet worden ist
                if(!isset($editedFiles[$langdir])) {
                    continue;
                }

                # Key kuerzen, sodass er nur den eigentlichen Keynamen enthält
                $key = $this->processKey($key);
                
                if (!strpos($key, "#")) {
                    $data[$langdir][$key] = $value;
                # Aufdröseln von 2D-Arrays
                } else {
                    $ref = &$data;
                    do {
                        $ref = &$ref[$langdir][substr($key, 0, strpos($key, "#"))];
                        $key = substr($key, strpos($key, "#") + 1);
                    } while (strpos($key, "#"));
                    $ref = &$ref[$key];
                    $ref = $value;
                }
            }

            if(file_exists("/tmp/langfiles.zip"))
                unlink("/tmp/langfiles.zip");

            $zip = new ZipArchive();

            if (empty($data) || $zip->open("/tmp/langfiles.zip", ZipArchive::CREATE) !== TRUE) {
                return redirect(url('synoptic', ['exclude' => $exclude]));
            } 
                
            try{
            # Erstelle Ausgabedateien
                foreach($data as $lang => $entries) {
                    $output = json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    $output = preg_replace("/\{/si", "[", $output);
                    $output = preg_replace("/\}/si", "]", $output);
                    $output = preg_replace("/\": ([\"\[])/si", "\"\t=>\t$1", $output);
                    $output = "<?php\n\nreturn $output;\n";
                    $zip->addEmptyDir($lang);
                    $zip->addFromString($lang."/".$filename, $output);
                }

            $zip->close();

            return response()->download("/tmp/langfiles.zip", $filename.".zip");
                    } catch(ErrorException $e) {
                echo("Failed to write ".$filename);
                }
        }
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

    private function decodeExcludedFiles($exclude)
    {
        $ex = ['files' => [], 'new' => 0];

        if ($exclude !== "") {
            try {
                $ex = unserialize(base64_decode($exclude));
            } catch (ErrorException $e) {
                $ex = ['files' => [], 'new' => 0];
            }
        }

        return $ex;
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

    private function extractLanguage($key) 
    {   
        # Kürzt bspw. "_new_de_redirect bzw. "de_redirect" zu "de"
        preg_match("/^(?:_new_)?([^_]*)/", $key, $matches);
        foreach($matches as $dir) {
            if(strlen($dir) == 2)
                return $dir;
            }
    }

    private function processKey($key) 
    {   
        $key = trim($key);
        # Kürzt bspw. "_new_de_redirect bzw. "de_redirect" zu "redirect"
        preg_match("/^(?:_new_)?(?:[^_]*)_(\w*.?\w*#?.?\w*)/", $key, $matches);
        foreach($matches as $processedKey) {
            if(strpos($processedKey, "_") === FALSE) {
                return $processedKey;
            }
        }
        return $key;
    }
}
