<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class LogRotate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:rotate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dieses Kommando bezieht alle Log-Einträge aus dem Redis System und exportiert diese in entsprechende Dateien.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        # Wir extrahieren die Logs mit den Daten der einzelnen Suchmaschinen:
        # In den Daten ist festgehalten, wie oft eine jede Suchmaschine abgefragt wurde
        # und wie häufig diese geantwortet hat:
        $this->extractSearchEngineLogs();
    }

    private function extractSearchEngineLogs()
    {
        $redis = Redis::connection('redisLogs');
        # Hier legen wir das Ergebnis ab:
        # [
        #   'fastbot'   => [
        #                       ''
        #                   ]
        # ]
        $searchEngineLogs = ['recent' => [], 'overall' => []];
        try {
            # Wir benötigen zunächst die Namen aller Suchmaschinen:
            $sumasFile = config_path() . "/sumas.xml";
            $xml       = simplexml_load_file($sumasFile);
            $xml       = $xml->xpath("suma");
            $sumaNames = [];
            foreach ($xml as $suma) {
                $searchEngineLogs['recent'][$suma["name"]->__toString()] = ["requests" => 0, "answered" => 0];
            }
            #Auslesen/hinzufügen der Daten:
            foreach ($searchEngineLogs['recent'] as $name => $values) {
                $searchEngineLogs['recent'][$name]["requests"] = $redis->getset('logs.engines.requests.' . $name, 0);
                $searchEngineLogs['recent'][$name]["answered"] = $redis->getset('logs.engines.answered.' . $name, 0);
            }

            $filePath = "/var/log/metager/";
            # Wir haben nun die aktuellen daten und müssen diese noch mit den aktuellen Kombinieren:
            # Hierbei behalten wir die Daten, seid dem letzten Rotate so wie sie sind und verknüpfen diese mit einer gesamt Statistik.
            if (file_exists($filePath . "engine.log") && is_readable($filePath . "engine.log")) {
                $oldData = file_get_contents($filePath . "engine.log");
                # JSON Decode
                $oldData = json_decode($oldData, true);
                if (isset($oldData['overall'])) {
                    $searchEngineLogs['overall'] = $oldData['overall'];
                }
            }

            # Jetzt fügen wir zu den Gesamtdaten die zuletzt erfassten hinzu:
            foreach ($searchEngineLogs['recent'] as $name => $values) {
                if (isset($searchEngineLogs['overall'][$name]["requests"])) {
                    $searchEngineLogs['overall'][$name]["requests"] += $values["requests"];
                } else {
                    $searchEngineLogs['overall'][$name]["requests"] = $values["requests"];
                }
                if (isset($searchEngineLogs['overall'][$name]["answered"])) {
                    $searchEngineLogs['overall'][$name]["answered"] += $values["answered"];
                } else {
                    $searchEngineLogs['overall'][$name]["answered"] = $values["answered"];
                }
            }

            # Ins JSON Format umwandeln:
            $searchEngineLogs = json_encode($searchEngineLogs, JSON_PRETTY_PRINT);

            # Und in die Datei sichern
            if (((file_exists($filePath . "engine.log") && is_writable($filePath . "engine.log")) || (!file_exists($filePath . "engine.log") && is_writable($filePath))) && file_put_contents($filePath . "engine.log", $searchEngineLogs) !== false) {
                $this->info("Logs der Suchmaschinen erfolgreich exportiert.");
                return;
            } else {
                # Der Schreibvorgang war nicht erfolgreich. Wir packen die Daten zurück
                foreach (json_decode($searchEngineLogs, true) as $name => $values) {
                    $redis->incrby('logs.engines.requests.' . $name, $values["requests"]);
                    $redis->incrby('logs.engines.answered.' . $name, $values["answered"]);
                }
                $this->info("Konnte die Datei \"$filePath" . "engine.log\" nicht erstellen. Keine Rechte.");
                return;
            }

        } catch (\ErrorException $e) {
            return;
        }
    }
}
