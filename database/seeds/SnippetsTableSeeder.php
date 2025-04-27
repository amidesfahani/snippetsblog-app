<?php

use App\Snippet;
use Illuminate\Database\Seeder;

class SnippetsTableSeeder extends Seeder
{
    public function run()
    {
        factory(App\Snippet::class, 20)->create();
    }
}