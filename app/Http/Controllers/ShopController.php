<?php

/**
 * @todo Discuss possibility of refactoring slightly
 * to clean up controller and use as reference class to view
 * All business logic should either be seperated into Repository pattern
 * or service class? This would also allow us to follow S.O.L.I.D better
 */

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
        $dateFilter = $this->validateDate($dt);

        $dateFormatted = $dateFilter->format('Y-m-d H:i:s');
        $currentTime = $dateFilter->format('H:i:s');
        $currentDayInt = $dateFilter->format('N');

        $hasClosure = ShopClosure::where([
            ['start', '<=', $dateFormatted],
            ['finish', '>=', $dateFormatted],
            ['enabled', '=', true]
        ])->exists();

        if (!$hasClosure) {
            $isOperating = ShopOperatingTime::where([
                ['shop_operating_weekday_id', '=', $currentDayInt],
                ['opening_time', '<=', $dateFormatted],
                ['closing_time', '>=', $dateFormatted]
            ])->exists();
            return $isOperating;
        }

        return false;
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
        $dateFilter = $this->validateDate($dt);

        return !$this->isOpen($dateFilter);
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
        $dateFilter = $this->validateDate($dt);
        $closuresForTheDay = $this->getClosuresForDate($dateFilter)->exists();

        if ($closuresForTheDay) {

            $dateFilter->modify('+1 day');

            $closuresForTheDay = $this->getClosuresForDate($dateFilter);
            $nextOpen = $this->nextOpen($dateFilter);
        }

        if ($this->isClosed($dateFilter)) {

            $firstOpenDay = ShopOperatingTime::min('shop_operating_weekday_id');
            $lastOpenDay = ShopOperatingTime::max('shop_operating_weekday_id');
            $currentDayInt = $dateFilter->format('N');

            if ($this->isPastCutOffTime($dateFilter) && $currentDayInt >= $lastOpenDay) {
                $nextOpening = ShopOperatingTime::with('operatingWeekday')->where([
                    ['shop_operating_weekday_id', '=', $firstOpenDay]
                ]);
                $dateFilter->modify('next ' . $nextOpening->first()->operatingWeekday->weekday_label);
            } else {
                $nextOpening = ShopOperatingTime::where([
                    ['shop_operating_weekday_id', '=', $currentDayInt],
                    ['opening_time', '>=', $dateFilter->format('H:i:s')],
                ]);

                if (!$nextOpening->exists()) {
                    $nextOpening = ShopOperatingTime::where([
                        ['shop_operating_weekday_id', '=', $currentDayInt],
                        ['opening_time', '<=', $dateFilter->format('H:i:s')],
                    ]);
                    $dateFilter->modify('+1 day');
                }
            }
            $nextOpeningTime = explode(
                ":",
                $nextOpening->first()->opening_time
            );

            return $dateFilter->setTime(
                $nextOpeningTime[0],
                $nextOpeningTime[1],
                $nextOpeningTime[2]
            );
        }

        return $dateFilter;
    }

    /**
     * Get store closures by specific date
     *
     * @param DateTime $dt
     * @return object
     */
    private function getClosuresForDate(DateTime $dt)
    {
        $dateFilter = $dt->format('Y-m-d H:i:s');

        $shopClosures = ShopClosure::where([
            ['start', '<=', $dateFilter],
            ['finish', '>=', $dateFilter],
            ['enabled', '=', true]
        ]);

        return $shopClosures;
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
        $dateFilter = $this->validateDate($dt);

    }

    /**
     * Index
     * 
     * All method implementations are referenced here
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $dateToFilter = null;

        /**
         * @todo remove this from controller
         * Should be in a request class?
         */
        if ($request->query()) {
            $request->validate([
                'date' => 'required|date|date_format:Y-m-d',
                'time' => 'required|date_format:H:i:s'
            ]);

            $dateToFilter = new DateTime(
                $request->query('date') . $request->query('time')
            );
        }

        $currentDay = $this->getDayofWeek($dateToFilter);
        $allUpcomingClosures = ShopClosure::getClosures(
            $dateToFilter
        )->get();
        $weekdayOpeningTimes = ShopOperatingWeekday::with(
            ['operatingTimes']
        )->get();

        $isShopOpen = $this->isOpen($dateToFilter);
        $shopNextOpen = $this->nextOpen($dateToFilter)->format('l jS F \a\t H:i:s');

        return View('shop.operating-hours')->with(
            compact(
                'currentDay',
                'weekdayOpeningTimes',
                'allUpcomingClosures',
                'isShopOpen',
                'shopNextOpen',
                'isShopClosed',
                'shopNextClosed'
            )
        );
    }

    /**
     * Validate DateTime
     *
     * @todo Move to DateTime helper class
     * 
     * @param DateTime $dt
     * @return DateTime
     */
    private function validateDate(DateTime $dt = null)
    {
        return (!$dt instanceof DateTime ? new DateTime('now') : $dt);
    }

    /**
     * Check if current DateTime is passed our cut off date/time
     *
     * @param DateTime $dt
     * @return boolean
     */
    private function isPastCutOffTime(DateTime $dt)
    {
        $currentTime = $dt->format('H:i:s');
        $currentDayInt = $dt->format('N');
        $cutOffTimeToday = ShopOperatingTime::where([
            ['shop_operating_weekday_id', '=', $currentDayInt],
            ['opening_time', '<=', $currentTime],
            ['closing_time', '>=', $currentTime]
        ]);

        return !$cutOffTimeToday->exists();
    }

    /**
     * Get day from dateFilter
     *
     * @param DateTime $dt
     * @todo Move to DateTime helper class
     * @return string
     */
    public function getDayofWeek(DateTime $dt = null)
    {
        if (!$dt instanceof DateTime) {
            $dt = new DateTime('now');
        }

        return $dt->format('l');
    }
}
