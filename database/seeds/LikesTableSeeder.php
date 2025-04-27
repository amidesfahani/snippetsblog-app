<?php

use App\Like;
use Illuminate\Database\Seeder;

class LikesTableSeeder extends Seeder
{
    public function run()
    {
        factory(App\Like::class, 100)->create();
    }
}