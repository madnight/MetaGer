<?php

namespace App\Http\Middleware;

use Captcha;
use Carbon;
use Closure;
use DB;
use Illuminate\Http\Response;

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

            $id = md5($request->ip() . $_SERVER["AGENT"]);
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

            $user = DB::table('humanverification')->where('id', $id)->first();
            $createdAt = Carbon::now();
            $unusedResultPages = 1;
            $locked = false;
            # If this user doesn't have an entry we will create one
            if ($user === null) {
                DB::table('humanverification')->insert(
                    ['id' => $id, 'unusedResultPages' => 1, 'locked' => false, "lockedKey" => "", 'updated_at' => Carbon::now()]
                );
                # Insert the URL the user tries to reach
                $url = url()->full();
                DB::table('usedurls')->insert(['user_id' => $id, 'url' => $url]);
                $user = DB::table('humanverification')->where('id', $id)->first();
            } else if ($user->locked !== 1) {
                $unusedResultPages = intval($user->unusedResultPages);
                $unusedResultPages++;
                # We have different security gates:
                #   50, 75, 85, >=90 => Captcha validated Result Pages
                # If the user shows activity on our result page the counter will be deleted
                # Maybe I'll add a ban if the user reaches 100
                if ($unusedResultPages === 50 || $unusedResultPages === 75 || $unusedResultPages === 85 || $unusedResultPages >= 90) {
                    $locked = true;
                }
                DB::table('humanverification')->where('id', $id)->update(['unusedResultPages' => $unusedResultPages, 'locked' => $locked, 'updated_at' => $createdAt]);
                # Insert the URL the user tries to reach
                DB::table('usedurls')->insert(['user_id' => $id, 'url' => url()->full()]);
            }
            $request->request->add(['verification_id' => $id, 'verification_count' => $unusedResultPages]);

            # If the user is locked we will force a Captcha validation
            if ($user->locked === 1) {
                $captcha = Captcha::create("default", true);
                DB::table('humanverification')->where('id', $id)->update(['lockedKey' => $captcha["key"]]);
                return
                new Response(
                    view('captcha')
                        ->with('title', "Bestätigung erforderlich")
                        ->with('id', $id)
                        ->with('url', url()->full())
                        ->with('image', $captcha["img"])
                );
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Failure in contacting metager3.de
        }

        return $next($request);
    }
}
