<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FbPageCentricSeeder extends Seeder {
    public function run(): void {
        // Minimal seed to ensure tables exist and app boots.
        DB::table('page_configs')->insertOrIgnore([
            'page_id' => 'DEMO_PAGE_ID',
            'settings' => json_encode([
                'posting_defaults' => ['published' => true],
                'schedule' => [],
                'auto_reply' => ['enabled' => false],
            ]),
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
