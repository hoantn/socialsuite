<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder; use Illuminate\Support\Facades\Hash;
use App\Models\User; use App\Models\Plan;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(['id'=>1], ['name'=>'Free','price'=>0,'features'=>['max_pages'=>1,'max_scheduled_posts'=>5,'inbox'=>false]]);
        Plan::updateOrCreate(['id'=>2], ['name'=>'Premium','price'=>200000,'features'=>['max_pages'=>10,'max_scheduled_posts'=>-1,'inbox'=>true]]);
        User::updateOrCreate(['username'=>'admin'], ['password'=>Hash::make('admin123'),'email'=>null,'phone'=>null,'is_admin'=>true,'plan_id'=>2]);
    }
}
