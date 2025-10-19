<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class PageController extends Controller {
    public function index() {
        // Load user's connected pages (mocked)
        $pages = [
            ['id' => '123', 'name' => 'Demo Fanpage', 'avatar' => 'https://via.placeholder.com/40']
        ];
        return Inertia::render('Pages/Index', ['pages' => $pages]);
    }
}