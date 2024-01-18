<?php

/**
 * @package     B2C
 * @subpackage  Social Media Link
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Social Media Link.
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
use App\Traits\Uuids;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class SocialMediaLink extends Model
{
    use HasFactory, LogsActivity, Uuids;
    protected $table = 'social_media_links';
    protected $guarded = [];
    protected static $logAttributes = ['name', 'link', 'status'];

    /**
     * get list or single or all record to display
     */
    public static function getSocialMediaLinkData($option = array())
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
            'where' => array(),
            'whereHas' => array(),
            'orWhere' => array()
        );
        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = SocialMediaLink::query();
                $query->select(
                    "social_media_links.*"
                );
                $query->where('social_media_links.id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = SocialMediaLink::query();
                $query->select(
                    "social_media_links.*"
                );
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
                $result->setPath('?per_page=' . $config['per_page']);
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        }

        if (!empty($result)) {
            $return['status'] = 1;
            $return['message'] = 'Social media link list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * update record into database
     */
    public static function updateSocialMediaLink($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $socialMediaLinkData = array(
                'name'  => $requestData['name'],
                'link'  => $requestData['link'],
                'status'  => $requestData['status'],
            );
            
            // save to table
            try {
                DB::beginTransaction();
                $checkNameExists = SocialMediaLink::where('name',$requestData['name'])->first();
                
                if ($checkNameExists) {

                    $matchSocialMediaLinkData = ['id' => $checkNameExists['id']];
                    $updateSocialMediaLinkData = SocialMediaLink::updateOrCreate($matchSocialMediaLinkData, $socialMediaLinkData);
                
                    if ($updateSocialMediaLinkData) {

                        $name = $updateSocialMediaLinkData['name'];
                        
                        $return['status'] = 1;
                        $return['message'] = 'Social Media Link [' . $name . '] saved successfully';
                        $return['data'] = $updateSocialMediaLinkData;
                    }

                }else{

                    $SocialMediaLink = SocialMediaLink::create($socialMediaLinkData);
                    if ($SocialMediaLink) {

                        $name = $SocialMediaLink['name'];
                        
                        $return['status'] = 1;
                        $return['message'] = 'Social Media Link [' . $name . '] saved successfully';
                        $return['data'] = $SocialMediaLink;
                    }
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
