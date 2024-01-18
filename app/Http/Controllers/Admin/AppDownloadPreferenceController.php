<?php
/**
 * @package     B2C
 * @subpackage  App Download Preference
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  App Download Preference.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use App\Traits\ActiveLog;

class AppDownloadPreferenceController extends Controller
{
    /**
     * Display a listing of the App Download Preference.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAppDownloadPreference()
    {
        if (!hasPermission('AppDownloadPreference', 'read')) {
            return view('admin/401');
        }

        $header['title'] = 'App Download Preference';
        $header['heading'] = 'App Download Preference';

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

        $titleEn = Setting::where('config_key','appDownloadPreference|titleEn')->first();
        $titleAr = Setting::where('config_key','appDownloadPreference|titleAr')->first();
        $qrCodeImage = Setting::where('config_key','appDownloadPreference|qrCodeImage')->first();
        $bannerImageEn = Setting::where('config_key','appDownloadPreference|bannerImageEn')->first();
        $bannerImageAr = Setting::where('config_key','appDownloadPreference|bannerImageAr')->first();
        $googlePlaystoreURL = Setting::where('config_key','appDownloadPreference|googlePlaystoreURL')->first();
        $appStoreURL = Setting::where('config_key','appDownloadPreference|appStoreURL')->first();
        $HUAWEIStoreURL = Setting::where('config_key','appDownloadPreference|HUAWEIStoreURL')->first();

        $data = [
            'titleEn' => $titleEn,
            'titleAr' => $titleAr,
            'qrCodeImage' => $qrCodeImage,
            'bannerImageEn' => $bannerImageEn,
            'bannerImageAr' => $bannerImageAr,
            'googlePlaystoreURL' => $googlePlaystoreURL,
            'appStoreURL' => $appStoreURL,
            'HUAWEIStoreURL' => $HUAWEIStoreURL
        ];

        return view('admin/app-download-preference/index')->with(['header' => $header, 'items' => $items, 'data' => $data]);
    }

    /**
     * Update or create the specified App Download Preference info in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function AppDownloadPreference(Request $request)
    {
        if (!hasPermission('AppDownloadPreference', 'create') && !hasPermission('AppDownloadPreference', 'update')) {
            return view('admin/401');
        }
       
        $requestData = $request->all();
        unset($requestData['_token']);

        //upload qrCodeImage
        if (isset($requestData['appDownloadPreference|qrCodeImage']) && $requestData['appDownloadPreference|qrCodeImage'] != "") {
            try {
                $base64_image_path = $requestData['appDownloadPreference|qrCodeImage'];
                // Extract the data and MIME type from the data URI
                list($data, $encoded_data) = explode(',', $base64_image_path);

                // Determine the file extension from the MIME type
                $mime_type_parts = explode(';', $data);
                if (count($mime_type_parts) > 0) {
                    $mime_type = trim($mime_type_parts[0]);
                    $image_type = null;
                    if ($mime_type === 'image/jpeg' || $mime_type === 'image/jpg') {
                        $image_type = IMAGETYPE_JPEG;
                    } elseif ($mime_type === 'image/png') {
                        $image_type = IMAGETYPE_PNG;
                    } else {
                        // Default to a specific extension (e.g., '.png') if the MIME type is not recognized
                        $image_type = IMAGETYPE_PNG;
                    }

                    $extension = image_type_to_extension($image_type);
                } else {
                    // Default to a specific extension (e.g., '.png') if no MIME type is provided
                    $extension = '.png';
                }

                // Decode the base64 data into binary image data
                $image_data = base64_decode($encoded_data);
                $destinationPath = storage_path() . '/app/public/app-download-preference/';
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true); // Create directory with default permissions
                    File::chmod($destinationPath, 0777); // Explicitly set permissions using Laravel's File::chmod method
                }

                $file = $image_data;
                $image_resize = Image::make($image_data);
                $fileName =  uniqid() . time() . $extension;
                $image_resize->save($destinationPath . $fileName);
                $filePath = $destinationPath . $fileName;
                if (file_exists($filePath)) {
                    chmod($filePath, 0777); // Change file permissions (consider using more restricted permissions)
                }
                $url = URL::to('/storage/') . '/app-download-preference/' . $fileName;
                $qrCodeImage = $url;
            } catch (Exception $e) {
                $return['message'] = 'Error during save image customer :' . $e->getMessage();
            }
        }else{
            if (isset($requestData['oldQrCodeImage'])) {
                $qrCodeImage = $requestData['oldQrCodeImage'];
            }
        }

        //upload bannerImageEn
        if (isset($requestData['appDownloadPreference|bannerImageEn']) && $requestData['appDownloadPreference|bannerImageEn'] != "") {
            try {
                $base64_image_path = $requestData['appDownloadPreference|bannerImageEn'];
                // Extract the data and MIME type from the data URI
                list($data, $encoded_data) = explode(',', $base64_image_path);

                // Determine the file extension from the MIME type
                $mime_type_parts = explode(';', $data);
                if (count($mime_type_parts) > 0) {
                    $mime_type = trim($mime_type_parts[0]);
                    $image_type = null;
                    if ($mime_type === 'image/jpeg' || $mime_type === 'image/jpg') {
                        $image_type = IMAGETYPE_JPEG;
                    } elseif ($mime_type === 'image/png') {
                        $image_type = IMAGETYPE_PNG;
                    } else {
                        // Default to a specific extension (e.g., '.png') if the MIME type is not recognized
                        $image_type = IMAGETYPE_PNG;
                    }

                    $extension = image_type_to_extension($image_type);
                } else {
                    // Default to a specific extension (e.g., '.png') if no MIME type is provided
                    $extension = '.png';
                }

                // Decode the base64 data into binary image data
                $image_data = base64_decode($encoded_data);
                $destinationPath = storage_path() . '/app/public/app-download-preference/';
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true); // Create directory with default permissions
                    File::chmod($destinationPath, 0777); // Explicitly set permissions using Laravel's File::chmod method
                }

                $file = $image_data;
                $image_resize = Image::make($image_data);
                $fileName =  uniqid() . time() . $extension;
                $image_resize->save($destinationPath . $fileName);
                $filePath = $destinationPath . $fileName;
                if (file_exists($filePath)) {
                    chmod($filePath, 0777); // Change file permissions (consider using more restricted permissions)
                }
                $url = URL::to('/storage/') . '/app-download-preference/' . $fileName;
                $bannerImageEn = $url;
            } catch (Exception $e) {
                $return['message'] = 'Error during save image customer :' . $e->getMessage();
            }
        }else{
            if (isset($requestData['oldBannerImageEn'])) {
                $bannerImageEn = $requestData['oldBannerImageEn'];
            }
        }

        //upload bannerImageAr
        if (isset($requestData['appDownloadPreference|bannerImageAr']) && $requestData['appDownloadPreference|bannerImageAr'] != "") {
            try {
                $base64_image_path = $requestData['appDownloadPreference|bannerImageAr'];
                // Extract the data and MIME type from the data URI
                list($data, $encoded_data) = explode(',', $base64_image_path);

                // Determine the file extension from the MIME type
                $mime_type_parts = explode(';', $data);
                if (count($mime_type_parts) > 0) {
                    $mime_type = trim($mime_type_parts[0]);
                    $image_type = null;
                    if ($mime_type === 'image/jpeg' || $mime_type === 'image/jpg') {
                        $image_type = IMAGETYPE_JPEG;
                    } elseif ($mime_type === 'image/png') {
                        $image_type = IMAGETYPE_PNG;
                    } else {
                        // Default to a specific extension (e.g., '.png') if the MIME type is not recognized
                        $image_type = IMAGETYPE_PNG;
                    }

                    $extension = image_type_to_extension($image_type);
                } else {
                    // Default to a specific extension (e.g., '.png') if no MIME type is provided
                    $extension = '.png';
                }

                // Decode the base64 data into binary image data
                $image_data = base64_decode($encoded_data);
                $destinationPath = storage_path() . '/app/public/app-download-preference/';
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true); // Create directory with default permissions
                    File::chmod($destinationPath, 0777); // Explicitly set permissions using Laravel's File::chmod method
                }

                $file = $image_data;
                $image_resize = Image::make($image_data);
                $fileName =  uniqid() . time() . $extension;
                $image_resize->save($destinationPath . $fileName);
                $filePath = $destinationPath . $fileName;
                if (file_exists($filePath)) {
                    chmod($filePath, 0777); // Change file permissions (consider using more restricted permissions)
                }
                $url = URL::to('/storage/') . '/app-download-preference/' . $fileName;
                $bannerImageAr = $url;
            } catch (Exception $e) {
                $return['message'] = 'Error during save image customer :' . $e->getMessage();
            }
        }else{
            if (isset($requestData['oldBannerImageAr'])) {
                $bannerImageAr = $requestData['oldBannerImageAr'];
            }
        }

        $insertDataArr = [
            'appDownloadPreference|titleEn' => $requestData['appDownloadPreference|titleEn'],
            'appDownloadPreference|titleAr' => $requestData['appDownloadPreference|titleAr'],
            'appDownloadPreference|qrCodeImage' => $qrCodeImage,
            'appDownloadPreference|bannerImageEn' => $bannerImageEn,
            'appDownloadPreference|bannerImageAr' => $bannerImageAr,
            'appDownloadPreference|googlePlaystoreURL' => $requestData['appDownloadPreference|googlePlaystoreURL'],
            'appDownloadPreference|appStoreURL' => $requestData['appDownloadPreference|appStoreURL'],
            'appDownloadPreference|HUAWEIStoreURL' => $requestData['appDownloadPreference|HUAWEIStoreURL']
        ];

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $insertDataArr;
        ActiveLog::createBackendActiveLog($activityLog);

        \DB::transaction(function () use ($insertDataArr) {
            foreach ($insertDataArr as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->route('create-app-download-preference')->with('success', 'App Download Preference Details Saved Successfully');
    }
}
