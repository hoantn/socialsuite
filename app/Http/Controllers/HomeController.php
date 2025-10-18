<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    // [SOCIALSUITE][GPT][2025-10-18 10:00 +07] Homepage + SEO meta
    public function index(Request $request)
    {
        $meta = [
            'title'       => 'SocialSuite — Tự động hoá & Quản lý Facebook cho Doanh nghiệp',
            'description' => 'SocialSuite giúp bạn đăng bài, quản lý trang, lên lịch và theo dõi hiệu quả trên Facebook nhanh chóng, an toàn, chuẩn chính sách Meta.',
            'keywords'    => 'quản lý facebook, đăng bài tự động, lên lịch facebook, quản trị fanpage, social automation',
            'url'         => URL::to('/'),
            'image'       => URL::to('/og-socialsuite.jpg'),
            'brand'       => 'SocialSuite',
        ];

        return view('home', compact('meta'));
    }

    // Simple static sitemap (add more as you add pages)
    public function sitemap()
    {
        $base = url('/');
        $urls = [
            ['loc' => $base.'/', 'prio' => '1.0'],
            ['loc' => $base.'/login', 'prio' => '0.6'],
            ['loc' => $base.'/register', 'prio' => '0.6'],
            ['loc' => $base.'/auth/facebook/login', 'prio' => '0.5'],
        ];

        $xml = view('sitemap', compact('urls'))->render();
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
