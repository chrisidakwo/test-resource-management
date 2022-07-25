<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $i = 0;

        while ($i < 35) {
            $type = Arr::random([Resource::TYPE_LINK, Resource::TYPE_HTML, Resource::TYPE_PDF]);
            Resource::factory()->randomType($type)->create();

            $i++;
        }
    }
}
