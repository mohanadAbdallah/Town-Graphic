<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Data\Setting;
class SettingSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'key'=>'location',
            'value'=>'(24.717478459820086, 46.69484198335567)',
        ]);
    }
}
