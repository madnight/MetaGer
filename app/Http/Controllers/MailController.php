<?php

namespace App\Http\Controllers;

use App\Mail\Kontakt;
use App\Mail\Sprachdatei;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LaravelLocalization;
use Mail;
use Validator;
use \IBAN;
use \IBANCountry;

class MailController extends Controller
{
    /**
     * Load Startpage accordingly to the given URL-Parameter and Mobile
     *
     * @param  int  $id
     * @return Response
     */
    public function contactMail(Request $request)
    {

        # Nachricht, die wir an den Nutzer weiterleiten:
        $messageType = ""; # [success|error]
        $returnMessage = '';

        # Wir benötigen 3 Felder von dem Benutzer wenn diese nicht übermittelt wurden, oder nicht korrekt sind geben wir einen Error zurück
        $validator = Validator::make(
            [
                'email' => $request->input('email'),
            ],
            [
                'email' => 'required|email',
            ]
        );

        if ($validator->fails()) {
            return view('kontakt.kontakt')->with('formerrors', $validator)->with('title', trans('titles.kontakt'))->with('navbarFocus', 'kontakt');
        }

        $name = $request->input('name', '');

        $replyTo = $request->input('email', 'noreply@metager.de');
        if ($replyTo === "") {
            $replyTo = "noreply@metager.de";
        } else {
            $replyTo = $request->input('email');
        }

        if (!$request->has('message') || !$request->has('subject')) {
            $messageType = "error";
            $returnMessage = "Tut uns leid, aber leider haben wir mit Ihrer Kontaktanfrage keine Daten erhalten. Die Nachricht wurde nicht versandt.";
        } else {
            # Wir versenden die Mail des Benutzers an uns:
            $message = $request->input('message');
            $subject = $request->input('subject');
            Mail::to("support@suma-ev.de")
                ->send(new Kontakt($name, $replyTo, $subject, $message));

            $returnMessage = 'Ihre Nachricht wurde uns erfolgreich zugestellt. Vielen Dank dafür! Wir werden diese schnellstmöglich bearbeiten und uns dann ggf. wieder bei Ihnen melden.';
            $messageType = "success";
        }

        return view('kontakt.kontakt')
            ->with('title', 'Kontakt')
            ->with('js', ['openpgp.min.js', 'kontakt.js'])
            ->with($messageType, $returnMessage);
    }

    public function donation(Request $request)
    {
        $data = [
            'name' => $request->input('Name', ''),
            'iban' => $request->input('iban', ''),
            'bic' => $request->input('bic', ''),
            'email' => $request->input('email', ''),
            'betrag' => $request->input('Betrag', ''),
            'nachricht' => $request->input('Nachricht', ''),
        ];
        $name = $request->input('Name', '');
        $iban = $request->input('iban', '');
        $bic = $request->input('bic', '');
        $email = $request->input('email', '');
        $betrag = $request->input('Betrag', '');
        $nachricht = $request->input('Nachricht', '');

        # Der enthaltene String wird dem Benutzer nach der Spende ausgegeben
        $messageToUser = "";
        $messageType = ""; # [success|error]

        # Check the IBAN
        $iban = new IBAN($iban);
        $bic = $request->input('Bankleitzahl', '');
        $country = new IBANCountry($iban->Country());
        $isSEPA = filter_var($country->IsSEPA(), FILTER_VALIDATE_BOOLEAN);

        # Check the amount
        $validBetrag = is_numeric($betrag) && $betrag > 0;
        $betrag = filter_var($betrag, FILTER_VALIDATE_INT);

        # Validate Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = "anonymous@suma-ev.de";
        }

