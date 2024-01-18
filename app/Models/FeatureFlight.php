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
use DateTime;
use DB;
use Illuminate\Support\Facades\Hash;

class FeatureFlight extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'featured_flights';

    protected $guarded = [];

    protected static $logName = 'featured_flights';
    protected $fillable = ['airline_code', 'from_airport_code', 'to_airport_code', 'location_image', 'price', 'status'];


    public function getAirline()
    {
        return $this->belongsTo('App\Models\Airline', 'airline_code', 'airline_code')->withTrashed()->with('airlineCodeName');
    }

    public function getFromAirport()
    {
        return $this->belongsTo('App\Models\Airport', 'from_airport_code', 'iata_code')->withTrashed()->with(['airportName','getCountry','getCity']);
    }

    public function getToAirport()
    {
        return $this->belongsTo('App\Models\Airport', 'to_airport_code', 'iata_code')->withTrashed()->with(['airportName','getCountry','getCity']);
    }

    /**
     * ger list or single or all records to display
     */
    public static function getFeatureFlightType($option = array())
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
                $query = FeatureFlight::query();
                $query->with(['getAirline', 'getFromAirport', 'getToAirport']);
                $query->select(
                    'featured_flights.*',
                    DB::raw('(CASE WHEN featured_flights.status = "0" THEN "In-Active" '
                        . 'WHEN featured_flights.status = "1" THEN "Active" '
                        . 'END) AS featured_flights_status_text')
                );
                $query->where('featured_flights.id', $config['id']);
                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = FeatureFlight::query();
                $query->with(['getAirline', 'getFromAirport', 'getToAirport']);
                $query->select(
                    'featured_flights.*',
                    DB::raw('(CASE WHEN featured_flights.status = "0" THEN "In-Active" '
                        . 'WHEN featured_flights.status = "1" THEN "Active" '
                        . 'END) AS featured_flights_status_text')
                );
                $query->orderBy($config['order_by'], $config['sorting']);

                if (!empty($config['where'])) {
                    foreach ($config['where'] as $where) {
                        $query->where('featured_flights.' . $where[0], $where[1], $where[2]);
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
            $return['message'] = 'Featured Flights list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    public static function createFeatureFlightType($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        $featureflightArrayData = array(
            'airline_code' => $requestData['airline_code'],
            'from_airport_code' => $requestData['from_airport_code'],
            'to_airport_code' => $requestData['to_airport_code'],
            'price' => $requestData['price'],
            'status' => $requestData['status'],
        );
        if (isset($requestData['location_image'])) {
            //upload image
            try {
                $destinationPath = storage_path() . '/app/public/feature-flight/';
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777);
                }

                $file = $requestData['location_image'];
                $image_resize = Image::make($requestData['location_image']);
                $image_resize->resize(300, 300);
                $fileName =  uniqid() . time() . '.' . $requestData['location_image']->extension();
                $image_resize->save($destinationPath . $fileName);
                $url = URL::to('/storage/') . '/feature-flight/' . $fileName;
                $featureflightArrayData['location_image'] = $url;
            } catch (Exception $e) {
                $return['message'] = 'Error during save image banner :' . $e->getMessage();
            }
        }
        try {
            DB::beginTransaction();
            $featureflightTypeData = FeatureFlight::create($featureflightArrayData);

            DB::commit();
            $airportNameId = Airline::where('airline_code', $featureflightTypeData['airline_code'])->value('id');
            $airlineName = AirlineI18ns::select('airline_name')->where('airline_id', $airportNameId)->get()->toArray();
            foreach ($airlineName as $key => $name) {
                $airlinemsg[] = $name['airline_name'];
            }
            if ($featureflightTypeData) {
                $return['status'] = 1;
                $return['message'] = 'Featured Flights [' . implode(', ', $airlinemsg) . '] save successfully';
                $return['data'] = $featureflightTypeData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save Featured Flights : ' . $e->getMessage();
        }

        return $return;
    }
    public static function updateFeatureFlightType($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {

            @$featureflightTypeDetails = array(
                'id' => $requestData['feature_flight_id'],
                'airline_code' => $requestData['airline_code'],
                'from_airport_code' => $requestData['from_airport_code'],
                'to_airport_code' => $requestData['to_airport_code'],
                'price' => $requestData['price'],
                'status' => $requestData['status'],
                'location_image' => $requestData['old_image'],
            );
            if (isset($requestData['location_image'])) {
                try {
                    $destinationPath = storage_path() . '/app/public/feature-flight/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }

                    $file = $requestData['location_image'];
                    $image_resize = Image::make($requestData['location_image']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() . '.' . $requestData['location_image']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/feature-flight/' . $fileName;
                    $featureflightTypeDetails['location_image'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during update Featured Flights :' . $e->getMessage();
                }
            }
            try {

                DB::beginTransaction();
                $matchFeatureflightType = ['id' => $featureflightTypeDetails['id']];
                $FeatureflightType = FeatureFlight::updateOrCreate($matchFeatureflightType, $featureflightTypeDetails);
            
                DB::commit();
                $airportNameId = Airline::where('airline_code', $FeatureflightType['airline_code'])->value('id');
                $airlineName = AirlineI18ns::select('airline_name')->where('airline_id', $airportNameId)->get()->toArray();
                foreach ($airlineName as $key => $name) {
                    $airlinemsg[] = $name['airline_name'];
                }
                if ($FeatureflightType) {
                    $return['status'] = 1;
                    $return['message'] = 'Featured Flights  [' . implode(',', $airlinemsg) . '] Update Successfully';
                    $return['data'] = $FeatureflightType;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during update Featured Flights : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    public static function deleteFeatureFlight($feature_flight_id)
    {

        $is_dependent = FeatureFlight::checkDependancy($feature_flight_id);
     
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );
        $FeatureflightType = FeatureFlight::where('id', $feature_flight_id)->first()->toArray();
   
        $airportNameId = Airline::where('airline_code', $FeatureflightType['airline_code'])->value('id');
        $airlineName = AirlineI18ns::select('airline_name')->where('airline_id', $airportNameId)->get()->toArray();
        foreach ($airlineName as $key => $name) {
            $airlinemsg[] = $name['airline_name'];
        }
        if (($is_dependent)) { 
            //update status to deleted
            FeatureFlight::where('id', $feature_flight_id)->update(['status' => 2]);
            $return['status'] = 1;
            $return['message'] = 'Featured Flight Type  [' . implode(',', $airlinemsg) . ']  soft deleted successfully';
        }

        FeatureFlight::where('id', $feature_flight_id)->delete();
        $return['status'] = 1;
        $return['message'] = 'Featured Flight  [' . implode(',', $airlinemsg) . '] deleted successfully';

        return $return;
    }
    public static function checkDependancy($feature_flight_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        return $dep_modules;
    }
}
