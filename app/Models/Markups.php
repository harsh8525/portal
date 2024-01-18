<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MarkupsAirline;
use App\Models\MarkupsChannel;
use App\Models\MarkupsSupplier;
use App\Models\MarkupsAgent;
use App\Models\ServiceType;
use App\Models\Country;
use App\Models\Agency;
use App\Traits\Uuids;
use DateTime;

class Markups extends Model
{
    use HasFactory, LogsActivity, Uuids, SoftDeletes;
    protected $table = 'markups';

    protected $guarded = [];

    protected static $logName = 'markups';
    protected $fillable = [
        'rule_name',
        'service_type_id',
        'destination_name',
        'destination_criteria',
        'origin_name',
        'origin_criteria',
        'from_booking_date',
        'to_booking_date',
        'from_travel_date',
        'to_travel_date',
        'booking_class',
        'cabin_class',
        'trip_type',
        'pax_type',
        'from_price_range',
        'to_price_range',
        'fare_type',
        'b2c_markup_type',
        'b2c_markup',
        'b2b_markup_type',
        'b2b_markup',
        'comm_markup_on',
        'priority',
    ];

    protected $dates = ['deleted_at'];

    public function getServiceType()
    {
        return $this->belongsTo('App\Models\ServiceType', 'service_type_id');
    }

    public function getOriginCountry()
    {
        return $this->belongsTo('App\Models\Country', 'origin_name', 'iso_code');
    }
    public function getOriginCity()
    {
        return $this->belongsTo('App\Models\City', 'origin_name', 'iso_code');
    }
    public function getOriginAirport()
    {
        return $this->belongsTo('App\Models\Airport', 'origin_name', 'id');
    }
    public function getDestinationCountry()
    {
        return $this->belongsTo('App\Models\Country', 'destination_name', 'iso_code');
    }
    public function getDestinationCity()
    {
        return $this->belongsTo('App\Models\City', 'destination_name', 'iso_code');
    }
    public function getDestinationAirport()
    {
        return $this->belongsTo('App\Models\Airport', 'destination_name', 'id');
    }
    public function getAirline()
    {
        return $this->hasMany('App\Models\MarkupsAirline', 'markups_id');
    }

    public function getSupplier()
    {
        return $this->hasMany('App\Models\MarkupsSupplier', 'markups_id');
    }

    public function getChannel()
    {
        return $this->hasMany('App\Models\MarkupsChannel', 'markups_id');
    }

    public function getAgent()
    {
        return $this->hasMany('App\Models\MarkupsAgent', 'markups_id')->with('getAgentName');
    }

    /**
     * get list or single or all records to display
     */
    public static function getMarkupsData($option = array())
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
                $query = Markups::query();
                $query->with(['getServiceType', 'getChannel', 'getOriginCountry.countryCode', 'getOriginCity.cityCode', 'getOriginAirport.AirportName', 'getDestinationCountry.countryCode', 'getDestinationCity.cityCode', 'getDestinationAirport.AirportName', 'getAirline.getMarkupsAirline', 'getSupplier.getMarkupsSupplier', 'getAgent']);
                $query->select(
                    'markups.*'
                );
                $query->where('id', $config['id']);
                $query->where('service_type_id', $config['service_type_id']);
                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
             

                $query = Markups::query();
                $query->with(['getServiceType', 'getChannel', 'getOriginCountry.countryCode', 'getOriginCity.cityCode', 'getOriginAirport.AirportName', 'getDestinationCountry.countryCode', 'getDestinationCity.cityCode', 'getDestinationAirport.AirportName', 'getAirline.getMarkupsAirline', 'getSupplier.getMarkupsSupplier', 'getAgent']);
                
                $query->orderBy($config['order_by'], $config['sorting']);

