<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ActiveLog;
use App\Traits\AmadeusService;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Bookings;
use App\Models\Suppliers;
use App\Models\Agency;
use App\Models\ServiceType;
use App\Models\Setting;
use Mpdf\Config\FontVariables;
use Mpdf\Config\ConfigVariables;
use \Mpdf\Mpdf as mPDF;



class BookingController extends Controller
{
    use AmadeusService;

    public function __construct()
    {

        $this->perPage = count(Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')) > 0 ? Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')[0]['value'] : "20";

        //set AMADEUS API configuration from config key
        $this->amadeusAPIEnvironment = count(Setting::where('config_key', 'amadeus|api|credential')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|credential')->get('value')[0]['value'] : "test";

        if ($this->amadeusAPIEnvironment == 'test') {
            $this->amadeusAPIEndPoint = count(Setting::where('config_key', 'amadeus|api|test|APIEndPoint')->get('value')) > 0 ? trim(Setting::where('config_key', 'amadeus|api|test|APIEndPoint')->get('value')[0]['value']) : "https://test.api.amadeus.com";
            $this->amadeusAPIClientID = count(Setting::where('config_key', 'amadeus|api|test|clientId')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|test|clientId')->get('value')[0]['value'] : "zFKYlQPsA1sJjtId13ab1vSE5FyLraqR";
            $this->amadeusAPIClientSecret = count(Setting::where('config_key', 'amadeus|api|test|clientSecret')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|test|clientSecret')->get('value')[0]['value'] : "wos5It0hZHUbBAdH";
            $this->amadeusAPIGrantType = count(Setting::where('config_key', 'amadeus|api|test|grantType')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|test|grantType')->get('value')[0]['value'] : "client_credentials";
        } else {
            $this->amadeusAPIEndPoint = count(Setting::where('config_key', 'amadeus|api|live|APIEndPoint')->get('value')) > 0 ? trim(Setting::where('config_key', 'amadeus|api|live|APIEndPoint')->get('value')[0]['value']) : "https://test.api.amadeus.com";
            $this->amadeusAPIClientID = count(Setting::where('config_key', 'amadeus|api|live|clientId')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|live|clientId')->get('value')[0]['value'] : "zFKYlQPsA1sJjtId13ab1vSE5FyLraqR";
            $this->amadeusAPIClientSecret = count(Setting::where('config_key', 'amadeus|api|live|clientSecret')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|live|clientSecret')->get('value')[0]['value'] : "wos5It0hZHUbBAdH";
            $this->amadeusAPIGrantType = count(Setting::where('config_key', 'amadeus|api|live|grantType')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|live|grantType')->get('value')[0]['value'] : "client_credentials";
        }

        $this->amadeusAPISecret = count(Setting::where('config_key', 'amadeus|api|secret')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|secret')->get('value')[0]['value'] : "";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission('Booking', 'read')) {
            return view('admin/401');
        }
        $header['title'] = "Booking";
        $header['heading'] = "Booking";

        $queryStringConcat = '?';
        if (isset($_GET['per_page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'];
        }
        if (isset($_GET['page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'];
        }
        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'supplier_id' => (request()->input('supplier_id') != NULL) ? request()->input('supplier_id') : '',
            'service_id' => (request()->input('service_id') != NULL) ? request()->input('service_id') : '',
            'booking_id' => (request()->input('booking_id') != NULL) ? request()->input('booking_id') : '',
            'booking_status' => (request()->input('booking_status') != NULL) ? request()->input('booking_status') : '',
            'agency_id' => (request()->input('agency_id') != NULL) ? request()->input('agency_id') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        );
        if (request()->input('full_name') != NULL) {
            $fullName = request()->input('full_name');
            $filter['where'][] = [
                DB::raw("CONCAT(first_name, ' ', last_name)"),
                'LIKE',
                '%' . $fullName . '%'
            ];
        }
        if (request()->input('booking_date') != NULL) {
            $filter['where'][] = ['booking_date', 'like', '%' . request()->input('booking_date') . '%'];
        }
        if (request()->input('supplier_id') != NULL) {
            $filter['where'][] = ['supplier_id', '=', request()->input('supplier_id')];
        }
        if (request()->input('service_id') != NULL) {
            $filter['where'][] = ['service_id', '=', request()->input('service_id')];
        }
        if (request()->input('booking_id') != NULL) {
            $filter['where'][] = ['id', '=', request()->input('booking_id')];
        }
        if (request()->input('booking_status') != NULL) {
            $filter['where'][] = ['booking_status', '=', request()->input('booking_status')];
        }
        if (request()->input('agency_id') != NULL) {
            $filter['where'][] = ['agency_id', '=', request()->input('agency_id')];
        }
        if (request()->input('customer_name') != NULL) {
            $fullName = request()->input('customer_name');
            $filter['whereHas'][] = [
                DB::raw("CONCAT(first_name, ' ', last_name)"),
                'LIKE',
                '%' . $fullName . '%'
            ];
        }

        if (request()->input('email') != NULL) {
            $filter['where'][] = ['customers.email', 'like', '%' . request()->input('email') . '%'];
        }
        if (request()->input('price_from') != NULL) {
            $filter['where'][] = ['sub_total', '>=', request()->input('price_from')];
        }
        if (request()->input('price_to') != NULL) {
            $filter['where'][] = ['sub_total', '<=', request()->input('price_to')];
        }
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['customers.status', '=', request()->input('status')];
        }
        $bookingListData = Bookings::getBookingData($filter);
        $bookingDataCount = Bookings::count();
        $bookingData = $bookingListData['data'];
        $supplierDataList = Suppliers::get()->toArray();
        $getServiceType = ServiceType::get()->toArray();
        $getAgency = Agency::get()->toArray();
        $activityLog['request'] =  request()->input();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $bookingData;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($bookingListData['status'] == 1) {
            return view('admin/booking/index')->with(['header' => $header, 'getAgency' => $getAgency, 'getServiceType' => $getServiceType, 'supplierDataList' => $supplierDataList, 'bookingData' => $bookingData, 'bookingDataCount' => $bookingDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/booking/index')->with(['error' => $bookingListData['message'], 'header' => $header, 'bookingData' => $bookingData, 'bookingDataCount' => $bookingDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $service = $request->service;

        $getServiceType = ServiceType::select('id', 'code')->where('code', $request->service)->first();
        $getBookingId = Bookings::select('id', 'supplier_booking_ref')->where('id', $id)->first();
        $getLanguageCode = Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];

        $filter = array(
            'id' => $id,
            'service' => $getServiceType->id ?? ''
        );

        if ($service == 'Flight') {

            $header['title'] = "Flight Booking - View";
            $header['heading'] = "Flight Booking - View";

            // Booking detail from flight order offer api
            $bookingDetail = $this->getFlightOrderDetails($id);


            if (isset($bookingDetail) && !empty($bookingDetail)) {
                return view('admin/booking/flight/view')->with(['header' => $header, 'bookingDetail' => $bookingDetail, 'getLanguageCode' => $getLanguageCode]);
            } else {
                return redirect()->route('booking.index')->with('error', $bookingDetail['message']);
            }
        } elseif ($service == 'Hotel') {

            $header['title'] = "Hotel Booking - View";
            $header['heading'] = "Hotel Booking - View";

            if (isset($header['title']) && !empty($header['title'])) {
                return view('admin/booking/hotel/view')->with(['header' => $header]);
            } else {
                return redirect()->route('booking.index')->with('error', 'Something went wrong.');
            }
        } else {
            return redirect()->route('booking.index')->with('error', 'Service not found');
        }
    }
    
    /**
     * Download the specified e-ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eTicket(Request $request, $id)
    {
        $tempDir = '/var/www/html/travel-portal/vendor/mpdf/mpdf/tmp'; // replace with your actual path
        // Initialize Mpdf
        $config = (new ConfigVariables())->getDefaults();
        $fontDirs = $config['fontDir'];
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => [320, 460],
            'autoScriptToLang' => true,
            'margin_left' => '10',
            'margin_right' => '10'
        ]);

        // Write HTML content to the mPDF instance

        $service = $request->service;

        $getServiceType = ServiceType::select('id', 'code')->where('code', $request->service)->first();
        $getBookingId = Bookings::select('id', 'supplier_booking_ref')->where('id', $id)->first();
        $getLanguageCode = Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];
        $getAgencyDetails['billingCompanyNameEn'] = Setting::where('config_key', 'invoice|general|billingCompanyNameEn')->get('value')[0]['value'];
        $getAgencyDetails['billingCompanyNameAr'] = Setting::where('config_key', 'invoice|general|billingCompanyNameAr')->get('value')[0]['value'];
        $getAgencyDetails['addressEn'] = Setting::where('config_key', 'invoice|general|addressEn')->get('value')[0]['value'];
        $getAgencyDetails['addressAr'] = Setting::where('config_key', 'invoice|general|addressAr')->get('value')[0]['value'];
        $getAgencyDetails['sitePhoneNo'] = Setting::where('config_key', 'general|basic|sitePhoneNo')->get('value')[0]['value'];
        $getAgencyDetails['siteEmail'] = Setting::where('config_key', 'general|basic|siteEmail')->get('value')[0]['value'];
        $getAgencyDetails['agencyIATANumber'] = Setting::where('config_key', 'invoice|general|agencyIATANumber')->get('value')[0]['value'];
        $getAgencyDetails['cityName'] = Setting::where('config_key', 'invoice|general|cityName')->get('value')[0]['value'];
        $getAgencyDetails['countryName'] = Setting::where('config_key', 'invoice|general|countryName')->get('value')[0]['value'];
        $getAgencyDetails['termsAndConditionsEn'] = Setting::where('config_key', 'invoice|sales|invoice|termsAndConditionsEn')->get('value')[0]['value'];
        $getAgencyDetails['termsAndConditionsAr'] = Setting::where('config_key', 'invoice|sales|invoice|termsAndConditionsAr')->get('value')[0]['value'];
        $getAgencyDetails['notesEn'] = Setting::where('config_key', 'invoice|sales|invoice|notesEn')->get('value')[0]['value'];
        $getAgencyDetails['notesAr'] = Setting::where('config_key', 'invoice|sales|invoice|notesAr')->get('value')[0]['value'];

        $filter = array(
            'id' => $id,
            'service' => $getServiceType->id ?? ''
        );

        if ($service == 'Flight') {

            $header['title'] = "Flight Booking - View";
            $header['heading'] = "Flight Booking - View";

            // Booking detail from flight order offer api
            $bookingDetail = $this->getFlightOrderDetails($id);
            if (isset($bookingDetail) && !empty($bookingDetail)) {
                $pdfContent = view('admin/booking/flight/e-ticket')->with(['header' => $header, 'bookingDetail' => $bookingDetail, 'getLanguageCode' => $getLanguageCode, 'getAgencyDetails' => $getAgencyDetails])->render();
                $mpdf->WriteHTML($pdfContent);
                // Output the PDF to the browser
                $mpdf->Output('Traveler-e-Ticket.pdf', 'D');
            } else {
                return redirect()->route('booking.index')->with('error', $bookingDetail['message']);
            }
        } elseif ($service == 'Hotel') {

            $header['title'] = "Hotel Booking - View";
            $header['heading'] = "Hotel Booking - View";

            if (isset($header['title']) && !empty($header['title'])) {
                return view('admin/booking/hotel/e-ticket')->with(['header' => $header]);
            } else {
                return redirect()->route('booking.index')->with('error', 'Something went wrong.');
            }
        } else {
            return redirect()->route('booking.index')->with('error', 'Service not found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function designShow(Request $request)
    {
        $header['title'] = "Flight Booking - View";
        $header['heading'] = "Flight Booking - View";

        return view('admin/booking/flight/design-view')->with(['header' => $header]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
