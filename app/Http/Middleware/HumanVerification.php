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

            // The specific user
            $user = DB::table('humanverification')->where('uid', $uid)->first();

            $createdAt = Carbon::now();
            $unusedResultPages = 1;
            $locked = false;
            # If this user doesn't have an entry we will create one

            if ($user === null) {
                DB::table('humanverification')->insert(
                    [
                        'uid' => $uid,
                        'id' => $id,
                        'unusedResultPages' => 0,
                        'whitelist' => false,
                        'whitelistCounter' => 0,
                        'locked' => false,
                        "lockedKey" => "",
                        'updated_at' => Carbon::now(),
                    ]
                );
                # Insert the URL the user tries to reach
                $url = url()->full();
                DB::table('usedurls')->insert(['uid' => $uid, 'id' => $id, 'eingabe' => $request->input('eingabe', '')]);
                $user = DB::table('humanverification')->where('uid', $uid)->first();
            }

            # Lock out everyone in a Bot network
            # Find out how many requests this IP has made
            $sum = DB::table('humanverification')->where('id', $id)->where('whitelist', false)->sum('unusedResultPages');

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
            $alone = DB::table('humanverification')->where('id', $id)->count() === 1;
            if ((!$alone && $sum >= 50 && $user->whitelist !== 1) || $refererLock) {
                DB::table('humanverification')->where('uid', $uid)->update(['locked' => true]);
                $user->locked = 1;
            }

            # If the user is locked we will force a Captcha validation
            if ($user->locked === 1) {
                $captcha = Captcha::create("default", true);
                DB::table('humanverification')->where('uid', $uid)->update(['lockedKey' => $captcha["key"]]);
                return
                new Response(
                    view('humanverification.captcha')
                        ->with('title', "Bestätigung erforderlich")
                        ->with('id', $uid)
                        ->with('url', url()->full())
                        ->with('image', $captcha["img"])
                );
            }

            $unusedResultPages = intval($user->unusedResultPages);
            $unusedResultPages++;
            $locked = false;

            if ($alone || $user->whitelist === 1) {
                # This IP doesn't need verification yet
                # The user currently isn't locked

                # We have different security gates:
                #   50, 75, 85, >=90 => Captcha validated Result Pages
                # If the user shows activity on our result page the counter will be deleted
                # Maybe I'll add a ban if the user reaches 100

                if ($unusedResultPages === 50 || $unusedResultPages === 75 || $unusedResultPages === 85 || $unusedResultPages >= 90) {
                    $locked = true;
                }

            }
            DB::table('humanverification')->where('uid', $uid)->update(['unusedResultPages' => $unusedResultPages, 'locked' => $locked]);
            # Insert the URL the user tries to reach
            DB::table('usedurls')->insert(['uid' => $uid, 'id' => $id, 'eingabe' => $request->input('eingabe', '')]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Failure in contacting metager3.de
        }
        $request->request->add(['verification_id' => $uid, 'verification_count' => $unusedResultPages]);
        return $next($request);
    }
}
