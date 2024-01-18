<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Models\HomeBannerI18ns;
use DateTime;
use Intervention\Image\ImageManagerStatic as Image;
use DB;
use App\Traits\Uuids;
use Illuminate\Support\Facades\Hash;

class HomeBanner extends Model
{
    use HasFactory, Uuids, SoftDeletes;
    protected $guarded = [];
    public function homeBannerCode()
    {
        return $this->hasMany('App\Models\HomeBannerI18ns', 'banner_id', 'id');
    }

    /**
     * get list or single or all records to display
     */
    public static function getBanners($option = array())
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $data = array(
            'id' => '',
            'order_by' => 'created_at',
            'sorting' => 'desc',
            'status' => '',
            'where' => array(),
            'whereHas' => array(),
            'orWhere' => array()
        );
        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = HomeBanner::query();
                $query->with(['homeBannerCode']);
                $query->select(
                    'home_banners.*',
                    DB::raw('(CASE WHEN home_banners.status = "0" THEN "In-Active" '
                        . 'WHEN home_banners.status = "1" THEN "Active" '
                        . 'END) AS banner_status_text')
                );
                $query->where('id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = HomeBanner::query();
                $query->with(['homeBannerCode']);
                $query->whereHas('homeBannerCode', function ($q) use ($config) {
                    if (!empty($config['whereHas'])) {
                        foreach ($config['whereHas'] as $where) {
                            $q->where($where[0], $where[1], $where[2]);
                        }
                    }
                });
                $query->select(
                    "home_banners.*"
                );

                if ($config['order_by'] == 'name') {
                    $query->join('home_banner_i18ns', 'home_banners.id', '=', 'home_banner_i18ns.banner_id')
                        ->where('home_banner_i18ns.language_code', 'en')
                        ->orderBy('home_banner_i18ns.banner_title', $config['sorting']);
                }
                if ($config['order_by'] == 'sort_order') {
                    $query->orderBy('sort_order', $config['sorting']);
                }



                if (!empty($config['where'])) {
                    foreach ($config['where'] as $where) {
                        $query->where($where[0], $where[1], $where[2]);
                    }
                }
                if (!empty($config['orWhere'])) {
                    foreach ($config['orWhere'] as $orWhere) {
                        $query->orWhere($orWhere[0], $orWhere[1], $orWhere[2]);
                    }
                }

                $result = $query->paginate($config['per_page']);
                $result->setPath('?per_page=' . $config['per_page']);
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        }

        if (!empty($result)) {
            $return['status'] = 1;
            $return['message'] = 'Home Banner list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * insert new record in database
     */
    public static function createBanner($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $bannerData = array(
            'category_id' => (isset($requestData['category_id'])) ? $requestData['category_id'] : NULL,
            'media_type' => "image",
            'panel' => $requestData['panel'],
            'sort_order' => $requestData['sort_order'],
            'from_date' => date("Y-m-d", strtotime($requestData['from_date'])),
            'to_date' => date("Y-m-d", strtotime($requestData['to_date'])),
            'banner_image' => NULL,
            'video_link' => (isset($requestData['video_link'])) ? $requestData['video_link'] : NULL,
            'status' => $requestData['status'],

        );
        if (isset($requestData['croppedImage']) && $requestData['croppedImage'] != "") {
            //upload image
            try {
                $base64_image_path = $requestData['croppedImage'];
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
                $destinationPath = storage_path() . '/app/public/home_banner/';
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777);
                }
                $file = $image_data;
                $image_resize = Image::make($image_data);
                // $image_resize->resize(300, 300);
                $fileName =  uniqid() . time() .  $extension;
                $image_resize->save($destinationPath . $fileName);
                $url = URL::to('/storage/') . '/home_banner/' . $fileName;
                $bannerData['banner_image'] = $url;
            } catch (Exception $e) {
                $return['message'] = 'Error during save image suppliers :' . $e->getMessage();
            }
        } else if (isset($requestData['upload_banner'])) {

            //upload profile image
            try {
                $destinationPath = storage_path() . '/app/public/home_banner/';
                if (!is_dir($destinationPath)) {
                    /* Directory does not exist, so lets create it. */
                    mkdir($destinationPath, 0777);
                }
                $file = $requestData['upload_banner'];
                $fileName =  uniqid() . '_' .  time() . '.' . $requestData['upload_banner']->extension();
                $file->move($destinationPath, $fileName);
                $url = URL::to('/storage/') . '/home_banner/' . $fileName;

                $bannerData['banner_image'] = $url;
            } catch (\Exception $e) {
                $return['message'] = 'Error during save home banner image : ' . $e->getMessage();
            }
        }
        try {
            DB::beginTransaction();
            $homeBannerData = HomeBanner::create($bannerData);

            DB::commit();
            if ($homeBannerData) {
                $homeBannerTitles = $requestData['banner_names'];
                foreach ($homeBannerTitles as $key => $name) {
                    $nameData = array(
                        'banner_id' => $homeBannerData->id,
                        'banner_title' => $name['banner_name'],
                        'language_code' => $name['language_code']
                    );
                    HomeBannerI18ns::create($nameData);
                    $bannermsg[] = $name['banner_name'];
                }

                $return['status'] = 1;
                $return['message'] = 'Home Banner [' . implode(', ', $bannermsg) . '] saved successfully';
                $return['data'] = $homeBannerData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save home banner record : ' . $e->getMessage();
        }


        return $return;
    }

