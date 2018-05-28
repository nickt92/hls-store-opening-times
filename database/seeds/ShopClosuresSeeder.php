<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;
use App\ShopClosure;

class ShopClosuresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shopClosurePresets = Config::get('shopclosures');
        $currentDate = new DateTime('now');

        try {
            DB::table('shop_closures')->delete();

            foreach ($shopClosurePresets as $closureConfig) {
                ShopClosure::create([
                    'description' => $closureConfig['description'],
                    'start' => DateTime::createFromFormat(
                        'd-m-Y',
                        $closureConfig['start']
                    )->setTime(0, 0)->format('Y-m-d H:i:s'),
                    'finish' => DateTime::createFromFormat(
                        'd-m-Y',
                        $closureConfig['end']
                    )->setTime(0, 0)->format('Y-m-d H:i:s'),
                    'reoccurring' => $closureConfig['reoccurring'],
                    'enabled' => $closureConfig['enabled'],
                ]);
            }
        } catch (QueryException $error) {
            $this->command->error($error->getMessage() . "\n");
        }
    }
}
