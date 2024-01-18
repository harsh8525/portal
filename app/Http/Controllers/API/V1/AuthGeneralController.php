<?php

/**
 * @package     AuthGeneral
 * @subpackage  AuthGeneral
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the AuthGeneral.
 */

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;
use Laravel\Passport\PersonalAccessTokenFactory;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use Illuminate\Support\Str;
use App\Models\AppUsers;
use App\Models\AppUserOtp;
use App\Models\AppUserAddresses;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\UserLoginHistory;
use App\Models\ApiLogin;
use DB;
use App\Traits\EmailService;
use Carbon\Carbon;

class AuthGeneralController extends BaseController
{
    use EmailService;

    /**
     * @OA\Post(
     *   path="/v1/api-login",
     *   tags={"Authentication"},
     *   summary="Api Users Login",
     *   description="Enter Username Ex:b2cweb, b2cmobile </br>or</br>Enter Password: P@ssw0rd",
     *   operationId="api-login",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           required={"username","password"},
     *           @OA\Property(property="username", type="string" ),
     *           @OA\Property(property="password", type="string" ),
     *      )
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
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function ApiUserLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }

            // Authentication logic for two types of users
            $b2cUser = ApiLogin::where('name', $request->username)->where(function ($query) {
                $query->where('type', 'b2c_web')->orWhere('type', 'b2c_mobile');
            })->first();

            $b2cWebUser = ApiLogin::where('name', $request->username)->where('type', 'b2c_web')->first();
            $b2cMobileUser = ApiLogin::where('name', $request->username)->where('type', 'b2c_mobile')->first();

            if (($b2cWebUser || $b2cMobileUser) && Hash::check($request->password, $b2cUser->password)) {
                $token = $b2cUser->createToken('AuthToken');
                $success = $b2cUser;
                $success['token'] = $token;

                return $this->sendResponse([$success], 'API [' . $b2cUser->name . '] User Login Successfully');
            } else {
                return $this->sendError('Invalid Credentials');
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Get(
     *   path="/v1/check-token-validity",
     *   tags={"Authentication"},
     *   summary="Check Passport Token Validity",
     *   description="Check the validity of a Passport Token.",
     *   operationId="check-token-validity",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      required=true,
     *      description="The ID of the token to check validity.",
     *      @OA\Schema(
     *           required={"tokenId"},
     *           @OA\Property(property="tokenId", type="string" ),
     *      )
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
    public function checkTokenValidity(Request $request)
    {
        $requestData = $request->only([
            'tokenId'
        ]);
   
        $validator = Validator::make($requestData, [
            'tokenId' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }

        try {
            $tokenId = $request->tokenId ?? '';
            $token = Token::find($tokenId);
            if ($token) {
                if ($token && $token->expires_at > Carbon::now()) {
                    $token['expire'] = false;
                    return $this->sendResponse($token, 'Token is valid');
                } else {
                    $token['expire'] = true;
                    return $this->sendError($token, 'Token is expired');
                }
            }else{
                return $this->sendError('Token is not valid');
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Get(
     *   path="/v1/refresh-token",
     *   tags={"Authentication"},
     *   summary="Refresh Passport Token",
     *   description="Refreshes a Passport Token for a api user.",
     *   operationId="refresh-token",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      required=true,
     *      description="The ID of the token to be refreshed.",
     *      @OA\Schema(
     *           required={"tokenId"},
     *           @OA\Property(property="tokenId", type="string" ),
     *      )
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
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function refreshToken(Request $request)
    {
        $requestData = $request->only([
            'tokenId'
        ]);
   
        $validator = Validator::make($requestData, [
            'tokenId' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }

        try {
            $tokenId = $request->tokenId ?? '';
            $expiredToken = Token::find($tokenId);
            if ($expiredToken) {

                if ($expiredToken->expires_at > Carbon::now()) {
                    return $this->sendResponse($expiredToken,'Token is valid');
                } else {
                    $apiUser = ApiLogin::where('id',$expiredToken->user_id)->first();
                    $token = $apiUser->createToken('AuthToken');
                    $success = $token;
                    return $this->sendResponse([$success], 'Api ['.$apiUser->name.'] User Login Successfully');   
                }
            }else{
                return $this->sendError('Token is not valid');
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    
}
