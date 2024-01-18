<?php

namespace B2BApp\Http\Controllers\API\V1;

use B2BApp\Http\Controllers\API\V1\BaseController as BaseController;
use B2BApp\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use App\Models\AppUsers;
use App\Models\AppUserOtp;
use App\Models\AppUserAddresses;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use App\Models\Customer;
use App\Models\ActivityLog;
use App\Models\UserLoginHistory;
use App\Models\GeoCountryLists;
use DB;
use App\Traits\EmailService;
use Carbon\Carbon;

class UserController extends BaseController
{
    use EmailService;

    // for authorized user
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->token()->name != 'b2bAuthToken') {
                return response()->json(['status' => false, 'message' => 'Not authorized user', 'data' => []], 401);
            }

            return $next($request);
        });
    }

    /**
     * @OA\Post(
     *   path="/v1/user/get-user",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"User"},
     *   summary="Get Single User",
     *   description="Get Single User Request<br>*need to pass user_id that user want to get",
     *   operationId="user-show",
     *   @OA\RequestBody(
     *     required=false,
     *     description="Get User", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             @OA\Property(property="user_id", type="string", description="need to pass user id"),
     *           )
     *     ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * )
     * Logout
     *
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    {
        if (!hasPermission('USERS', 'read')) {
            $success = [];
            return $this->sendError('User have no Permission', $success, 401);
        }
        try {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            $userId = request()->input('user_id');
            $matchAgencyId = User::where('id', $userId)->where('agency_id', Auth::user()->agency_id)->value('id');
            $matchId = User::where('id', $userId)->value('id');
            if ($matchAgencyId == "") {
                if ($matchId == "") {

                    $success = [];
                    return $this->sendError('User Not Found', $success, 400);
                } else {
                    $success = [];
                    return $this->sendError(unauth_message, $success, 401);
                }
            }
            $user_id = $request->input('user_id');
            $agency_id = Auth::user()->agency_id;
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

            $query->where('users.id', $user_id);
            $query->where('users.agency_id', $agency_id);
            // $query->orderBy($config['order_by'], $config['sorting']);

            $response = $query->first();
            if ($response) {
                $success = 1;
                return $this->sendResponse($response, 'User Fetched Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('User Not Found', $success, 200);
            }
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error During Get User Details', $success);
        }
    }

    /**
     * @OA\Post(
     * path="/v1/user/get-all-users",
     *  security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"User"},
     *   summary="Get All Users",
     *   description="Get All Users Request,<br>per_page: need to pass per page that user want to show i.e 10,20,50,100 etc.<br>order_by: pass key value that receive in response i.e name, mobile, email etc.<br>sorting: pass key value 'desc' or 'asc'.<br>status: need to pass status for user active or in-active, pass value for active=1 and in-active=0<br>name: pass user name<br>mobile: pass user's mobile number<br>email: pass user's email address<br>user_role: need to pass role code that fetch by call API 'get-role-code'",
             
     *   operationId="Get-Users",
     *  @OA\RequestBody(
     *     required=false,
     *     description="Get All Users", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             @OA\Property(property="per_page", type="string", default="", description="need to pass per page that user want to show i.e 10,20,50,100 etc."),
     *           @OA\Property(property="order_by", type="string", default="", description="pass key value that receive in response i.e name, mobile, email etc."),
     *           @OA\Property(property="sorting", type="string", default="", description="pass key value asc or desc."), 
     *           @OA\Property(property="status", type="string", default="", description="need to pass status for user active or in-active, pass value for active=1 and in-active=0"),
     *           @OA\Property(property="name", type="string", default="", description="pass user name" ),
     *           @OA\Property(property="mobile", type="string", default="", description="pass user's mobile number"),
     *           @OA\Property(property="email", type="string", default="", description="pass user's email address"),
     *           @OA\Property(property="user_role", type="string", default="", description="need to pass role code that fetch by call API 'get-role-code'"),
     *           )
     *     ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     *  List User Api
     *  @return \Illuminate\Http\Response
     **/
    public function index(Request $request)
    {

        if (!hasPermission('USERS', 'read')) {
            $success = [];
            return $this->sendError('User have no Permission', $success, 401);
        }
        try {

            $validator = Validator::make($request->all(), [
                'per_page' => 'nullable|numeric',
                'mobile' => 'nullable|numeric',
                'name' => 'nullable|regex:/^[\pL\s]+$/u',
                'email' => 'nullable',
                'status' => 'nullable|in:0,1',
                'sorting' => 'nullable|in:desc,asc',
            ],
            [
                'sorting.in' => 'Please Enter Valid sorting value',
                'status.in' => 'Please Enter Valid status value'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            $queryStringConcat = '?';
            if (isset($_GET['per_page'])) {
                $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'];
            }
            if (isset($_GET['page'])) {
                $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'];
            }
            $filter = array(
                'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')[0]['value'],
                'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'id',
                'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
                // 'mobile_verified' => (request()->input('mobile_verified') != NULL) ? request()->input('mobile_verified') : '',
                'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
                'name' => (request()->input('name') != NULL) ? request()->input('name') : '',
                'mobile' => (request()->input('mobile') != NULL) ? request()->input('mobile') : '',
                'email' => (request()->input('email') != NULL) ? request()->input('email') : '',
                'user_role' => (request()->input('user_role') != NULL) ? request()->input('user_role') : '',
                'agancy_name' => (request()->input('agancy_name') != NULL) ? request()->input('agancy_name') : '',

            );
            if (request()->input('name') != NULL) {
                $filter['where'][] = ['users.name', 'like', '%' . request()->input('name') . '%'];
            }
            if (request()->input('mobile') != NULL) {

                $filter['where'][] = ['users.mobile', 'like', '%' . request()->input('mobile') . '%'];
            }

            if (request()->input('email') != NULL) {
                $filter['where'][] = ['users.email', 'like', '%' . request()->input('email') . '%'];
            }
            if (request()->input('agancy_name') != NULL) {
                $filter['where'][] = ['agencies.full_name', 'like', '%' . request()->input('agancy_name') . '%'];
            }
            if (request()->input('user_role') != NULL) {

                $filter['where'][] = ['users.role_code', 'like', '%' . request()->input('user_role') . '%'];
            }

            $filter['where'][] = ['users.agency_id', '=', Auth::user()->agency_id];
            if (request()->input('status') != NULL) {
                $filter['where'][] = ['users.status', '=', request()->input('status')];
            }
            $AdminUserListData = User::getAdminUsers($filter);

            $response = $AdminUserListData;
            if ($response['data'][0]) {
                $success = 1;
                return $this->sendResponse($response['data'], 'User Listed Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('User List Not Found', $success, 200);
            }
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error During List User Details', $success);
        }
    }


    /**
     * @OA\Post(
     *   path="/v1/user/user-create",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"User"},
     *   summary="User Create",
     *   description="create user",
     *   operationId="create-user",
     *   @OA\RequestBody(
     *     required=true,
     *     description="User Create", 
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *            required={"full_name","mobile","isd_code","email","role"},
     *           @OA\Property(property="full_name", type="string", default="",description="pass user's full name" ),
     *           @OA\Property(property="mobile", type="string", default="",description="pass user's mobile number" ),
     *           @OA\Property(property="isd_code", type="string", default="",description="need to pass isd_code that fetch by call API 'get-country-code'" ),
     *           @OA\Property(property="email", type="string", default="",description="pass user's email address" ),
     *           @OA\Property(property="profile_image", type="file", format="binary", default="",description="select user's profile image *ensure that you are uploading an image is 2MB or less and one of the following types: JPG,JPEG, or PNG" ),
     *           @OA\Property(property="role", type="string", default="",description="need to pass role code that fetch by call API 'get-role-code'" ),
     *           )
     *     ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * )
     * User Create History
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        if (!hasPermission('USERS', 'create')) {
            $success = [];
            return $this->sendError('User have no Permission', $success, 401);
        }
        try {
            $requestData = $request->only(["full_name", "mobile", "isd_code", "email", "profile_image", "role"]);

            // For Mobile length validation against isd_code
            $IsdCodeLength = GeoCountryLists::where('isd_code', request()->input('isd_code'))->value('max_mobile_number_length');
            $IsdCode = GeoCountryLists::where('isd_code', request()->input('isd_code'))->value('isd_code');
            $rules = [
                'full_name' => 'required|regex:/^[\pL\s]+$/u',
                'isd_code' => 'required|regex:/^\+\d{1,3}$/',
                'email' => 'required|email|unique:users',
                'profile_image' => 'nullable|mimes:jpeg,jpg,png|max:2048',
                'role' => 'required',
                'mobile' => 'required|numeric|unique:users',
            ];
            
            $customMessages = [
                'required'  => 'The :attribute field is required.',
                'unique'    => ':attribute is already taken',
            ];
            
            $niceNames = array();
            
            $this->validate($request, $rules, $customMessages, $niceNames);
            if ($IsdCode == '') {
                $success = [];
                return $this->sendError('The isd code is invalid.', $success, 400);
            }
            if ($IsdCodeLength != strlen(request()->input('mobile'))) {
                $success = [];
                return $this->sendError('The mobile must be ' .$IsdCodeLength. ' digits.', $success, 400);
            }
            //validation foe role code
            $query = Role::query();
            $query->select('roles.code');
            $query->where('role_type', 'b2b');
            $query->where('code', request()->input('role'));
            $query->where('status', '1');
            $role = $query->value('code');

            if (request()->input('role') != $role) {
                $success = [];
                return $this->sendError('Please Enter Valid b2b role code', $success, 400);
            }
            $userData['fname'] = $requestData['full_name'];
            $userData['mobile'] = $requestData['mobile'];
            $userData['isd_code'] = $requestData['isd_code'];
            $userData['email'] = $requestData['email'];
            $userData['profile_image'] = $requestData['profile_image'];
            $userData['role'] = $requestData['role'];
            $response = User::createUser($userData);

            if ($response['data']) {
                $success = 1;
                return $this->sendResponse($response, 'User Created Successfully', $success);
            } else {
                $success = [];
                return $this->sendError('User Not Create', $success, 200);
            }
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error During Create User', $success);
        }
    }

    /**
     * @OA\Post(
     *   path="/v1/user/user-update",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"User"},
     *   summary="User Update",
     *   description="update user",
     *   operationId="update-user",
     *   @OA\RequestBody(
     *     required=true,
     *     description="User Update", 
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *            required={"user_id","full_name","mobile","isd_code","email","role","status"},
     *           @OA\Property(property="user_id", type="string", default="",description="pass user_id" ),
     *           @OA\Property(property="full_name", type="string", default="",description="pass user's full name" ),
     *           @OA\Property(property="mobile", type="string", default="",description="pass user's mobile number" ),
     *           @OA\Property(property="isd_code", type="string", default="",description="need to pass isd_code that fetch by call API 'get-country-code'" ),
     *           @OA\Property(property="email", type="string", default="",description="pass user's email address" ),
     *           @OA\Property(property="profile_image", type="file", default="", format="binary",description="select user's profile image *ensure that you are uploading an image is 2MB or less and one of the following types: JPG,JPEG, or PNG" ),
     *           @OA\Property(property="role", type="string", default="",description="need to pass role code that fetch by call API 'get-role-code'" ),
     *           @OA\Property(property="status", type="string", default="",description="need to pass status for user active or in-active, pass value for active=1 and in-active=0" ),
     *           )
     *     ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!hasPermission('USERS', 'update')) {
            $success = [];
            return $this->sendError('User have no Permission', $success, 401);
        }
        try {
            $requestData = $request->only(['user_id', 'full_name', 'mobile', 'isd_code', 'email', 'profile_image', 'role', 'status']);
            // for user_id validation
            $userId = request()->input('user_id');
            $matchAgencyId = User::where('id', $userId)->where('agency_id', Auth::user()->agency_id)->value('id');
            $matchId = User::where('id', $userId)->value('id');
            if ($matchAgencyId == "") {
                if ($matchId == "") {

                    $success = [];
                    return $this->sendError('User Not Found', $success, 400);
                } else {
                    $success = [];
                    return $this->sendError(unauth_message, $success, 401);
                }
            }
            // For Mobile length validation against isd_code
            $IsdCodeLength = GeoCountryLists::where('isd_code', request()->input('isd_code'))->value('max_mobile_number_length');
            $IsdCode = GeoCountryLists::where('isd_code', request()->input('isd_code'))->value('isd_code');
        
            $rules = [
                'user_id' => 'required',
                'full_name' => 'required|regex:/^[\pL\s]+$/u',
                'isd_code' => 'required|regex:/^\+\d{1,3}$/',
                'mobile' => 'required|numeric|unique:users,mobile,' . $request->user_id,
                'email' => 'required|email|unique:users,email,' . $request->user_id,
                'profile_image' => 'nullable|mimes:jpeg,jpg,png|max:2048',
                'role' => 'required',
                'status' => 'in:1,0',
            ];

            $customMessages = [
                'required'  => 'The :attribute field is required.',
                'status.in' => 'Please Enter Valid status value',
                'unique'    => ':attribute is already taken'
            ];
            
            $niceNames = array();
            
            $this->validate($request, $rules, $customMessages, $niceNames);
            if ($IsdCode == '') {
                $success = [];
                return $this->sendError('The isd code is invalid.', $success, 400);
            }
            if ($IsdCodeLength != strlen(request()->input('mobile'))) {
                $success = [];
                return $this->sendError('The mobile must be ' .$IsdCodeLength. ' digits.', $success, 400);
            }
            //validation foe role code
            $query = Role::query();
            $query->select('roles.code');
            $query->where('role_type', 'b2b');
            $query->where('code', request()->input('role'));
            $query->where('status', '1');
            $role = $query->value('code');
            if (request()->input('role') != $role) {
                $success = [];
                return $this->sendError('Please Enter Valid b2b role code', $success, 400);
            }
            $userData['admin_user_id'] = $requestData['user_id'];
            $userData['fname'] = $requestData['full_name'];
            $userData['mobile'] = $requestData['mobile'];
            $userData['isd_code'] = $requestData['isd_code'];
            $userData['email'] = $requestData['email'];
            $userData['profile_image'] = $requestData['profile_image'];
            $userData['role'] = $requestData['role'];
            $userData['status'] = $requestData['status'];
            $response = User::updateUser($userData);

            if ($response['data']) {
                $success = 1;
                return $this->sendResponse($response, 'User Updated Successfully', $success);
            } else {
                $success = [];
                return $this->sendError('User Not Update', $success, 200);
            }
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error During Update User', $success);
        }
    }
    /**
     * @OA\Get(
     *   path="/v1/user/get-role-code",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"User"},
     *   summary="User Role Code",
     *   description="user role code",
     *   operationId="get-role",
     
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function userRole(Request $request)
    {
        try {
            // get role type
            $query = Role::query();
            $query->select(
                'roles.*',
                DB::raw('(CASE WHEN roles.status = "1" THEN "Active" '
                    . 'ELSE "In-active" END) AS roles_status_text')
            );
            $query->where('role_type', 'b2b');
            $query->where('status', '1');
            $response = $query->get();

            if ($response) {
                $success = 1;
                return $this->sendResponse($response, 'Role Get Successfully', $success);
            } else {
                $success = [];
                return $this->sendError('Role Not Get', $success, 200);
            }
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error During Get Role', $success);
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/user/get-user-activity",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"User"},
     *   summary="Get User Activity",
     *   description="Get User Activity Request<br>*need to pass user_id that user want to get user activity",
     *   operationId="user-activity",
     *   @OA\RequestBody(
     *     required=false,
     *     description="Get User activity", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             @OA\Property(property="user_id", type="string", description="need to pass user id"),
     *           )
     *     ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * )
     * Logout
     *
     * @return \Illuminate\Http\Response
     */

    public function activityLog(Request $request)
    {
        if (!hasPermission('USERS', 'read')) {
            $success = [];
            return $this->sendError('User have no Permission', $success, 401);
        }
        try {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            // for user_id validation
            $userId = request()->input('user_id');
            $matchAgencyId = User::where('id', $userId)->where('agency_id', Auth::user()->agency_id)->value('id');
            $matchId = User::where('id', $userId)->value('id');
            if ($matchAgencyId == "") {
                if ($matchId == "") {

                    $success = [];
                    return $this->sendError('User Not Found', $success, 400);
                } else {
                    $success = [];
                    return $this->sendError('User Unauthenticated', $success, 401);
                }
            }
            // get user activity log
            $query = ActivityLog::query();
            $query->select('activity_log.*', 'users.name as user_name');
            $query->join('users', 'users.id', 'activity_log.causer_id');
            $query->where('activity_log.causer_id', $userId);
            $query->orderBy('activity_log.id', 'DESC');
            $response = $query->get()->toArray();
            if ($response) {
                $success = 1;
                return $this->sendResponse($response, 'User Activity Fetched Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('User Activity Not Found', $success, 200);
            }
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error During Get User Activity Details', $success);
        }
    }
}
