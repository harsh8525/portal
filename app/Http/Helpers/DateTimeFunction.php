<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use App\Models\Setting;
use Carbon\Carbon;

//change date format for all module
function changeDate($data)
{
    $dateFormat = count(Setting::where('config_key', 'general|site|dateFormat')->get('value')) > 0 ? Setting::where('config_key', 'general|site|dateFormat')->get('value')[0]['value'] : "YYYY/MM/DD";
    if ($dateFormat == 'm/d/y') {
        $date = date("Y-m-d", strtotime($data));
    } else {
        $date = str_replace('/', '-', $data);
        $date = date("Y-m-d", strtotime($date));
    }
    return $date;
}

// set date as timezone to display in list, add and update page for all module
function getDateTimeZone($data)
{
    $dateFormat = count(Setting::where('config_key', 'general|site|dateFormat')->get('value')) > 0 ? Setting::where('config_key', 'general|site|dateFormat')->get('value')[0]['value'] : "YYYY/MM/DD";
    $getTimeZone = count(Setting::where('config_key', 'general|site|timeZone')->get('value')) > 0 ? Setting::where('config_key', 'general|site|timeZone')->get('value')[0]['value'] : "(GMT+05:30) Asia/Kolkata";
    if ($dateFormat == 'F j, Y') {
        $date = Carbon::parse($data)->timezone($getTimeZone)->format('F j, Y');
    } else if ($dateFormat == 'Y-m-d') {
        $date = Carbon::parse($data)->timezone($getTimeZone)->format('Y-m-d');
    } else if ($dateFormat == 'd/m/y') {
        $date = Carbon::parse($data)->timezone($getTimeZone)->format('d/m/Y');
    } else if ($dateFormat == 'm/d/y') {
        $date = Carbon::parse($data)->timezone($getTimeZone)->format('m/d/y');
    } else {
        $date = Carbon::parse($data)->timezone($getTimeZone)->format('Y/m/d');
    }
    return $date;
}

// set time as timezone to display in list, add and update page for all module
function getTimeZone($data)
{
    $format = count(Setting::where('config_key', 'general|site|timeFormat')->get('value')) > 0 ? Setting::where('config_key', 'general|site|timeFormat')->get('value')[0]['value'] : "h:m:s A";
    $getTimeZone = count(Setting::where('config_key', 'general|site|timeZone')->get('value')) > 0 ? Setting::where('config_key', 'general|site|timeZone')->get('value')[0]['value'] : "(GMT+05:30) Asia/Kolkata";
    $time = '';
    if ($format == 'H:m:s') {
        $time = Carbon::parse($data)->timezone($getTimeZone)->format('H:i:s');
    } else if ($format == 'h:m:s A') {
        $time = Carbon::parse($data)->timezone($getTimeZone)->format('h:i:s A');
    } else if ($format == 'H:m') {
        $time = Carbon::parse($data)->timezone($getTimeZone)->format('H:i');
    } else if ($format == 'h:m A') {
        $time = Carbon::parse($data)->timezone($getTimeZone)->format('h:i A');
    } else {
        $time = Carbon::parse($data)->timezone($getTimeZone)->format('h:i A');
    }
    $timeFormat = $time;
    return $timeFormat;
}


// set date to display in list, add and update page for all module
function dateFunction($data)
{
    $dateFormat = count(Setting::where('config_key', 'general|site|dateFormat')->get('value')) > 0 ? Setting::where('config_key', 'general|site|dateFormat')->get('value')[0]['value'] : "YYYY/MM/DD";
    if ($dateFormat == 'F j, Y') {
        $date = date("F d, Y", strtotime($data));
    } else if ($dateFormat == 'Y-m-d') {
        $date = date("Y-m-d", strtotime($data));
    } else if ($dateFormat == 'd/m/y') {
        $date = date("d/m/Y", strtotime($data));
    } else if ($dateFormat == 'm/d/y') {
        $date = date("m/d/Y", strtotime($data));
    } else {
        $date = date("Y/m/d", strtotime($data));
    }
    return $date;
}

