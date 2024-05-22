<?php

namespace Modules\WhatsappSupport\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\WhatsappSupport\Entities\Agents;
use Modules\WhatsappSupport\Entities\Message;

class WhatsappSupportDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $agent = Agents::create([
            'name' => 'Demo Agent',
            'designation' => 'Support Engineer',
            'avatar' => 'public/whatsapp-support/demo-avatar.jpg',
            'always_available' => 1,
        ]);

        Message::create([
            'message' => 'Test message from Dasktop',
            'ip' => '127.0.0.1',
            'number' => '8801234556677',
            'device_type' => 'Desktop',
            'os' => 'Linux',
            'browser' => 'Chrome',
        ]);

        Message::create([
            'message' => 'Test message from Mobile',
            'ip' => '127.0.0.1',
            'number' => '8801234556677',
            'device_type' => 'Mobile',
            'os' => 'Android',
            'browser' => 'Chrome',
        ]);
    }
}