                if (!empty($config['where'])) {
                    foreach ($config['where'] as $where) {
                        $query->where('markups.' . $where[0], $where[1], $where[2]);
                    }
                }
                if (isset($config['whereHas'])) {
                    $query->whereHas('getChannel', function ($channelQuery) use ($config) {
                        $channelQuery->where('channel', $config['whereHas'][2]);
                    });
                }
                if (!empty($config['orWhere'])) {
                    foreach ($config['orWhere'] as $orWhere) {
                        $query->orWhere($orWhere[0], $orWhere[1], $orWhere[2]);
                    }
                }
                $query->where('service_type_id', $config['service_type_id']);
                $result = $query->paginate($config['per_page']);
                $result->setPath('?per_page=' . $config['per_page']);
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        }

        if (!empty($result)) {
            $return['status'] = 1;
            $return['message'] = 'Markups list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * insert record in database
     */
    public static function createMarkups($requestData)
    {
        // echo "<pre>";print_r($requestData);die;
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        //Check Duplicate record exits
        $checkDuplicateData = Markups::where('service_type_id', $requestData['service_type_id'])
            ->whereDate('from_booking_date', Carbon::parse($requestData['fromBookingDate'])->format('Y-m-d'))
            ->whereDate('to_booking_date', Carbon::parse($requestData['toBookingDate'])->format('Y-m-d'))
            ->first();

        if ($checkDuplicateData) {
            $return['status'] = 0;
            $return['message'] = 'Duplicate Data Already Exists In Markups List.';
            $return['data'] = $checkDuplicateData;
        } else {
            $markupsArrayData = array(
                'rule_name' => $requestData['ruleName'],
                'service_type_id' => $requestData['service_type_id'],
                'destination_name' => $requestData['destinationName'],
                'destination_criteria' => $requestData['destinationCriteria'],
                'origin_name' => $requestData['originName'],
                'origin_criteria' => $requestData['originCriteria'],
                'from_booking_date' => Carbon::createFromFormat('d-m-Y', $requestData['fromBookingDate'])->format('Y-m-d'),
                'to_booking_date' => Carbon::createFromFormat('d-m-Y', $requestData['toBookingDate'])->format('Y-m-d'),
                'from_travel_date' => Carbon::createFromFormat('d-m-Y', $requestData['fromTravelDate'])->format('Y-m-d'),
                'to_travel_date' => Carbon::createFromFormat('d-m-Y', $requestData['toTravelDate'])->format('Y-m-d'),
                'booking_class' => $requestData['bookingClass'],
                'cabin_class' => $requestData['cabinClass'],
                'trip_type' => $requestData['tripType'],
                'pax_type' => $requestData['paxType'],
                'from_price_range' => $requestData['from_price_range'],
                'to_price_range' => $requestData['to_price_range'],
                'fare_type' => $requestData['fareType'],
                'b2c_markup_type' => $requestData['b2c_markup_type'],
                'b2c_markup' => $requestData['b2c_markup'],
                'b2b_markup_type' => $requestData['b2b_markup_type'],
                'b2b_markup' => $requestData['b2b_markup'],
                'comm_markup_on' => $requestData['commMarkupOn'],
                'priority' => $requestData['priority'],
            );
            
            try {
                DB::beginTransaction();
                $markupsArrayData = Markups::create($markupsArrayData);

                $markupsId = $markupsArrayData->id;// Get the last inserted ID

                //insert multiple channel flow
                foreach ($requestData['channel'] as $channel) {
                    $MarkupChannelData = array('markups_id' => $markupsId, 'channel' => $channel);
                    MarkupsChannel::create($MarkupChannelData);
                }

                //insert multiple supplier flow
                foreach ($requestData['supplier'] as $supplier) {
                    $MarkupSupplierData = array('markups_id' => $markupsId, 'supplier_id' => $supplier);
                    MarkupsSupplier::create($MarkupSupplierData);
                }

                //insert multiple airline flow
                foreach ($requestData['airlines'] as $airline) {
                    $getAirlineDetail = Airline::where('id', $airline)->first();

                    $airline_code = $getAirlineDetail['airline_code'];
                    $MarkupsAirlineData = array('markups_id' => $markupsId, 'airline_id' => $airline, 'airline_code' => $airline_code);
                    MarkupsAirline::create($MarkupsAirlineData);
                }

                //insert multiple agent flow
                if (isset($requestData['agency']) && $requestData['agency'] != '') {
                    foreach ($requestData['agency'] as $agency) {
                        $getAgencyDetail = Agency::where('id', $agency)->first();

                        $MarkupsAgentData = array('markups_id' => $markupsId, 'agency_id' => $agency);
                        MarkupsAgent::create($MarkupsAgentData);
                    }
                }

                DB::commit();
                if ($markupsArrayData) {
                    $return['status'] = 1;
                    $return['message'] = 'Markups ['.$markupsArrayData['rule_name'].'] saved successfully';
                    $return['data'] = $markupsArrayData;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during save Markups : ' . $e->getMessage();
            }
        }

        return $return;
    }

    /**
     * update record in database
     */
    public static function updateMarkups($requestData)
    {
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {
            $markupsArrayData = array(
                'id' => $requestData['markups_id'],
                'rule_name' => $requestData['ruleName'],
                'service_type_id' => $requestData['service_type_id'],
                'destination_name' => $requestData['destinationName'],
                'destination_criteria' => $requestData['destinationCriteria'],
                'origin_name' => $requestData['originName'],
                'origin_criteria' => $requestData['originCriteria'],
                'from_booking_date' => Carbon::parse($requestData['fromBookingDate'])->format('Y-m-d'),
                'to_booking_date' => Carbon::parse($requestData['toBookingDate'])->format('Y-m-d'),
                'from_travel_date' => Carbon::parse($requestData['fromTravelDate'])->format('Y-m-d'),
                'to_travel_date' => Carbon::parse($requestData['toTravelDate'])->format('Y-m-d'),
                'booking_class' => $requestData['bookingClass'],
                'cabin_class' => $requestData['cabinClass'],
                'trip_type' => $requestData['tripType'],
                'pax_type' => $requestData['paxType'],
                'from_price_range' => $requestData['from_price_range'],
                'to_price_range' => $requestData['to_price_range'],
                'fare_type' => $requestData['fareType'],
                'b2c_markup_type' => $requestData['b2c_markup_type'],
                'b2c_markup' => $requestData['b2c_markup'],
                'b2b_markup_type' => $requestData['b2b_markup_type'],
                'b2b_markup' => $requestData['b2b_markup'],
                'comm_markup_on' => $requestData['commMarkupOn'],
                'priority' => $requestData['priority'],
            );
            try {

                DB::beginTransaction();
                $matchMarkups = ['id' => $markupsArrayData['id']];
                $markups = Markups::updateOrCreate($matchMarkups, $markupsArrayData);


                //insert multiple channel flow
                MarkupsChannel::where('markups_id', $markupsArrayData['id'])->delete();
                foreach ($requestData['channel'] as $channel) {
                    $MarkupChannelData = array('markups_id' => $markupsArrayData['id'], 'channel' => $channel);
                    MarkupsChannel::create($MarkupChannelData);
                }

                //insert multiple supplier flow
                MarkupsSupplier::where('markups_id', $markupsArrayData['id'])->delete();
                foreach ($requestData['supplier'] as $supplier) {
                    $MarkupSupplierData = array('markups_id' => $markupsArrayData['id'], 'supplier_id' => $supplier);
                    MarkupsSupplier::create($MarkupSupplierData);
                }

                //insert multiple airline flow
                MarkupsAirline::where('markups_id', $markupsArrayData['id'])->delete();
                foreach ($requestData['airlines'] as $airline) {
                    $getAirlineDetail = Airline::where('id', $airline)->first();
                    $airline_id = $getAirlineDetail['id'];
                    $airline_code = $getAirlineDetail['airline_code'];

                    $MarkupsAirlineData = array('markups_id' => $markupsArrayData['id'], 'airline_id' => $airline, 'airline_code' => $airline_code);
                    MarkupsAirline::create($MarkupsAirlineData);
                }

                //insert multiple agent flow
                MarkupsAgent::where('markups_id', $markupsArrayData['id'])->delete();
                if (isset($requestData['agency']) && $requestData['agency'] != '') {
                    foreach ($requestData['agency'] as $agency) {

                        $MarkupsAgentData = array('markups_id' => $markupsArrayData['id'], 'agency_id' => $agency);
                        MarkupsAgent::create($MarkupsAgentData);
                    }
                }

                DB::commit();
                if ($markups) {
                    $return['status'] = 1;
                    $return['message'] = 'Markups ['.$markups['rule_name'].'] Updated Successfully';
                    $return['data'] = $markups;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during update Markups : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    /**
     * delete record fron database
     */
    public static function deleteMarkups($markups_id)
    {
        $is_dependent = Markups::checkDependancy($markups_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );
        $markups = Markups::where('id', $markups_id)->first()->toArray();
        if ($is_dependent) {
            //update status to deleted
            Markups::where('id', $markups_id)->delete();
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Markups exist. Hence, it can soft deleted';
        } else {
            Markups::where('id', $markups_id)->forceDelete();

            $return['status'] = 1;
            $return['message'] = 'Markups ['.$markups['rule_name'].'] deleted successfully';
        }

        return $return;
    }
    public static function checkDependancy($markups_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        return $dep_modules;
    }
}
