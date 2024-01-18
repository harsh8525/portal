@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<style>
.image_div{
    display: flex;
    justify-content: center;
}
</style>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['heading'] }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                <li class="breadcrumb-item"><a href="{{ route('agency.index') }}">Agencies</a></li>

                <li class="breadcrumb-item active">@lang('adminUser.view')</li>
              </ol>
              <div class="breadcrumb-btn">
                
                <div class="add-breadcrumb">
                  <a class="" href="{{ route('agency.edit',$agencyDetails['id']) }}">
                    <?xml version="1.0"?>
                    <svg fill="#fff" viewBox="0 0 24 24" width="20" height="20">
                    <path
                        d="M 19.171875 2 C 18.448125 2 17.724375 2.275625 17.171875 2.828125 L 16 4 L 20 8 L 21.171875 6.828125 C 22.275875 5.724125 22.275875 3.933125 21.171875 2.828125 C 20.619375 2.275625 19.895625 2 19.171875 2 z M 14.5 5.5 L 3 17 L 3 21 L 7 21 L 18.5 9.5 L 14.5 5.5 z" />
                    </svg>
                  Edit
                  </a>
                </div>
              </div>
            </div><!-- /.col -->

          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Info boxes -->
          <div class="row">
            <div class="card pb-4 pt-4 px-3 w-100">
              <div class="row view_page mb-0">

                    <div class="container">
                        
                        <div class="row image_div">

                            @if($agencyDetails['logo'] != "")
                            <img class="viewcon-user" width="150" height="150" style="border:0px !important;" src="{{ $agencyDetails['logo'] }}" alt="">
                            @else
                            <img class="viewcon-user" width="150" height="150" src="{{ URL::asset('assets/images/no-image.png') }}" alt="">
                            @endif

                              <div class="image_div mt-2">
                                  <span class="mr-2" style="font-size: 12px"><b>Member since {{ dateFunction($agencyDetails['created_at']) }}</b></span>
                                    @if($agencyDetails['status'] == 'active')
                                    <span  title="Status" class="btn-xs btn-info" style="border-radius: 8px;" disabled>Active</span>
                                    @elseif($agencyDetails['status'] == 'inactive')
                                    <span title="Status" class="btn-xs btn-info" style="border-radius: 8px;" disabled>In-Active</span>
                                    @elseif($agencyDetails['status'] == 'terminated')
                                    <span title="Status" class="btn-xs btn-info" style="border-radius: 8px;" disabled>Terminated</span>
                                    @endif
                              </div>
                        </div>
                    </div>

                <div class="col-md-6 mt-3">

                    
                  <div class="view_user_data">
                    <table class="">
                        <tbody>
                     <tr>
                        <th colspan="2" class="bg-blue" style="width: 481px">General Information</th>
                    </tr>
                  
                    <tr>
                        <th>Agency ID :</th>
                        <td> {{ $agencyDetails['agency_id'] }}</td>
                    </tr>
                    <tr>
                        <th>Full Name :</th>
                        <td>{{ ucwords($agencyDetails['full_name']) }} </td>
                    </tr>
                    <tr>
                        <th>Contact Person :</th>
                       <td>{{ ucwords($agencyDetails['contact_person_name']) }} </td>
                    </tr>
                    <tr>
                        <th>Position :</th>
                      <td>{{ ucwords($agencyDetails['designation']) }} </td>
                    </tr>
                    
                    <tr>
                        <th>Email :</th>
                        <td>{{ $agencyDetails['email'] }} </td>
                    </tr>
                    <tr>
                        <th>Licence Number :</th>

                        <td>{{ ucwords($agencyDetails['license_number']) }} </td>
                    </tr>
                    <tr>
                        <th>Agency Type :</th>
                       
                       <td>{{ ucwords($agencyDetails['agency_type_name']) }}</td>
                       
                    </tr>
                    <tr>
                        <th>Phone No :</th>
                        <td>{{ $agencyDetails['phone_no'] }} </td>
                    </tr>
                    <tr>
                        <th>Fax No :</th>
                        <td>{{ $agencyDetails['fax_no'] }} </td>
                    </tr>
                    <tr>
                        <th>Web Url :</th>
                       <td>{{ $agencyDetails['web_link'] }} </td>
                    </tr>
                    <tr>
                        <th>IATA Number :</th>
                       <td>{{ $agencyDetails['iata_number'] ? $agencyDetails['iata_number'] : "-" }} </td>
                    </tr>
                              </tbody>                  
                    </table>
                  </div>
                </div>

                <div class="col-md-6 mt-3">

                   
                  <div class="view_user_data">
                    <table class="">
                     <tr>
                        <th colspan="2" class="tableInfo bg-blue" style="width: 480px">System options</th>
                    </tr>
                    <tr>
                        <th>Stop Buy :</th>
                        
                       <td>{{ ($agencyDetails['is_stop_buy'] == '1') ? 'True' : 'False'}} </td>
                      </tr>
                      <tr>
                        <th>Search Only:</th>
                        <td>{{ ($agencyDetails['is_search_only'] == '1') ? 'True' : 'False'}} </td>
                        
                      </tr>
                      <tr>
                        <th>Cancel Right :</th>
                        <td>{{ ($agencyDetails['is_cancel_right'] == '1') ? 'True' : 'False'}} </td>
                    </tr>
                 
                    @if($agencyDetails['agency_type_name'] !== 'Supplier')
                    <tr>
                        <th>Agency Payment Type :</th>
                      
                        <?php foreach($agencyDetails['agencyPaymentTypes'] as $data)
                        {
                            $values[] = App\Models\PaymentMethod::where('id',$data['core_payment_type_id'])->value('name');
                        } 
                            $agencyPaymentTypes = implode(', ',$values);
                        ?>
                        <td> {{$agencyPaymentTypes}}</td>
                    </tr>
                    <tr>
                      @endif
                      @if($agencyDetails['agency_type_name'] !== 'Supplier')
                        <th>Service Type :</th>
                        <?php 
                        foreach($agencyDetails['agencyServiceTypes'] as $data) 
                        {
                          $valuesServiceType[] = App\Models\ServiceType::where('id',$data['core_service_type_id'])->value('name');
                        }
                          $serviceTypes = implode(', ',$valuesServiceType);
                        ?>
                        <td> {{ ucwords($serviceTypes) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Enable Currencies :</th>
                         <?php 
                        foreach($agencyDetails['agencyEnableCurrencies'] as $data) 
                        {
                          $currency[] = App\Models\Currency::where('id',$data['currency_id'])->value('name');
                        }
                          $currencies = implode(', ',$currency);
                        ?>
                        <td> {{ ucwords($currencies) }}</td>
                    </tr>
                     @if($agencyDetails['agency_type_name'] !== 'Supplier')
                    <tr>
                      
                        <th>Agency Payment Gateway :</th>
                         <?php 
                        foreach($agencyDetails['agencyPaymentGateway'] as $data) 
                        {
                          $agencyPaymentGateway[] = App\Models\PaymentGateway::where('id',$data['core_payment_gateway_id'])->value('name');
                        }
                          $agencyPaymentGateways = implode(', ',$agencyPaymentGateway);
                        ?>
                        <td> {{ ucwords($agencyPaymentGateways) }}</td>
                    </tr>
                     @endif
                    </tr>
                                                
                    </table>
                  </div>
                </div>
                <div class="col-md-6 mt-3">

                   
                  <div class="view_user_data">
                    <table class="">
                     <tr>
                        <th colspan="2" class="tableInfo bg-blue" style="width: 480px">Address Information</th>
                    </tr>
                    <tr>
                        <th>Address :</th>
                        <td>{{ ucwords($agencyDetails['agencyAddress']['address1']) }} </td>
                    </tr>
                    <tr>
                        <th>City :</th>
                        <td>{{ ucwords($agencyDetails['agencyAddress']['city']) }} </td>
                    </tr>
                    <tr>
                        <th>State :</th>
                       <td>{{ ucwords($agencyDetails['agencyAddress']['state']) }}  </td>
                    </tr>
                    <tr>
                        <th>Country :</th>
                         <td>{{ ucwords($agencyDetails['agencyAddress']['country']) }}</td>
                    </tr>
                    
                    <tr>
                        <th>Zip Code :</th>
                        <td>{{ $agencyDetails['agencyAddress']['pincode'] }} </td>
                    </tr>
                                                
                    </table>
                  </div>
                </div>


              </div>
            </div>
            <!-- /.row -->
          </div>
          <!--/. container-fluid -->
      </section>

      @endsection