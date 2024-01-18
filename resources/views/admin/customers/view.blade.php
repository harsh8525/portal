@extends('admin.layout.main')
@section('title', $header['title'])
  
@section('content')
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['title'] }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('customers.dashboard') </a></li>
                <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">@lang('customers.moduleHeading')</a></li>
                <li class="breadcrumb-item active">@lang('customers.view')</li>
              </ol>
              <div class="breadcrumb-btn">
                @if(empty($customerDetail['password']))
                 <div class="add-breadcrumb" style="width:120px !important">
                    <span>
                    <a class="" href="{{ route('admin.customer-active-account',$customerDetail['id']) }}" title="View">
                      Resend Activation Mail
                    </a></span> 
                    <span>
                </div>
                @endif
                <div class="add-breadcrumb @if($customerDetail['status'] == 'deleted') d-none @endif">
                  <a class="" href="{{ route('customers.edit',$customerDetail['id']) }}"  title="Edit">
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
                <div class="d-flex brdr-btm ">
                  <div class="viewpage_img">
                      @if($customerDetail['profile_image'] != "")
                     <img data-toggle="popover" height="200" width="200" src="{{ $customerDetail['profile_photo'] ?: URL::asset('assets/images/no-image.png')}}" alt="">
                      @else
                      <img class="viewcon-user" width="150" height="150" src="{{ $customerDetail['profile_photo'] ?: URL::asset('assets/images/no-image.png')}}" alt="">
                      @endif
                  
                  </div>
                  <?php 
                    $haystack = $customerDetail['mobile'];
                    $needle    = ' ';
                    $cust_isdCode = strstr($haystack, $needle, true);
                    $data = $customerDetail['mobile'];
                    $cust_mobile = substr($data, strpos($data, " ") + 1);   
                  ?>
                  <div class="view_user_data">
                    <table class="">
                      <tr>
                        <th>@lang('customers.customerFirstName') :</th>
                        <td>{{ $customerDetail->first_name ? $customerDetail->first_name : '-' }}</td>
                      </tr>
                      <tr>
                        <th>@lang('customers.customerLastName') :</th>
                        <td>{{$customerDetail->last_name ?? '-'}}</td>
                      </tr>
                      <tr>
                        <th>@lang('customers.mobileNumber') :</th>
                        <td> {{ $cust_mobile ? $cust_isdCode." ".$cust_mobile : '-'}}</td>
                      </tr>
                      <tr>
                        <th>@lang('customers.emailAddress') :</th>
                        <td>{{$customerDetail->email ?: '-'}}</td>
                      </tr>
                      <tr>
                        <th>Gender :</th>
                        <td>{{ ucwords($customerDetail->gender) ?: '-'}}</td>
                      </tr>
                      <tr>
                        <th>Marital Status :</th>
                        <td>{{ucwords($customerDetail->marital_status) ?: '-'}}</td>
                      </tr>
                      @if($customerDetail->marriage_aniversary_date)
                      <tr>
                        <th>Marriage Aniversary Date :</th>
                        <td>{{getDateTimeZone($customerDetail->marriage_aniversary_date) ?: '-'}}</td>
                      </tr>
                      @endif
                    </table>
                  </div>
                  <div class="view_user_data">
                    <table class="">
                      <tr>
                        <th>@lang('customers.createdDate') :</th>
                        <td>{{getDateTimeZone($customerDetail->created_at)}}</td>
                      </tr>
                      <tr>
                        <th>@lang('customers.updatedDate') :</th>
                        <td>{{getDateTimeZone($customerDetail->updated_at)}}</td>
                      </tr>
                      <tr style="display: none;">
                        <th>@lang('customers.sortOrder') :</th>
                        <td>2</td>
                      </tr>
                       <tr>
                        <th>@lang('customers.dateOfBirth') :</th>
                        <td>{{ $customerDetail->date_of_birth ? getDateTimeZone($customerDetail->date_of_birth) : '-'}}</td>
                      </tr>
                      <tr>
                        <th>@lang('customers.status') :</th>
                        <td>{{$customerDetail['customer_status_text'] ?? '-' }}</td>
                      </tr>
                      @if($customerDetail->status == 'deleted')
                      <tr>
                        <th>Deleted Reason :</th>
                        <td>{{$customerDetail['deleted_reason'] ?? '-' }}</td>
                      </tr>
                      @endif
                    </table>
                  </div>
                </div>
                <div class="seo_view pt-4 discount brdr-btm">
                  <h3 class="view_seo mb-2">@lang('customers.addressInformation')</h3>
                  <div class="seo_data_list">
                    <table class="">
                      <tr>
                        <th>@lang('customers.address1') :</th>
                        <td>{{ $customerDetail['getCustomerAddress'] ? ucwords($customerDetail['getCustomerAddress']['address1']) : '-' }}</td>
                      </tr>
                         <th>@lang('customers.address2') :</th>
                        <td>{{ $customerDetail['getCustomerAddress'] ? ucwords($customerDetail['getCustomerAddress']['address2']) : '-' }} </td>
                      </tr>
                      <tr>
                        <th>@lang('customers.country') :</th>
                     
                        <td>
                            @if(isset($customerDetail['getCustomerAddress']))
                                @foreach($customerDetail['getCustomerAddress']['getCountry']['countryCode'] as $country)
                                    {{ $country->country_name ?? '-'}}<br>
                                @endforeach
                            @else
                            {{'-'}}
                            @endif
                        </td>
                      </tr>
                      <tr>
                        <th>@lang('customers.state') :</th>
                        <td>
                        @if(isset($customerDetail['getCustomerAddress']) && $customerDetail['getCustomerAddress']['getState'])
                          @foreach($customerDetail['getCustomerAddress']['getState']['stateName'] as $state)
                            {{ $state->state_name ?? '-'}}<br>
                          @endforeach
                        @else
                          {{'-'}}
                        @endif
                        </td>
                      </tr>
                      <tr>
                        <th>@lang('customers.city') :</th>
                        <td>
                        @if(isset($customerDetail['getCustomerAddress']))
                          @foreach($customerDetail['getCustomerAddress']['getCity']['cityCode'] as $city)
                              {{ $city->city_name ?? '-'}}<br>
                          @endforeach
                        @else
                          {{'-'}}
                        @endif
                        </td>
                      </tr>
                      <tr>
                        <th>@lang('customers.pincode') :</th>
                        <td>{{$customerDetail['getCustomerAddress']['pincode'] ?? '-'}}</td>
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