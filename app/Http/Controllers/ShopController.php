<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShopClosure;
use App\ShopOperatingWeekday;

class ShopController extends Controller
{
    /**
     * Is the shop open on the provided date/time
     * If provided a DateTime object, check relative to that, otherwise use now
     *
     * @param DateTime $dt
     * @return boolean
     */
    public function isOpen(DateTime $dt = null)
    {

    }

    /**
     * Is the shop closed on the provided date/time
     * If provided a DateTime object, check relative to that, otherwise use now
     *
     * @param DateTime $dt
     * @return boolean
     */
    public function isClosed(DateTime $dt = null)
    {

    }

    /**
     * At what date/time will the shop next be open
     * If provided a DateTime object, check relative to that, otherwise use now
     * If the shop is already open, return the provided datetime/now
     *
     * @param DateTime $dt
     * @return DateTime
     */
    public function nextOpen(DateTime $dt = null)
    {

    }

    /**
     * At what date/time will the shop next be closed
     * If provided a DateTime object, check relative to that, otherwise use now
     * If the shop is already closed, return the provided datetime/now
     *
     * @param DateTime $dt
     * @return DateTime
     */
    public function nextClosed(DateTime $dt = null)
    {

    }

    /**
     * Index
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $shopClosures = ShopClosure::all()->toArray();
        $weekdayOpeningTimes = ShopOperatingWeekday::with(['operatingTimes'])->get()->toArray();

        return View('shop.operating-hours')->with(compact('weekdayOpeningTimes'));
    }
}
