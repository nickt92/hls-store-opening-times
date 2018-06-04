<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{__('HLS - Shop')}}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100%;
                margin: 0;
            }
            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }
            .position-ref {
                position: relative;
            }
            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }
            .content {
                text-align: center;
            }
            .title {
                font-size: 84px;
            }
            .sub-title {
                font-size: 54px;
            }
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            .m-b-md {
                margin-bottom: 10px;
            }
            table {
                margin-top: 30px;
            }
            #opening-times, #store-closures {
                border-collapse: collapse;
                width: 100%;
            }
            #opening-times td, #opening-times th,
            #store-closures td, #store-closures th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            #opening-times tr:nth-child(even),
            #store-closures tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            #opening-times tr:hover,
            #store-closures tr:hover {
                background-color: #ddd;
            }

            #opening-times th,
            #store-closures th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: center;
                background-color: #FF0000;
                color: white;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref">
            <div class="content">
                <div class="title m-b-md">
                    {{__('HLS Demo Shop')}}
                </div>
                <div class="sub-title">
                    {{__('Opening Times')}}:
                </div>
                @if ($errors->any()) 
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <p>
                    {{__('Current Shop Status')}}: {{$isShopOpen ? __('Open') : __('Closed')}}
                </p>
                @if ($isShopOpen)
                    <p>
                        {{__('Shop will next be closed')}} : 
                    </p>
                @else
                    <p>
                        {{__('Shop will next be open')}} : {{$shopNextOpen}}
                    </p>
                @endif
                <table id="opening-times">
                    <thead>
                        <tr>
                            <th colspan="4">{{__('Store Hours')}}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weekdayOpeningTimes as $weekdayOpeningInfo)
                            <tr>
                                <td colspan="4">
                                    {{$weekdayOpeningInfo->weekday_label}} 
                                    @if ($currentDay === $weekdayOpeningInfo->weekday_label) 
                                        - <strong>{{__('Current')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                            @if ($weekdayOpeningInfo->operatingTimes->count() > 0)
                                @foreach ($weekdayOpeningInfo->operatingTimes as $weekdayTimes)
                                <tr>
                                    <td>
                                        {{$weekdayTimes->opening_time}}
                                    </td>
                                    <td>{{$weekdayTimes->open_message}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        {{$weekdayTimes->closing_time}}
                                    </td>
                                    <td>{{$weekdayTimes->closed_message}}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">{{__('Closed')}}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <table id="store-closures">
                    <thead>
                        <tr>
                            <th>Store Closures</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($allUpcomingClosures->count() > 0))
                            @foreach ($allUpcomingClosures as $closureInfo)
                                <tr>
                                    <td>
                                        {{__('Closed for')}} {{$closureInfo->description}} :
                                        {{ Carbon\Carbon::parse($closureInfo->start)->format('l jS F') }}
                                        @if ($closureInfo->start !== $closureInfo->finish)
                                            - {{ Carbon\Carbon::parse($closureInfo->finish)->format('l jS F') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>{{__('No upcoming closures')}}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
