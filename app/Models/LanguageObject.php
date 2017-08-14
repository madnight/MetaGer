<?php

namespace App\Models;

/*
*	Hilfsklasse, welche zu je einer Sprache Angaben zum Pfad der jeweiligen Datei, sowie die vorhandenen Übersetzungen enthält
*/
class LanguageObject 
{
	public $language = "";

	public $filePath = "";

	# 2D-Array der Form [$filename][$key]
	public $stringMap = [];

	public function __construct($lang, $path) 
    {
    	$this->language = $lang;
        $this->filePath = $path;
    }

    #Speichert Daten in $stringMap, entdimensionalisiert ggbf. $value
    public function saveData($filename, $key, $value)
    {	
    	if(is_array($value)) {
    		$this->deMultiDimensionalize($filename, $key, $value);
    	} else {
    		$this->stringMap[$filename][$key] = $value;
 	   }
	}

    #Helferfunktion für saveData
    private function deMultiDimensionalize($filename, $key, $value)
    {
    	foreach($value as $key2 => $value2) {
    		$this->saveData($filename, $key."#".$key2, $value2);
    	}
    }


}