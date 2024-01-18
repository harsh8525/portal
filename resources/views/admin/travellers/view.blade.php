@extends('admin.layout.main')
@section('title', $header['title'])
  
@section('content')
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['heading'] }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('travellers.dashboard') </a></li>
                <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">@lang('customers.moduleHeading') </a></li>
                <li class="breadcrumb-item"><a href="{{ route('travellers.index',['customer_id' => $travellerDetail['customer_id']]) }}">@lang('travellers.moduleHeading')</a></li>
                <li class="breadcrumb-item active">@lang('travellers.view')</li>
              </ol>
              @if($travellerDetail['deleted_at'] == null)
              <div class="breadcrumb-btn">
                <div class="add-breadcrumb">
                  <a class="" href="{{ route('travellers.edit',$travellerDetail['id']) }}"  title="Edit">
                    <?xml version="1.0"?>
                    <svg fill="#fff" viewBox="0 0 24 24" width="20" height="20">
                    <path
                        d="M 19.171875 2 C 18.448125 2 17.724375 2.275625 17.171875 2.828125 L 16 4 L 20 8 L 21.171875 6.828125 C 22.275875 5.724125 22.275875 3.933125 21.171875 2.828125 C 20.619375 2.275625 19.895625 2 19.171875 2 z M 14.5 5.5 L 3 17 L 3 21 L 7 21 L 18.5 9.5 L 14.5 5.5 z" />
                    </svg>
                  Edit
                  </a>
                </div>
              </div>
              @endif
            </div><!-- /.col -->

          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Info boxes -->
          <h4 class="fw-bold">Traveller - View</h4>
          <div class="row">
            <div class="card pb-4 pt-4 px-3 w-100">
              <div class="row view_page mb-0">
                <div class="d-flex brdr-btm ">
                  <div class="viewpage_img">
                      @if($travellerDetail['profile_image'] != "")
                     <img data-toggle="popover" height="200" width="200" src="{{ $travellerDetail['document'] ?: URL::asset('assets/images/no-image.png')}}" alt="">
                      @else
                      <img class="viewcon-user" width="150" height="150" src="{{ $travellerDetail['document'] ?: URL::asset('assets/images/no-image.png')}}" alt="">
                      @endif
                  </div>
                  <?php 
                    $haystack = $travellerDetail['mobile'];
                    $needle    = ' ';
                    $cust_isdCode = strstr($haystack, $needle, true);
                    $data = $travellerDetail['mobile'];
                    $cust_mobile = substr($data, strpos($data, " ") + 1);   
                  ?>
                  <div class="view_user_data">
                    <table class="">
                      <tr>
                        <th>@lang('travellers.travellerFirstName') :</th>
                        <td>{{ ucwords($travellerDetail->first_name) ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th>@lang('travellers.travellerSecondName') :</th>
                        <td>{{ ucwords($travellerDetail->second_name) ? $travellerDetail->second_name : '-' }}</td>
                      </tr>
                      <tr>
                        <th>@lang('travellers.travellerLastName') :</th>
                        <td>{{ ucwords($travellerDetail->last_name) ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th>Gender :</th>
                        <td>{{ ucwords($travellerDetail->gender) ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th>@lang('travellers.nationality') :</th>
                        <td>
                          @if(isset($getNationality->countryCode))
                            @foreach($getNationality->countryCode as $country)
                              {{$country->country_name ?: '-'}}<br>
                            @endforeach
                          @endif
                        </td>
                      </tr>
                      
                    </table>
                  </div>
                  <div class="view_user_data">
                    <table class="">
                      
                        <th>@lang('travellers.idType') :</th>
                        <td>{{ucwords($travellerDetail->id_type) ?: '-'}}</td>
                      </tr>
                      <tr>
                        <th>@lang('travellers.idNumber') :</th>
                        <td>{{$travellerDetail->id_number ?: '-'}}</td>
                      </tr>
                      <tr>
                        <th>@lang('issueDate') :</th>
                        <td>{{ getDateTimeZone($travellerDetail->issue_date) ?: '-'}}</td>
                      </tr>
                      <tr>
                        <th>@lang('travellers.expiryDate') :</th>
                        <td>{{ getDateTimeZone($travellerDetail->expiry_date) ?: '-'}}</td>
                      </tr>
                      <tr>
                        <th>@lang('travellers.issueCountry') :</th>
                        <td>
                        @if(isset($getCountry->countryCode))
                          @foreach($getCountry->countryCode as $country)
                          {{$country->country_name ? $country->country_name : '-'}}<br>
                          @endforeach
                        @endif
                        </td>
                      </tr>
                      
                    </table>
                  </div>
                </div>
                <div class="row view_page mb-0 col-md-4">
                <div class="view_user_data mt-3">
                  <table class="">
                    <tr>
                     <th>@lang('travellers.dateOfBirth') :</th>
                     <td>{{ getDateTimeZone($travellerDetail->date_of_birth) }}</td>
                   </tr>
                    <tr>
                      <th>@lang('travellers.createdDate') :</th>
                      <td>{{getDateTimeZone($travellerDetail->created_at)}}</td>
                    </tr>
                    <tr>
                      <th>@lang('travellers.updatedDate') :</th>
                      <td>{{getDateTimeZone($travellerDetail->updated_at)}}</td>
                    </tr>
                    <tr>
                      <th>@lang('travellers.status') :</th>
                      <td>{{ucwords($travellerDetail['status'])}}</td>
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