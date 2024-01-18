<?php

/**
 * @package     Settings
 * @subpackage  SMS Template 
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Settings.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\SmsTemplateI18ns;
use App\Traits\Uuids;
use DateTime;

class SmsTemplate extends Model
{
    use HasFactory, Uuids;
    public function smsCodeName()
    {
        return $this->hasMany('App\Models\SmsTemplateI18ns', 'sms_id', 'id');
    }
    protected $guarded = [];
    protected static $logAttributes = ['code', 'to_phone_no'];
    protected static $logName = 'sms_templates';

    /*
    * get list or single or all record to display
    */
    public static function getSmsTemplateData($option = array())
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
                $query = SmsTemplate::query();
                $query->where('id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = SmsTemplate::query();
                $query->with(['smsCodeName']);
                $query->select(
                    "sms_templates.*"
                );

                if ($config['order_by'] == 'name') {
                    $query->join('sms_template_i18ns', 'sms_templates.id', '=', 'sms_template_i18ns.sms_id')
                        ->where('sms_template_i18ns.language_code', 'en')
                        ->orderBy('sms_template_i18ns.name', $config['sorting']);
                }

                if (!empty($config['where'])) {
                    foreach ($config['where'] as $where) {
                        $query->whereHas('smsCodeName', function ($q) use ($where) {
                            $q->where($where[0], $where[1], $where[2]);
                        });
                    }
                }
                if (!empty($config['where'])) {
                    foreach ($config['where'] as $orWhere) {
                        $query->whereHas('smsCodeName', function ($q) use ($orWhere) {
                            $q->where($orWhere[0], $orWhere[1], $orWhere[2]);
                        });
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
            $return['message'] = 'SMS Template list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /*
    * update record in database
    */
    public static function updateSmsTemplate($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {
            @$smsTemplateDetails = array(
                'id' => $requestData['sms_template_id'],
                'code' => $requestData['code'],
            );
            try {
                DB::beginTransaction();

                $matchSmsTemplateType = ['id' => $smsTemplateDetails['id']];
                $smsTemplateData = SmsTemplate::updateOrCreate($matchSmsTemplateType, $smsTemplateDetails);

                if ($smsTemplateData) {

                    $smsNames['sms'] = $requestData['sms'];
                    foreach ($smsNames['sms'] as $key => $name) {
                        $nameData = array(
                            'sms_id' => $smsTemplateData->id,
                            'name' => $name['name'],
                            'content' => $name['content'],
                            'language_code' => $name['language_code'],
                        );
                        $matchpageData = ['id' => $name['smsTemplate_i18ns_id']];
                        SmsTemplateI18ns::updateOrCreate($matchpageData, $nameData);
                        $pagemsg[] = $name['name'];
                    }
                }
                DB::commit();
                if ($smsTemplateData) {
                    $return['status'] = 1;
                    $return['message'] = 'Sms [' . implode(', ', $pagemsg) . '] updated Successfully';
                    $return['data'] = $smsTemplateData;
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
    * insert data from seeder
    */
    public static function createSeederSmsTemplates($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $smsData = array(
                'code'  => $requestData['code'],
                'to_phone_no'  => $requestData['to_phone_no']

            );
            try {
                DB::beginTransaction();
                $smsDetails = SmsTemplate::create($smsData);


                if ($smsDetails) {

                    $pagesData = $requestData['sms_data'];

                    foreach ($pagesData as $key => $sms) {

                        $nameData = array(
                            'sms_id' => $smsDetails->id,
                            'name' => $sms['name'],
                            'content' => $sms['content'],
                            'language_code' => $sms['language_code']
                        );
                        SmsTemplateI18ns::create($nameData);
                        $smsmsg[] = $sms['name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Sms [' . implode(', ', $smsmsg) . '] saved successfully';
                    $return['data'] = $sms;
                }
                DB::commit();
            } catch (\Exception $e) {
                $return['message'] = 'Error during save record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }
}
