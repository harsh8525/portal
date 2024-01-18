<?php

/**
 * @package     Prefrences
 * @subpackage  Language
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Language.
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
use Illuminate\Support\Facades\File;
use DB;
use Illuminate\Support\Facades\Hash;

class Language extends Model
{

    use HasFactory, LogsActivity;
    protected $guarded = [];
    protected $table = 'core_languages';
    protected static $logAttributes = ['language_code', 'language_name', 'language_type', 'status'];
    protected static $logName = 'core_languages';



    /*Start Insert Code - Submit Form*/
    public static function createLanguage($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $languageData = array(
            'language_code' => $requestData['language_code'],
            'language_name' => $requestData['language_name'],
            'language_type' => $requestData['language_type'],
            'sort_order' => $requestData['sort_order'],
            'is_default' => $requestData['is_default'],
            'status' => $requestData['status'],

        );
        try {
            DB::beginTransaction();
            if($languageData['is_default'] == 1){
            Language::where('is_default', 1)->update(['is_default' => 0]);      // For is_default status
            }
            $languageAddData = Language::create($languageData);

            DB::commit();
            if ($languageAddData) {
                $return['status'] = 1;
                $return['message'] = 'Language [' . $languageAddData['language_name'] . '] Add Successfully';
                $return['data'] = $languageAddData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save  record : ' . $e->getMessage();
        }

        return $return;
    }
    /* End Insert Code - Submit Form */


    /*Start GetData - Edit*/
    public static function getLanguage($option = array())
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
                $query = Language::query();
                $query->select('core_languages.*');
                $query->where('id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = Language::query();
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
            $return['message'] = 'language list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }
    /*End GetData - Edit*/


    /* Start Update Record */

    public static function updateLanguage($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {
            @$languageData = array(
                'id' => $requestData['language_id'],
                'language_code' => $requestData['language_code'],
                'language_name' => $requestData['language_name'],
                'language_type' => $requestData['language_type'],
                'sort_order' => $requestData['sort_order'],
                'is_default' => $requestData['is_default'],
                'status' => $requestData['status'],
            );
            try {
                DB::beginTransaction();
                if($languageData['is_default'] == 1){
                    Language::where('is_default', 1)->update(['is_default' => 0]);    // For is_default status
                    }
                $matchPaymentId = ['id' => $languageData['id']];
                $languageUpdateData = Language::updateOrCreate($matchPaymentId, $languageData);

                DB::commit();
                if ($languageUpdateData) {
                    $return['status'] = 1;
                    $return['message'] = 'Language [' . $languageUpdateData['language_name'] . '] Update Successfully';
                    $return['data'] = $languageUpdateData;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during save language record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }
    /* End of Update Record */

    /*Start Delete Record single/multiple  */
    public function deleteLanguage($language_id)
    {
        $is_dependent = Language::checkDependancy($language_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $languageData = Language::where('id', $language_id)->first()->toArray();
        $langCode = Language::where('id', $language_id)->value('language_code');
        $path = resource_path('lang/' . $langCode);
        
        if (file_exists($path)) {

            File::deleteDirectory($path); // Delete Lang folder
            
            $return['status'] = 1;
            $return['message'] = 'Language [' . $languageData['language_name'] . '] deleted successfully';
        }else{
            Language::where('id', $language_id)->delete();
            $return['status'] = 1;
            $return['message'] = 'Language [' . $languageData['language_name'] . '] deleted successfully';
        }
        return $return;
    }
    /*End Delete Record single/multiple  */

    public function checkDependancy($id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        return $dep_modules;
    }
}
