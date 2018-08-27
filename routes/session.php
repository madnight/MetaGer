<?php
# In this File we collect all routes which require a session or other cookies to be active


Route::get('captcha/api/{config?}', '\Mews\Captcha\CaptchaController@getCaptchaApi')->middleware('session');
Route::get('captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha')->middleware('session');
Route::match(['get', 'post'], 'meta/verification/{id}/{url}', 'HumanVerification@captcha');