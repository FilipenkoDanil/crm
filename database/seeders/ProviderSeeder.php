<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $providers = ['github', 'vkontakte', 'discord'];

        foreach ($providers as $provider) {
            Provider::create([
                'name' => $provider
            ]);
        }
    }
}
