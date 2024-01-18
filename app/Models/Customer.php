<?php

/**
 * @package     Settings
 * @subpackage  Customer 
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Settings.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerAddresses;
use App\Models\GeoRegionLists;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;
use App\Traits\Uuids;
use DateTime;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Authenticatable
{
    protected $guarded = [];
    use HasFactory, Uuids, HasApiTokens, LogsActivity;
    protected static $logName = 'customers';



    public function getCustomerAddress()
    {
        return $this->hasOne(CustomerAddresses::class, 'customer_id', 'id')->with('getCountry', 'getState', 'getCity');
    }


    /**
     * get list or single or all record to display
     */
    public static function getCustomers($option = array())
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
            'mobile_verified' => '',
            'where' => array(),
            'orWhere' => array()
        );

        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = Customer::query();
                $query->with('getCustomerAddress');
                $query->select(
                    "customers.*",
                    DB::raw('(CASE WHEN customers.title = "mr" THEN "Mr" '
                        . 'WHEN customers.title = "mrs" THEN "Mrs" '
                        . 'WHEN customers.title = "miss" THEN "Miss" '
                        . 'END) AS display_title'),
                    DB::raw('(CASE WHEN customers.status = "inactive" THEN "In-Active" '
                        . 'WHEN customers.status = "active" THEN "Active" '
                        . 'WHEN customers.status = "deleted" THEN "Deleted" '
                        . 'WHEN customers.status = "terminated" THEN "Terminated" '
                        . 'END) AS customer_status_text'),
                );
                $query->where('customers.id', $config['id']);
                $result = $query->first();
                // echo "<pre>";print_r($result);die;
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = Customer::query();
                $query->with('getCustomerAddress');
                $query->select(
                    "customers.*",
                    DB::raw('(CASE WHEN customers.title = "mr" THEN "Mr" '
                        . 'WHEN customers.title = "mrs" THEN "Mrs" '
                        . 'WHEN customers.title = "miss" THEN "Miss" '
                        . 'END) AS display_title'),
                    DB::raw('(CASE WHEN customers.status = "inactive" THEN "In-Active" '
                        . 'WHEN customers.status = "active" THEN "Active" '
                        . 'WHEN customers.status = "deleted" THEN "Deleted" '
                        . 'WHEN customers.status = "terminated" THEN "Terminated" '
                        . 'END) AS customer_status_text'),
                );
                $query->orderBy($config['order_by'], $config['sorting']);

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
            $return['message'] = 'Customer list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * insert new record in database
     */
    public static function createCustomers($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        if (isset($requestData['marriage_aniversary_date'])) {
            if ($requestData['marriage_aniversary_date'] == null) {
                $marriage_aniversary_date = null;
            } else {
                $marriage_aniversary_date = Carbon::parse($requestData['marriage_aniversary_date'])->format('Y-m-d');
            }
        } else {
            $marriage_aniversary_date = null;
        }
        try {
            $customerData = array(
                'first_name' => ucwords($requestData['first_name']),
                'title' => ucwords($requestData['title']),
                'last_name' => ucwords($requestData['last_name']),
                'mobile' => $requestData['isd_code'] . ' ' . $requestData['mobile'],
                'email' => $requestData['email'],
                'status' => $requestData['status'],
                'date_of_birth' => date('Y-m-d', strtotime($requestData['date_of_birth'])),
                'gender' => $requestData['gender'],
                'marital_status' => $requestData['marital_status'],
                'marriage_aniversary_date' => $marriage_aniversary_date,
                'profile_photo' => (@$requestData['profile_photo'] != "") ? $requestData['profile_photo'] : '',
            );

            $customerAddressData = array(
                'customer_id' => 0,
                'address1' => $requestData['address1'],
                'address2' => $requestData['address2'],
                'country' => $requestData['country'],
                'state' => $requestData['state_code'],
                'city' => $requestData['city_code'],
                'pincode' => $requestData['pincode'],
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
                    $destinationPath = storage_path() . '/app/public/customer/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $image_data;
                    $image_resize = Image::make($image_data);
                    $fileName =  uniqid() . time() .  $extension;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/customer/' . $fileName;
                    $customerData['profile_photo'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image suppliers :' . $e->getMessage();
                }
            } else if (isset($requestData['profile_photo'])) {
                //upload image
                try {
                    $destinationPath = storage_path() . '/app/public/customer/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['profile_photo'];
                    $image_resize = Image::make($requestData['profile_photo']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() .  '.' . $requestData['profile_photo']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/customer/' . $fileName;
                    $customerData['profile_photo'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image suppliers :' . $e->getMessage();
                }
            }
            try {
                DB::beginTransaction();
                $customer = Customer::create($customerData);
                $customerAddressData['customer_id'] = $customer->id;
                $customerAddress = CustomerAddresses::create($customerAddressData);
                DB::commit();
                if ($customer) {
                    $return['status'] = 1;
                    $return['message'] = 'Customer [' . ucwords($customer->first_name) . ' ' . $customer->last_name . '] saved successfully';
                    $return['data'] = $customer;
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
     * update record in database
     */
    public static function updateCustomer($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        if (isset($requestData['marriage_aniversary_date'])) {
            if ($requestData['marriage_aniversary_date'] == null) {
                $marriage_aniversary_date = null;
            } else {
                $marriage_aniversary_date = Carbon::parse($requestData['marriage_aniversary_date'])->format('Y-m-d');
            }
        } else {
            $marriage_aniversary_date = null;
        }

        try {
            $customerData = array(
                // 'id' => $requestData['customer_id'],
                'first_name' => ucwords($requestData['first_name']),
                'title' => ucwords($requestData['title']),
                'last_name' => ucwords($requestData['last_name']),
                'mobile' => $requestData['isd_code'] . ' ' . $requestData['mobile'],
                'email' => $requestData['email'],
                'status' => $requestData['status'],
                'date_of_birth' => date('Y-m-d', strtotime($requestData['date_of_birth'])),
                'gender' => $requestData['gender'],
                'marital_status' => $requestData['marital_status'],
                'marriage_aniversary_date' => $marriage_aniversary_date,
            );

            $customerAddressData1 = array(
                'address1' => $requestData['address1'],
                'address2' => $requestData['address2'],
                'country' => $requestData['country'],
                'state' => $requestData['state_code'],
                'city' => $requestData['city_code'],
                'pincode' => $requestData['pincode'],

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
                    $destinationPath = storage_path() . '/app/public/customer/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }

                    $file = $image_data;
                    $image_resize = Image::make($image_data);
                    $fileName =  uniqid() . time() . $extension;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/customer/' . $fileName;
                    $customerData['profile_photo'] = $url;
                    if (isset($requestData['old_photo'])) {
                        $p = parse_url($requestData['old_photo']);


                        if ($p['path'] != "") {

                            $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                            $image_path = storage_path($image_path);

                            if (file_exists($image_path)) {
                                unlink($image_path);
                            }
                        }
                    }
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image customer :' . $e->getMessage();
                }
            } else if (isset($requestData['profile_photo'])) {

                //upload image
                try {

                    $destinationPath = storage_path() . '/app/public/customer/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }

                    $file = $requestData['profile_photo'];
                    $image_resize = Image::make($requestData['profile_photo']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() . '.' . $requestData['profile_photo']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/customer/' . $fileName;
                    $customerData['profile_photo'] = $url;
                    if (isset($requestData['old_photo'])) {
                        $p = parse_url($requestData['old_photo']);


                        if ($p['path'] != "") {

                            $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                            $image_path = storage_path($image_path);

                            if (file_exists($image_path)) {
                                unlink($image_path);
                            }
                        }
                    }
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image customer :' . $e->getMessage();
                }
            }
            //upload with no-image
            try {

                // echo
                // "<pre>";
                // print_r($requestData);
                // die;
                DB::beginTransaction();
                $matchCustomer = ['id' => $requestData['customer_id']];
                $customer = Customer::updateOrCreate($matchCustomer, $customerData);
                $matchRecordSecond1 = ['customer_id' => $requestData['customer_id']];
                $customer['address'] = CustomerAddresses::updateOrCreate($matchRecordSecond1, $customerAddressData1);
                DB::commit();
                if ($customer) {
                    $return['status'] = true;
                    $return['message'] = 'Customer [' . $customer->first_name . ' ' . $customer->last_name . '] update successfully';
                    $return['data'] = $customer;
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
    public static function deleteCustomers($customer_id)
    {
        $is_dependent = Customer::checkDependancy($customer_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $customerData = Customer::where('id', $customer_id)->first()->toArray();
        if ($is_dependent) {
            //update status to deleted
            Customer::where('id', $customer_id)->update(['status' => 2]);
            $return['status'] = 1;
            $return['message'] = 'Customer [' . $customerData['profile_photo'] . '] soft deleted successfully';
        } else {
            //delete record from table
            $p = parse_url($customerData['profile_photo']);
            if ($p['path'] != "") {
                $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                $image_path = storage_path($image_path);

                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            Customer::where('id', $customer_id)->delete();
            $customerAddress = CustomerAddresses::where('customer_id', $customer_id)->get();
            if ($customerAddress) {
                CustomerAddresses::where('customer_id', $customer_id)->delete();
            }
            $return['status'] = 1;
            $return['message'] = 'Customer [' . $customerData['first_name'] . '] deleted successfully';
        }

        return $return;
    }
    public static function checkDependancy($customer_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        return $dep_modules;
    }

    /**
     * get record for customer report
     */
    static public function getMasterCustomerReport($option = array(), $pagination = true, $getTotalPrice = false)
    {
        $data = array(
            'id' => '',
            'order_by' => 'created_at',
            'sorting' => 'desc',
            'status' => '',
            'where' => array(),
            'orWhere' => array(),
            'per_page' => 10, // Set a default value for per_page, adjust as needed
        );

        $config = array_merge($data, $option);
        $query = Customer::query();

        if (!empty($config['dates'])) {
            foreach ($config['dates'] as $dates) {
                $query->whereDate('created_at', '>=', $dates[0])->whereDate('created_at', '<=', $dates[1]);
            }
        }

        $query->orderBy($config['order_by'], $config['sorting']);
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

        if ($pagination) {
            $result = $query->paginate($config['per_page']);
            $result->setPath('?per_page=' . $config['per_page']);
        } else {
            $result = $query->latest()->get();
        }


        return $result;
    }

    /**
     * get record for monthly customer report
     */
    static public function getMasterMonthlyCustomerReport($fromDate, $toDate)
    {

        // Get the data from the database
        $data = Customer::whereBetween(DB::raw('DATE(created_at)'), [$fromDate, $toDate])
            ->selectRaw('DATE_FORMAT(created_at, "%M, %Y") as month, COUNT(id)

                        as count')
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%M, %Y")'))
            ->get();

        // Create an associative array with month names as keys and counts as values
        $monthData = [];
        foreach ($data as $row) {
            $monthData[$row->month] = $row->count;
        }

        // Get all months between $fromDate and $toDate
        $start = Carbon::parse($fromDate);
        $end = Carbon::parse($toDate);
        $result = [];

        while ($start->lte($end)) {
            $formattedMonth = $start->format('F, Y');
            $count = isset($monthData[$formattedMonth]) ? $monthData[$formattedMonth] : 0;
            $result[] = ['month' => $formattedMonth, 'count' => $count];

            // Increment to the next month
            $start->addMonth();
        }
        return $result;
    }
}
