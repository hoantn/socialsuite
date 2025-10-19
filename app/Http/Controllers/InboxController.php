<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class InboxController extends Controller
{
    public function index()
    {
        $messages = [
            ['from' => 'User A', 'text' => 'Hi there!', 'time' => now()->toDateTimeString()],
            ['from' => 'You', 'text' => 'Hello!', 'time' => now()->toDateTimeString()],
        ];
        return Inertia::render('Inbox/Index', ['messages' => $messages]);
    }

    public function send()
    {
        return back()->with('success', 'Message queued');
    }
}