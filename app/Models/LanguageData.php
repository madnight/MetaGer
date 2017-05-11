<?php

namespace App\Models;

/*
*	Beinhaltet zu je einer Sprache Angaben zum Pfad der jeweiligen Datei, sowie die vorhandenen Übersetzungen
*/
class LanguageData 
{
	public $language = "";

	public $filePath = "";

	public $stringMap = [[]];

	public function __construct($lang, $path) 
    {
    	$this->language = $lang;
        $this->filePath = $path;
    }

    public function saveData($filename, $key, $value)
    {	
    	if(is_array($value)) {
    		$this->deMultiDimensionalize($filename, $key, $value);
    	} else {
    		$this->stringMap[$filename][$key] = $value;
 	   }
	}


    private function deMultiDimensionalize($filename, $key, $value)
    {
    	foreach($value as $key2 => $value2) {
    		$this->saveData($filename, $key."#".$key2, $value2);
    	}
    }


}