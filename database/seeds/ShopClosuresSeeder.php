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
     * @todo convert back too model::create() based on 
     * https://github.com/laravel/framework/issues/22502
     * 
     * @return void
     */
    public function run()
    {
        $shopClosurePresets = Config::get('shopclosures');

        ShopClosure::insert($shopClosurePresets);
    }
}
