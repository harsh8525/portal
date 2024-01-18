<?php

/**
 * @package     Offers
 * @subpackage  Coupon
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Geography.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use DateTime;
use App\Models\AirlineI18ns;
use App\Traits\Uuids;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class Coupon extends Model
{
    use HasFactory, LogsActivity, Uuids, SoftDeletes;
    protected $guarded = [];
    public function couponCodeName()
    {
        return $this->hasMany('App\Models\CouponI18ns', 'coupon_id', 'id');
    }
    public function applicableCustomer()
    {
        return $this->hasMany('App\Models\CouponApplicableCustomer', 'coupon_id', 'id');
    }
    public function serviceType()
    {
        return $this->hasMany('App\Models\ServiceType', 'id', 'service_type_id');
    }

    /**
     * get list or single or all record to display
     */
    public static function getCoupons($option = array())
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
            'where' => array(),
            'whereHas' => array(),
            'orWhere' => array()
        );
        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = Coupon::query();
                $query->withTrashed();
                $query->with(['couponCodeName', 'applicableCustomer', 'serviceType']);
                $query->select(
                    "coupons.*",
                );
                $query->where('id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = Coupon::query();
                $query->withTrashed();
                $query->with(['couponCodeName', 'applicableCustomer', 'serviceType']);
                $query->whereHas('couponCodeName', function ($q) use ($config) {
                    if (!empty($config['whereHas'])) {
                        foreach ($config['whereHas'] as $where) {
                            $q->where($where[0], $where[1], $where[2]);
                        }
                    }
                });
                $query->select(
                    "coupons.*",
                );
                if ($config['order_by'] == 'coupon_name') {
                    $query->join('coupon_i18ns', 'coupons.id', '=', 'coupon_i18ns.coupon_id')
                        ->where('coupon_i18ns.language_code', 'en')
                        ->orderBy('coupon_i18ns.coupon_name', $config['sorting']);
                }
                if ($config['order_by'] == 'coupon_code') {
                    $query->join('coupons as c', 'coupons.coupon_code', '=', 'c.coupon_code')
                        ->join('coupon_i18ns as ci', 'c.id', '=', 'ci.coupon_id')
                        ->where('ci.language_code', 'en')
                        ->orderBy('c.coupon_code', $config['sorting']);
                }
                if ($config['order_by'] == 'created_at' || $config['order_by'] == 'coupon_amount' || $config['order_by'] == 'from_date' || $config['order_by'] == 'to_date' || $config['order_by'] == 'minimum_spend' || $config['order_by'] == 'maximum_spend' || $config['order_by'] == 'limit_per_coupon' || $config['order_by'] == 'limit_per_customer' || $config['order_by'] == 'coupon_name') {

                    $query->orderBy($config['order_by'], $config['sorting']);
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
            $return['message'] = 'Coupon list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }
    /**
     * insert new record in database
     */
    public static function createCoupon($requestData)
    {
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        if ($requestData['customer_type'] == 'B2C') {
            $selectedCustomer = 'Customer';
            if (isset($requestData['customer']) && $requestData['customer'] == '') {
                $selectedCustomer = 'Customer';
            }
            if (isset($requestData['customerAll']) && $requestData['customerAll'] == 'on') {
                $selectedCustomer = 'All Customer';
            }
        } else if ($requestData['customer_type'] == 'B2B') {
            $selectedCustomer = 'Agency';
            if (isset($requestData['agency']) && $requestData['agency'] == '') {
                $selectedCustomer = 'Agency';
            }
            if (isset($requestData['agencyAll']) && $requestData['agencyAll'] == 'on') {
                $selectedCustomer = 'All Agency';
            }
        }
        try {
            $couponData = array(
                'customer_type'  => $requestData['customer_type'],
                'coupon_code'  => $requestData['coupon_code'],
                'coupon_amount'  => $requestData['coupon_amount'],
                'discount_type'  => $requestData['discount_type'],
                'from_date'  => $requestData['from_date'],
                'to_date'  => $requestData['to_date'],
                'minimum_spend'  => $requestData['minimum_spend'],
                'maximum_spend'  => $requestData['maximum_spend'],
                'service_type_id'  => $requestData['service_type'],
                'customer'  => $selectedCustomer,
                'limit_per_coupon'  => $requestData['limit_per_coupon'],
                'limit_per_customer'  => $requestData['limit_per_customer'],
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
                    $destinationPath = storage_path() . '/app/public/coupon/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $image_data;
                    $image_resize = Image::make($image_data);
                    $fileName =  uniqid() . time() .  $extension;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/coupon/' . $fileName;
                    $couponData['upload_image'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image suppliers :' . $e->getMessage();
                }
            } else if (isset($requestData['upload_image'])) {
                //upload image
                try {
                    $destinationPath = storage_path() . '/app/public/coupon/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['upload_image'];
                    $image_resize = Image::make($requestData['upload_image']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() .  '.' . $requestData['upload_image']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/coupon/' . $fileName;
                    $couponData['upload_image'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image suppliers :' . $e->getMessage();
                }
            }
            // save to table
            try {
                DB::beginTransaction();

                $CouponDetails = Coupon::create($couponData);


                if ($CouponDetails) {
                    if ($requestData['customer_type'] == 'B2B') {
                        if (isset($CouponDetails['customer']) && $CouponDetails['customer'] != 'All Agency') {
                            foreach ($requestData['agency'] as $agency_id) {
                                $applicableCouponData = array(
                                    'coupon_id' => $CouponDetails->id,
                                    'agency_id' => $agency_id,
                                );
                                CouponApplicableCustomer::create($applicableCouponData);
                            }
                        }
                    }
                    if ($requestData['customer_type'] == 'B2C') {

                        if (isset($requestData['customer']) && $requestData['customer'] != 'All Customer') {
                            foreach ($requestData['customer'] as $customer_id) {
                                $applicableCouponData = array(
                                    'coupon_id' => $CouponDetails->id,
                                    'customer_id' => $customer_id,
                                );
                                CouponApplicableCustomer::create($applicableCouponData);
                            }
                        }
                    }
                    $couponNames = $requestData['coupon_names'];
                    foreach ($couponNames as $key => $name) {
                        $nameData = array(
                            'coupon_id' => $CouponDetails->id,
                            'coupon_name' => $name['coupon_name'],
                            'language_code' => $name['language_code']
                        );
                        CouponI18ns::create($nameData);

                        $couponmsg[] = $name['coupon_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Coupon [' . implode(', ', $couponmsg) . '] saved successfully';

                    $return['data'] = $CouponDetails;
                }
                DB::commit();
            } catch (\Exception $e) {
                $return['message'] = 'Error during save record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    /**
     * update record into database
     */
    public static function updateCoupon($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        if ($requestData['customer_type'] == 'B2C') {
            $requestData['selectedCustomer'] = 'Customer';
            if (isset($requestData['customer']) && $requestData['customer'] == '') {
                $requestData['selectedCustomer'] = 'Customer';
            }
            if (isset($requestData['customerAll']) && $requestData['customerAll'] == 'on') {
                $requestData['selectedCustomer'] = 'All Customer';
            }
        } else if ($requestData['customer_type'] == 'B2B') {
            $requestData['selectedCustomer'] = 'Agency';
            if (isset($requestData['agency']) && $requestData['agency'] == '') {
                $requestData['selectedCustomer'] = 'Agency';
            }
            if (isset($requestData['agencyAll']) && $requestData['agencyAll'] == 'on') {
                $requestData['selectedCustomer'] = 'All Agency';
            }
        }
        try {
            $couponData = array(
                'customer_type'  => $requestData['customer_type'],
                'coupon_code'  => $requestData['coupon_code'],
                'coupon_amount'  => $requestData['coupon_amount'],
                'discount_type'  => $requestData['discount_type'],
                'from_date'  => $requestData['from_date'],
                'to_date'  => $requestData['to_date'],
                'minimum_spend'  => $requestData['minimum_spend'],
                'maximum_spend'  => $requestData['maximum_spend'],
                'service_type_id'  => $requestData['service_type'],
                'customer'  => $requestData['selectedCustomer'],
                'limit_per_coupon'  => $requestData['limit_per_coupon'],
                'limit_per_customer'  => $requestData['limit_per_customer'],
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
                    $destinationPath = storage_path() . '/app/public/coupon/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $image_data;
                    $image_resize = Image::make($image_data);
                    $fileName =  uniqid() . time() .  $extension;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/coupon/' . $fileName;
                    $couponData['upload_image'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image coupon :' . $e->getMessage();
                }
            } else if (isset($requestData['upload_image'])) {
                //upload image
                try {
                    $destinationPath = storage_path() . '/app/public/coupon/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['upload_image'];
                    $image_resize = Image::make($requestData['upload_image']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() .  '.' . $requestData['upload_image']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/coupon/' . $fileName;
                    $couponData['upload_image'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image coupon :' . $e->getMessage();
                }
            }
            // save to table
            try {
                DB::beginTransaction();

                $matchcouponData = ['id' => $requestData['coupon_id']];
                $updateCouponDetails = Coupon::updateOrCreate($matchcouponData, $couponData);


                if ($updateCouponDetails) {
                    if (isset($requestData['applicable_customer_id'])) {
                        CouponApplicableCustomer::whereIn('id', $requestData['applicable_customer_id'])->delete();
                    }
                    if ($requestData['customer_type'] == 'B2C') {

                        if ($updateCouponDetails['customer'] != 'All Customer') {

                            foreach ($requestData['customer'] as $customer_id) {


                                $applicableCouponData = array(
                                    'coupon_id' => $updateCouponDetails->id,
                                    'customer_id' => $customer_id,
                                );
                                CouponApplicableCustomer::create($applicableCouponData);
                            }
                        }
                    }
                    if ($requestData['customer_type'] == 'B2B') {

                        if ($updateCouponDetails['customer'] != 'All Agency') {

                            foreach ($requestData['agency'] as $agency_id) {


                                $applicableCouponData = array(
                                    'coupon_id' => $updateCouponDetails->id,
                                    'agency_id' => $agency_id,
                                );
                                CouponApplicableCustomer::create($applicableCouponData);
                            }
                        }
                    }
                    $couponNames = $requestData['coupon_names'];
                    foreach ($couponNames as $key => $name) {
                        $nameData = array(
                            'coupon_id' => $updateCouponDetails->id,
                            'coupon_name' => $name['coupon_name'],
                            'language_code' => $name['language_code']
                        );
                        $matchcouponDataapp = ['id' => $name['coupon_i18ns_id']];
                        CouponI18ns::updateOrCreate($matchcouponDataapp,$nameData);

                        $couponmsg[] = $name['coupon_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Coupon [' . implode(', ', $couponmsg) . '] updated successfully';

                    $return['data'] = $updateCouponDetails;
                }
                DB::commit();
            } catch (\Exception $e) {
                $return['message'] = 'Error during save record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    /**
     * remove record from database
     */
    public static function deleteCustomers($delete_coupon_id)
    {
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $couponData = Coupon::where('id', $delete_coupon_id)->with(['couponCodeName', 'applicableCustomer'])->withTrashed()->first()->toArray();
        $is_dependent = Coupon::checkDependancy($couponData['coupon_code'], $delete_coupon_id);
        foreach ($couponData['coupon_code_name'] as $key => $name) {
            $nameData = array(
                'coupon_name' => $name['coupon_name'],
                'language_code' => $name['language_code']
            );
            $couponmsg[] = $name['coupon_name'];
        }
        if ($is_dependent) {
            Coupon::where('id', $delete_coupon_id)->delete();
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Coupon Name [' . implode(', ', $couponmsg) . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {
            Coupon::where('id', $delete_coupon_id)->forceDelete();

            $return['status'] = 1;
            $return['message'] = 'Coupon [' . implode(', ', $couponmsg) . '] deleted successfully';
        }
        return $return;
    }

    public static function checkDependancy($code, $delete_coupon_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        return $dep_modules;
    }

    /**
     * restore deleted record
     **/
    public static function restoreCoupons($restore_coupon_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $couponData = Coupon::withTrashed()->find($restore_coupon_id);
        if ($couponData) {
            $couponData->restore();
            $return['status'] = 1;
            $return['message'] = 'Coupon [' . $couponData['coupon_code'] . '] restored successfully';
        }
        return $return;
    }
}
