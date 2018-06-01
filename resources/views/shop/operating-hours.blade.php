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

            .full-height {
                height: 100vh;
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
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
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
                        {{__('Shop will next be open')}} :
                    </p>
                @endif
                <table>
                    <thead>
                        <tr>
                            <th>Store Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weekdayOpeningTimes as $id => $weekdayInfo)
                            <tr>
                                <td>{{$weekdayInfo['weekday_label']}}</td>
                            </tr>
                            <tr>
                            @if (!empty($weekdayInfo['operating_times']))
                                @foreach ($weekdayInfo['operating_times'] as $id => $timesInfo)
                                <tr>
                                    <td>
                                        {{$timesInfo['opening_time']}} ({{$timesInfo['open_message']}}) - 
                                        {{$timesInfo['closing_time']}} ({{$timesInfo['closed_message']}})
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>{{__('Closed')}}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th>Store Closures</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($allUpcomingClosures))
                            @foreach ($allUpcomingClosures as $id => $closureInfo)
                                <tr>
                                    <td>
                                        {{__('Closed for')}} {{$closureInfo['description']}} :
                                        {{ Carbon\Carbon::parse($closureInfo['start'])->format('l jS F') }}
                                        @if ($closureInfo['start'] !== $closureInfo['finish'])
                                            - {{ Carbon\Carbon::parse($closureInfo['finish'])->format('l jS F') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
