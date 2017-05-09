<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Redis;
use Predis\Connection\ConnectionException;
use Log;

class Searcher implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $name, $ch, $pid, $counter, $lastTime;
    protected $MAX_REQUESTS = 500;

    /**
     * Create a new job instance.
     * This is our new Worker/Searcher Class
     * It will take it's name from the sumas.xml as constructor argument
     * Each Searcher is dedicated to one remote server from our supported Searchengines
     * It will listen to a queue in the Redis Database within the handle() method and
     * answer requests to this specific search engine.
     * The curl handle will be left initialized and opened so that we can make full use of
     * keep-alive requests.
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->pid = getmypid();
        // Submit this worker to the Redis System
        Redis::expire($this->name, 5);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // This Searches is freshly called so we need to initialize the curl handle $ch
        $this->ch = $this->initCurlHandle();
        $this->counter = 0;                 // Counts the number of answered jobs
        $time = microtime(true);
        while(true){
            // Update the expire
            Redis::expire($this->name, 5);
            Redis::expire($this->name . ".stats", 5);
            // One Searcher can handle a ton of requests to the same server
            // Each search to the server of this Searcher will be submitted to a queue
            // stored in redis which has the same name as this searchengine appended by a ".queue"
            // We will perform a blocking pop on this queue so the queue can remain empty for a while 
            // without killing this searcher directly.
            $mission = Redis::blpop($this->name . ".queue", 4);

            // The mission can be empty when blpop hit the timeout
            if(empty($mission)){
                continue;
            }else{
                $mission = $mission[1];
                $this->counter++;#
                $poptime = microtime(true) - $time;
            }

            // The mission is a String which can be divided to retrieve two informations:
            // 1. The Hash Value where the result should be stored
            // 2. The Url to Retrieve
            // These two informations are divided by a ";" in the mission string
            $hashValue = substr($mission, 0, strpos($mission, ";"));
            $url = substr($mission, strpos($mission, ";") + 1);

            Redis::hset('search.' . $hashValue, $this->name, "connected");

            $result = $this->retrieveUrl($url);

            $this->storeResult($result, $poptime, $hashValue);

            if($this->counter === 3){
                Redis::set($this->name, "running");
            }

            // Reset the time of the last Job so we can calculate
            // the time we have spend waiting for a new job
            // We submit that calculation to the Redis systemin the method
            // storeResult()
            $time = microtime(true);

            // In sync mode every Searcher may only retrieve one result because it would block
            // the execution of the remaining code otherwise:
            if(getenv("QUEUE_DRIVER") === "sync" || $this->counter > $this->MAX_REQUESTS){
                break;
            } 
        }
        // When we reach this point, time has come for this Searcher to retire
        $this->shutdown();
    }

    private function retrieveUrl($url){
        // Set this URL to the Curl handle
        curl_setopt($this->ch, CURLOPT_URL, $url);

        $result = curl_exec($this->ch);

        if(!empty(curl_error($this->ch))){
            // Es gab einen Fehler beim Abruf des Ergebnisses
            // diesen sollten wir protokollieren
            Log::error("Fehler in der Searcher Klasse, beim Abruf des Ergebnisses: \n" . curl_error($this->ch));
        }

        return $result;
    }

    private function storeResult($result, $poptime, $hashValue){
        Redis::hset('search.' . $hashValue, $this->name, $result);
        $connectionInfo = base64_encode(json_encode(curl_getinfo($this->ch), true));
        Redis::hset($this->name . ".stats", $this->pid, $connectionInfo . ";" . $poptime);
        $this->lastTime = microtime(true);
    }

    private function shutdown(){
        Redis::hdel($this->name . ".stats", $this->pid);
        if(sizeof(Redis::hgetall($this->name . ".stats")) === 0){
            Redis::del($this->name);
        }
        // We should close our curl handle before we do so
        curl_close($this->ch);
    }

    private function initCurlHandle(){
        $ch = curl_init();

        curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1",
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_MAXCONNECTS => 500,
                CURLOPT_LOW_SPEED_LIMIT => 500,
                CURLOPT_LOW_SPEED_TIME => 5,
                CURLOPT_TIMEOUT => 10
        ));

        return $ch;
    }
}
