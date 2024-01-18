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
class ActivityLog extends Model
{
    use HasFactory,LogsActivity;
    protected $table = 'activity_log';
    
    protected $guarded = [];
    protected static $logAttributes = ['core_agency_type_id', 'parent_id', 'agency_id','core_supplier_id','full_name','short_name','contact_person_name','license_number','phone_no','fax_no','email','web_link',
                                        'is_stop_buy','is_search_only','is_cancel_right','status'];
    
    protected static $logName = 'activity_log';

    
    
}
