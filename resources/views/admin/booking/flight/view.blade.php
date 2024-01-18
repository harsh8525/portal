@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
                <h1 class="m-0">{{ $header['title'] }}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('customers.dashboard')
                        </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking.index') }}">Booking</a></li>
                    <li class="breadcrumb-item active">@lang('customers.view')</li>
                </ol>
            </div>
        </div><!-- /.col -->

    </div><!-- /.row -->
</div><!-- /.container-fluid -->

<div class="container-fluid">
    <div class="main-img main-bg-img">
        <div class="bg-imgg">
            <!-- <img src="" alt=""> -->
            <div class="container">
                <div class="row">
                    <div class="col-12">
                       <div class="mainmian">
                       <div class="four-ticket">
                            <div class="first-tic">
                                <a href="#">Email E-Ticket(s)</a>
                            </div>
                            <div class="second-tic">
                                <a
                                    href="{{ route('booking.e-ticket',$bookingDetail['bookingDetail']['id']) }}?service={{ $bookingDetail['bookingDetail']['getServiceType']['name'] ?? '' }}">Download
                                    E-Ticket(s) </a>
                            </div>
                            <div class="third-tic">
                                <a href="#">Download Customer Invoice(s)</a>
                            </div>
                            <div class="fourth-tic">
                                <a href="#">Download Admin Invoice(s)</a>
                            </div>
                        </div>

                        <!-- your-flight -->
                        

                        <!-- threee-status-new -->
                        <div class="three-status-new">
                        <div class="your-flight-text">
                            <h1>Your Flight booking has completed!</h1>
                        </div>
                        <div class="threee">
                        <div class="f-t-new">
                                <span class="status-first-span">Booking ID :</span><span
                                    class="status-second-span">{{ $bookingDetail['bookingDetail']['booking_ref'] ?? '' }}</span>
                            </div>
                            <div class="s-t-new">
                                <span class="status-first-span">System Ref ID :</span><span
                                    class="status-second-span">{{ $bookingDetail['data']['id'] ?? '' }}</span>
                            </div>
                            <div class="t-t-new">
                                <span class="status-first-span">Booked On :</span><span
                                    class="status-second-span">{{ \Carbon\Carbon::parse($bookingDetail['bookingDetail']['booking_date'] ?? '')->format('d M Y') }}</span>
                            </div>
                        </div>    
                        </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- four-ticket -->
        <?php // echo "<pre>";print_r($bookingDetail);die;?>


        <!-- main-flight-information -->
    </div>



    <div class="container">
        <div class="row">
            @foreach($bookingDetail['data']['flightOffers'] as $flightOffer)
            @php
            $itinerariesCount = count($flightOffer['itineraries']);
            @endphp

            @if($itinerariesCount == 1)
                @php
                $firstSegments = $flightOffer['itineraries'][0]['segments'];
                $firstSegment = $firstSegments[0];
                
                $getDepartureHeading = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
                    $query->where('language_code', $getLanguageCode);
                }])->where('iso_code', $firstSegment['departure']['iataCode'])->first();

                $lastSegment = $firstSegments[count($firstSegments) - 1];
                $getArrivalHeading = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
                    $query->where('language_code', $getLanguageCode);
                }])->where('iso_code', $lastSegment['arrival']['iataCode'])->first();
                @endphp

            @elseif($itinerariesCount == 2)
                @php
                $firstSegments = $flightOffer['itineraries'][0]['segments'];
                $lastSegments = $flightOffer['itineraries'][1]['segments'];

                $firstDeparture = $firstSegments[0]['departure']['iataCode'];
                $lastArrival = $lastSegments[count($lastSegments) - 1]['arrival']['iataCode'];

                if ($firstDeparture == $lastArrival) {
                    $getDepartureHeading = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
                        $query->where('language_code', $getLanguageCode);
                    }])->where('iso_code', $firstDeparture)->first();

                    $getArrivalHeading = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
                        $query->where('language_code', $getLanguageCode);
                    }])->where('iso_code', $lastSegments[0]['departure']['iataCode'])->first();
                } else {
                    
                    $getDepartureHeading = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
                        $query->where('language_code', $getLanguageCode);
                    }])->where('iso_code', $firstDeparture)->first();

                    $getArrivalHeading = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
                        $query->where('language_code', $getLanguageCode);
                    }])->where('iso_code', $lastSegments[0]['arrival']['iataCode'])->first();
                }
                @endphp

            @elseif($itinerariesCount >= 2)
                @php
                $firstSegments = $flightOffer['itineraries'][0]['segments'];
                $lastSegments = $flightOffer['itineraries'][$itinerariesCount - 1]['segments'];

                
                $getDepartureHeading = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
                    $query->where('language_code', $getLanguageCode);
                }])->where('iso_code', $firstSegments[0]['departure']['iataCode'])->first();

                $getArrivalHeading = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
                    $query->where('language_code', $getLanguageCode);
                }])->where('iso_code', $lastSegments[count($lastSegments) - 1]['arrival']['iataCode'])->first();
                @endphp

            @endif
            @endforeach
            <div class="col-12 flight-infooo">
                <div class="flight-information">
                    <div class="left-info">
                        <span class="from-text">{{$getDepartureHeading['cityCode'][0]['city_name']}}</span>
                        <span><svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0"
                                viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"
                                class="">
                                <g>
                                    <path
                                        d="m506.134 241.843-.018-.019-104.504-104c-7.829-7.791-20.492-7.762-28.285.068-7.792 7.829-7.762 20.492.067 28.284L443.558 236H20c-11.046 0-20 8.954-20 20s8.954 20 20 20h423.557l-70.162 69.824c-7.829 7.792-7.859 20.455-.067 28.284 7.793 7.831 20.457 7.858 28.285.068l104.504-104 .018-.019c7.833-7.818 7.808-20.522-.001-28.314z"
                                        fill="#000000" opacity="1" data-original="#000000" class=""></path>
                                </g>
                            </svg></span>
                        <span class="to-text">{{$getArrivalHeading['cityCode'][0]['city_name']}}</span>
                    </div>
                    <div class="right-info">
                        <span class="booking-status">Booking Status : </span><span class="com-text">
                            {{ ucfirst($bookingDetail['bookingDetail']['booking_status'] ?? '') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- two-boxes -->
            @foreach($bookingDetail['data']['flightOffers'] as $flightOffers)
            

            <div class="col-12 combine-both">
                <div class="flight-status-bx">
                    <div class="row">
                        @php
            $firstDeparture = '';
            $lastArrival = '';
            @endphp

            @foreach ($flightOffers['itineraries'] as $index => $itinerary)
            @php
            $firstSegment = $itinerary['segments'][0];
            $lastSegment = end($itinerary['segments']);

            $departureIataCode = $firstSegment['departure']['iataCode'];
            $arrivalIataCode = $lastSegment['arrival']['iataCode'];

            if ($index === 0) {
            $firstDeparture = $departureIataCode;
            $lastArrival = $arrivalIataCode;
            } else {
            if ($index === 1 && $firstDeparture === $lastArrival) {
            $firstDeparture = $firstSegment['departure']['iataCode'];
            $lastArrival = $lastSegment['arrival']['iataCode'];
            } else {
            $firstDeparture = $departureIataCode;
            $lastArrival = $arrivalIataCode;
            }
            }

            $getDeparture = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
            $query->where('language_code', $getLanguageCode);
            }])->where('iso_code', $firstDeparture)->first();

            $getArrival = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
            $query->where('language_code', $getLanguageCode);
            }])->where('iso_code', $lastArrival)->first();
            @endphp
                        <div class="col-4">
                            <div class="two-boxes">
                            <div class="f-line-line">
                                <span class=fll-text-first>
                                    {{$getDeparture['cityCode'][0]['city_name']}}
                                </span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"
                                        class="">
                                        <g>
                                            <path
                                                d="m506.134 241.843-.018-.019-104.504-104c-7.829-7.791-20.492-7.762-28.285.068-7.792 7.829-7.762 20.492.067 28.284L443.558 236H20c-11.046 0-20 8.954-20 20s8.954 20 20 20h423.557l-70.162 69.824c-7.829 7.792-7.859 20.455-.067 28.284 7.793 7.831 20.457 7.858 28.285.068l104.504-104 .018-.019c7.833-7.818 7.808-20.522-.001-28.314z"
                                                fill="#000000" opacity="1" data-original="#000000" class=""></path>
                                        </g>
                                    </svg>
                                </span>
                                <span class="fll-text-second">
                                    {{$getArrival['cityCode'][0]['city_name']}}
                                </span>
                            </div>
                            <div class="s-line-line">
                                <span class="sll-text-first">
                                    {{ \Carbon\Carbon::parse($firstSegment['departure']['at'] ?? '')->format("D, d M'y") }}
                                </span>
                                <span class="sll-text-second">
                                    {{ count($bookingDetail['data']['travelers']) }} Traveller(s)
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    </div>
                </div>
                
            </div>

            
            @endforeach


            <!-- first - colllll -->
            @php $i = 1; @endphp
            @foreach($bookingDetail['data']['flightOffers'] as $flightOffers)
            <div class="col-12">
                @foreach($flightOffers['itineraries'] as $itineraries)
                <div>
                    <div class="first-coll" data-bs-toggle="collapse" href="#collapseExample-f-lll-{{$loop->iteration}}"
                        role="button" aria-expanded="false" aria-controls="collapseExample-f-lll">
                        <?php 
                          $uniqueIataCodes = collect($itineraries['segments'])
                            ->flatMap(function ($segment) {
                                return [$segment['departure']['iataCode'], $segment['arrival']['iataCode']];
                            })->unique()->values()->all();
                            $cityNames = "";
                            
                            foreach ($uniqueIataCodes as $iataCode) {
                                $getDepartureSengment = \App\Models\City::with(['cityCode' => function ($query) use ($getLanguageCode) {
                                    $query->where('language_code', $getLanguageCode);
                                    }])->where('iso_code',$iataCode)->first();
                                    $tempName = '<span class="first-coll-text">'.$getDepartureSengment['cityCode'][0]['city_name'].'</span>';
                                    $cityNames .= $tempName.'<span><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="m506.134 241.843-.018-.019-104.504-104c-7.829-7.791-20.492-7.762-28.285.068-7.792 7.829-7.762 20.492.067 28.284L443.558 236H20c-11.046 0-20 8.954-20 20s8.954 20 20 20h423.557l-70.162 69.824c-7.829 7.792-7.859 20.455-.067 28.284 7.793 7.831 20.457 7.858 28.285.068l104.504-104 .018-.019c7.833-7.818 7.808-20.522-.001-28.314z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg></span>';
                                    
                            }
                            $lastSvgPosition = strrpos($cityNames, '<span><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="m506.134 241.843-.018-.019-104.504-104c-7.829-7.791-20.492-7.762-28.285.068-7.792 7.829-7.762 20.492.067 28.284L443.558 236H20c-11.046 0-20 8.954-20 20s8.954 20 20 20h423.557l-70.162 69.824c-7.829 7.792-7.859 20.455-.067 28.284 7.793 7.831 20.457 7.858 28.285.068l104.504-104 .018-.019c7.833-7.818 7.808-20.522-.001-28.314z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg></span>');
                            echo $cityNames = substr_replace($cityNames, '', $lastSvgPosition, strlen('<span><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="m506.134 241.843-.018-.019-104.504-104c-7.829-7.791-20.492-7.762-28.285.068-7.792 7.829-7.762 20.492.067 28.284L443.558 236H20c-11.046 0-20 8.954-20 20s8.954 20 20 20h423.557l-70.162 69.824c-7.829 7.792-7.859 20.455-.067 28.284 7.793 7.831 20.457 7.858 28.285.068l104.504-104 .018-.019c7.833-7.818 7.808-20.522-.001-28.314z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg></span>'));

                        ?>



                        <span class="ud-btn">
                            <div class="ud-btn-main">
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                        </span>
                        <span class="free-linee"></span>
                    </div>

                    <!-- first-collll -->
                    <div class="collapse border-0 @if($loop->iteration == '1') show @endif"
                        id="collapseExample-f-lll-{{$loop->iteration}}">
                        <div class="card border-0 card-body">
                            @foreach($itineraries['segments'] as $segments)
                            @php

                            $getDepartureAirport = \App\Models\Airport::with(['airportName' => function ($query) use
                            ($getLanguageCode) {
                            $query->where('language_code', $getLanguageCode);
                            }])->where('iata_code',$segments['departure']['iataCode'])->first();

                            $getArrivalAirport = \App\Models\Airport::with(['airportName' => function ($query) use
                            ($getLanguageCode) {
                            $query->where('language_code', $getLanguageCode);
                            }])->where('iata_code',$segments['arrival']['iataCode'])->first();

                            $getDepartureSengment = \App\Models\City::with(['cityCode' => function ($query) use
                            ($getLanguageCode) {
                            $query->where('language_code', $getLanguageCode);
                            }])->where('iso_code',$segments['departure']['iataCode'])->first();

                            $getArrivalSengment = \App\Models\City::with(['cityCode' => function ($query) use
                            ($getLanguageCode) {
                            $query->where('language_code', $getLanguageCode);
                            }])->where('iso_code',$segments['arrival']['iataCode'])->first();

                            $getAirline = \App\Models\Airline::with(['airlineCodeName' => function ($query) use
                            ($getLanguageCode) {
                            $query->where('language_code', $getLanguageCode);
                            }])->where('airline_code',$segments['carrierCode'])->first();

                            $interval = new DateInterval($segments['duration']);
                            $formattedDuration = '';
                            if ($interval->h > 0) {
                            $formattedDuration .= $interval->h . 'h ';
                            }
                            if ($interval->i > 0) {
                            $formattedDuration .= $interval->i . 'm';
                            }
                            $duration = $formattedDuration;

                            @endphp
                            <div class="fll-one @if($loop->iteration == '2') mt-3 @endif">
                                <div class="left-fll-one">
                                    <div class="left-sub-one">
                                        <div class="l-img">
                                            <img src="{{$getAirline['airline_logo'] ? $getAirline['airline_logo'] : URL::asset('assets/images/airlineLogo/'.$getAirline['airline_code'].'.png')}}"
                                                alt="">
                                        </div>
                                        <div class="l-text">
                                            <p>{{$getAirline['airlineCodeName'][0]['airline_name']}} AIR..</p>
                                            <p>{{$segments['carrierCode']}}-{{$segments['aircraft']['code']}}</p>
                                        </div>
                                    </div>
                                    <div class="left-sub-two">
                                        <div class="a">
                                            <div class="mb-line">
                                                <span
                                                    class="mi-text">{{$getDepartureSengment['cityCode'][0]['city_name']}}</span>
                                                - <span>{{$segments['departure']['iataCode']}}</span>
                                            </div>
                                            <div class="date-text">
                                                <span
                                                    class="mi-text">{{\Carbon\Carbon::parse($segments['departure']['at'])->format("H:i")}},</span><span>
                                                    {{\Carbon\Carbon::parse($segments['departure']['at'])->format("d M'y")}}</span>
                                            </div>
                                        </div>
                                        <div class="b">
                                            <span> {{ $getDepartureAirport['airportName'][0]['airport_name'] ?? '' }},
                                                Terminal {{ $segments['departure']['terminal'] ?? '' }} </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="right-fll-one">
                                    <div class="right-s-one">
                                        <div class="dum-air">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512"
                                                x="0" y="0" viewBox="0 0 64 64"
                                                style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                                <g>
                                                    <path
                                                        d="M4.27 32.34a1.013 1.013 0 0 1 .3-1.59l4.62-2.21a1.017 1.017 0 0 1 .9.02l6.23 3.28a1.027 1.027 0 0 0 .92.01l9.81-4.88a1.015 1.015 0 0 0 .23-1.66L14.55 13.85a1.004 1.004 0 0 1 .29-1.66l2.41-.99a7.9 7.9 0 0 1 6.62.26l15.14 7.68a2.618 2.618 0 0 0 2.39-.02l2.95-1.56c3.67-2.23 9.88-2.05 13.51-.58a3.323 3.323 0 0 1 .22 6.12L21.42 40.65a9.741 9.741 0 0 1-11.65-2.52z"
                                                        fill="#000000" opacity="1" data-original="#000000" class="">
                                                    </path>
                                                    <rect width="56" height="6" x="4" y="47.4" rx="1" fill="#000000"
                                                        opacity="1" data-original="#000000" class=""></rect>
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="airline-dum-text">
                                            <h6 class="dep-time">{{$duration}}</h6>
                                        </div>
                                        <div class="sp-border"></div>

                                    </div>
                                    <div class="right-s-two">
                                        <div class="a">
                                            <div class="mb-line">
                                                <span
                                                    class="mi-text">{{$getArrivalSengment['cityCode'][0]['city_name']}}</span>
                                                - <span>{{$segments['arrival']['iataCode']}}</span>
                                            </div>
                                            <div class="date-text">
                                                <span
                                                    class="mi-text">{{\Carbon\Carbon::parse($segments['arrival']['at'])->format("H:i")}},</span><span>
                                                    {{\Carbon\Carbon::parse($segments['arrival']['at'])->format("d M'y")}}</span>
                                            </div>
                                        </div>
                                        <div class="b">
                                            <span>{{$getArrivalAirport['airportName'][0]['airport_name'] ?? '' }},
                                                Terminal {{$segments['arrival']['terminal'] ?? ''}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="fll-two my-3">
                                @foreach($bookingDetail['data']['flightOffers'] as $flightOffers)
                                @if(isset($flightOffers['travelerPricings'][0]['fareDetailsBySegment'][0]))
                                <div class="fff-icon">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0"
                                            y="0" viewBox="0 0 500 500" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve" class="">
                                            <g>
                                                <path
                                                    d="M479.17 270.83c5.21 0 10.42-5.21 10.42-10.42v-62.5c0-17.71-13.54-31.25-31.25-31.25H375v291.67h83.33c17.71 0 31.25-13.54 31.25-31.25v-62.5a10.44 10.44 0 0 0-10.42-10.42 41.67 41.67 0 0 1 0-83.33z"
                                                    fill="#000000" opacity="1" data-original="#000000"></path>
                                                <path
                                                    d="M471.88 135.42c5.21-2.08 8.33-8.33 6.25-13.54L457.29 62.5c-5.21-16.67-24-25-39.58-19.79L121.88 144.79h333.33a52.83 52.83 0 0 1 16.67-9.37z"
                                                    data-name="Path" fill="#000000" opacity="1" data-original="#000000">
                                                </path>
                                                <path
                                                    d="M10.42 197.92v62.5c0 6.25 4.17 10.42 10.42 10.42a41.67 41.67 0 1 1 0 83.33c-6.25 0-10.42 4.17-10.42 10.42v62.5c0 17.71 13.54 31.25 31.25 31.25h312.5V166.67H41.67c-17.67 0-31.25 13.54-31.25 31.25zm125 52.08h62.5c6.25 0 10.42 4.17 10.42 10.42s-4.17 10.42-10.42 10.42h-62.5c-6.25 0-10.42-4.17-10.42-10.42S129.17 250 135.42 250zm0 52.08h135.41c6.25 0 10.42 4.17 10.42 10.42s-4.17 10.42-10.42 10.42H135.42c-6.25 0-10.42-4.17-10.42-10.42s4.17-10.42 10.42-10.42zm0 52.08h135.41c6.25 0 10.42 4.17 10.42 10.42S277.08 375 270.83 375H135.42c-6.25 0-10.42-4.17-10.42-10.42s4.17-10.41 10.42-10.41z"
                                                    fill="#000000" opacity="1" data-original="#000000"></path>
                                            </g>
                                        </svg></span> <span class="t-icon-text">
                                        {{$flightOffers['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin']}}
                                    </span>
                                </div>
                                <div class="sss-icon">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0"
                                            y="0" viewBox="0 0 60 60" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve">
                                            <g>
                                                <path
                                                    d="M13 22v28a6.006 6.006 0 0 0 6 6 4 4 0 0 0 8 0h6a4 4 0 0 0 8 0 6.006 6.006 0 0 0 6-6V22a6.006 6.006 0 0 0-6-6V5.816A2.993 2.993 0 0 0 40 0H20a2.993 2.993 0 0 0-1 5.816V16a6.006 6.006 0 0 0-6 6zm10 36a2 2 0 0 1-2-2h4a2 2 0 0 1-2 2zm14 0a2 2 0 0 1-2-2h4a2 2 0 0 1-2 2zM20 2h20a1 1 0 0 1 0 2H20a1 1 0 0 1 0-2zm19 4v10h-2V6zm-4 0v10H25V6zM23 6v10h-2V6zm-3 12h21a4 4 0 0 1 4 4v28a4 4 0 0 1-4 4H19a4 4 0 0 1-4-4V22a4 4 0 0 1 4-4z"
                                                    fill="#000000" opacity="1" data-original="#000000"></path>
                                                <path
                                                    d="M22 49a1 1 0 0 0 1-1V24a1 1 0 0 0-2 0v24a1 1 0 0 0 1 1zM30 49a1 1 0 0 0 1-1V24a1 1 0 0 0-2 0v24a1 1 0 0 0 1 1zM38 49a1 1 0 0 0 1-1V24a1 1 0 0 0-2 0v24a1 1 0 0 0 1 1z"
                                                    fill="#000000" opacity="1" data-original="#000000"></path>
                                            </g>
                                        </svg></span><span class="t-icon-text">
                                        Check In -
                                        @if(isset($flightOffers['travelerPricings'][0]['fareDetailsBySegment'][0]['includedCheckedBags']['weight']))
                                        {{ $flightOffers['travelerPricings'][0]['fareDetailsBySegment'][0]['includedCheckedBags']['weight'] }}
                                        Kgs per adult
                                        @else
                                        {{ $flightOffers['travelerPricings'][0]['fareDetailsBySegment'][0]['includedCheckedBags']['quantity'] }}
                                        per adult
                                        @endif
                                        
                                        
                                    </span>
                                </div>
                                <div class="ttt-icon">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0"
                                            y="0" viewBox="0 0 682.667 682.667"
                                            style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                            <g>
                                                <defs>
                                                    <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                                        <path d="M0 512h512V0H0Z" fill="#000000" opacity="1"
                                                            data-original="#000000"></path>
                                                    </clipPath>
                                                </defs>
                                                <path
                                                    d="M113.567 383.786H65.079v30.305h48.488zM398.433 383.786h48.488v30.305h-48.488zM113.567 173.672h43.437v60.61h-43.437z"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="matrix(1.33333 0 0 -1.33333 0 682.667)" fill="none"
                                                    stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-miterlimit="10"
                                                    stroke-dasharray="none" stroke-opacity="" data-original="#000000">
                                                </path>
                                                <path d="M113.567 44.371h43.437v339.415h-43.437z"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="matrix(1.33333 0 0 -1.33333 0 682.667)" fill="none"
                                                    stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-miterlimit="10"
                                                    stroke-dasharray="none" stroke-opacity="" data-original="#000000">
                                                </path>
                                                <g clip-path="url(#a)"
                                                    transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                                    <path
                                                        d="M0 0v-76.067c0-11.045-8.954-20-20-20h-457.05c-11.045 0-20 8.955-20 20V110"
                                                        style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        transform="translate(504.55 140.438)" fill="none"
                                                        stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-miterlimit="10"
                                                        stroke-dasharray="none" stroke-opacity=""
                                                        data-original="#000000"></path>
                                                    <path
                                                        d="M0 0v188.254c0 11.046-8.954 20-20 20h-457.05c-11.045 0-20-8.954-20-20V110"
                                                        style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        transform="translate(504.55 175.531)" fill="none"
                                                        stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-miterlimit="10"
                                                        stroke-dasharray="none" stroke-opacity=""
                                                        data-original="#000000"></path>
                                                    <path
                                                        d="M0 0h78.186v-53.539h30.305v63.641c0 11.158-9.045 20.203-20.203 20.203H-68.288c-11.158 0-20.203-9.045-20.203-20.203v-63.641h30.305V0h23.191"
                                                        style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        transform="translate(245.495 437.324)" fill="none"
                                                        stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-miterlimit="10"
                                                        stroke-dasharray="none" stroke-opacity=""
                                                        data-original="#000000"></path>
                                                    <path d="M0 0h43.437"
                                                        style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        transform="translate(113.567 203.977)" fill="none"
                                                        stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-miterlimit="10"
                                                        stroke-dasharray="none" stroke-opacity=""
                                                        data-original="#000000"></path>
                                                    <path d="M398.433 173.672h-43.437v60.61h43.437z"
                                                        style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        fill="none" stroke="#000000" stroke-width="15"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                        data-original="#000000"></path>
                                                    <path d="M398.433 44.371h-43.437v339.415h43.437z"
                                                        style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        fill="none" stroke="#000000" stroke-width="15"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                        data-original="#000000"></path>
                                                    <path d="M0 0h-43.437"
                                                        style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        transform="translate(398.433 203.977)" fill="none"
                                                        stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-miterlimit="10"
                                                        stroke-dasharray="none" stroke-opacity=""
                                                        data-original="#000000"></path>
                                                </g>
                                            </g>
                                        </svg></span><span class="t-icon-text">
                                        Carry On - 7 Kgs per adult
                                    </span>
                                </div>
                                @endif

                            </div>

                            <div class="fill-three">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">TRAVELLER NAME </th>
                                            <th scope="col">AIRLINE PNR</th>
                                            <th scope="col">GDS PNR</th>
                                            <th scope="col">E-TICKET NO.</th>
                                            <th scope="col">BASIC FARE</th>
                                            <th scope="col">TAXES & FEES</th>
                                            <th scope="col">MEAL FEES</th>
                                            <th scope="col">SEAT FEES</th>
                                            <th scope="col">BAGG FEES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookingDetail['bookingDetail']['getFlightBookingTraveler'] as
                                        $traveler)
                                        @foreach($bookingDetail['data']['travelers'] as $apitravelers)
                                        @if($apitravelers['id'] == $traveler['traveler_id'])
                                        <tr>
                                            <td scope="row">{{ ucfirst($apitravelers['name']['firstName'] ?? '') }}
                                                {{ ucfirst($apitravelers['name']['lastName'] ?? '') }} <br>
                                                <span class="small">{{ ucfirst($traveler['traveler_type'] ?? '') }} ,
                                                    {{$traveler['gender']}}</span></td>
                                            <td>-</td>
                                            <td>{{$bookingDetail['bookingDetail']['pnr_number']}}</td>
                                            <td>-</td>
                                            <td>SAR {{$traveler['admin_base_fare'] ?? 0.00}}</td>
                                            <td>SAR {{$traveler['admin_s_charge'] ?? 0.00}}</td>
                                            <td>- <br> <span class="small"></span> </td>
                                            <td>- <br> <span class="small"></span> </td>
                                            <td>- <br> <span class="small"></span> </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
                            @if($loop->first)
                            @foreach($bookingDetail['layover'] as $layover)
                            @if($segments['id'] == $layover['fromSegmentId'])
                            <div class="fill-fourr">
                                <span><svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                        viewBox="0 0 682.667 682.667" style="enable-background:new 0 0 512 512"
                                        xml:space="preserve" class="">
                                        <g>
                                            <defs>
                                                <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                                    <path d="M0 512h512V0H0Z" fill="#000000" opacity="1"
                                                        data-original="#000000"></path>
                                                </clipPath>
                                                <clipPath id="b" clipPathUnits="userSpaceOnUse">
                                                    <path d="M0 512h512V0H0Z" fill="#000000" opacity="1"
                                                        data-original="#000000"></path>
                                                </clipPath>
                                            </defs>
                                            <path d="M0 0h-96.194l-16.032 64.129H16.032Z"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 202.387 266.516)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0h20.04"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 181.011 223.764)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0h52.104"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 68.785 223.764)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="m0 0-8.016 32.065h64.129L48.097 0"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 106.193 181.011)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0v-112.226"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 106.193 266.516)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0v-112.226"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 170.322 266.516)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0v64.129"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 138.258 138.258)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                                <path d="M0 0h497"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(7.5 39.565)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                                <path d="M0 0h497"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(7.5 199.887)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                                <path d="M0 0v-160.323"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(488.468 199.887)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                                <path d="M0 0v160.323"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(23.532 39.565)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                                <path d="M0 0h376.755"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(111.712 167.823)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                                <path d="M0 0h56.111"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(23.532 167.823)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                            </g>
                                            <path d="M0 0v128.258"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 341.333 629.914)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0v128.258"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 255.828 629.914)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0v128.258"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 170.322 629.914)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0v72.144"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 597.849 555.094)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0v24.049"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 597.849 629.914)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0v128.258"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 512.344 629.914)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0v128.258"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 426.839 629.914)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <path d="M0 0v128.258"
                                                style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="matrix(1.33333 0 0 -1.33333 84.817 629.914)" fill="none"
                                                stroke="#000000" stroke-width="15" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none"
                                                stroke-opacity="" data-original="#000000" class=""></path>
                                            <g clip-path="url(#b)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                                <path
                                                    d="m0 0-112.991-41.125c-16.64-6.057-35.04 2.523-41.097 19.164L-10.967 30.13c8.321 3.029 17.521-1.261 20.549-9.582C12.61 12.229 8.32 3.028 0 0Z"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(461.882 345.14)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                                <path d="m0 0 15.065 5.483"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(292.729 317.695)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                                <path
                                                    d="m0 0-15.758 19.856L.6 25.81a16.03 16.03 0 0 0 12.259-.535l24.804-11.567"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(315.327 325.92)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                                <path d="m0 0-30.131-10.967"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(296.163 284.823)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                                <path d="m0 0-45.196-16.45"
                                                    style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                    transform="translate(262.598 306.729)" fill="none" stroke="#000000"
                                                    stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                    data-original="#000000" class=""></path>
                                            </g>
                                        </g>
                                    </svg></span><span>
                                    {{$layover['layoverDurationText']}} layover Stopover in
                                    {{$getArrivalSengment['cityCode'][0]['city_name']}}
                                    ({{$segments['arrival']['iataCode']}}), you will have to change the plane
                                </span>
                            </div>
                            @endif
                            @endforeach
                            @endif

                            @endforeach
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

            <!-- second colllllll -->

            <!-- cancel-box -->
            <div class="col-12 mt-4">
                <div class="new-cnacel-box">
                    <h2>CANCELLATION</h2>
                    <p>Your trip is completed and as your flight has already departed, online cancellation is not
                        allowed.</p>
                    <span class="free-linee-new"></span>
                </div>
            </div>

            <!-- fare--box -->
            <div class="col-12">
                <div class="fare-box mt-4">
                    <span class="fare-one">FARE DETAILS</span>
                    <span class="fare-two">
                        Customer Booked in {{$bookingDetail['bookingDetail']['customer_currency'] ?? ''}} Currency with
                        Exchange Rate of 1 {{$bookingDetail['bookingDetail']['admin_currency'] ?? ''}}
                        =
                        {{ ($bookingDetail['bookingDetail']['currency_conversion_rate'] + $bookingDetail['bookingDetail']['currency_markup']) }}
                        {{$bookingDetail['bookingDetail']['customer_currency'] ?? ''}}
                    </span>

                    @foreach($bookingDetail['bookingDetail']['getFlightBookingTraveler'] as $traveler)
                    <div class="pure-tra-one mt-3">
                        <div class="tra-line-one py-1">
                            <span>Traveller {{$traveler['traveler_id'] ?? ''}}
                                ({{$traveler['traveler_type'] ?? ''}})</span><span>{{$traveler['admin_currency'] ?? ''}}
                                {{$traveler['admin_total'] ?? 0.00}}</span>
                        </div>
                        <div class="tra-line-two py-1">
                            <span>Fare</span><span>{{$traveler['admin_currency'] ?? ''}}
                                {{$traveler['admin_base_fare'] ?? 0.00}}</span>
                        </div>
                        <div class="tra-line-three py-1">
                            <span>Taxes & Fees</span><span>{{$traveler['admin_currency'] ?? ''}}
                                {{$traveler['admin_s_charge'] ?? 0.00}}</span>
                        </div>
                    </div>
                    @endforeach

                    <div class="pure-tra-one py-2">
                        <div class="tra-line-one py-1">
                            <span>Addon Meal
                                Fees</span><span>{{$bookingDetail['bookingDetail']['admin_currency'] ?? ''}} 0.00</span>
                        </div>
                    </div>

                    <div class="pure-tra-one py-2">
                        <div class="tra-line-one py-1">
                            <span>Addon Seat
                                Fees</span><span>{{$bookingDetail['bookingDetail']['admin_currency'] ?? ''}} 0.00</span>
                        </div>
                    </div>


                    <div class="pure-tra-one py-2">
                        <div class="tra-line-one py-1">
                            <span>Addon Bagg
                                Fees</span><span>{{$bookingDetail['bookingDetail']['admin_currency'] ?? ''}} 0.00</span>
                        </div>
                    </div>

                    <div class="pure-tra-one py-2">
                        <div class="tra-line-one py-1">
                            <span>Markup Fee</span><span>{{$bookingDetail['bookingDetail']['admin_currency'] ?? ''}}
                                {{$bookingDetail['bookingDetail']['admin_s_tax']}}</span>
                        </div>
                    </div>

                    <div class="pure-tra-one py-2">
                        <div class="tra-line-one py-1">
                            <span>Vat ({{ round($bookingDetail['bookingDetail']['admin_tax']) }}%)
                                <span class="on-text">Vat on Mark Up Fee for International Flight booking</span>
                            </span><span>{{$bookingDetail['bookingDetail']['admin_currency'] ?? ''}}
                                {{$bookingDetail['bookingDetail']['admin_s_charge']}}</span>

                        </div>

                    </div>


                    <div class="pure-tra-one py-2 border-0 ">
                        <div class="tra-line-one py-1">
                            <span>Total Amount</span><span>{{$bookingDetail['bookingDetail']['admin_currency'] ?? ''}}
                                {{$bookingDetail['bookingDetail']['admin_sub_total']}}</span>
                        </div>
                    </div>
                    <span class="free-linee-new-new"></span>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="col-12 mt-4">
                <div class="customer-info">
                    <span>CUSTOMER CONTACT INFORMATION</span>
                    <p class="about-text py-1">Information about primary traveller. Any communication by airlines will
                        be sent to these details.</p>

                    <div class="six-box py-3">
                        <div class="row">
                            @foreach($bookingDetail['data']['contacts'] as $contacts)
                            <div class="col-5 py-2 ms-2">
                                <span class="form-img-new"><svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                        viewBox="0 0 512 512.001" style="enable-background:new 0 0 512 512"
                                        xml:space="preserve" class="hovered-paths">
                                        <g>
                                            <path
                                                d="M210.352 246.633c33.882 0 63.218-12.153 87.195-36.13 23.969-23.972 36.125-53.304 36.125-87.19 0-33.876-12.152-63.211-36.129-87.192C273.566 12.152 244.23 0 210.352 0c-33.887 0-63.22 12.152-87.192 36.125s-36.129 53.309-36.129 87.188c0 33.886 12.156 63.222 36.13 87.195 23.98 23.969 53.316 36.125 87.19 36.125zM144.379 57.34c18.394-18.395 39.973-27.336 65.973-27.336 25.996 0 47.578 8.941 65.976 27.336 18.395 18.398 27.34 39.98 27.34 65.972 0 26-8.945 47.579-27.34 65.977-18.398 18.399-39.98 27.34-65.976 27.34-25.993 0-47.57-8.945-65.973-27.34-18.399-18.394-27.344-39.976-27.344-65.976 0-25.993 8.945-47.575 27.344-65.973zM426.129 393.703c-.692-9.976-2.09-20.86-4.149-32.351-2.078-11.579-4.753-22.524-7.957-32.528-3.312-10.34-7.808-20.55-13.375-30.336-5.77-10.156-12.55-19-20.16-26.277-7.957-7.613-17.699-13.734-28.965-18.2-11.226-4.44-23.668-6.69-36.976-6.69-5.227 0-10.281 2.144-20.043 8.5a2711.03 2711.03 0 0 1-20.879 13.46c-6.707 4.274-15.793 8.278-27.016 11.903-10.949 3.543-22.066 5.34-33.043 5.34-10.968 0-22.086-1.797-33.043-5.34-11.21-3.622-20.3-7.625-26.996-11.899-7.77-4.965-14.8-9.496-20.898-13.469-9.754-6.355-14.809-8.5-20.035-8.5-13.313 0-25.75 2.254-36.973 6.7-11.258 4.457-21.004 10.578-28.969 18.199-7.609 7.281-14.39 16.12-20.156 26.273-5.558 9.785-10.058 19.992-13.371 30.34-3.2 10.004-5.875 20.945-7.953 32.524-2.063 11.476-3.457 22.363-4.149 32.363C.343 403.492 0 413.668 0 423.949c0 26.727 8.496 48.363 25.25 64.32C41.797 504.017 63.688 512 90.316 512h246.532c26.62 0 48.511-7.984 65.062-23.73 16.758-15.946 25.254-37.59 25.254-64.325-.004-10.316-.351-20.492-1.035-30.242zm-44.906 72.828c-10.934 10.406-25.45 15.465-44.38 15.465H90.317c-18.933 0-33.449-5.059-44.379-15.46-10.722-10.208-15.933-24.141-15.933-42.587 0-9.594.316-19.066.95-28.16.616-8.922 1.878-18.723 3.75-29.137 1.847-10.285 4.198-19.937 6.995-28.675 2.684-8.38 6.344-16.676 10.883-24.668 4.332-7.618 9.316-14.153 14.816-19.418 5.145-4.926 11.63-8.957 19.27-11.98 7.066-2.798 15.008-4.329 23.629-4.56 1.05.56 2.922 1.626 5.953 3.602 6.168 4.02 13.277 8.606 21.137 13.625 8.86 5.649 20.273 10.75 33.91 15.152 13.941 4.508 28.16 6.797 42.273 6.797 14.114 0 28.336-2.289 42.27-6.793 13.648-4.41 25.058-9.507 33.93-15.164 8.043-5.14 14.953-9.593 21.12-13.617 3.032-1.973 4.903-3.043 5.954-3.601 8.625.23 16.566 1.761 23.636 4.558 7.637 3.024 14.122 7.059 19.266 11.98 5.5 5.262 10.484 11.798 14.816 19.423 4.543 7.988 8.208 16.289 10.887 24.66 2.801 8.75 5.156 18.398 7 28.675 1.867 10.434 3.133 20.239 3.75 29.145v.008c.637 9.058.957 18.527.961 28.148-.004 18.45-5.215 32.38-15.937 42.582zm0 0"
                                                fill="#000000" opacity="1" data-original="#000000" class="hovered-path">
                                            </path>
                                        </g>
                                    </svg></span><span class="form-text-new">
                                    {{ $contacts['addresseeName']['firstName'] ?? '' }}</span>
                            </div>
                            <div class="col-6 py-2 ms-2">
                                <span class="form-img-new"><svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512"
                                        xml:space="preserve" class="">
                                        <g>
                                            <g fill-rule="evenodd" stroke-linecap="round" stroke-miterlimit="10"
                                                clip-rule="evenodd">
                                                <path
                                                    d="M65.445 0C29.38 0 0 29.38 0 65.446v381.111C0 482.623 29.38 512 65.445 512h381.11C482.62 512 512 482.623 512 446.557V65.446C512 29.38 482.62 0 446.555 0zm0 22.002h381.11c24.258 0 43.445 19.185 43.445 43.444v381.111C490 470.815 470.813 490 446.555 490H65.445C41.187 490 22 470.815 22 446.557V65.446c0-24.259 19.187-43.444 43.445-43.444z"
                                                    fill="#000000" opacity="1" data-original="#000000"></path>
                                                <path
                                                    d="M174.32 136.114c-36.012 0-65.443 29.43-65.443 65.443 0 20.491 9.546 38.831 24.395 50.85-45.9 16.829-78.823 60.844-78.823 112.487a11 11 0 0 0 11 11 11 11 0 0 0 11-11c0-54.195 43.698-97.892 97.891-97.892 54.193 0 97.89 43.697 97.89 97.892a11 11 0 0 0 11 11 11 11 0 0 0 11-11c0-51.653-32.935-95.677-78.849-112.498 14.84-12.02 24.381-30.354 24.381-50.84 0-36.012-29.429-65.443-65.441-65.442zm0 22.001a43.275 43.275 0 0 1 43.442 43.442 43.275 43.275 0 0 1-43.441 43.44c-24.123 0-43.444-19.318-43.444-43.44 0-24.123 19.32-43.442 43.444-43.442zM337.672 326.666a10.998 10.998 0 0 0-10.998 10.998 10.998 10.998 0 0 0 10.998 10.998l108.888.004a10.998 10.998 0 0 0 11-10.998 10.998 10.998 0 0 0-10.998-11zM446.562 245.002l-136.113.002a10.998 10.998 0 0 0-10.998 10.998 10.998 10.998 0 0 0 10.998 11l136.113-.004A10.998 10.998 0 0 0 457.56 256a10.998 10.998 0 0 0-10.998-10.998zM283.228 163.334a11 11 0 0 0-11 11 11 11 0 0 0 11 11H446.56a11 11 0 0 0 11-11 11 11 0 0 0-11-11z"
                                                    fill="#000000" opacity="1" data-original="#000000"></path>
                                            </g>
                                        </g>
                                    </svg></span><span class="form-text-new">
                                    @foreach($contacts['address']['lines'] as $addr)
                                    {{$addr}}
                                    @endforeach
                                </span>
                            </div>
                            <div class="col-5 py-2 ms-2">
                                <span class="form-img-new"><svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512"
                                        xml:space="preserve" class="">
                                        <g>
                                            <path
                                                d="M467 76H45C20.137 76 0 96.262 0 121v270c0 24.885 20.285 45 45 45h422c24.655 0 45-20.03 45-45V121c0-24.694-20.057-45-45-45zm-6.302 30L287.82 277.967c-8.5 8.5-19.8 13.18-31.82 13.18s-23.32-4.681-31.848-13.208L51.302 106h409.396zM30 384.894V127.125L159.638 256.08 30 384.894zM51.321 406l129.587-128.763 22.059 21.943c14.166 14.166 33 21.967 53.033 21.967s38.867-7.801 53.005-21.939l22.087-21.971L460.679 406H51.321zM482 384.894 352.362 256.08 482 127.125v257.769z"
                                                fill="#000000" opacity="1" data-original="#000000"></path>
                                        </g>
                                    </svg></span><span class="form-text-new">
                                    {{ $contacts['emailAddress'] ?? '' }}</span>
                            </div>
                            <!-- <div class="col-6 py-2 ms-2">
                                      <span class="form-img-new"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g fill-rule="evenodd" stroke-linecap="round" stroke-miterlimit="10" clip-rule="evenodd"><path d="M65.445 0C29.38 0 0 29.38 0 65.446v381.111C0 482.623 29.38 512 65.445 512h381.11C482.62 512 512 482.623 512 446.557V65.446C512 29.38 482.62 0 446.555 0zm0 22.002h381.11c24.258 0 43.445 19.185 43.445 43.444v381.111C490 470.815 470.813 490 446.555 490H65.445C41.187 490 22 470.815 22 446.557V65.446c0-24.259 19.187-43.444 43.445-43.444z" fill="#000000" opacity="1" data-original="#000000"></path><path d="M174.32 136.114c-36.012 0-65.443 29.43-65.443 65.443 0 20.491 9.546 38.831 24.395 50.85-45.9 16.829-78.823 60.844-78.823 112.487a11 11 0 0 0 11 11 11 11 0 0 0 11-11c0-54.195 43.698-97.892 97.891-97.892 54.193 0 97.89 43.697 97.89 97.892a11 11 0 0 0 11 11 11 11 0 0 0 11-11c0-51.653-32.935-95.677-78.849-112.498 14.84-12.02 24.381-30.354 24.381-50.84 0-36.012-29.429-65.443-65.441-65.442zm0 22.001a43.275 43.275 0 0 1 43.442 43.442 43.275 43.275 0 0 1-43.441 43.44c-24.123 0-43.444-19.318-43.444-43.44 0-24.123 19.32-43.442 43.444-43.442zM337.672 326.666a10.998 10.998 0 0 0-10.998 10.998 10.998 10.998 0 0 0 10.998 10.998l108.888.004a10.998 10.998 0 0 0 11-10.998 10.998 10.998 0 0 0-10.998-11zM446.562 245.002l-136.113.002a10.998 10.998 0 0 0-10.998 10.998 10.998 10.998 0 0 0 10.998 11l136.113-.004A10.998 10.998 0 0 0 457.56 256a10.998 10.998 0 0 0-10.998-10.998zM283.228 163.334a11 11 0 0 0-11 11 11 11 0 0 0 11 11H446.56a11 11 0 0 0 11-11 11 11 0 0 0-11-11z" fill="#000000" opacity="1" data-original="#000000"></path></g></g></svg></span><span class="form-text-new">
                                        Opp: Saraspur Nagrik Bank, Navrangpura</span>
                                  </div> -->
                            <div class="col-5 py-2 ms-2">
                                <span class="form-img-new"><svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                        viewBox="0 0 25.625 25.625" style="enable-background:new 0 0 512 512"
                                        xml:space="preserve">
                                        <g>
                                            <path
                                                d="M22.079 17.835c-1.548-1.324-3.119-2.126-4.648-.804l-.913.799c-.668.58-1.91 3.29-6.712-2.234-4.801-5.517-1.944-6.376-1.275-6.951l.918-.8c1.521-1.325.947-2.993-.15-4.71l-.662-1.04C7.535.382 6.335-.743 4.81.58l-.824.72c-.674.491-2.558 2.087-3.015 5.119-.55 3.638 1.185 7.804 5.16 12.375 3.97 4.573 7.857 6.87 11.539 6.83 3.06-.033 4.908-1.675 5.486-2.272l.827-.721c1.521-1.322.576-2.668-.973-3.995l-.931-.801z"
                                                style="" fill="#030104" data-original="#030104"></path>
                                        </g>
                                    </svg></span><span class="form-text-new">
                                    @foreach($contacts['phones'] as $phones)
                                    +{{$phones['countryCallingCode']}}-{{$phones['number']}}
                                    @endforeach</span>
                            </div>
                            <div class="col-6 py-2 ms-2">
                                <span class="form-img-new"><svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512"
                                        xml:space="preserve">
                                        <g>
                                            <path
                                                d="M256 0C153.755 0 70.573 83.182 70.573 185.426c0 126.888 165.939 313.167 173.004 321.035 6.636 7.391 18.222 7.378 24.846 0 7.065-7.868 173.004-194.147 173.004-321.035C441.425 83.182 358.244 0 256 0zm0 278.719c-51.442 0-93.292-41.851-93.292-93.293S204.559 92.134 256 92.134s93.291 41.851 93.291 93.293-41.85 93.292-93.291 93.292z"
                                                fill="#000000" opacity="1" data-original="#000000"></path>
                                        </g>
                                    </svg></span><span class="form-text-new">
                                    @php
                                    $getCityName = \App\Models\City::with(['cityCode' => function ($query) use
                                    ($getLanguageCode) {
                                    $query->where('language_code', $getLanguageCode);
                                    }])->where('iso_code',$contacts['address']['cityName'])->first();

                                    $getCountryName = \App\Models\Country::with(['countryCode' => function ($query) use
                                    ($getLanguageCode) {
                                    $query->where('language_code', $getLanguageCode);
                                    }])->where('iso_code',$contacts['address']['countryCode'])->first();
                                    @endphp
                                    {{$getCityName['cityCode'][0]['city_name'] ?? ''}}
                                    @if(isset($getCityName['cityCode'][0]['city_name'])),@endif
                                    {{$getCountryName['countryCode'][0]['country_name'] ?? ''}}
                                    {{$contacts['address']['postalCode']}}

                                </span>
                            </div>
                            @endforeach

                        </div>

                    </div>
                    <span class="free-linee-new-new"></span>
                </div>
            </div>

            <!-- airline-contact-information -->
            <div class="col-12 mt-4">
                <div class="airline-contact">
                    <div class="left-air">
                        <span class="aci-text">AIRLINE CONTACT INFORMATION</span><span><svg
                                xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0"
                                viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"
                                class="">
                                <g>
                                    <path
                                        d="m506.134 241.843-.018-.019-104.504-104c-7.829-7.791-20.492-7.762-28.285.068-7.792 7.829-7.762 20.492.067 28.284L443.558 236H20c-11.046 0-20 8.954-20 20s8.954 20 20 20h423.557l-70.162 69.824c-7.829 7.792-7.859 20.455-.067 28.284 7.793 7.831 20.457 7.858 28.285.068l104.504-104 .018-.019c7.833-7.818 7.808-20.522-.001-28.314z"
                                        fill="#000000" opacity="1" data-original="#000000" class=""></path>
                                </g>
                            </svg></span>
                    </div>
                    <div class="right-air">
                        @foreach($bookingDetail['airlineList'] as $air)
                        <div class="lll-one">
                            <img src="{{$air['logo']}}" alt="">
                        </div>
                        <div class="rrr-one">
                            <p>{{$air['name'] ?? ''}} AIRLINES</p>
                            <p>011-4102XXXX</p>
                        </div>
                        @endforeach
                    </div>
                    <span class="free-linee-new-new"></span>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- second design Page -->



</div>
<!-- /.content-header -->



@endsection
