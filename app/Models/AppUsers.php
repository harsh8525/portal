<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AppUserAddresses;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Traits\EmailService;
use App\Models\Redeem;
use App\Models\CouponCode;
use App\Models\Order;

class AppUsers extends Authenticatable
{
    use HasFactory, HasApiTokens, EmailService;

    protected $guarded = [];

    public function AauthAcessToken()
    {
        return $this->hasMany('\App\OauthAccessToken');
    }

    public function GetAppUserAddress()
    {
        return $this->hasOne('App\Models\AppUserAddresses', 'user_id');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }


    /**
     * get list or single or all record to display
     */
    public static function getUsers($option = array())
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        $data = array(
            'id' => '',
            'order_by' => 'id',
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
                $query = AppUsers::query();
                $query->select(
                    "app_users.*",
                    "app_user_addresses.id AS app_user_address_id",
                    "app_user_addresses.address",
                    "app_user_addresses.country",
                    "app_user_addresses.city",
                    "app_user_addresses.pincode",
                    "app_user_addresses.state",
                    DB::raw('(CASE WHEN app_users.user_type = "dealer" THEN "Dealer" '
                        . 'WHEN app_users.user_type = "exclusive_dealer" THEN "Exclusive Dealer" '
                        . 'WHEN app_users.user_type = "channel_partner" THEN "Channel Partner" '
                        . 'WHEN app_users.user_type = "distributor" THEN "Distributor" '
                        . 'WHEN app_users.user_type = "contractor" THEN "Contractor" '
                        . 'ELSE "Cancelled" END) AS user_type_text'),
                    DB::raw('(CASE WHEN app_users.status = "0" THEN "In-Active" '
                        . 'WHEN app_users.status = "1" THEN "Active" '
                        . 'WHEN app_users.status = "2" THEN "Deleted" '
                        . 'END) AS user_status_text'),
                    DB::raw("(SELECT owner_name FROM app_users AS distributor WHERE distributor.id=app_users.distributor) as distributor_name"),
                    DB::raw("(SELECT owner_name FROM app_users AS ref_distributor WHERE ref_distributor.id=app_users.ref_distributor) as ref_distributor_name"),
                    DB::raw("(SELECT owner_name FROM app_users AS ref_dealer WHERE ref_dealer.id=app_users.ref_dealer) as ref_dealer_name"),
                    DB::raw("(SELECT COUNT(id) AS address_count FROM app_user_addresses WHERE app_user_addresses.user_id=app_users.id) as address_count")
                );
                $query->join('app_user_addresses', 'app_users.id', 'app_user_addresses.user_id');
                $query->where('app_user_addresses.address_type', 'billing');
                $query->where('app_users.id', $config['id']);
                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = AppUsers::query();

                $query->select(
                    "app_users.*",
                    "app_user_addresses.id AS app_user_address_id",
                    "app_user_addresses.address",
                    "app_user_addresses.country",
                    "app_user_addresses.city",
                    "app_user_addresses.pincode",
                    "app_user_addresses.state",
                    DB::raw('(CASE WHEN app_users.user_type = "dealer" THEN "Dealer" '
                        . 'WHEN app_users.user_type = "exclusive_dealer" THEN "Exclusive Dealer" '
                        . 'WHEN app_users.user_type = "channel_partner" THEN "Channel Partner" '
                        . 'WHEN app_users.user_type = "distributor" THEN "Distributor" '
                        . 'WHEN app_users.user_type = "contractor" THEN "Contractor" '
                        . 'ELSE "Cancelled" END) AS user_type_text'),
                    DB::raw('(CASE WHEN app_users.status = "0" THEN "In-Active" '
                        . 'WHEN app_users.status = "1" THEN "Active" '
                        . 'WHEN app_users.status = "2" THEN "Deleted" '
                        . 'END) AS user_status_text'),
                    DB::raw("(SELECT owner_name FROM app_users AS distributor WHERE distributor.id=app_users.distributor) as distributor_name"),
                    DB::raw("(SELECT owner_name FROM app_users AS ref_distributor WHERE ref_distributor.id=app_users.ref_distributor) as ref_distributor_name"),
                    DB::raw("(SELECT owner_name FROM app_users AS ref_dealer WHERE ref_dealer.id=app_users.ref_dealer) as ref_dealer_name"),
                    DB::raw("(SELECT COUNT(id) AS address_count FROM app_user_addresses WHERE app_user_addresses.user_id=app_users.id) as address_count")
                );
                $query->join('app_user_addresses', 'app_users.id', 'app_user_addresses.user_id');
                $query->where('app_user_addresses.address_type', 'billing');

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
            $return['message'] = 'User list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * insert new record in database
     */
    public static function createUser($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $userData = array(
                'id' => 0,
                'firm' => ucwords($requestData['firm']),
                'owner_name' => ucwords($requestData['owner_name']),
                'mobile' => $requestData['mobile'],
                'password' => ($requestData['password'] != null) ? Hash::make($requestData['password']) : '',
                'email' => $requestData['email'],
                'profile_image' => '',
                'gst_certificate' => '',
                'user_type' => $requestData['user_type'],
                'status' => $requestData['status'],
                'distributor' => $requestData['distributor'],
                'ref_distributor' => $requestData['ref_distributor'],
                'ref_dealer' => $requestData['ref_dealer'],
                'website' => $requestData['website'],
                'company_gst_no' => strtoupper($requestData['company_gst_no']),
                'company_certificate' => '',
                'shop_name' => ucwords($requestData['shop_name']),
                'shop_gst_no' => strtoupper($requestData['shop_gst_no']),
                'working_city' => ucwords($requestData['working_city']),
                'working_state' => ucwords($requestData['working_state']),
            );

            $userAddressData = array(
                'id' => 0,
                'user_id' => 0,
                'name' => ucwords($requestData['owner_name']),
                'mobile' => $requestData['mobile'],
                'address' => $requestData['address'],
                'country' => ucwords($requestData['country']),
                'state' => ucwords($requestData['state']),
                'city' => ucwords($requestData['city']),
                'pincode' => $requestData['pincode'],
                'address_type' => 'billing',
            );

            if (isset($requestData['profile_image'])) {
                //upload profile image
                try {
                    $destinationPath = storage_path() . '/app/public/user_profile/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['profile_image'];
                    $image_resize = Image::make($requestData['profile_image']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . '_' . str_replace(' ', '_', $requestData['owner_name']) . '_' . time() . '.' . $requestData['profile_image']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/user_profile/' . $fileName;

                    $userData['profile_image'] = $url;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save profile image : ' . $e->getMessage();
                }
            } else {
                //upload with no-image
                try {
                    $destinationPath = storage_path() . '/app/public/user_profile/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777, true);
                    }
                    $path = URL::asset('assets/images/no-image.png');
                    $fileName =  uniqid() . time() . 'no-image.png';
                    file_put_contents($destinationPath . $fileName, file_get_contents($path));
                    $userData['profile_image'] = URL::to('/storage/') . '/user_profile/' . $fileName;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save no-image profile image : ' . $e->getMessage();
                }
            }

            if (isset($requestData['gst_certificate'])) {
                //upload gst certificate
                try {
                    $destinationPath = storage_path() . '/app/public/gst_certificate/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['gst_certificate'];
                    $image_resize = Image::make($requestData['gst_certificate']);
                    $fileName =  uniqid() . '_' . str_replace(' ', '_', $requestData['firm']) . '_gst_certificate_' . time() . '.' . $requestData['gst_certificate']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/gst_certificate/' . $fileName;
                    $userData['gst_certificate'] = $url;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save gst certificate : ' . $e->getMessage();
                }
            }

            if (isset($requestData['company_certificate'])) {
                //upload company certificate
                try {
                    $destinationPath = storage_path() . '/app/public/company_certificate/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['company_certificate'];
                    $image_resize = Image::make($requestData['company_certificate']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . '_' . str_replace(' ', '_', $requestData['firm']) . '_company_certificate_' . time() . '.' . $requestData['company_certificate']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/company_certificate/' . $fileName;

                    $userData['company_certificate'] = $url;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save company certificate : ' . $e->getMessage();
                }
            }

            try {
                DB::beginTransaction();
                $matchUser = ['id' => $userData['id']];
                $appUsers = AppUsers::create($userData);
                $userAddressData['user_id'] = $appUsers->id;
                $matchUserAddress = ['id' => $userAddressData['id']];
                $appUserAddress = AppUserAddresses::create($userAddressData);
                DB::commit();
                if ($appUsers) {
                    $return['status'] = 1;
                    $return['message'] = 'User [' . $appUsers->owner_name . '] saved successfully';
                    $return['data'] = $appUsers;
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
     *update record in database  
     */
    public static function updateUser($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {

            $userData = array(
                'id' => $requestData['app_user_id'],
                'firm' => ucwords($requestData['firm']),
                'owner_name' => ucwords($requestData['owner_name']),
                'email' => $requestData['email'],
                'profile_image' => (@$requestData['old_profile_image'] != "") ? $requestData['old_profile_image'] : '',
                'gst_certificate' => (@$requestData['old_gst_certificate'] != "") ? $requestData['old_gst_certificate'] : '',
                'user_type' => $requestData['user_type'],
                'status' => $requestData['status'],
                'distributor' => $requestData['distributor'],
                'ref_distributor' => $requestData['ref_distributor'],
                'ref_dealer' => $requestData['ref_dealer'],
                'website' => $requestData['website'],
                'company_gst_no' => strtoupper($requestData['company_gst_no']),
                'company_certificate' => (@$requestData['old_company_certificate'] != "") ? $requestData['old_company_certificate'] : '',
                'shop_name' => ucwords($requestData['shop_name']),
                'shop_gst_no' => strtoupper($requestData['shop_gst_no']),
                'working_city' => ucwords($requestData['working_city']),
                'working_state' => ucwords($requestData['working_state']),
            );

            $userAddressData = array(
                'id' => $requestData['app_user_address_id'],
                'user_id' => $requestData['app_user_id'],
                'name' => ucwords($requestData['owner_name']),
                'address' => $requestData['address'],
                'country' => ucwords($requestData['country']),
                'state' => ucwords($requestData['state']),
                'city' => ucwords($requestData['city']),
                'pincode' => $requestData['pincode'],
                'address_type' => 'billing',
            );

            if (isset($requestData['password']) && $requestData['password'] != "") {
                $userData['password'] = Hash::make($requestData['password']);
            }
            if (isset($requestData['profile_image'])) {
                //upload profile image
                try {
                    $destinationPath = storage_path() . '/app/public/user_profile/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['profile_image'];
                    $image_resize = Image::make($requestData['profile_image']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . '_' . str_replace(' ', '_', $requestData['owner_name']) . '_' . time() . '.' . $requestData['profile_image']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/user_profile/' . $fileName;

                    $userData['profile_image'] = $url;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during update profile image : ' . $e->getMessage();
                }
            } else if ($userData['profile_image'] == "") {
                //upload with no-image
                try {
                    $destinationPath = storage_path() . '/app/public/user_profile/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777, true);
                    }
                    $path = URL::asset('assets/images/no-image.png');
                    $fileName =  uniqid() . time() . 'no-image.png';
                    file_put_contents($destinationPath . $fileName, file_get_contents($path));
                    $userData['profile_image'] = URL::to('/storage/') . '/user_profile/' . $fileName;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during update no-image profile image : ' . $e->getMessage();
                }
            }

            if (isset($requestData['gst_certificate'])) {
                //upload gst certificate
                try {
                    $destinationPath = storage_path() . '/app/public/gst_certificate/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['gst_certificate'];
                    $image_resize = Image::make($requestData['gst_certificate']);
                    $fileName =  uniqid() . '_' . str_replace(' ', '_', $requestData['firm']) . '_gst_certificate_' . time() . '.' . $requestData['gst_certificate']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/gst_certificate/' . $fileName;
                    $userData['gst_certificate'] = $url;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during update gst certificate : ' . $e->getMessage();
                }
            }

            if (isset($requestData['company_certificate'])) {
                //upload company certificate
                try {
                    $destinationPath = storage_path() . '/app/public/company_certificate/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['company_certificate'];
                    $image_resize = Image::make($requestData['company_certificate']);
                    $fileName =  uniqid() . '_' . str_replace(' ', '_', $requestData['firm']) . '_company_certificate_' . time() . '.' . $requestData['company_certificate']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/company_certificate/' . $fileName;

                    $userData['company_certificate'] = $url;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during update company certificate : ' . $e->getMessage();
                }
            }

            try {
                DB::beginTransaction();
                $matchUser = ['id' => $userData['id']];
                $appUsers = AppUsers::updateOrCreate($matchUser, $userData);

                $matchUserAddress = ['id' => $userAddressData['id']];
                $appUserAddress = AppUserAddresses::updateOrCreate($matchUserAddress, $userAddressData);
                DB::commit();
                if ($appUsers) {
                    $return['status'] = 1;
                    $return['message'] = 'User [' . $appUsers->owner_name . '] saved successfully';
                    $return['data'] = $appUsers;
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
    public function deleteUser($user_id)
    {
        $is_dependent = AppUsers::checkDependancy($user_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $userData = AppUsers::where('id', $user_id)->first()->toArray();
        if (!empty($is_dependent)) {
            //update status to deleted
            AppUsers::where('id', $user_id)->update(['status' => 2]);
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'User [' . $userData['owner_name'] . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {
            //delete record from table
            $p = parse_url($userData['profile_image']);
            if ($p['path'] != "") {
                $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                $image_path = storage_path($image_path);

                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            $p = parse_url($userData['gst_certificate']);
            if ($p['path'] != "") {
                $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                $image_path = storage_path($image_path);

                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            $p = parse_url($userData['company_certificate']);
            if ($p['path'] != "") {
                $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                $image_path = storage_path($image_path);

                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            AppUserAddresses::where('user_id', $user_id)->delete();
            AppUsers::where('id', $user_id)->delete();
            $return['status'] = 1;
            $return['message'] = 'User [' . $userData['owner_name'] . '] deleted successfully';
        }

        return $return;
    }

    public function checkDependancy($id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        //check for home banner module
        $coupon_code_record = CouponCode::where('contractorid', $id)->count();
        $redeem_record = Redeem::where('user_id', $id)->count();
        $order_record = Order::where('app_user_id', $id)->count();

        if ($coupon_code_record > 0) {
            array_push($dep_modules, 'Coupon Codes');
        }

        if ($redeem_record > 0) {
            array_push($dep_modules, 'Redeem');
        }

        if ($order_record > 0) {
            array_push($dep_modules, 'Orders');
        }

        return $dep_modules;
    }

    /**
     * send password notification
     */
    public static function sendPasswordNotification($requestData)
    {

        $password = $requestData['password'];
        $owner_name = $requestData['owner_name'];
        $mobile = $requestData['mobile'];
        $email = $requestData['email'];

        if ($email != "") {
            //send password and username(mobile) on mail
            $subject = "Login credentials for Rehalti Application.";

            $mailData = "<html><table border=0>";
            $mailData .= "<tr><td><h1>Hello Admin,</h1> </td></tr>";
            $mailData .= "<tr><td>New dealer register in system.</td></tr>";
            $mailData .= "<tr><td>Name: " . $owner_name . '<td/></tr>';
            $mailData .= "<tr><td>Email: " . $email . '<td/></tr>';
            $mailData .= "<tr><td>username: " . $mobile . '<td/></tr>';
            $mailData .= "<tr><td>password: " . $password . '</td><tr/>';
            $mailData .= "</table></html>";

            $this->sendEmail($email, $subject, $mailData);
        }

        if ($mobile != "") {
            //send password and username(mobile) on SMS
        }
    }

    /**
     * get record for user report
     */
    public static function getAppUserExportData($option = array())
    {

        $data = array(
            'id' => '',
            'order_by' => 'id',
            'sorting' => 'desc',
            'status' => '',
            'mobile_verified' => '',
            'where' => array(),
            'orWhere' => array()
        );

        $config = array_merge($data, $option);
        $result = [];

        $query = AppUsers::query();

        $query->select(
            "app_users.*",
            "app_user_addresses.id AS app_user_address_id",
            "app_user_addresses.address",
            "app_user_addresses.country",
            "app_user_addresses.city",
            "app_user_addresses.pincode",
            "app_user_addresses.state",
            DB::raw('(CASE WHEN app_users.user_type = "dealer" THEN "Dealer" '
                . 'WHEN app_users.user_type = "exclusive_dealer" THEN "Exclusive Dealer" '
                . 'WHEN app_users.user_type = "channel_partner" THEN "Channel Partner" '
                . 'WHEN app_users.user_type = "distributor" THEN "Distributor" '
                . 'WHEN app_users.user_type = "contractor" THEN "Contractor" '
                . 'ELSE "Cancelled" END) AS user_type_text'),
            DB::raw('(CASE WHEN app_users.status = "0" THEN "In-Active" '
                . 'WHEN app_users.status = "1" THEN "Active" '
                . 'WHEN app_users.status = "2" THEN "Deleted" '
                . 'END) AS user_status_text'),
            DB::raw("(SELECT owner_name FROM app_users AS distributor WHERE distributor.id=app_users.distributor) as distributor_name"),
            DB::raw("(SELECT owner_name FROM app_users AS ref_distributor WHERE ref_distributor.id=app_users.ref_distributor) as ref_distributor_name"),
            DB::raw("(SELECT owner_name FROM app_users AS ref_dealer WHERE ref_dealer.id=app_users.ref_dealer) as ref_dealer_name"),
            DB::raw("(SELECT COUNT(id) AS address_count FROM app_user_addresses WHERE app_user_addresses.user_id=app_users.id) as address_count")
        );
        $query->join('app_user_addresses', 'app_users.id', 'app_user_addresses.user_id');
        $query->where('app_user_addresses.address_type', 'billing');

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

        $result = $query->get();
        return $result;
    }
}
