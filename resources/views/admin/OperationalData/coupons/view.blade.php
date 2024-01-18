@extends('admin.layout.main')
@section('title', $header['title'])
  
@section('content')
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['title'] }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('coupons.dashboard') </a></li>
                <li class="breadcrumb-item"><a href="{{ route('coupons.index') }}">@lang('coupons.moduleHeading')</a></li>
                <li class="breadcrumb-item active">@lang('coupons.view')</li>
              </ol>
              <div class="breadcrumb-btn">
                <div class="add-breadcrumb @if($couponDetail['status'] == 2) d-none @endif">
                  <a class="" href="{{ route('coupons.edit',$couponDetail['id']) }}"  title="Edit">
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
                      @if($couponDetail['upload_image'] != "")
                     <img data-toggle="popover" height="200" width="200" src="{{ $couponDetail['upload_image'] ?: URL::asset('assets/images/no-image.png')}}" alt="">
                      @else
                      <img class="viewcon-user" width="150" height="150" src="{{ $couponDetail['upload_image'] ?: URL::asset('assets/images/no-image.png')}}" alt="">
                      @endif
                  
                  </div>
                  <?php 
                    $haystack = $couponDetail['mobile'];
                    $needle    = ' ';
                    $cust_isdCode = strstr($haystack, $needle, true);
                    $data = $couponDetail['mobile'];
                    $cust_mobile = substr($data, strpos($data, " ") + 1);   
                  ?>
                  <div class="view_user_data">
                    <table class="">
                    <div class="discount">
                            <h5>General</h5>
                        </div>
                      <tr>
                        <th>Customer type :</th>
                        <td>{{$couponDetail->customer_type}}</td>
                      </tr>
                        <th>Coupon Name :</th>
                        <td>
                          @foreach($couponDetail['couponCodeName'] as $couponName)
                          {{ ucwords($couponName->coupon_name) }}<br>
                          @endforeach
                        </td>
                      </tr>
                      <tr>
                      <tr>
                        <th>Coupon Code  :</th>
                        <td> {{$couponDetail['coupon_code']}}</td>
                      </tr>
                      <tr>
                        <th>Coupon Amount  :</th>
                        <td> {{$couponDetail['coupon_amount']}}</td>
                      </tr>
                      <tr>
                        <th>Discount Type :</th>
                        <td>{{$couponDetail->discount_type ?: '-'}}</td>
                      </tr>
                      <tr>
                        <th>From Date :</th>
                        <td>{{ getDateTimeZone($couponDetail->from_date) ?: '-' }}</td>
                      </tr>
                      <tr>
                        <th>To Date :</th>
                        <td>{{ getDateTimeZone($couponDetail->to_date) ?: '-' }}</td>
                      </tr>
                    </table>
                  </div>
                  <div class="view_user_data">
                    <table class="">
                      <div class="discount">
                        <h5>Usage Restriction</h5>
                      </div>
                      <tr>
                        <th>Minimum Spend :</th>
                        <td>{{$couponDetail->minimum_spend}}</td>
                      </tr>
                      <tr>
                        <th>Maximum Spend :</th>
                        <td>{{$couponDetail->maximum_spend}}</td>
                      </tr>
                      <tr style="display: none;">
                        <th>@lang('coupons.sortOrder') :</th>
                        <td>2</td>
                      </tr>
                       <tr>
                        <th>Only For Services :</th>
                        <td>
                          @foreach($couponDetail['serviceType'] as $data)
                            {{ $data['name'] }}
                          @endforeach  
                        </td>
                      </tr>
                      <tr>
                        <th>Customer Name :</th>
                        <td>
                        <?php 
                        $custName ='';
                              foreach($couponDetail['applicableCustomer'] as $coupon_code){
                                $customerDetail = App\Models\Customer::select('first_name','last_name')->where('id',$coupon_code['customer_id'])->first();
                               $custName .= $customerDetail['first_name'].' '.$customerDetail['last_name'].',';
                              }
                              echo rtrim($custName, ", ");
                        ?>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="view_user_data col-3 mt-4">
                    <table class="">
                      <div class="discount">
                        <h5>Usage Limits</h5>
                      </div>
                      <tr>
                        <th>Limit Per Coupon :</th>
                        <td>
                          {{$couponDetail['limit_per_coupon']}}
                        </td>
                      </tr>
                      <tr>
                        <th>Limit Per Customer :</th>
                        <td>
                          {{$couponDetail['limit_per_customer']}}
                        </td>
                      </tr>  
                    </table>
                </div>
             
              </div>
            </div>
            <!-- /.row -->
          </div>
          <!--/. container-fluid -->
      </section>

@endsection