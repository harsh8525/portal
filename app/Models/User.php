<?php

/**
 * @package     Users
 * @subpackage  Users
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Users.
 */

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use DB;
use Illuminate\Support\Facades\Hash;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use App\Traits\EmailService;
use App\Models\Setting;
use App\Models\Agency;
use App\Models\AgencyType;
use App\Models\ActivityLog;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\Uuids;




class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, EmailService, LogsActivity, Uuids;
    protected $table = 'users';
    protected $guarded = [];
    protected static $logAttributes = [
        'agency_id', 'name', 'profile_image', 'email', 'isd', 'mobile', 'status', 'role_code', 'app_name'
    ];

    protected static $logName = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUserAgency()
    {
        return $this->hasOne('App\Models\Agency', 'id', 'agency_id');
    }
    public function getRole()
    {
        return $this->hasOne('App\Models\Role', 'code', 'role_code');
    }

    /*
    * get list or single or all record to display
    */
    public static function getAdminUsers($option = array())
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
            'orWhere' => array()
        );

        $config = array_merge($data, $option);

        $result = [];
        if ($config['id'] != '') {

            try {
                $query = User::query();
                $query->select(
                    'users.*',
                    'agencies.id as agency_id',
                    'agencies.full_name as agancy_name',
                    DB::raw('(CASE WHEN users.status = "0" THEN "In-Active" '
                        . 'WHEN users.status = "1" THEN "Active" '
                        . 'WHEN users.status = "2" THEN "Deleted" '
                        . 'END) AS user_status_text'),

                );
                $query->leftjoin('agencies', 'agencies.id', 'users.agency_id', 'left');

                $query->where('users.id', $config['id']);
                $query->orderBy($config['order_by'], $config['sorting']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {

                $query = User::query();
                $query->select(
                    'users.*',

                    'agencies.full_name as agancy_name',
                    DB::raw('(CASE WHEN users.app_name = "managerapp" THEN "Manager Panel" '
                        . 'WHEN users.app_name = "b2bapp" THEN "B2B Panel" '
                        . 'WHEN users.app_name = "supplierapp" THEN "Supplier Panel" '
                        . 'END) AS users_app_text'),
                    DB::raw('(CASE WHEN users.status = "0" THEN "In-Active" '
                        . 'WHEN users.status = "1" THEN "Active" '
                        . 'WHEN users.status = "2" THEN "Deleted" '
                        . 'END) AS user_status_text'),
                );
                $query->leftjoin('agencies', 'users.agency_id', 'agencies.id', 'left');

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
            $return['message'] = 'Admin User list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /*
    * insert record in database
    */
    public static function createUser($requestData)
    {
        if (Auth::guard('b2b')->check()) {
            $agency_id = Auth::guard('b2b')->user()->agency_id;
            $app_name = Auth::guard('b2b')->user()->app_name;
        } else {
            $agency_id = Auth::user()->agency_id;
            $app_name = Auth::user()->app_name;
        }


        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {
            $userData = array(
                'id' => 0,
                'name' => ucwords($requestData['fname']),
                'isd' => $requestData['isd_code'],
                'mobile' =>  $requestData['mobile'],
                'agency_id' => $agency_id,
                'app_name' => $app_name,
                'password' => (isset($requestData['password'])) ? Hash::make($requestData['password']) : "",
                'email' => $requestData['email'],
                'role_code' => $requestData['role'],
                'status' => 0,
                'password_updated_at' => date('Y-m-d h:i:s')
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
                    $destinationPath = storage_path() . '/app/public/admin_users/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $image_data;
                    $image_resize = Image::make($image_data);
                    $fileName =  uniqid() . time() .  $extension;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/admin_users/' . $fileName;
                    $userData['profile_image'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image user :' . $e->getMessage();
                }
            } else if (isset($requestData['profile_image'])) {
                try {
                    $destinationPath = storage_path() . '/app/public/admin_users/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['profile_image'];
                    $image_resize = Image::make($requestData['profile_image']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . '_' . str_replace(' ', '_', $requestData['fname']) . '_' . time() . '.' . $requestData['profile_image']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/admin_users/' . $fileName;
                    $userData['profile_image'] = $url;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save profile image : ' . $e->getMessage();
                }
            } else {
                //upload with no-image
                try {
                    $destinationPath = storage_path() . '/app/public/admin_users/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777, true);
                    }
                    $path = URL::asset('assets/images/no-image.png');
                    $fileName =  uniqid() . time() . 'no-image.png';
                    file_put_contents($destinationPath . $fileName, file_get_contents($path));
                    $userData['profile_image'] = URL::to('/storage/') . '/admin_users/' . $fileName;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save no-image profile image : ' . $e->getMessage();
                }
            }

            try {

                DB::beginTransaction();
                $matchUser = ['id' => $userData['id']];
                $user = User::create($userData);
                DB::commit();
                if ($user) {
                    $userName = $user['name'];
                    $userEmail = $user['email'];
                    $isMail = Setting::select('value')->where('config_key', '=', 'mail|smtp|server')->first();
                    $siteEmail = count(Setting::where('config_key', 'general|basic|siteEmail')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteEmail')->get('value')[0]['value'] : "";
                    $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Rehlte";
                    $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $language_code = Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];
                    $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $token = Str::random(60);
                    if (isset($isMail) && $isMail->value == '0') {
                        $return['message'] = 'User [' . $user->name . '] saved successfully';
                    } else {

                        $code = 'USER_SIGNUP';
                        $language_code = Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];
                        $link = 'http://admin.' . config('app.url') . '/reset-password/' . $token;

                        $data = array(
                            'user_name' => $userName,
                            'site_name' => $siteName,
                            'agency_name' => $agencyName,
                            'agency_logo' => $agencyLogo,
                            'email' => $userEmail,
                            'activation_link' => $link
                        );
                        $getTemplateData = EmailService::userSignUpMailTemplate($code, $data, $language_code);

                        if ($getTemplateData['status'] == 'false') {
                            return back()->with('error', $getTemplateData['error']);
                        } else {
                            $subject = $getTemplateData['data']['subject'];
                            $mailData = $getTemplateData['data']['mailData'];
                            $toEmail = $user['email'];
                            $files = [];
                            $getTemplateData['agencyName'] = $agencyName;
                            // set data in sendEmail function
                            $data = EmailService::sendEmail($toEmail, $subject, $mailData, $files, $getTemplateData['agencyName']);
                            if ($data['status'] == 'false') {
                                return back()->with('error', $data['error']);
                            } else {
                                $return['message'] = 'User [' . $user->name . '] saved successfully';
                            }
                        }
                    }
                    $return['status'] = 1;
                    $return['message'] = 'User [' . $user->name . '] saved successfully';
                    $return['data'] = $user;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during save user record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }

        return $return;
    }

    /*
    * update record in database
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
                'id' => $requestData['admin_user_id'],
                'name' => ucwords($requestData['fname']),
                'mobile' => $requestData['mobile'],
                'isd' => $requestData['isd_code'],
                'email' => $requestData['email'],
                // 'profile_image' => (@$requestData['old_profile_image'] != "") ? $requestData['old_profile_image'] : '',
                'role_code' => $requestData['role'],
                'status' => $requestData['status'],

            );
            if (isset($requestData['password'])) {
                $userData['password_updated_at'] = date('Y-m-d h:i:s');
            }

            if (isset($requestData['password']) && $requestData['password'] != "") {
                $userData['password'] = Hash::make($requestData['password']);
            }

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
                    $destinationPath = storage_path() . '/app/public/admin_users/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $image_data;
                    $image_resize = Image::make($image_data);
                    $fileName =  uniqid() . time() .  $extension;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/admin_users/' . $fileName;
                    $userData['profile_image'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image admin_users :' . $e->getMessage();
                }
            } else if (isset($requestData['profile_image'])) {
                try {
                    $destinationPath = storage_path() . '/app/public/admin_users/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['profile_image'];
                    $image_resize = Image::make($requestData['profile_image']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() . '.' . $requestData['profile_image']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/admin_users/' . $fileName;
                    $userData['profile_image'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image banner :' . $e->getMessage();
                }
            }

            try {

                DB::beginTransaction();
                $matchUser = ['id' => $userData['id']];
                $user = User::updateOrCreate($matchUser, $userData);
                DB::commit();
                if ($user) {
                    $return['status'] = 1;
                    $return['message'] = 'User [' . $user->name . '] updated successfully';
                    $return['data'] = $user;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during save user record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }

        return $return;
    }

    /*
    * delete record from database
    */
    public static function deleteUser($user_id)
    {
        $userData = User::where('id', $user_id)->first()->toArray();
        $is_dependent = User::checkDependancy($userData['agency_id']);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );



        if (!empty($is_dependent)) {
            User::where('id', $user_id)->update(['status' => 2]);
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'User [' . $userData['name'] . '] exist in [' . $module_names . ']. Hence, it can not deleted';
        } else {
            $p = parse_url($userData['profile_image']);
            if ($p['path'] != "") {
                $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                $image_path = storage_path($image_path);

                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            User::where('id', $user_id)->delete();
            $return['status'] = 1;
            $return['message'] = 'User [' . $userData['name'] . '] deleted successfully';
        }

        return $return;
    }

    public static function checkDependancy($agency_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        $isUserHasAgency = Agency::where('id', $agency_id)->count();

        if ($isUserHasAgency > 0) {
            array_push($dep_modules, 'Agency');
        }
        return $dep_modules;
    }

    /*
    * send password notofication
    */
    public static function sendPasswordNotification($requestData)
    {

        $password = $requestData['password'];
        $fname = $requestData['fname'];
        $mobile = $requestData['mobile'];
        $email = $requestData['email'];

        if ($email != "") {
            $subject = "Login credentials for Rehlati Application.";

            $mailData = "<html><table border=0>";
            $mailData .= "<tr><td><h1>Hello Admin,</h1> </td></tr>";
            $mailData .= "<tr><td>New User register in system.</td></tr>";
            $mailData .= "<tr><td>Name: " . $fname . '<td/></tr>';
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

    /*
    * update new password
    */
    public static function updateNewPassAdmin($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $userData = array(
                'id' => $requestData['user_id'],
                'password' => Hash::make($requestData['confirm_password']),

            );

            if (isset($requestData['confirm_password']) && $requestData['confirm_password'] != "") {
                $userData['password'] = Hash::make($requestData['confirm_password']);
            }

            try {

                DB::beginTransaction();
                $matchUser = ['id' => $userData['id']];
                $user = User::updateOrCreate($matchUser, $userData);

                DB::commit();
                if ($user) {
                    $return['status'] = 1;
                    $return['message'] = 'Admin User [' . $user->name . '] Password Changed Successfully';
                    $return['data'] = $user;
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
     * create new user while creating new agency
     */
    public static function createAgencyUser($requestData, $agency_id = null)
    {
        $roleCode = AgencyType::where('id', $requestData['agency_type_id'])->get('code')[0]['code'];
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );


        try {
            $userData = array(

                'agency_id' => $agency_id,
                'name' => ucwords($requestData['operator_full_name']),
                'isd' => $requestData['isd_code'],
                'mobile' => $requestData['operator_mobile'],
                'email' => $requestData['operator_email'],
                'role_code' => ($roleCode == 'B2B') ? 'B2B_AGENCY_OWNER' : 'SUPPLIER_AGENCY_OWNER',
                'app_name' => ($roleCode == 'B2B') ? 'b2bapp' : 'supplierapp',
                'primary_user' => '1',
                'status' => '0',

            );
            try {
                DB::beginTransaction();
                $user = User::create($userData);
                DB::commit();
                if ($user) {

                    $isMail = Setting::select('value')->where('config_key', '=', 'mail|smtp|server')->first();
                    $siteEmail = count(Setting::where('config_key', 'general|basic|siteEmail')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteEmail')->get('value')[0]['value'] : "";
                    if (isset($isMail) && $isMail->value == '0') {
                        $return['message'] = 'User [' . $user->name . '] saved successfully';
                    } else {

                        //send welcome agency mail to primary user
                        $welcomeAgencyCode = 'WELCOME_AGENCY';

                        $getWelcomeAgencyTemplateData = EmailService::welcomeAgencyMailTemplate($welcomeAgencyCode, $user);
                        if ($getWelcomeAgencyTemplateData['status'] == 'false') {
                            return back()->with('error', $getWelcomeAgencyTemplateData['error']);
                        } else {
                            $welcomeMailsubject = $getWelcomeAgencyTemplateData['data']['subject'];
                            $welcomeMailData = $getWelcomeAgencyTemplateData['data']['mailData'];
                            $welcomeAgencyToEmail = $user['email'];
                            $files = [];

                            // set data in sendEmail function
                            $data = EmailService::sendEmail($welcomeAgencyToEmail, $welcomeMailsubject, $welcomeMailData, $files, $getWelcomeAgencyTemplateData['agencyName']);
                        }

                        //send user signup mail to primary user when new agency created
                        $code = 'USER_SIGNUP';

                        $getTemplateData = EmailService::userSignUpMailTemplate($code, $user);
                        if ($getTemplateData['status'] == 'false') {
                            return back()->with('error', $getTemplateData['error']);
                        } else {
                            $subject = $getTemplateData['data']['subject'];
                            $mailData = $getTemplateData['data']['mailData'];
                            $toEmail = $user['email'];
                            $files = [];

                            // set data in sendEmail function
                            $data = EmailService::sendEmail($toEmail, $subject, $mailData, $files, $getTemplateData['agencyName']);
                        }
                    }
                    $return['status'] = 1;
                    $return['message'] = 'User [' . $user->name . '] saved successfully';
                    $return['data'] = $user;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during save user record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }

        return $return;
    }


    /* Get Activity log */
    public static function getActivityList($option = array())
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
            'where' => array(),
            'orWhere' => array()
        );

        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = ActivityLog::query();

                $query->select(
                    "activity_log.*"
                );
                $query->where('id', $config['id']);
                $query->orderBy($config['order_by'], $config['sorting']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = ActivityLog::query();
                $query->select(
                    "activity_log.*"
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
            $return['message'] = 'Get Activity list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /*
    * get record for user report
    */
    static public function getMasterUserReport($option = array(), $pagination = true, $getTotalPrice = false)
    {
        $data = array(
            'id' => '',
            'order_by' => 'created_at',
            'sorting' => 'desc',
            'status' => '',
            'where' => array(),
            'orWhere' => array(),
            'per_page' => 10,
        );

        $config = array_merge($data, $option);
        $query = User::query();
        $query->with(['getUserAgency', 'getRole'])->select(
            "users.*",
            DB::raw('(CASE WHEN users.status = "0" THEN "In-Active" '
                . 'WHEN users.status = "1" THEN "Active" '
                . 'WHEN users.status = "2" THEN "Deleted" '
                . 'END) AS user_status_text'),
        );
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
            $result = $query->get();
        }
        return $result;
    }
}
