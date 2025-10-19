<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PageController extends Controller
{
    public function index()
    {
        $pages = [
            ['id' => '123', 'name' => 'Demo Fanpage'],
            ['id' => '456', 'name' => 'Another Page'],
        ];
        return Inertia::render('Pages/Index', ['pages' => $pages]);
    }
}