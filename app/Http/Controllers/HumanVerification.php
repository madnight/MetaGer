<?php

namespace App\Http\Controllers;

use Captcha;
use Carbon;
use DB;
use Illuminate\Hashing\BcryptHasher as Hasher;
use Illuminate\Http\Request;
use Input;

class HumanVerification extends Controller
{
    public static function captcha(Request $request, Hasher $hasher, $id, $url = null)
    {
        if ($url != null) {
            $url = base64_decode(str_replace("<<SLASH>>", "/", $url));
        } else {
            $url = $request->input('url');
        }

        if ($request->getMethod() == 'POST') {
            $user = DB::table('humanverification')->where('uid', $id)->first();

            $lockedKey = $user->lockedKey;
            $key = $request->input('captcha');
            $key = strtolower($key);

            if (!$hasher->check($key, $lockedKey)) {
                $captcha = Captcha::create("default", true);
                DB::table('humanverification')->where('uid', $id)->update(['lockedKey' => $captcha["key"]]);
                return view('humanverification.captcha')->with('title', 'Bestätigung notwendig')
                    ->with('id', $id)
                    ->with('url', $url)
                    ->with('image', $captcha["img"])
                    ->with('errorMessage', 'Fehler: Falsches Captcha eingegeben!');
            } else {
                # If we can unlock the Account of this user we will redirect him to the result page
                if ($user !== null && $user->locked === 1) {
                    # The Captcha was correct. We can remove the key from the user
                    DB::table('humanverification')->where('uid', $id)->update(['locked' => false, 'lockedKey' => "", 'whitelist' => 1]);
                    return redirect($url);
                } else {
                    return redirect('/');
                }
            }
        }
        $captcha = Captcha::create("default", true);
        DB::table('humanverification')->where('uid', $id)->update(['lockedKey' => $captcha["key"]]);
        return view('humanverification.captcha')->with('title', 'Bestätigung notwendig')
            ->with('id', $id)
            ->with('url', $url)
            ->with('image', $captcha["img"]);
    }

    public static function remove(Request $request)
    {
        if (!$request->has('mm')) {
            abort(404, "Keine Katze gefunden.");
        }

        if (HumanVerification::checkId($request, $request->input('mm'))) {
            HumanVerification::removeUser($request, $request->input('mm'));
        }
        return response(hex2bin('89504e470d0a1a0a0000000d494844520000000100000001010300000025db56ca00000003504c5445000000a77a3dda0000000174524e530040e6d8660000000a4944415408d76360000000020001e221bc330000000049454e44ae426082'), 200)
            ->header('Content-Type', 'image/png');
    }

    public static function removeGet(Request $request, $mm, $password, $url)
    {
        $url = base64_decode(str_replace("<<SLASH>>", "/", $url));

        # If the user is correct and the password is we will delete any entry in the database
        $requiredPass = md5($mm . Carbon::NOW()->day . $url . env("PROXY_PASSWORD"));
        if (HumanVerification::checkId($request, $mm) && $requiredPass === $password) {
            HumanVerification::removeUser($request, $mm);
        }
        return redirect($url);
    }

    private static function removeUser($request, $uid)
    {
        $id = hash("sha512", $request->ip());

        $sum = DB::table('humanverification')->where('id', $id)->where('whitelist', false)->sum('unusedResultPages');
        $user = DB::table('humanverification')->where('uid', $uid)->first();

        if ($user === null) {
            return;
        }

        # Check if we have to whitelist the user or if we can simply delete the data
        if ($user->unusedResultPages < $sum && $user->whitelist === 0) {
            # Whitelist
            DB::table('humanverification')->where('uid', $uid)->update(['whitelist' => true, 'whitelistCounter' => 0]);
            $user->whitelist = 1;
            $user->whitelistCounter = 0;
        }

        if ($user->whitelist === 1) {
            DB::table('humanverification')->where('uid', $uid)->update(['unusedResultPages' => 0]);
        } else {
            DB::table('humanverification')->where('uid', $uid)->where('updated_at', '<', Carbon::NOW()->subSeconds(2))->delete();

        }

    }

    private static function checkId($request, $id)
    {
        if (hash("sha512", $request->ip() . $_SERVER["AGENT"]) === $id) {
            return true;
        } else {
            return false;
        }
    }
}
