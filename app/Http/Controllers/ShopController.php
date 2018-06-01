<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShopClosure;
use App\ShopOperatingTime;
use App\ShopOperatingWeekday;
use DateTime;
use Illuminate\Support\Facades\Validator;

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
        if (!$dt instanceof DateTime) {
            $dt = new DateTime('now');
        }
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
        if (!$dt instanceof DateTime) {
            $dt = new DateTime('now');
        }

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
        if (!$dt instanceof DateTime) {
            $dt = new DateTime('now');
        }
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
        $dateToFilter = null;

        if ($request->query()) {
            $request->validate([
                'date' => 'required|date|date_format:Y-m-d',
                'time' => 'required|date_format:H:i:s'
            ]);

            $dateToFilter = new DateTime(
                $request->query('date') . $request->query('time')
            );
        }

        $allUpcomingClosures = ShopClosure::getAllFutureClosures(
            $dateToFilter
        )->get()->toArray();

        $isShopOpen = $this->isOpen($dateToFilter);
        $shopNextOpen = $this->nextOpen($dateToFilter);
        $isShopClosed = $this->isClosed($dateToFilter);
        $shopNextClosed = $this->nextClosed($dateToFilter);

        $weekdayOpeningTimes = ShopOperatingWeekday::with(
            ['operatingTimes']
        )->get()->toArray();

        return View('shop.operating-hours')->with(
            compact(
                'weekdayOpeningTimes',
                'allUpcomingClosures',
                'isShopOpen',
                'shopNextOpen',
                'isShopClosed',
                'shopNextClosed'
            )
        );
    }
}
