<?php

namespace App\Models;

/*
*	Beinhaltet zu je einer Sprache Angaben zum Pfad der jeweiligen Datei, sowie die vorhandenen Übersetzungen
*/
class LanguageFile 
{

	public $filePath = "";
	public $stringMap = [];

	public function __construct($path) 
    {
        $this->filePath = $path;
    }



}