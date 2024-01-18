@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<style>
  .blurIcon {
    background-color: #dfdfe9 !important;
    color: black !important;
  }

  .table-responsive {
    max-height: 300px;
  }
</style>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
        <h1 class="m-0">Flight Markup - View</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
          <li class="breadcrumb-item"><a href="{{ route('flight-markups.index') }}">Flight Markups List</a></li>
          <li class="breadcrumb-item active">View</li>
        </ol>
        <div class="breadcrumb-btn">
        </div>
      </div><!-- /.col -->

    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->

<section class="content" id="profile"><!--Start Profile Div -->
  <div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
      @if (session('success'))
      <div class="alert alert-success" role="alert">
        <?php echo session('success'); ?>
      </div>
      @endif
      @if (session('error'))
      <div class="alert alert-danger" role="alert">
        {{ session('error') }}
      </div>
      @endif
      @if (isset($error))
      <div class="alert alert-danger" role="alert">
        {{ $error }}
      </div>
      @endif
      <div class="card pb-4 pt-4 px-3 w-100">
        <div class="row view_page mb-0">
          <div class="d-flex brdr-btm ">
            <div class="d-flex">
              <div class="view_user_data discount">
                <h3 class="view_seo mb-2 pt-4">Markup Base</h3>
                <table class="">
                  <tr>
                    <th>Service Type :</th>
                    <td>{{ $service_type }}</td>
                  </tr>
                  <tr>
                    <th>Rule Name :</th>
                    <td>{{ $markupsDetail['rule_name'] }}</td>
                  </tr>
                  <tr>
                    <th>Channel:</th>
                    <td>@foreach($markupsDetail->getChannel as $key => $channel)
                      {{ $channel->channel }}
                      @if(!$loop->last)
                      ,
                      @endif
                      @endforeach
                    </td>
                  </tr>
                  <tr>
                    <tr>
                      <th>Origin Criteria :</th>
                      <td>{{ $markupsDetail['origin_criteria'] }}</td>
                    </tr>
                    <th>Origin :</th>
                    <td>
                      
                      @if($markupsDetail['origin_criteria'] == 'country')
                      @if($markupsDetail['origin_name'] == 'all')
                      {{ $markupsDetail['origin_name'] }}
                      @else
                      @forelse($markupsDetail->getOriginCountry->countryCode as $country_name)
                      {{$country_name['country_name']}} <br>
                      @endforeach
                      @endif
                      @endif
                      @if($markupsDetail['origin_criteria'] == 'city')
                      @if($markupsDetail['origin_name'] == 'all')
                      {{ $markupsDetail['origin_name'] }}
                      @else
                      @forelse($markupsDetail->getOriginCity->cityCode as $city_name)
                      {{$city_name['city_name']}} <br>
                      @endforeach
                      @endif
                      @endif
                      @if($markupsDetail['origin_criteria'] == 'airport')
                      @if($markupsDetail['origin_name'] == 'all')
                      {{ $markupsDetail['origin_name'] }}
                      @else
                      @forelse($markupsDetail->getOriginAirport->airportName as $airport_name)
                      {{$airport_name['airport_name']}} <br>
                      @endforeach
                      @endif
                      @endif
                      @if($markupsDetail['origin_criteria'] == 'all')
                      {{ 'All' }} <br>
                      @endif
                    </td>
                  </tr>  
                  <tr>
                      <th>Destination Criteria :</th>
                      <td>{{ $markupsDetail['destination_criteria'] }}</td>
                    </tr>
                  <tr>
                    <th>Destination :</th>
                    <td>
                     
                      @if($markupsDetail['destination_criteria'] == 'country')
                      @if($markupsDetail['destination_name'] == 'all')
                      {{ $markupsDetail['destination_name'] }}
                      @else
                          @forelse($markupsDetail->getdestinationCountry->countryCode as $country_name)
                          {{$country_name['country_name'] ?? ''}} <br>
                          @endforeach
                        @endif
                      @endif
                      @if($markupsDetail['destination_criteria'] == 'city')
                      @if($markupsDetail['destination_name'] == 'all')
                      {{ $markupsDetail['destination_name'] }}
                      @else
                          @forelse($markupsDetail->getdestinationCity->cityCode as $city_name)
                          {{$city_name['city_name']}} <br>
                          @endforeach
                        @endif
                      @endif
                      @if($markupsDetail['destination_criteria'] == 'airport')
                      @if($markupsDetail['destination_name'] == 'all')
                      {{ $markupsDetail['destination_name'] }}
                      @else
                          @forelse($markupsDetail->getdestinationAirport->airportName as $airport_name)
                          {{$airport_name['airport_name']}} <br>
                          @endforeach
                        @endif
                      @endif
                      @if($markupsDetail['destination_criteria'] == 'all')
                        {{ 'All' }}
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <th>From Booking Date :</th>
                    <td>{{ \Carbon\Carbon::parse($markupsDetail->from_booking_date)->format('d-m-Y') }}</td>
                  </tr>
                  <tr>
                    <th>To Booking Date :</th>
                    <td>{{ \Carbon\Carbon::parse($markupsDetail->to_booking_date)->format('d-m-Y') }}</td>
                  </tr>
                  <tr>
                    <th>From Travel Date :</th>
                    <td>{{ \Carbon\Carbon::parse($markupsDetail->from_travel_date)->format('d-m-Y') }}</td>
                  </tr>
                  <tr>
                    <th>To Travel Date :</th>
                    <td>{{ \Carbon\Carbon::parse($markupsDetail->to_travel_date)->format('d-m-Y') }}</td>
                  </tr>
                  <tr>
                    <th>Booking Class :</th>
                    <td>{{ $markupsDetail->booking_class ?? '' }}</td>
                  </tr>
                  <tr>
                    <th>Cabin Class :</th>
                    <td>{{ ucfirst($markupsDetail->cabin_class ?? '') }}</td>
                  </tr>
                  <tr>
                    <th>Trip Type :</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $markupsDetail->trip_type)) }}</td>
                  </tr>
                  <tr>
                    <th>Pax Type :</th>
                    <td>{{ ucfirst($markupsDetail->pax_type ?? '') }}</td>
                  </tr>
                </table>
              </div>
              <div class="view_user_data discount">
                <h3 class="view_seo mb-2 pt-4">Markup</h3>
                <table class="">
                  <tr>
                    <th>Fare Type :</th>
                    <td>
                      {{ ucfirst($markupsDetail->fare_type ?? '') }}
                    </td>
                  </tr>
                  <tr>
                    <th>B2C Markup Type :</th>
                    <td> @if($markupsDetail->b2c_markup_type == 'percentage')
                      %Percentage
                      @elseif($markupsDetail->b2c_markup_type == 'fixed_amount')
                      Fixed amount
                      @endif</td>
                  </tr>
                  <tr>
                    <th>B2C Markup :</th>
                    <td> {{ $markupsDetail->b2c_markup ?? '' }}</td>
                  </tr>
                  <tr>
                    <th>B2B Markup Type :</th>
                    <td>@if($markupsDetail->b2b_markup_type == 'percentage')
                      %Percentage
                      @elseif($markupsDetail->b2b_markup_type == 'fixed_amount')
                      Fixed amount
                      @endif</td>
                  </tr>
                  <tr>
                    <th>B2B Markup :</th>
                    <td> {{ $markupsDetail->b2b_markup ?? '' }}</td>
                  </tr>
                  <tr>
                    <th>Comm On/Markup On :</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $markupsDetail->comm_markup_on ?? '')) }}</td>
                  </tr>
                  <tr>
                    <th>Priority :</th>
                    <td>{{ $markupsDetail->priority ?? '' }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="d-flex brdr-btm ">
            <div class="d-flex">
              <div class="view_user_data discount">
                <h3 class="view_seo mb-2 pt-4">Supplier</h3>
                <table class="">
                  @foreach($markupsDetail->getSupplier as $supplier)
                  <tr>
                    <td>{{ $supplier->getMarkupsSupplier->name ?? '' }}</td>
                  </tr>
                  @endforeach
                </table>
              </div>
              <div class="view_user_data discount">
                <h3 class="view_seo mb-2 pt-4">Airlines</h3>
                <div style="max-height: 200px; overflow-y: auto;">
                  @foreach($markupsDetail->getAirline as $airline)
                  <table>
                    <tr>
                      <td>
                        @foreach($airline->getMarkupsAirline->airlineCodeName as $air)
                        {{$air->airline_name}} <br>
                        @endforeach
                      </td>
                    </tr>
                  </table>
                  @endforeach
                </div>
              </div>

            </div>
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!--/. container-fluid -->
</section><!-- End Profile div -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<!-- Page specific script -->

@endsection