    /**
     * update record in database
     */
    public static function updateBanner($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {
            @$bannerData = array(
                'media_type' => "image",
                'panel' => $requestData['panel'],
                'video_link' => $requestData['video_link'] ?? NULL,
                'sort_order' => $requestData['sort_order'],
                'from_date' => date("Y-m-d", strtotime($requestData['from_date'])),
                'to_date' => date("Y-m-d", strtotime($requestData['to_date'])),
                'status' => $requestData['status'],
            );
            if (isset($requestData['croppedImage']) && $requestData['croppedImage'] != "") {
                //upload image
                try {
                    $base64_image_path = $requestData['croppedImage'];
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
                    $destinationPath = storage_path() . '/app/public/home_banner/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }

                    $file = $image_data;
                    $image_resize = Image::make($image_data);
                    // $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() . $extension;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/home_banner/' . $fileName;
                    $bannerData['banner_image'] = $url;

                    $p = parse_url($requestData['old_photo']);


                    if ($p['path'] != "") {

                        $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                        $image_path = storage_path($image_path);

                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image Home Banner :' . $e->getMessage();
                }
            } else if (isset($requestData['upload_banner'])) {

                //upload profile image
                try {
                    $destinationPath = storage_path() . '/app/public/home_banner/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['upload_banner'];
                    $fileName =  uniqid() . '_' .  time() . '.' . $requestData['upload_banner']->extension();
                    $file->move($destinationPath, $fileName);
                    $url = URL::to('/storage/') . '/home_banner/' . $fileName;

                    $bannerData['banner_image'] = $url;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save Home Banner : ' . $e->getMessage();
                }
            }

            try {
                DB::beginTransaction();

                $matchBanner = ['id' => $requestData['banner_id']];
                $homeBannerData = HomeBanner::updateOrCreate($matchBanner, $bannerData);

                DB::commit();
                if ($homeBannerData) {
                    $bannerNames = $requestData['banner_names'];
                    foreach ($bannerNames as $key => $name) {
                        $nameData = array(
                            'banner_id' => $homeBannerData->id,
                            'banner_title' => $name['banner_name'],
                            'language_code' => $name['language_code']
                        );
                        $matchBannerI18nsData = ['id' => $name['home_banner_i18ns_id']];
                        HomeBannerI18ns::updateOrCreate($matchBannerI18nsData, $nameData);
                        $bannermsg[] = $name['banner_name'];
                    }
                    $return['status'] = 1;
                    $return['message'] = 'Home Banner [' . implode(', ', $bannermsg) . '] Saved Successfully';
                    $return['data'] = $homeBannerData;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during save user record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    /**
     * delete record from database
     */
    public static function deleteBanner($banner_id)
    {

        $is_dependent = HomeBanner::checkDependancy($banner_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $bannerData = HomeBanner::where('id', $banner_id)->first()->toArray();
        $homeBannerData = HomeBanner::where('id', $banner_id)->with('homeBannerCode')->withTrashed()->first()->toArray();
        foreach ($homeBannerData['home_banner_code'] as $key => $name) {
            $nameData = array(
                'banner_title' => $name['banner_title'],
                'language_code' => $name['language_code']
            );
            $bannertmsg[] = $name['banner_title'];
        }
        if ($is_dependent) {
            //update status to deleted
            HomeBanner::where('id', $banner_id)->update(['status' => 2]);
            $return['status'] = 1;
            $return['message'] = 'Home Banner [' . implode(', ', $bannertmsg) . '] soft deleted successfully';
        } else {
            //delete record from table
            $p = parse_url($bannerData['banner_image']);
            if ($p['path'] != "") {
                $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                $image_path = storage_path($image_path);

                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            HomeBanner::where('id', $banner_id)->forceDelete();
            $return['status'] = 1;
            $return['message'] = 'Home Banner [' . implode(', ', $bannertmsg) . '] deleted successfully';
        }

        return $return;
    }

    public static function checkDependancy($banner_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        return $dep_modules;
    }
}
