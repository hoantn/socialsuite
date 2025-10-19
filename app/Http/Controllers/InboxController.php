<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class InboxController extends Controller {
    public function index() {
        $messages = [
            ['from' => 'User A', 'text' => 'Hi there!', 'time' => '2025-10-18 10:00'],
            ['from' => 'You', 'text' => 'Hello!', 'time' => '2025-10-18 10:01']
        ];
        return Inertia::render('Inbox/Index', ['messages' => $messages]);
    }

    public function send(Request $request) {
        // Handle sending message to FB API (mocked)
        return back()->with('success', 'Message sent!');
    }
}