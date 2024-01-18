<?php
/**
 * @package     B2C
 * @subpackage   Instagram Feed
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Instagram Feed.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;

class InstagramFeedController extends Controller
{
    /**
     * Display a listing of the instagram feed.
     *
     * @return \Illuminate\Http\Response
     */
    public function createInstagramFeed()
    {
        if (!hasPermission('INSTAGRAM_FEED', 'read')) {
            return view('admin/401');
        }

        $header['title'] = 'Instagram Feed';
        $header['heading'] = 'Instagram Feed';

        $timezones = \DateTimeZone::listIdentifiers();
        $items = array();
        foreach ($timezones as $timezoneId) {
            $timezone = new \DateTimeZone($timezoneId);
            $offsetInSeconds = $timezone->getOffset(new \DateTime());
            $items[$timezoneId] = $offsetInSeconds;
        }
        asort($items);
        array_walk($items, function (&$offsetInSeconds, $timezoneId) {
            $offsetPrefix = $offsetInSeconds < 0 ? '-' : '+';
            $offset = gmdate('H:i', abs($offsetInSeconds));
            $offset = "(GMT${offsetPrefix}${offset}) " . explode('/', $timezoneId)[0] . '/' . @explode('/', $timezoneId)[1];
            $offsetInSeconds = $offset;
        });

        $instaFeedData = Setting::where('config_key','instagramFeed')->first();
        return view('admin/instagram-feed/index')->with(['header' => $header, 'items' => $items, 'instaFeedData'=>$instaFeedData]);
    }

    /**
     * Update or create the specified instagram feed info in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function instagramFeed(Request $request)
    {
        if (!hasPermission('INSTAGRAM_FEED', 'create') && !hasPermission('INSTAGRAM_FEED', 'update')) {
            return view('admin/401');
        }
        $data = $request->all();
        unset($data['_token']);

        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->route('create-instagram-feed')->with('success', 'Instagram Feed Details Saved Successfully');
    }
}
