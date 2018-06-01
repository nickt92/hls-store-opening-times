<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;
use App\ShopOperatingWeekday;
use App\ShopOperatingTime;

class ShopOpeningTimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listOfShopOperatingTimes = Config::get('shopoperatingtimes');
        foreach ($listOfShopOperatingTimes as $weekDay => $data) {
            $operatingWeekday = ShopOperatingWeekday::create([
                'weekday_label' => $weekDay
            ]);
            foreach ($data as $dailyOpeningConfig) {
                ShopOperatingTime::create([
                    'shop_operating_weekday_id' => $operatingWeekday->id,
                    'opening_time' => $dailyOpeningConfig['open'],
                    'open_message' => $dailyOpeningConfig['openedResponseMessage'],
                    'closing_time' => $dailyOpeningConfig['closed'],
                    'closed_message' => $dailyOpeningConfig['closedResponseMessage'],
                ]);
            }
        }
    }
}
