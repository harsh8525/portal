<?php

use Illuminate\Support\Facades\Validator;
use App\Models\CurrencyExchangeRates;
use App\Models\Currency;

const DECIMAL_POINT_LENGTH = 2; // Set the appropriate value
function handleJsonRequest($jsonRequest)
{
    Validator::extend('checkReturnDate', function ($attribute, $value, $parameters, $validator) {
        return ucwords($value) === $value;
    });
    // Define validation rules for the JSON data
    $tempArray = [];
    $originDevice = Validator::make($jsonRequest, [
        'originDevice' => 'required|in:web,android,ios'

    ]);
    array_push($tempArray, $originDevice->errors());
    $searchType = Validator::make($jsonRequest, [
        'searchType' => 'required|in:one-way,round-trip,multi-city'

    ]);
    array_push($tempArray, $searchType->errors());
    $currencyCode = Validator::make($jsonRequest, [
        'currencyCode' => 'required|alpha'

    ]);
    array_push($tempArray, $currencyCode->errors());
    foreach ($jsonRequest['originDestinations'] as $data) {

        $validator = Validator::make($data, [
            'originLocationCode' => 'required|alpha',
            'destinationLocationCode' => 'required|alpha',
            'departureDate' => 'required|date|date_format:Y-m-d',
            'returnDate' => 'nullable|date|date_format:Y-m-d|after_or_equal:departureDate',
        ]);

        array_push($tempArray, $validator->errors());
    }
    foreach ($jsonRequest['travelers'] as $traveler) {


        $validator = Validator::make($traveler, [
            'type' => 'required|in:ADULT,CHILD,HELD_INFANT,SENIOR,YOUNG,SEATED_INFANT,STUDENT',
            'count' => 'required|numeric',

        ]);


        array_push($tempArray, $validator->errors());

    }

    $travelerClass = Validator::make($jsonRequest, [
        'travelClass' => 'required|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST'

    ]);

    array_push($tempArray, $travelerClass->errors());
    if ($originDevice->fails()) {
        return ['success' => false, 'error' => $tempArray];
    }
    if ($validator->fails()) {
        return ['success' => false, 'error' => $tempArray];
    }
    if ($searchType->fails()) {
        return ['success' => false, 'error' => $tempArray];
    }
    if ($currencyCode->fails()) {
        return ['success' => false, 'error' => $tempArray];
    }

    if ($travelerClass->fails()) {
        return ['success' => false, 'error' => $tempArray];
    }

    return ['success' => true];

}
/**
 * get currency code details using code from currencies table
 * created date 21-11-2023
 */
function getCurrency($conditions, $options)
{
    // Check if the 'code' condition is set
    if (isset($conditions['code'])) {
        $getCurrencyInfo = Currency::where('code', $conditions['code'])->first();

        //set currency info details into object
        return (object)[
            'decimalSeparator' => $getCurrencyInfo['decimal_separator'],
            'thousandSeparator' => $getCurrencyInfo['thousand_separator'],
            'symbol' => $getCurrencyInfo['symbol'],
        ];
    } else {
        // Handle the case where 'code' condition is not set
        return null;
    }
}
