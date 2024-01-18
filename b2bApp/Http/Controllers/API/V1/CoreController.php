<?php

namespace B2BApp\Http\Controllers\API\V1;

use B2BApp\Http\Controllers\API\V1\BaseController as BaseController;
use B2BApp\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use App\Models\Currency;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use App\Models\FeatureFlight;
use App\Models\Airports;
use App\Models\Language;
use App\Models\HomeBanner;
use App\Models\PaymentMethod;
use App\Models\PaymentGateway;
use App\Models\ServiceType;
use DB;

class CoreController extends BaseController
{
    public function __construct(){
        
        $this->perPage = count(Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')) > 0 ? Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')[0]['value'] : "20";
    }
    /**
     * @OA\Get(
     *   path="/v1/core/get-payment-method",
     *   tags={"Core"},
     *   summary="Get payment method",
     *   description="get payment method",
     *   operationId="get-payment-method",
    
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
     * get driver status
     *
     * @return \Illuminate\Http\Response
    */
    public function getPaymentOption(Request $request){
        
        try{
            $query = PaymentMethod::query();
            $query->select('core_payment_types.*',
                        DB::raw('(CASE WHEN core_payment_types.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_payment_types.is_active = "1" THEN "Active" '
                        . 'END) AS payment_type_status_text'));
            $paymentMethods = $query->paginate($this->perPage);
            
        
        return $this->sendResponse([$paymentMethods], 'Payment Method Fetched Successfully');
        
        }catch(Exception $e){
            $success = [];
            return $this->sendError($success,'Something went wrong', ['error'=>$e], 500);
        }
        
    }

    /**
     * @OA\Get(
     *   path="/v1/core/get-payment-gateway",
     *   tags={"Core"},
     *   summary="Get payment gateway",
     *   description="get payment gateway",
     *   operationId="get-payment-gateway",
    
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
     * get driver status
     *
     * @return \Illuminate\Http\Response
    */
    public function getPaymentGateway(Request $request){
        
        try{
            $query = PaymentGateway::query();
            $query->select('core_payment_gateways.*',
            
                        DB::raw('(CASE WHEN core_payment_gateways.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_payment_gateways.is_active = "1" THEN "Active" '
                        . 'END) AS payment_gateway_type_status_text'));
            $paymentGateway = $query->paginate($this->perPage);
            
        
        return $this->sendResponse([$paymentGateway], 'Payment Gateway Fetched Successfully');
        
        }catch(Exception $e){
            $success = [];
            return $this->sendError($success,'Something went wrong', ['error'=>$e], 500);
        }
        
    }

    
    /**
     * @OA\Get(
     *   path="/v1/core/get-service-type",
     *   tags={"Core"},
     *   summary="Get Service Type",
     *   description="get Service Type",
     *   operationId="get-service-type",
    
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
     * get driver status
     *
     * @return \Illuminate\Http\Response
    */
    public function getServiceType(Request $request){
        
        try{
            $query = ServiceType::query();
            $query->select('core_service_types.*',
                        DB::raw('(CASE WHEN core_service_types.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_service_types.is_active = "1" THEN "Active" '
                        . 'ELSE "Deleted" END) AS service_type_status_text'));
            $serviceType = $query->paginate($this->perPage);
            
        
        return $this->sendResponse([$serviceType], 'Payment Gateway Fetched Successfully');
        
        }catch(Exception $e){
            $success = [];
            return $this->sendError($success,'Something went wrong', ['error'=>$e], 500);
        }
        
    }
    
    /**
     * @OA\Get(
     *   path="/v1/core/get-currency",
     *   tags={"Core"},
     *   summary="Get Currency",
     *   description="get Currency",
     *   operationId="get-currency",
    
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
     * get driver status
     *
     * @return \Illuminate\Http\Response
    */
    public function getCurrency(Request $request){
        
        try{
            $query = Currency::query();
            $query->select('currencies.*')->where('b2b_allowed_currency','1');
            
            $currencyDetails = $query->paginate($this->perPage);
            
        
        return $this->sendResponse([$currencyDetails], 'Currencies Fetched Successfully');
        
        }catch(Exception $e){
            $success = [];
            return $this->sendError($success,'Something went wrong', ['error'=>$e], 500);
        }
        
    }
    
}
