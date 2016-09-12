<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
                        $sum = array_add($sum, str_replace(".php", "", basename($filename)) . str_replace(".", "", $key), 1);
                    }
                    $langTexts[$dir]["textCount"] += count($tmp);
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

    public function createEditPage($from, $to)
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

        foreach ($dirs as $dir) {
            if ($from !== "all" && $dir !== $to && $dir !== $from) {
                continue;
            }

            # Wir überprüfen nun für jede Datei die Anzahl der vorhandenen Übersetzungen
            $di                           = new RecursiveDirectoryIterator($languageFilePath . $dir);
            $langTexts[$dir]["textCount"] = 0;
            $langTexts[$dir]["fileCount"] = 0;
            foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
                if (!$this->endsWith($filename, ".")) {
                    $langTexts[$dir]["fileCount"] += 1;
                    $tmp = include $filename;
                    foreach ($tmp as $key => $value) {
                        $texts[basename($filename)][$key][$dir] = $value;
                    }
                }

            }
        }
        $langs = [];
        $fn    = "";
        $t     = "";
        foreach ($texts as $filename => $texts) {
            # Hier können wir später die bereits bearbeiteten Dateien ausschließen.
            foreach ($texts as $textname => $languages) {
                foreach ($languages as $lang => $value) {
                    if ($lang !== $to) {
                        $langs = array_add($langs, $lang, $lang);
                    }

                }
                if (!isset($languages[$to])) {
                    $fn = $filename;
                    $t  = $texts;
                    break;
                }
            }

        }

        return view('languages.edit')
            ->with('texts', $t)
            ->with('filename', $fn)
            ->with('title', trans('titles.languages.edit'))
            ->with('langs', $langs)
            ->with('to', $to);
    }

    private function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    private function endsWith($haystack, $needle)
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }
}
