<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use App\Models\Country;
use App\Models\Language;
use App\Traits\ActiveLog;
use Intervention\Image\ImageManagerStatic as Image;
use File;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoice.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission('INVOICE', 'read')) {
            return view('admin/401');
        }

        $header['title'] = 'Invoice Settings';
        $header['heading'] = 'Invoice Settings';

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

        $getCountry = Country::get();
        $getLanguages = Language::get();

        return view('admin/setting/invoice')->with(['header' => $header, 'items' => $items, 'getCountry' => $getCountry, 'getLanguages' => $getLanguages]);
    }

    /**
     * Update or create the specified general info in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function general(Request $request)
    {
        if (!hasPermission('INVOICE', 'create') && !hasPermission('INVOICE', 'update')) {
            return view('admin/401');
        }
        $requestData = $request->all();
        unset($requestData['_token']);
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
                $destinationPath = storage_path() . '/app/public/images/general/';
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
                $url = URL::to('/storage/') . '/images/general/' . $fileName;
                $qrCodeImage = $url;
            } catch (Exception $e) {
                $return['message'] = 'Error during save image customer :' . $e->getMessage();
            }
        }else{
            if (isset($requestData['oldQrCodeImage'])) {
                $qrCodeImage = $requestData['oldQrCodeImage'];
            }
        }
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
                $destinationPath = storage_path() . '/app/public/images/general/';
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
                $url = URL::to('/storage/') . '/images/general/' . $fileName;
                $bannerImageEn = $url;
            } catch (Exception $e) {
                $return['message'] = 'Error during save image customer :' . $e->getMessage();
            }
        }else{
            if (isset($requestData['oldBannerImageEn'])) {
                $bannerImageEn = $requestData['oldBannerImageEn'];
            }
        }
       ;
        $insertDataArr = [
            'invoice|general|billingCompanyNameEn' => $requestData['invoice|general|billingCompanyNameEn'],
            'invoice|general|billingCompanyNameAr' => $requestData['invoice|general|billingCompanyNameAr'],
            'invoice|general|agencyIATANumber' => $requestData['invoice|general|agencyIATANumber'],
            'invoice|general|cityName' => $requestData['invoice|general|cityName'],
            'invoice|general|countryName' => $requestData['invoice|general|countryName'],
            'invoice|general|logoEnglish' => $qrCodeImage,
            'invoice|general|logoArabic' => $bannerImageEn,
            'invoice|general|addressEn' => $requestData['invoice|general|addressEn'],
            'invoice|general|addressAr' => $requestData['invoice|general|addressAr']
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

        return redirect()->back()->with('success', 'Setting - Invoice [Genaral] details Saved Successfully');
    }
    /**
     * Update or create the specified sales info in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales(Request $request)
    {
        if (!hasPermission('INVOICE', 'create') && !hasPermission('INVOICE', 'update')) {
            return view('admin/401');
        }
        $data = $request->all();
        unset($data['_token']);

        $activityLog['request'] = $data;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);
        
        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->back()->with('success', 'Setting - Invoice [Sales] details Saved Successfully');
    }
   /**
     * Update or create the specified purchase info in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchase(Request $request)
    {
        if (!hasPermission('INVOICE', 'create') && !hasPermission('INVOICE', 'update')) {
            return view('admin/401');
        }
        $data = $request->all();
        unset($data['_token']);

        $activityLog['request'] = $data;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);

        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->back()->with('success', 'Setting - Invoice [Purchase] details Saved Successfully');
    }

   
}