// set date js file when select date in add and update page for all module
function getDateFunction()
{

    $format = count(Setting::where('config_key', 'general|site|dateFormat')->get('value')) > 0 ? Setting::where('config_key', 'general|site|dateFormat')->get('value')[0]['value'] : "YYYY/MM/DD";
    $date = '';
    if ($format == 'F j, Y') {
        $date = 'MMMM DD, YYYY';
    } else if ($format == 'Y-m-d') {
        $date = 'YYYY-MM-DD';
    } else if ($format == 'd/m/y') {
        $date = 'DD/MM/YYYY';
    } else if ($format == 'm/d/y') {
        $date = 'MM/DD/YYYY';
    } else {
        $date = 'YYYY/MM/DD';
    }
    $dateFormat = $date;

    return $dateFormat;
}

// set time js file when select time in add and update page for all module
function getTimeFunction()
{

    $format = count(Setting::where('config_key', 'general|site|timeFormat')->get('value')) > 0 ? Setting::where('config_key', 'general|site|timeFormat')->get('value')[0]['value'] : "h:m A";
    $time = '';
    if ($format == 'H:m:s') {
        $time = 'HH:mm:ss';
    } else if ($format == 'h:m:s A') {
        $time = 'hh:mm:ss A';
    } else if ($format == 'H:m') {
        $time = 'HH:mm';
    } else if ($format == 'h:m A') {
        $time = 'hh:mm A';
    } else {
        $time = 'hh:mm A';
    }
    $timeFormat = $time;
    return $timeFormat;
}

// set time to display in list, add and update page for all module
function timeFunction($data)
{
    $format = count(Setting::where('config_key', 'general|site|timeFormat')->get('value')) > 0 ? Setting::where('config_key', 'general|site|timeFormat')->get('value')[0]['value'] : "h:m:s A";
    $time = '';
    if ($format == 'H:m:s') {
        $time = date("H:i:s", strtotime($data));
    } else if ($format == 'h:m:s A') {
        $time = date("h:i:s A", strtotime($data));
    } else if ($format == 'H:m') {
        $time = date("H:i", strtotime($data));
    } else if ($format == 'h:m A') {
        $time = date("h:i A", strtotime($data));
    } else {
        $time = date("h:i A", strtotime($data));
    }
    $timeFormat = $time;
    return $timeFormat;
}

//currency format to display on dashboard
function thousandsCurrencyFormat($num)
{

    if ($num > 1000) {

        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('k', 'm', 'b', 't');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];

        return $x_display;
    }

    return $num;
}

//get hours and minute from format "PT19H20M"
function getHourMinute($input)
{


    // Regular expression pattern to extract hours and minutes
    $pattern = '/PT(?:(\d+)H)?(?:(\d+)M)?/';

    // Initialize variables to store the extracted values
    $hours = 0;
    $minutes = 0;

    // Perform the regular expression match
    if (preg_match($pattern, $input, $matches)) {
        // If the hour part exists in the string
        if (isset($matches[1])) {
            $hours = (int)$matches[1];
        }

        // If the minute part exists in the string
        if (isset($matches[2])) {
            $minutes = (int)$matches[2];
        }
    }
    $FormatedTime = $hours . 'h ' . $minutes . 'm';
    return $FormatedTime;
}

//convert datetime ISO fromat
function convertDateTime($isoDateTime)
{
    // Create a DateTime object from the ISO string
    $dateTime = DateTime::createFromFormat('Y-m-d\TH:i:s', $isoDateTime);

    if ($dateTime instanceof DateTime) {
        // Format the DateTime object to the desired format (e.g., "d-M-Y H:i:s")
        $formattedTime = $dateTime->format('H:i A');
        return $formattedTime;
    } else {
        echo "Invalid ISO format.";
    }
}

function getTimeDifference($startTime, $endTime)
{
    $startDateTime = new DateTime($startTime);
    $endDateTime = new DateTime($endTime);

    $interval = $startDateTime->diff($endDateTime);

    $hours = $interval->h + ($interval->days * 24);
    $minutes = $interval->i;

    return ["hours" => $hours, "minutes" => $minutes];
}
function getTimeFromDateTimeFormat($dateTime)
{
    $time = date("H:i:s", strtotime($dateTime));
    return $time;
}
