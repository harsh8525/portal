<?php

/**
 * @package     Settings
 * @subpackage  Mail Template 
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
use App\Models\MailTemplateI18ns;
use Illuminate\Support\Carbon;
use DateTime;
use App\Traits\Uuids;
use Illuminate\Support\Facades\Hash;

class MailTemplate extends Model
{
    use HasFactory, Uuids;
    protected $table = 'mail_templates';
    protected $guarded = [];
    public function mailCodeName()
    {
        return $this->hasMany('App\Models\MailTemplateI18ns', 'mail_id', 'id');
    }
    public function mailCodeNameSingle()
    {
        return $this->hasOne('App\Models\MailTemplateI18ns', 'mail_id', 'id');
    }

    /**
     * get list or single or all records to display
     */
    public static function getMailTemplateData($option = array())
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
                $query = MailTemplate::query();
                $query->with('mailCodeName');
                $query->select(
                    "mail_templates.*",

                );
                $query->where('mail_templates.id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = MailTemplate::query();
                $query->with('mailCodeName');
                $query->whereHas('mailCodeName', function ($q) use ($config) {
                    if (!empty($config['whereHas'])) {
                        foreach ($config['whereHas'] as $where) {
                            $q->where($where[0], $where[1], $where[2]);
                        }
                    }
                });
                $query->select(
                    "mail_templates.*",

                );
                if ($config['order_by'] == 'name') {
                    $query->join('mail_templates_i18ns', 'mail_templates.id', '=', 'mail_templates_i18ns.mail_id')
                        ->where('mail_templates_i18ns.language_code', 'en')
                        ->orderBy('mail_templates_i18ns.name', $config['sorting']);
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
                $result = $query->paginate($config['per_page']);
                $result->setPath('?per_page=' . $config['per_page']);
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        }

        if (!empty($result)) {
            $return['status'] = 1;
            $return['message'] = 'Mail Template list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * update record in database
     */
    public static function updateMailTemplate($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {
            @$mailTemplateDetails = array(
                'id' => $requestData['mail_template_id'],
                'code' => $requestData['code'],
                'from_email' => $requestData['from_email'],
                'to_email' => $requestData['to_email'],
                'cc' => $requestData['cc'],
                'bcc' => $requestData['bcc'],
            );
            try {
                DB::beginTransaction();

                $matchMailTemplateType = ['id' => $mailTemplateDetails['id']];
                $mailTemplateData = MailTemplate::updateOrCreate($matchMailTemplateType, $mailTemplateDetails);

                if ($mailTemplateData) {

                    $mailNames['mail'] = $requestData['mail'];
                    foreach ($mailNames['mail'] as $key => $name) {
                        $nameData = array(
                            'mail_id' => $mailTemplateData->id,
                            'name' => $name['name'],
                            'content' => $name['content'],
                            'subject' => $name['subject'],
                            'language_code' => $name['language_code'],
                        );
                        $matchpageData = ['id' => $name['mailTemplate_i18ns_id']];
                        MailTemplateI18ns::updateOrCreate($matchpageData, $nameData);
                        $pagemsg[] = $name['name'];
                    }
                }
                DB::commit();
                if ($mailTemplateData) {
                    $return['status'] = 1;
                    $return['message'] = 'Mail [' . implode(', ', $pagemsg) . '] updated Successfully.';
                    $return['data'] = $mailTemplateData;
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
     * indert data in database by seeder file
     */
    public static function createSeederMailTemplates($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $mailData = array(
                'code'  => $requestData['code'],
                'from_email'  => $requestData['from_email'],
                'to_email'  => $requestData['to_email'],
                'cc'  => $requestData['cc'],
                'bcc'  => $requestData['bcc']

            );
            // save to table
            try {
                DB::beginTransaction();
                $mailDetails = MailTemplate::create($mailData);


                if ($mailDetails) {

                    $pagesData = $requestData['mail_data'];

                    foreach ($pagesData as $key => $mail) {

                        $nameData = array(
                            'mail_id' => $mailDetails->id,
                            'name' => $mail['name'],
                            'content' => $mail['content'],
                            'subject' => $mail['subject'],
                            'language_code' => $mail['language_code']
                        );
                        MailTemplateI18ns::create($nameData);
                        $mailmsg[] = $mail['name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Sms [' . implode(', ', $mailmsg) . '] saved successfully.';
                    $return['data'] = $mail;
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
