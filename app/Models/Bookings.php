<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Traits\Uuids;
use App\Models\BookingHotelRooms;

class Bookings extends Model
{
    use HasFactory, Uuids;
    protected $table = 'bookings';
    protected $guarded = [];
    
    public function getServiceType()
    {
        return $this->belongsTo('App\Models\ServiceType', 'service_id');
    }
    public function getSupplier()
    {
        return $this->belongsTo('App\Models\Suppliers', 'supplier_id');
    }
    public function getCustomer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public function getAgency()
    {
        return $this->belongsTo('App\Models\Agency', 'agency_id');
    }
    public function getFlightBookingTraveler()
    {
        return $this->hasMany('App\Models\FlightBookingTraveler', 'booking_id', 'id');
    }
    public function getBookingHotelRooms()
    {
        return $this->hasMany(BookingHotelRooms::Class, 'booking_id', 'id');
    }

     /**
     * get list or single or all records to display
     */
    public static function getBookingData($option = array())
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
                $query = Bookings::query();
                $query->select('bookings.*');
                $query->with(['getFlightBookingTraveler','getBookingHotelRooms','getServiceType','getSupplier','getCustomer','getAgency']);
                $query->where('id', $config['id']);
                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        }else {
            try {
                // echo "<pre>";print_r($config);die;
                $query = Bookings::query();
                $query->select('bookings.*');
                $query->with(['getFlightBookingTraveler','getBookingHotelRooms','getServiceType','getSupplier','getCustomer','getAgency']);

                if ($config['order_by'] == 'service_id') {
                    $query->join('core_service_types as c', 'bookings.service_id', '=', 'c.id')
                        ->orderBy('c.name', $config['sorting']);
                }
                if ($config['order_by'] == 'supplier_id') {
                    $query->join('core_suppliers as c', 'bookings.supplier_id', '=', 'c.id')
                        ->orderBy('c.name', $config['sorting']);
                }
                if ($config['order_by'] == 'customer_id') {
                    $query->join('customers as c', 'bookings.customer_id', '=', 'c.id')
                        ->orderBy('c.first_name', $config['sorting']);
                }
                if ($config['order_by'] == 'agency_id') {
                    $query->join('agencies as a', 'bookings.agency_id', '=', 'a.id')
                        ->orderBy('a.full_name', $config['sorting']);
                }
                $query->orderBy($config['order_by'], $config['sorting']);

                if (!empty($config['where'])) {
                    foreach ($config['where'] as $where) {
                        $query->where('bookings.' . $where[0], $where[1], $where[2]);
                    }
                }
                if (isset($config['whereHas'])) {
                    $query->whereHas('getCustomer', function ($channelQuery) use ($config) {
                        foreach ($config['whereHas'] as $orWhere) {
                        $channelQuery->where($orWhere[0], $orWhere[1], $orWhere[2]);
                        }
                    });
                }
                if (!empty($config['orWhere'])) {
                    foreach ($config['orWhere'] as $orWhere) {
                        $query->orWhere($orWhere[0], $orWhere[1], $orWhere[2]);
                    }
                }
                $result = $query->paginate($config['per_page']);
                // echo "<pre>";print_r($result);die;
                $result->setPath('?per_page=' . $config['per_page']);
            } catch (\Exception $e) {
                // echo "<pre>";print_r($e->getMessage());die;
                $return['message'] = $e->getMessage();
            }
        }

        if (!empty($result)) {
            $return['status'] = 1;
            $return['message'] = 'Booking list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }
}
