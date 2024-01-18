<?php

/**
 * @package     WithinEarth
 * @subpackage  HotelManagement
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Hotels.
 */

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use DB;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\AirlineI18ns;
use App\Models\Airport;
use App\Models\Airline;
use URL;

trait WithinEarth
{

    public $withinEarthBaseUrl = 'https://4hltconnect.withinearth.com';
    public $withinEarthToken = 'NntVOg/6oyUaFjXF9tPekq/hhkmaXj8o4tm/D77IBWz3nGAQ62Liy3qcKI1L9BvzxG71AIQ6UWEDpzvR4+/rdQ==';

    /**
     * create hotel availability search api with requested parameters
     */
    public function hotelAvailabilityGet($requestData)
    {

        $baseUrl = $this->withinEarthBaseUrl . "/api/xconnect/Availability/";

        $createRequestData = [
            'Token' => $this->withinEarthToken,
            'Request' => $requestData['request'],
            "AdvancedOptions" => $requestData['AdvancedOptions']
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($baseUrl, $createRequestData);

        if ($response->failed()) {
            return "Request failed: " . $response->status();
        }
        
        $decodedResponse = $response->json();
        
        return $decodedResponse;
    }
}
