<?php
/**
 * @package     Operational Data
 * @subpackage  Payment GatewayAttempts
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Payment Gateway.
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use DateTime;
use Intervention\Image\ImageManagerStatic as Image;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Models\AgencyPaymentGateway;

class PaymentGateway extends Model
{

    use HasFactory,LogsActivity;
    protected $guarded = [];
    protected $table = 'core_payment_gateways';
    protected static $logAttributes = ['name', 'description', 'is_active'];
    
    protected static $logName = 'core_payment_gateways';

    
    /* Insert Code - Submit Form*/
    public static function createPayment($requestData)
    {   
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $paymentData = array(
            'name' => $requestData['name'],
            'description' => $requestData['description'],
            'logo' =>'',
            'api_url' =>'',
            'credential' =>'',
            'is_active' => $requestData['status'],
            
        );

        if(isset($requestData['logo'])){
            
            //upload profile image
            try{
                $destinationPath = storage_path().'/app/public/payment-gateway/' ;
                if(!is_dir($destinationPath)){
                /* Directory does not exist, so lets create it. */
                    mkdir($destinationPath, 0777);
                }
                $file = $requestData['logo'] ;
                $fileName =  uniqid().'_'.str_replace(' ','_',$requestData['name']).'_'.time().'.'.$requestData['logo']->extension();
                $file->move($destinationPath,$fileName);
                $url = URL::to('/storage/').'/payment-gateway/'.$fileName;

                $paymentData['logo'] = $url;
            }catch(\Exception $e){
                $return['message'] = 'Error during save image : '.$e->getMessage();
            }
        }
        try{
            DB::beginTransaction();
            $paymentGatewayData = PaymentGateway::create($paymentData);
            
            DB::commit();
            if($paymentGatewayData){
                $return['status'] = 1;
                $return['message'] = 'Payment Gateway ['.$paymentGatewayData['name'].'] Save Successfully';
                $return['data'] = $paymentGatewayData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during save  record : '.$e->getMessage();
        }
        
        return $return;
    }

    /* View Data */ 
    public static function getPayment($option = array()){
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
        'where'=>array(),
        'orWhere'=>array()

    );
    $config = array_merge($data, $option);
    $result = [];
    if($config['id'] != ''){
        try{
            $query = PaymentGateway::query();
            $query->select('core_payment_gateways.*');
            $query->where('id',$config['id']);
           
            $result = $query->first();
        }catch(\Exception $e){
            $return['message'] = $e->getMessage();
        }
    }else{
        try{
            $query = PaymentGateway::query();
            $query->orderBy($config['order_by'],$config['sorting']);
            

            if(!empty($config['where'])){
                foreach($config['where'] AS $where){
                    $query->where($where[0], $where[1], $where[2]);
                }
            }
            if(!empty($config['orWhere'])){
                foreach($config['orWhere'] AS $orWhere){
                    $query->orWhere($orWhere[0],$orWhere[1],$orWhere[2]);
                }
            }

            $result = $query->paginate($config['per_page']);
            $result->setPath('?per_page='.$config['per_page']);
        } catch (\Exception $e){
            $return['message'] = $e->getMessage();
        }
    }
    
    if(!empty($result)){
        $return['status'] = 1;
        $return['message'] = 'Payment Gateway list successfully';
        $return['data'] = $result;
        return $return;
    }else{
        return $return;
    }
}

// Update Record 
public static function updatePayment($requestData){
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try{
             @$paymentData = array(
                    'id' => $requestData['payment_id'],
                    'name' => $requestData['name'],
                    'description' => $requestData['description'],
                    'is_active' => $requestData['status'],
            );
            if(isset($requestData['profile_image'])){
                
                //upload profile image
                try{
                    $destinationPath = storage_path().'/app/public/payment-gateway/' ;
                    if(!is_dir($destinationPath)){
                    /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['profile_image'] ;
                    $fileName =  uniqid().'_'.str_replace(' ','_',$requestData['name']).'_'.time().'.'.$requestData['profile_image']->extension();
                    $file->move($destinationPath,$fileName);
                    $url = URL::to('/storage/').'/payment-gateway/'.$fileName;

                    $paymentData['logo'] = $url;
                    
                    $p = parse_url($requestData['old_photo']);        
                    if ($p['path'] != "") {
                        $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                        $image_path = storage_path($image_path);

                    if (file_exists($image_path)) {
                        unlink($image_path);
                     }
                    }
                }
                catch(\Exception $e){
                    $return['message'] = 'Error during save profile image : '.$e->getMessage();
                }
            }
           
            
            try{
                DB::beginTransaction();
                
                $matchPaymentId = ['id'=>$paymentData['id']];
                $paymeteGatewayData = PaymentGateway::updateOrCreate($matchPaymentId,$paymentData);
                
                DB::commit();
                if($paymeteGatewayData){
                    $return['status'] = 1;
                    $return['message'] = 'Payment Gateway ['.$paymeteGatewayData['name'].'] Update Successfully';
                    $return['data'] = $paymeteGatewayData;
                }
            } catch (\Exception $e){
                $return['message'] = 'Error during save user record : '.$e->getMessage();
            }

        }catch(\Exception $e){
            $return['message'] = 'Something went wrong : '.$e->getMessage();
        }
        return $return;
    }

    /* Delete Record single/multiple  */
    public static function deletePayment($payment_id){
        $is_dependent = PaymentGateway::checkDependancy($payment_id);
        $return = array(
            'status'=>0,
            'message'=>'Something went wrong'
        );
        
        $paymentData = PaymentGateway::where('id',$payment_id)->first()->toArray();
        
        if(!empty($is_dependent)){
            
            //update status to deleted
            PaymentGateway::where('id',$payment_id)->update(['is_active'=>2]);
	        $module_names = implode(', ',$is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Payment Gateway ['.$paymentData['name'].'] exist in ['.$module_names.']. Hence, it can soft deleted';
        }else{
            
            //delete record from table
            $p=parse_url($paymentData['logo']);
            if($p['path']!=""){
                $image_path=str_replace('/storage/','app/public/',$p['path']);
                $image_path=storage_path($image_path);

                if(file_exists($image_path)){
                    unlink($image_path);
                }
            }
            
            PaymentGateway::where('id',$payment_id)->delete();
            $return['status'] = 1;
            $return['message'] = 'User ['.$paymentData['name'].'] deleted successfully';
        }
        
        return $return;
    }
    
    public static function checkDependancy($id){
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
        **/
        $dep_modules = [];
        $agency_payment_gateway_record = AgencyPaymentGateway::where('core_payment_gateway_id', $id)->count();

        if ($agency_payment_gateway_record > 0) {
            array_push($dep_modules, 'AgencyPaymentGateway');
        }
        
        return $dep_modules;
    }

}
