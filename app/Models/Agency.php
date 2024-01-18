<?php


/**
 * @package     Dashboard
 * @subpackage  Agency
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Dashboard.
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
use App\Models\Setting;
use App\Models\AgencyPaymentType;
use App\Models\AgencyServiceType;
use App\Models\AgencyPaymentGateway;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
class Agency extends Model
{
    use HasFactory,LogsActivity;
    protected $table = 'agencies';
    
    protected $guarded = [];
    protected static $logAttributes = ['core_agency_type_id', 'parent_id', 'agency_id','core_supplier_id','full_name','short_name','contact_person_name','license_number','phone_no','fax_no','email','web_link',
                                        'is_stop_buy','is_search_only','is_cancel_right','status'];
    
    protected static $logName = 'agencies';
    public function agencyServiceTypes()
    {
        return $this->hasMany('App\Models\AgencyServiceType','agency_id');   
    }
    public function agencyPaymentTypes()
    {
        return $this->hasMany('App\Models\AgencyPaymentType','agency_id');   
    }
    public function agencyPaymentGateway()
    {
        return $this->hasMany('App\Models\AgencyPaymentGateway','agency_id');   
    }
    public function agencyAddress()
    {
        return $this->hasOne('App\Models\AgencyAddress','agency_id');   
    }
    public function agencyEnableCurrencies()
    {
        return $this->hasMany('App\Models\AgencyCurrency','agency_id');   
    }

    public function getMasterUserAgencyReport()
    {
        return $this->hasMany('App\Models\User','agency_id','id');   
    }
    public function getAgencyType()
    {
        return $this->hasMany('App\Models\AgencyType','id','core_agency_type_id');   
    }
    /**
     * get list or single or all record to display
     */

    public static function getAgency($option = array())
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
            'where'=>array(),
            'orWhere'=>array()
        );
        
        $config = array_merge($data, $option);
        $result = [];
        if($config['id'] != ''){
             
            try{
                $query = Agency::query();

                $query->select('agencies.*','users.id as user_id','users.name as user_full_name','users.email as user_email','users.mobile as user_mobile',
                        DB::raw('(CASE WHEN agencies.status = "inactive" THEN "In-Active" '
                        . 'WHEN agencies.status = "active" THEN "Active" '
                        . 'WHEN agencies.status = "terminated" THEN "Terminated" '
                        . 'END) AS agency_status_text'),
                        DB::raw("(SELECT name FROM core_agency_types WHERE core_agency_types.id=agencies.core_agency_type_id) as agency_type_name"));
                $query->join('users','users.agency_id','agencies.id');
                $query->with(['agencyEnableCurrencies','agencyAddress']);
                $query->where('agencies.id',$config['id']);
            
                $result = $query->first();
                
            }catch(\Exception $e){
                $return['message'] = $e->getMessage();
            }
        }else{
            try{
                $query = Agency::query();
                $query->select('agencies.*',
                        DB::raw('(CASE WHEN agencies.status = "inactive" THEN "In-Active" '
                        . 'WHEN agencies.status = "active" THEN "Active" '
                        . 'WHEN agencies.status = "terminated" THEN "Terminated" '
                        . 'END) AS agency_status_text'),
                    DB::raw("(SELECT name FROM core_agency_types WHERE core_agency_types.id=agencies.core_agency_type_id) as agency_type_name"));
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
            $return['message'] = 'Agency list successfully';
            $return['data'] = $result;
            return $return;
        }else{
            return $return;
        }
    }
    /**
     * insert new record into database
     */
    public static function createAgency($requestData)
    {
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        //generate randomly 5 digits each time
        $randomString = '';
        $characters = '0123456789';
        $charactersLength = strlen($characters);

        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $randomString;
        //genrate reandom value of agency id using sitename and unique number
        $getSiteName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
        $words = explode(" ", $getSiteName); // Split the string into an array of words
  
        if (count($words) >= 2) {
            $firstCharWord1 = substr($words[0], 0, 1); // First word, first character
            $firstCharWord2 = substr($words[1], 0, 1); // Second word, first character
            
            $agency_id =  strtoupper($firstCharWord1 . $firstCharWord2).'-'.$randomString;
        } elseif (count($words) === 1) {
            $agency_id = strtoupper(substr($words[0], 0, 2)).'-'.$randomString; // Single word, first two characters
        } 
            
        $agencyGeneralInfoData = array(
            'agency_id'=> $agency_id,
            'parent_id'=> '0',
            'core_agency_type_id' => $requestData['agency_type_id'],
            'full_name' => $requestData['agency_name'],
            'short_name' => $requestData['short_name'],
            'contact_person_name' => $requestData['contact'],
            'designation' => $requestData['position'],
            'license_number' => $requestData['license_number'],
            'phone_no' => $requestData['phone_no'],
            'fax_no' => $requestData['fax_no'],
            'email' => $requestData['email'],
            'web_link' => $requestData['web_url'],
            'iata_number' => isset($requestData['iata_number']) ? $requestData['iata_number'] : "",
            'is_stop_buy' => isset($requestData['stop_by']) ? '1' : '0',
            'is_search_only' => isset($requestData['search_only']) ? '1' : '0',
            'is_cancel_right' => isset($requestData['cancel_right']) ? '1' : '0',
            'status' => $requestData['status'],
            
            
        );
        //upload image into folder and pass URL into data array 
        if(isset($requestData['agency_logo'])){
            
            //upload agency logo
            try{
                $destinationPath = storage_path().'/app/public/agency/' ;
                if(!is_dir($destinationPath)){
                /* Directory does not exist, so lets create it. */
                    mkdir($destinationPath, 0777);
                }
                $file = $requestData['agency_logo'];
                $image_resize = Image::make($requestData['agency_logo']);    
                $image_resize->resize(300, 300);
                
               
                $fileName =  uniqid().'_'.str_replace(' ','_',$requestData['agency_name']).'_'.time().'.'.$requestData['agency_logo']->extension();
                $image_resize->save($destinationPath.$fileName);
                $url = URL::to('/storage/').'/agency/'.$fileName;

                $agencyGeneralInfoData['logo'] = $url;
            }catch(\Exception $e){
                $return['message'] = 'Error during save agency logo: '.$e->getMessage();
            }
        }
        try{
            DB::beginTransaction();
               
            $agencyData = Agency::create($agencyGeneralInfoData);
            //insert new record with supplier agency name
            $agencyTypeName = AgencyType::where('id',$requestData['agency_type_id'])->value('code');
            if($agencyTypeName == 'SUPPLIER')
            {
                $checkIsExist = Suppliers::where('name','agency_name')->get()->toArray();
                if(empty($checkIsExist))
                {   
                    Suppliers::create([
                        'name'=>$requestData['agency_name'],
                        'description'=>$requestData['agency_name'],
                    ]);
                }
            }
            
            DB::commit();
            if($agencyData){
                $return['status'] = 1;
                $return['message'] = 'Agency ['.$agencyData['full_name'].'] save successfully';
                $return['data'] = $agencyData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during save Agency record : '.$e->getMessage();
        }
        
        
        return $return;
    }

    /**
     * update exisiting record into database
     */
    public static function updateAgency($requestData)
    {
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $agencyGeneralInfoData = array(
            'id' => $requestData['agency_id'],
            'parent_id'=> '0',
            'full_name' => $requestData['agency_name'],
            'short_name' => $requestData['short_name'],
            'contact_person_name' => $requestData['contact'],
            'designation' => $requestData['position'],
            'license_number' => $requestData['license_number'],
            'phone_no' => $requestData['phone_no'],
            'fax_no' => $requestData['fax_no'],
            'email' => $requestData['email'],
            'web_link' => $requestData['web_url'],
            'iata_number' => isset($requestData['iata_number']) ? $requestData['iata_number'] : "",
            'is_stop_buy' => isset($requestData['stop_by']) ? '1' : '0',
            'is_search_only' => isset($requestData['search_only']) ? '1' : '0',
            'is_cancel_right' => isset($requestData['cancel_right']) ? '1' : '0',
            'status' => $requestData['status'],
            
            
        );
        
        //upload image into folder and pass URL into data array 
        if(isset($requestData['agency_logo'])){
            
            //upload agency logo
            try{
                $destinationPath = storage_path().'/app/public/agency/' ;
                if(!is_dir($destinationPath)){
                /* Directory does not exist, so lets create it. */
                    mkdir($destinationPath, 0777);
                }
                $file = $requestData['agency_logo'];
                $image_resize = Image::make($requestData['agency_logo']);    
                $image_resize->resize(300, 300);
                
               
                $fileName =  uniqid().'_'.str_replace(' ','_',$requestData['agency_name']).'_'.time().'.'.$requestData['agency_logo']->extension();
                $image_resize->save($destinationPath.$fileName);
                $url = URL::to('/storage/').'/agency/'.$fileName;

                $agencyGeneralInfoData['logo'] = $url;

                //delete image from folder
                $p = parse_url($requestData['old_logo']);        
                if ($p['path'] != "") {
                    $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                    $image_path = storage_path($image_path);

                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }
            }catch(\Exception $e){
                $return['message'] = 'Error during save agency logo: '.$e->getMessage();
            }
        }
        try{
            DB::beginTransaction();
            $matchAgency = ['id'=>$agencyGeneralInfoData['id']];    
            $agencyData = Agency::updateOrCreate($matchAgency,$agencyGeneralInfoData);
            $agencyType = AgencyType::where('id',$agencyData['core_agency_type_id'])->value('code');
            if($agencyType == 'SUPPLIER')
            {
                AgencyPaymentType::where('agency_id',$agencyGeneralInfoData['id'])->delete();
                AgencyServiceType::where('agency_id',$agencyGeneralInfoData['id'])->delete();
                AgencyPaymentGateway::where('agency_id',$agencyGeneralInfoData['id'])->delete();
            }
            DB::commit();
            if($agencyData){
                $return['status'] = 1;
                $return['message'] = 'Agency ['.$agencyData['full_name'].'] updated successfully';
                $return['data'] = $agencyData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during save agency record : '.$e->getMessage();
        }
        
        
        return $return;
    }

    /**
     * created function to delete record form tables
     */
    public function deleteAgency($agency_id){
        
        $is_dependent = Agency::checkDependancy($agency_id);
        $return = array(
            'status'=>0,
            'message'=>'Something went wrong'
        );
        
        $agencyData = Agency::where('id',$agency_id)->first();
        if($is_dependent){
            //update status to deleted
            $agencyName = Agency::where('id',$agency_id)->value('full_name');
            Agency::where('id', $agency_id)->update(['status' => 2]);
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Agency type ['.$agencyName.'] exist in ['.$module_names.']. Hence, it can soft deleted';
            
        }else{
            //delete record from table
            $agencyName = Agency::where('id',$agency_id)->value('full_name');
            Agency::where('id',$agency_id)->delete();
            AgencyCurrency::where('agency_id',$agency_id)->delete();
            AgencyPaymentGateway::where('agency_id',$agency_id)->delete();
            AgencyAddress::where('agency_id',$agency_id)->delete();
            AgencyPaymentType::where('agency_id',$agency_id)->delete();
            AgencyServiceType::where('agency_id',$agency_id)->delete();
            User::where('agency_id',$agency_id)->delete();
            $return['status'] = 1;
            $return['message'] = 'Agency ['.$agencyName.'] deleted successfully';
        }
        
        return $return;
    }
    public function checkDependancy($agency_id){
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
        **/
        $dep_modules = [];

        $user_record = User::where('agency_id', $agency_id)->count();
        if ($user_record > 0) {
            array_push($dep_modules, 'User');
        }
        
        return $dep_modules;
    }

    /**
     * created function to fetch record form tables for Agency Report
     */
    static public function getMasterAgencyReport($option = array(), $pagination = true, $getTotalPrice = false)
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
        $query = Agency::query();
        $query->with(['getMasterUserAgencyReport'=>function($q){
            $q->where('primary_user',1);
        },'getAgencyType'])->select(
            "agencies.*"
        );

        if (!empty($config['dates'])) {
            foreach ($config['dates'] as $dates) {
                $query->whereDate('created_at', '>=',$dates[0])->whereDate('created_at','<=', $dates[1]);
             
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
    
}
