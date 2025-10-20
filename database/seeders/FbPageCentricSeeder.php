<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\FbPage;

class FbPageCentricSeeder extends Seeder {
    public function run(): void {
        // 1) Ensure the Page exists (FK target)
        FbPage::updateOrCreate(
            ['page_id' => 'DEMO_PAGE_ID'],
            [
                'name' => 'Demo Page',
                'username' => null,
                'category' => null,
                'avatar_url' => null,
                'connected_ig_id' => null,
                'page_access_token' => null,
                'token_expires_at' => null,
                'capabilities' => [],
            ]
        );

        // 2) Upsert config for that Page
        DB::table('page_configs')->updateOrInsert(
            ['page_id' => 'DEMO_PAGE_ID'],
            [
                'settings'   => json_encode([
                    'posting_defaults' => ['published' => true],
                    'schedule' => [],
                    'auto_reply' => ['enabled' => false],
                ], JSON_UNESCAPED_UNICODE),
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
