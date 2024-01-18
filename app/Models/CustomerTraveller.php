<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use App\Traits\Uuids;
use Illuminate\Support\Carbon;
use App\Models\Customers;
use Illuminate\Support\Facades\DB;

class CustomerTraveller extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $table = "customers_traveller";
    protected $guarded = [];
    protected $fillable = [
        'customer_id',
        'title',
        'first_name',
        'second_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nationality_id',
        'id_type',
        'id_number',
        'issue_date',
        'expiry_date',
        'country_id',
        'status',
        'document',
    ];

    protected static $logName = 'customers_traveller';

    public function airportName()
    {
        return $this->hasMany('App\Models\AirportI18ns', 'airport_id', 'id');
    }

    public function getCountry()
    {
        return $this->belongsTo('App\Models\Country', 'country_id', 'iso_code')->withTrashed()->with('countryCode');
    }
    public function getNationality()
    {
        return $this->belongsTo('App\Models\Country', 'nationality_id', 'iso_code')->withTrashed()->with('countryCode');
    }

    public function getCity()
    {
        return $this->belongsTo('App\Models\City', 'city_code', 'iso_code')->withTrashed()->with('cityCode');
    }

    /**
     * get list or single or all records to display
     */
    public static function getTravellerData($option = array())
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
            'where' => array(),
            'orWhere' => array()
        );
        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = CustomerTraveller::query();
                $query->withTrashed();
                $query->with('getCountry','getNationality');
                $query->select(
                    "customers_traveller.*",
                );
                $query->where('customers_traveller.id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {

            try {
                $query = CustomerTraveller::query();
                $query->withTrashed();
                $query->with('getCountry','getNationality');
                $query->where('customer_id', $config['customer_id']);
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

                if (!empty($config['order_by'])) {
                    if ($config['order_by'] == 'full_name') {
                        $query->orderBy('first_name', $config['sorting']);
                    }
                    if ($config['order_by'] == 'date_of_birth') {
                        $query->orderBy('date_of_birth', $config['sorting']);
                    }

                    if ($config['order_by'] == 'nationality_id') {
                        $query->join('countries as c', 'customers_traveller.nationality_id', '=', 'c.iso_code')
                            ->join('country_i18ns as ci', 'c.id', '=', 'ci.country_id')
                            ->where('ci.language_code', 'en')
                            ->orderBy('ci.country_name', $config['sorting']);
                    }
                }
                $result = $query->paginate($config['per_page']);
                $result->setPath('?per_page=' . $config['per_page']);
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        }

        if (!empty($result)) {
            $return['status'] = true;
            $return['message'] = 'Traveller list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * insert new record in database
     */
    public static function createTravellers($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        if ($requestData['expiry_date'] == null) {
            $expiry_date = null;
        } else {
            $expiry_date = Carbon::parse($requestData['expiry_date'])->format('Y-m-d');
        }
        try {
            $travellerData = array(
                'customer_id' => $requestData['customer_id'],
                'title' => $requestData['title'],
                'first_name' => ucwords($requestData['first_name']),
                'second_name' => $requestData['second_name'] ? $requestData['second_name'] : '',
                'last_name' => ucwords($requestData['last_name']),
                'date_of_birth' => date('Y-m-d', strtotime($requestData['date_of_birth'])),
                'gender' => $requestData['gender'],
                'nationality_id' => $requestData['nationality_id'],
                'id_type' => $requestData['id_type'],
                'id_number' => $requestData['id_number'],
                'issue_date' => date('Y-m-d', strtotime($requestData['issue_date'])),
                'expiry_date' => date('Y-m-d', strtotime($requestData['expiry_date'])),
                'country_id' => $requestData['country_id'],
                'status' => $requestData['status'],
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
                    $destinationPath = storage_path() . '/app/public/traveller/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $image_data;
                    $image_resize = Image::make($image_data);
                    $fileName =  uniqid() . time() .  $extension;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/traveller/' . $fileName;
                    $travellerData['document'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image suppliers :' . $e->getMessage();
                }
            } else if (isset($requestData['document'])) {
                //upload image
                try {
                    $destinationPath = storage_path() . '/app/public/traveller/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['document'];
                    $image_resize = Image::make($requestData['document']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() .  '.' . $requestData['document']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/traveller/' . $fileName;
                    $travellerData['document'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image suppliers :' . $e->getMessage();
                }
            }
            try {
                DB::beginTransaction();

                $traveller = CustomerTraveller::create($travellerData);
                DB::commit();
                if ($traveller) {

                    $return['status'] = true;
                    $return['message'] = 'Traveller [' . ucwords($traveller->first_name) . ' ' . $traveller->last_name . '] saved successfully';
                    $return['data'] = $traveller;
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
     * update record in database
     */
    public static function updateTraveller($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        if ($requestData['expiry_date'] == null) {
            $expiry_date = null;
        } else {
            $expiry_date = Carbon::parse($requestData['expiry_date'])->format('Y-m-d');
        }
        try {

            $travellerData = array(
                'id' => $requestData['traveller_id'],
                'customer_id' => $requestData['customer_id'],
                'title' => $requestData['title'],
                'first_name' => ucwords($requestData['first_name']),
                'second_name' => $requestData['second_name'] ? $requestData['second_name'] : '',
                'last_name' => ucwords($requestData['last_name']),
                'date_of_birth' => date('Y-m-d', strtotime($requestData['date_of_birth'])),
                'gender' => $requestData['gender'],
                'nationality_id' => $requestData['nationality_id'],
                'id_type' => $requestData['id_type'],
                'id_number' => $requestData['id_number'],
                'issue_date' => date('Y-m-d', strtotime($requestData['issue_date'])),
                'expiry_date' => date('Y-m-d', strtotime($requestData['expiry_date'])),
                'country_id' => $requestData['country_id'],
                'status' => $requestData['status'],
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
                    $destinationPath = storage_path() . '/app/public/traveller/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }

                    $file = $image_data;
                    $image_resize = Image::make($image_data);
                    $fileName =  uniqid() . time() . $extension;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/traveller/' . $fileName;
                    $travellerData['document'] = $url;

                    $p = parse_url($requestData['old_photo']);
                    if ($p['path'] != "") {
                        $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                        $image_path = storage_path($image_path);

                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image traveller :' . $e->getMessage();
                }
            } else if (isset($requestData['document'])) {

                //upload image
                try {

                    $destinationPath = storage_path() . '/app/public/traveller/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }

                    $file = $requestData['document'];
                    $image_resize = Image::make($requestData['document']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() . '.' . $requestData['document']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/traveller/' . $fileName;
                    $travellerData['document'] = $url;

                    if (isset($requestData['old_photo'])) {
                        $p = parse_url($requestData['old_photo']);
                        if ($p['path'] != "") {

                            $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                            $image_path = storage_path($image_path);

                            if (file_exists($image_path)) {
                                unlink($image_path);
                            }
                        }
                    }
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image Traveller :' . $e->getMessage();
                }
            }
            //upload with no-image
            try {

                DB::beginTransaction();
                $matchCustomer = ['id' => $travellerData['id']];
                $traveller = CustomerTraveller::updateOrCreate($matchCustomer, $travellerData);

                DB::commit();
                if ($traveller) {
                    $return['status'] = true;
                    $return['message'] = 'Traveller [' . $traveller->first_name . ' ' . $traveller->last_name . '] update successfully';
                    $return['data'] = $traveller;
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
    public static function deleteTravellers($traveller_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $travellerData = CustomerTraveller::where('id', $traveller_id)->withTrashed()->first()->toArray();

        $is_dependent = CustomerTraveller::checkDependancy($travellerData['customer_id'], $traveller_id);
        if ($is_dependent) {
            //update status to deleted
            CustomerTraveller::where('id', $traveller_id)->delete();
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Traveller [' . $travellerData['first_name'] . ' ' . $travellerData['second_name'] . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {
            CustomerTraveller::where('id', $traveller_id)->forceDelete();

            $return['status'] = 1;
            $return['message'] = 'Traveller [' . $travellerData['first_name'] . ' ' . $travellerData['second_name'] . '] deleted successfully';
        }
        return $return;
    }

    public static function checkDependancy($customer_id, $traveller_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        return $dep_modules;
    }

    /**
     * restore deleted record
     **/
    public static function restoreTravellers($restore_traveller_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $travellerData = CustomerTraveller::withTrashed()->find($restore_traveller_id);
        if ($travellerData) {
            $travellerData->restore();
            $return['status'] = true;
            $return['message'] = 'Traveller [' . $travellerData['first_name'] . ' ' . $travellerData['second_name'] . '] restored successfully';
        }
        return $return;
    }
}