        if (!$iban->Verify()) {
            $messageToUser = "Die eingegebene IBAN scheint nicht Korrekt zu sein. Nachricht wurde nicht gesendet";
            $messageType = "error";
        } else if (!$isSEPA && $bic === '') {
            $messageToUser = "Die eingegebene IBAN gehört nicht zu einem Land aus dem SEPA Raum. Für einen Bankeinzug benötigen wir eine BIC von Ihnen.";
            $messageType = "error";
        } else if (!$validBetrag) {
            $messageToUser = "Der eingegebene Spendenbetrag ist ungültig. Bitte korrigieren Sie Ihre Eingabe und versuchen es erneut.\n";
            $messageType = "error";
        } else {

            # Folgende Felder werden vom Spendenformular als Input übergeben:
            # Name
            # Telefon
            # email
            # Kontonummer ( IBAN )
            # Bankleitzahl ( BIC )
            # Nachricht

            $message = "\r\nName: " . $name;
            $message .= "\r\nIBAN: " . $iban->HumanFormat();
            if ($bic !== "") {
                $message .= "\r\nBIC: " . $bic;
            }

            $message .= "\r\nBetrag: " . $betrag;
            $message .= "\r\nNachricht: " . $nachricht;

            try {
                Mail::to("spenden@suma-ev.de")
                    ->send(new \App\Mail\Spende($email, $message, $name));

                $messageType = "success";
                $messageToUser = "Herzlichen Dank!! Wir haben Ihre Spendenbenachrichtigung erhalten.";

                try {
                    // Add the donation to our database
                    $spenden = DB::connection('spenden')->table('debits')->insert(
                        ['name' => $name,
                            'iban' => $iban->MachineFormat(),
                            'bic' => $bic,
                            'amount' => $betrag,
                            'message' => $nachricht,
                        ]
                    );
                    DB::disconnect('spenden');
                } catch (\Illuminate\Database\QueryException $e) {

                }

            } catch (\Swift_TransportException $e) {
                $messageType = "error";
                $messageToUser = 'Beim Senden Ihrer Spendenbenachrichtigung ist ein Fehler auf unserer Seite aufgetreten. Bitte schicken Sie eine E-Mail an: office@suma-ev.de, damit wir uns darum kümmern können.';
            }
        }

        if ($messageType === "error") {
            return view('spende.spende')
                ->with('title', 'Kontakt')
                ->with($messageType, $messageToUser)
                ->with('data', $data);
        } else {
            $data['iban'] = $iban->HumanFormat();
            $data = base64_encode(serialize($data));
            return redirect(LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), route("danke", ['data' => $data])));
        }

    }

    #Ueberprueft ob ein bereits vorhandener Eintrag bearbeitet worden ist
    public static function isEdited($k, $v, $filename)
    {
        try {
            $temp = include resource_path() . "/" . $filename;
            foreach ($temp as $key => $value) {
                if ($k === $key && $v !== $value) {
                    return true;
                }
            }
        } catch (\ErrorException $e) {
            #Datei existiert noch nicht
            return true;
        }
        return false;
    }

    public function sendLanguageFile(Request $request, $from, $to, $exclude = "", $email = "")
    {
        $filename = $request->input('filename');
        # Wir erstellen nun zunächst den Inhalt der Datei:
        $data = [];
        $new = 0;
        $emailAddress = "";
        $editedKeys = "";
        foreach ($request->all() as $key => $value) {

            if ($key === "filename" || $value === "") {
                continue;
            }
            if ($key === "email") {
                $emailAddress = $value;
                continue;
            }
            $key = base64_decode($key);
            if (strpos($key, "_new_") === 0 && $value !== "") {
                $new++;
                $key = substr($key, strpos($key, "_new_") + 5);
                $editedKeys = $editedKeys . "\n" . $key;

            } else if ($this->isEdited($key, $value, $filename)) {
                $new++;
                $editedKeys = $editedKeys . "\n" . $key;
            }

            $key = trim($key);
            if (!strpos($key, "#")) {
                $data[$key] = $value;
            } else {
                $ref = &$data;
                do {
                    $ref = &$ref[substr($key, 0, strpos($key, "#"))];
                    $key = substr($key, strpos($key, "#") + 1);
                } while (strpos($key, "#"));
                $ref = &$ref[$key];
                $ref = $value;
            }
        }

        $output = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $output = preg_replace("/\{/si", "[", $output);
        $output = preg_replace("/\}/si", "]", $output);
        $output = preg_replace("/\": ([\"\[])/si", "\"\t=>\t$1", $output);

        $output = "<?php\n\nreturn $output;\n";

        $message = "Moin moin,\n\nein Benutzer hat eine Sprachdatei aktualisiert.\nBearbeitet wurden die Einträge: $editedKeys\n\nSollten die Texte so in Ordnung sein, ersetzt, oder erstellt die Datei aus dem Anhang in folgendem Pfad:\n$filename\n\nFolgend zusätzlich der Inhalt der Datei:\n\n$output";

        # Wir haben nun eine Mail an uns geschickt, welche die entsprechende Datei beinhaltet.
        # Nun müssen wir den Nutzer eigentlich nur noch zurück leiten und die Letzte bearbeitete Datei ausschließen:
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
        $ex["new"] += $new;

        if ($new > 0) {
            if ($emailAddress !== "") {
                Mail::to("dev@suma-ev.de")
                    ->send(new Sprachdatei($message, $output, basename($filename), $emailAddress));
            } else {
                Mail::to("dev@suma-ev.de")
                    ->send(new Sprachdatei($message, $output, basename($filename)));
            }
        }
        $ex = base64_encode(serialize($ex));

        return redirect(url('languages/edit', ['from' => $from, 'to' => $to, 'exclude' => $ex, 'email' => $emailAddress]));
    }

}
