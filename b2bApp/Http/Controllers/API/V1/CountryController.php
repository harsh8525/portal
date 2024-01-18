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
use App\Models\GeoCountryLists;
use App\Models\UserLoginHistory;
use DB;
use App\Traits\EmailService;
use Carbon\Carbon;

class CountryController extends BaseController
{
    use EmailService;

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
     * @OA\Get(
     *   path="/v1/country/get-country-code",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Country"},
     *   summary="Get Country Code",
     *   description="Get Country Code",
     *   operationId="country-code",
     *  
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

    public function countryIsdCode(Request $request)
    {
        // if (!hasPermission('USERS', 'read')) {
        //     $success = [];
        //     return $this->sendError('User have no Permission', $success, 401);
        // }
        try {
            // echo "hello";die;
            // $user_id = $request->input('user_id');
            // $agency_id = Auth::user()->agency_id;
            // echo $user_id;die;
            $query = GeoCountryLists::query();
            $query->select('geo_country_lists.*','geo_region_lists.region_name as country_name',
            DB::raw('(CASE WHEN geo_region_lists.is_active = "1" THEN "Active" '
            . 'ELSE "In-active" END) AS country_status_text')
        );
            $query->join('geo_region_lists', 'geo_region_lists.id','geo_country_lists.country_id');
            // $query->where('users.agency_id', $agency_id);
            // $query->orderBy($config['order_by'], $config['sorting']);

            $response = $query->get()->toArray();
            // echo "<pre>";
            // print_r($response);
            // die;
            // $filter = array(
            //     'id' => $user_id
            // );
            // $response = User::getAdminUsers($filter);

            // $user = User::select('*')
            //     ->where('id', '=',  $user_id)
            //     ->where('status', '=', '1')->first();
            if ($response) {
                $success = 1;
                return $this->sendResponse($response, 'Country Isd Code Fetched Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('Country Isd Code Not Found', $success, 200);
            }
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error During Get Country Isd Code', $success);
        }
    }


}
