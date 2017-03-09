<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZitatController extends Controller
{
    public function zitatSuche(Request $request)
    {
        $validResults = [];
        if ($request->has("q")) {
            # The user searched for something
            $fileName    = storage_path() . "/app/public/zitate.txt";
            $fileContent = file_get_contents($fileName);

            # Die Suchworte sind UND verknüpft, müssen aber nicht als gesamtes Wort vorkommen
            $all = explode(PHP_EOL, $fileContent);

            $words = preg_split("/\s+/", $request->input('q'));

            # Loop through all
            foreach ($all as $zitat) {
                $valid = true;
                # A Result isn't valid when it doesn't Contain every search word
                foreach ($words as $word) {
                    if (stripos($zitat, $word) === false) {
                        $valid = false;
                        break;
                    }
                }
                if ($valid) {
                    # This Result is valid. We'll add it Sorted by author
                    if (preg_match("/^\"([^\"]+)\"\s(.*)$/", $zitat, $matches)) {
                        $quote  = $matches[1];
                        $author = $matches[2];

                        if (isset($validResults[$author])) {
                            $validResults[$author][] = $quote;
                        } else {
                            $validResults[$author] = [$quote];
                        }
                    }
                }
            }
        }

        return view('zitatsuche')
            ->with('title', 'Zitatsuche MetaGer')
            ->with('navbarFocus', 'dienste')
            ->with('results', $validResults)
            ->with('q', $request->input('q', ''));
    }
}
