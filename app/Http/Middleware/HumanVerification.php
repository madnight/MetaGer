<?php

namespace App\Http\Middleware;

use Captcha;
use Carbon;
use Closure;
use DB;
use Illuminate\Http\Response;
use URL;

class HumanVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // The specific user
        $user = null;
        $newUser = true;
        try {
            $id = hash("sha512", $request->ip());
            $uid = hash("sha512", $request->ip() . $_SERVER["AGENT"]);
            unset($_SERVER["AGENT"]);

            /**
             * If the user sends a Password or a key
             * We will not verificate the user.
             * If someone that uses a bot finds this out we
             * might have to change it at some point.
             */
            if ($request->has('password') || $request->has('key') || $request->has('appversion') || !env('BOT_PROTECTION', false)) {
                return $next($request);
            }

            
            $users = DB::select('select * from humanverification where id = ?', [$id]);
            
            # Lock out everyone in a Bot network
            # Find out how many requests this IP has made
            $sum = 0;
            foreach($users as $userTmp){
                if($uid == $userTmp->uid){
                    $user = ['uid' => $userTmp->uid,
                            'id' => $userTmp->id,
                            'unusedResultPages' => intval($userTmp->unusedResultPages),
                            'whitelist' => filter_var($userTmp->whitelist, FILTER_VALIDATE_BOOLEAN),
                            'whitelistCounter' => $userTmp->whitelistCounter,
                            'locked' => filter_var($userTmp->locked, FILTER_VALIDATE_BOOLEAN),
                            "lockedKey" => $userTmp->lockedKey,
                            'updated_at' => Carbon::now(),
                            ];
                    $newUser = false;
                }
                if($userTmp->whitelist === 0)
                    $sum += $userTmp->unusedResultPages;
            }
            # If this user doesn't have an entry we will create one

            if ($user === null) {
                $user =
                    [
                        'uid' => $uid,
                        'id' => $id,
                        'unusedResultPages' => 0,
                        'whitelist' => false,
                        'whitelistCounter' => 0,
                        'locked' => false,
                        "lockedKey" => "",
                        'updated_at' => Carbon::now(),
                    ];
            }

            # A lot of automated requests are from websites that redirect users to our result page.
            # We will detect those requests and put a captcha
            $referer = URL::previous();
            # Just the URL-Parameter
            $refererLock = false;
            if (stripos($referer, "?") !== false) {
                $referer = substr($referer, stripos($referer, "?") + 1);
                $referer = urldecode($referer);
                if (preg_match("/http[s]{0,1}:\/\/metager\.de\/meta\/meta.ger3\?.*?eingabe=([\w\d]+\.){1,2}[\w\d]+/si", $referer) === 1) {
                    $refererLock = true;
                }

            }

            // Defines if this is the only user using that IP Adress
            $alone = true;
            foreach($users as $userTmp){
                if($userTmp->uid != $uid && !$userTmp->whitelist)
                    $alone = false;
            }
            if ((!$alone && $sum >= 50 && !$user["whitelist"]) || $refererLock) {
                $user["locked"] = true;
            }

            # If the user is locked we will force a Captcha validation
            if ($user["locked"]) {
                $captcha = Captcha::create("default", true);
                $user["lockedKey"] = $captcha["key"];
                return
                new Response(
                    view('humanverification.captcha')
                        ->with('title', "Bestätigung erforderlich")
                        ->with('id', $uid)
                        ->with('url', url()->full())
                        ->with('image', $captcha["img"])
                );
            }

            $user["unusedResultPages"]++;

            if ($alone || $user["whitelist"]) {
                # This IP doesn't need verification yet
                # The user currently isn't locked

                # We have different security gates:
                #   50, 75, 85, >=90 => Captcha validated Result Pages
                # If the user shows activity on our result page the counter will be deleted
                # Maybe I'll add a ban if the user reaches 100

                if ($user["unusedResultPages"] === 50 || $user["unusedResultPages"] === 75 || $user["unusedResultPages"] === 85 || $user["unusedResultPages"] >= 90) {
                    $user["locked"] = true;
                }

            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Failure in contacting metager3.de
        } finally {
            // Update the user in the database
            if($newUser){
                DB::table('humanverification')->insert(
                    [
                        'uid' => $user["uid"],
                        'id' => $user["id"],
                        'unusedResultPages' => $user['unusedResultPages'],
                        'whitelist' => $user["whitelist"],
                        'whitelistCounter' => $user["whitelistCounter"],
                        'locked' => $user["locked"],
                        "lockedKey" => $user["lockedKey"],
                        'updated_at' => $user["updated_at"],
                    ]
                );
            }else{
                DB::table('humanverification')->where('uid', $uid)->update(
                    [
                        'uid' => $user["uid"],
                        'id' => $user["id"],
                        'unusedResultPages' => $user['unusedResultPages'],
                        'whitelist' => $user["whitelist"],
                        'whitelistCounter' => $user["whitelistCounter"],
                        'locked' => $user["locked"],
                        "lockedKey" => $user["lockedKey"],
                        'updated_at' => $user["updated_at"],
                    ]
                    );
            }
        }
        $request->request->add(['verification_id' => $user["uid"], 'verification_count' => $user["unusedResultPages"]]);
        return $next($request);
    }
}
