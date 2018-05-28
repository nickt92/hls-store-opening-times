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
        $openingTimePresets = Config::get('shopoperatingtimes');
        $currentDate = new DateTime('now');

        try {
            DB::table('shop_operating_weekdays')->delete();
            DB::table('shop_operating_times')->delete();

            foreach ($openingTimePresets as $weekDay => $configData) {
                $weekdayNumber = date('N', strtotime($weekDay));
                $operatingWeekdayId = ShopOperatingWeekday::create([
                    'weekday_number' => $weekdayNumber,
                    'weekday_label' => $weekDay
                ])->id;

                foreach ($openingTimePresets[$weekDay] as $dailyOpeningConfig) {
                    if (!empty($dailyOpeningConfig)) {
                        ShopOperatingTime::create([
                            'shop_operating_weekday_id' => $operatingWeekdayId,
                            'opening_time' => $dailyOpeningConfig['open'],
                            'open_message' => $dailyOpeningConfig['openedResponseMessage'],
                            'closing_time' => $dailyOpeningConfig['closed'],
                            'closed_message' => $dailyOpeningConfig['closedResponseMessage'],
                        ]);
                    }
                }
            }
        } catch (QueryException $error) {
            $this->command->error($error->getMessage() . "\n");
        }
    }
}
