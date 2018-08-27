<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Input;
use DB;
use Carbon;

class HumanVerification extends Controller
{
    public static function captcha(Request $request, $id, $url){
        if($request->getMethod() == 'POST'){
            $rules = ['captcha' => 'required|captcha'];
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return view('captcha')->with('title', 'Bestätigung notwendig')->with('id', $id)->with('url', base64_decode($url))->with('errorMessage', 'Bitte Captcha eingeben:');
            }else{
                # If we can unlock the Account of this user we will redirect him to the result page
                $id = $request->input('id');
                $url = $request->input('url');

                $user = DB::table('humanverification')->where('id', $id)->first();
                if($user !== null && $user->locked === 1){
                    DB::table('humanverification')->where('id', $id)->update(['locked' => false]);
                    return redirect($url);
                }else{
                    return redirect('/');
                }
            }
        }
        return view('captcha')->with('title', 'Bestätigung notwendig')->with('id', $id)->with('url', base64_decode($url));
    }

    public static function remove(Request $request){
        if(!$request->has('mm')){
            abort(404, "Keine Katze gefunden.");
        }
        $id = md5($request->ip());
        if(HumanVerification::checkId($request, $request->input('mm'))){
            # Remove the entry from the database
            DB::table('humanverification')->where('id', $id)->where('updated_at', '<', Carbon::NOW()->subSeconds(2) )->delete();
        }
        return response(hex2bin('89504e470d0a1a0a0000000d494844520000000100000001010300000025db56ca00000003504c5445000000a77a3dda0000000174524e530040e6d8660000000a4944415408d76360000000020001e221bc330000000049454e44ae426082'), 200)
            ->header('Content-Type', 'image/png');
    }

    public static function removeGet(Request $request, $mm, $password, $url){
        $url = base64_decode($url);

        # If the user is correct and the password is we will delete any entry in the database
        $requiredPass = md5($mm . Carbon::NOW()->day . $url . env("PROXY_PASSWORD"));
        if(HumanVerification::checkId($request, $mm) && $requiredPass === $password){
            # Remove the entry from the database
            DB::table('humanverification')->where('id', $mm)->where('updated_at', '<', Carbon::NOW()->subSeconds(2) )->delete();
        }
        return redirect($url);
    }

    private static function checkId($request, $id){
        if(md5($request->ip()) === $id){
            return true;
        }else{
            return false;
        }
    }
}
