<?php

/**
 * @package     Operational Data
 * @subpackage  Suppliers
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Suppliers.
 */

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

class Suppliers extends Model
{
    use HasFactory;
    protected $table = 'core_suppliers';
    protected $guarded = [];
    protected static $logAttributes = ['name', 'is_active'];
    protected static $logName = 'core_suppliers';

    /*
    * get list or single or all record to display
    */
    public static function getSuppliers($option = array())
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
                $query = Suppliers::query();
                $query->select(
                    'core_suppliers.*',
                    'core_service_types.name as service_name',
                    DB::raw('(CASE WHEN core_suppliers.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_suppliers.is_active = "1" THEN "Active" '
                        . 'END) AS supplier_type_status_text')
                );
                $query->join('core_service_types', 'core_service_types.id', 'core_suppliers.core_service_type_id');
                $query->where('core_suppliers.id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = Suppliers::query();
                $query->select(
                    'core_suppliers.*',
                    'core_service_types.name as service_name',
                    DB::raw('(CASE WHEN core_suppliers.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_suppliers.is_active = "1" THEN "Active" '
                        . 'END) AS suppliers_type_status_text')
                );
                $query->leftjoin('core_service_types', 'core_service_types.id', 'core_suppliers.core_service_type_id');
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
            $return['message'] = 'Suppliers list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /*
    * insert recors in database
    */
    public static function createSupplier($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        if ($_FILES['cover_image']['tmp_name'] == "") {
            $supplierArrayData = array(
                'name' => $requestData['supplier_name'],
                'core_service_type_id' => $requestData['core_service_type_id'],
                'code' => str_replace(' ', '_', strtoupper($requestData['supplier_name'])),
                'is_active' => $requestData['status'],
            );
        } else {
            $supplierArrayData = array(
                'name' => $requestData['supplier_name'],
                'core_service_type_id' => $requestData['core_service_type_id'],
                'cover_image' => $requestData['cover_image'],
                'code' => str_replace(' ', '_', strtoupper($requestData['supplier_name'])),
                'is_active' => $requestData['status'],
            );
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
                $destinationPath = storage_path() . '/app/public/suppliers/';
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777);
                }
                $file = $image_data;
                $image_resize = Image::make($image_data);
                $fileName =  uniqid() . time() .  $extension;
                $image_resize->save($destinationPath . $fileName);
                $url = URL::to('/storage/') . '/suppliers/' . $fileName;
                $supplierArrayData['cover_image'] = $url;
            } catch (Exception $e) {
                $return['message'] = 'Error during save image suppliers :' . $e->getMessage();
            }
        } else if (isset($requestData['cover_image'])) {
            try {
                $destinationPath = storage_path() . '/app/public/suppliers/';
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777);
                }

                $file = $requestData['cover_image'];
                $image_resize = Image::make($requestData['cover_image']);
                $image_resize->resize(300, 300);
                $fileName =  uniqid() . time() . '.' . $requestData['cover_image']->extension();
                $image_resize->save($destinationPath . $fileName);
                $url = URL::to('/storage/') . '/suppliers/' . $fileName;
                $supplierArrayData['cover_image'] = $url;
            } catch (Exception $e) {
                $return['message'] = 'Error during save image banner :' . $e->getMessage();
            }
        }

        try {
            DB::beginTransaction();
            $supplierData = Suppliers::create($supplierArrayData);

            DB::commit();
            if ($supplierData) {
                $return['is_active'] = 1;
                $return['message'] = 'Suppliers [' . $supplierData['name'] . '] save successfully';
                $return['data'] = $supplierData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save home banner record : ' . $e->getMessage();
        }

        return $return;
    }

    /*
    * update record in database
    */
    public static function updateSupplier($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {
            if ($_FILES['cover_image']['tmp_name'] == "") {
                $suppliersDetails = array(
                    'id' => $requestData['supplier_type_id'],
                    'name' => $requestData['supplier_name'],
                    'core_service_type_id' => $requestData['core_service_type_id'],
                    'code' => str_replace(' ', '_', strtoupper($requestData['supplier_name'])),
                    'is_active' => $requestData['status'],
                );
            } else {
                $suppliersDetails = array(
                    'id' => $requestData['supplier_type_id'],
                    'name' => $requestData['supplier_name'],
                    'core_service_type_id' => $requestData['core_service_type_id'],
                    'cover_image' => $requestData['cover_image'],
                    'code' => str_replace(' ', '_', strtoupper($requestData['supplier_name'])),
                    'is_active' => $requestData['status'],
                );
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
                    $destinationPath = storage_path() . '/app/public/suppliers/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $image_data;
                    $image_resize = Image::make($image_data);
                    $fileName =  uniqid() . time() .  $extension;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/suppliers/' . $fileName;
                    $suppliersDetails['cover_image'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image suppliers :' . $e->getMessage();
                }
            } else if (isset($requestData['cover_image'])) {
                try {
                    $destinationPath = storage_path() . '/app/public/suppliers/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['cover_image'];
                    $image_resize = Image::make($requestData['cover_image']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() . '.' . $requestData['cover_image']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/suppliers/' . $fileName;
                    $suppliersDetails['cover_image'] = $url;
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image banner :' . $e->getMessage();
                }
            }
            // echo "<pre>";print_r($suppliersDetails);die;
            try {
                DB::beginTransaction();

                $matchSuppliers = ['id' => $suppliersDetails['id']];
                $supplierData = Suppliers::updateOrCreate($matchSuppliers, $suppliersDetails);

                DB::commit();
                if ($supplierData) {
                    $return['status'] = 1;
                    $return['message'] = 'Suppliers [' . $supplierData['name'] . '] Updated Successfully';
                    $return['data'] = $supplierData;
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
    public static function deleteSupplier($supplier_id)
    {
        $is_dependent = Suppliers::checkDependancy($supplier_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $supplierData = Suppliers::where('id', $supplier_id)->first()->toArray();

        if ($is_dependent) {
            Suppliers::where('id', $supplier_id)->update(['is_active' => 2]);
            $return['status'] = 1;
            $return['message'] = 'Suplliers [' . $supplierData['name'] . '] soft deleted successfully';
        }
        Suppliers::where('id', $supplier_id)->delete();
        $return['status'] = 1;
        $return['message'] = 'Suplliers [' . $supplierData['name'] . '] Deleted successfully';

        return $return;
    }

    public static function checkDependancy($supplier_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        return $dep_modules;
    }
}
