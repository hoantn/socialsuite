<?php

namespace App\Http\Controllers;

class OAuthController extends Controller
{
    public function redirect() { return response('fb redirect stub'); }
    public function callback() { return response('fb callback stub'); }
}