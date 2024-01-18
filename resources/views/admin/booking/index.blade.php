@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['heading'] }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('customers.dashboard') </a></li>
                <li class="breadcrumb-item active">Booking </li>
              </ol>
              <div class="breadcrumb-btn">
                <div class="add-breadcrumb">
                  <a href="#" title="Add">
                    <?xml version="1.0" encoding="utf-8"?><svg fill="#ffffff" height="15" width="15" version="1.1"
                      id="Layer_1" x="0px" y="0px" width="122.881px" height="122.88px" viewBox="0 0 122.881 122.88"
                      enable-background="new 0 0 122.881 122.88" xml:space="preserve">
                      <g>
                        <path
                          d="M56.573,4.868c0-0.655,0.132-1.283,0.37-1.859c0.249-0.6,0.61-1.137,1.056-1.583C58.879,0.545,60.097,0,61.44,0 c0.658,0,1.287,0.132,1.863,0.371c0.012,0.005,0.023,0.011,0.037,0.017c0.584,0.248,1.107,0.603,1.543,1.039 c0.881,0.88,1.426,2.098,1.426,3.442c0,0.03-0.002,0.06-0.006,0.089v51.62l51.619,0c0.029-0.003,0.061-0.006,0.09-0.006 c0.656,0,1.285,0.132,1.861,0.371c0.014,0.005,0.025,0.011,0.037,0.017c0.584,0.248,1.107,0.603,1.543,1.039 c0.881,0.88,1.428,2.098,1.428,3.441c0,0.654-0.133,1.283-0.371,1.859c-0.248,0.6-0.609,1.137-1.057,1.583 c-0.445,0.445-0.98,0.806-1.58,1.055v0.001c-0.576,0.238-1.205,0.37-1.861,0.37c-0.029,0-0.061-0.002-0.09-0.006l-51.619,0.001 v51.619c0.004,0.029,0.006,0.06,0.006,0.09c0,0.656-0.133,1.286-0.371,1.861c-0.006,0.014-0.012,0.025-0.018,0.037 c-0.248,0.584-0.602,1.107-1.037,1.543c-0.883,0.882-2.1,1.427-3.443,1.427c-0.654,0-1.283-0.132-1.859-0.371 c-0.6-0.248-1.137-0.609-1.583-1.056c-0.445-0.444-0.806-0.98-1.055-1.58h-0.001c-0.239-0.575-0.371-1.205-0.371-1.861 c0-0.03,0.002-0.061,0.006-0.09V66.303H4.958c-0.029,0.004-0.059,0.006-0.09,0.006c-0.654,0-1.283-0.132-1.859-0.371 c-0.6-0.248-1.137-0.609-1.583-1.056c-0.445-0.445-0.806-0.98-1.055-1.58H0.371C0.132,62.726,0,62.097,0,61.44 c0-0.655,0.132-1.283,0.371-1.859c0.249-0.6,0.61-1.137,1.056-1.583c0.881-0.881,2.098-1.426,3.442-1.426 c0.031,0,0.061,0.002,0.09,0.006l51.62,0l0-51.62C56.575,4.928,56.573,4.898,56.573,4.868L56.573,4.868z" />
                      </g>
                    </svg>
                    @lang('customers.add')
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
            <div class="card pb-3 pt-3 px-3 w-100">
              <div class="discount">
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
                <div class="grid-list-head py-2">
                  <h3 class="mt-2 mb-0">
                  @lang('Booking List')
                  </h3>
                  <div class="gridlist-icons">
                    <?php if(isset($_GET['per_page'])  && $_GET['per_page'] !== ""){
                      ?>
                  <div>
                      <h6>Showing {{ $bookingData->firstItem() ? $bookingData->firstItem() :'0' }} to {{ $bookingData->lastItem() ? $bookingData->lastItem() :'0' }}
                          of total {!! $bookingData->total() !!} entries (filtered from {{ $bookingDataCount }} total entries) </h6>
                  </div>
                  <?php } else {?> 
                  <div>
                      <h6>Showing {{ $bookingData->firstItem() ? $bookingData->firstItem() :'0' }} to {{ $bookingData->lastItem() ? $bookingData->lastItem() :'0' }}
                        of total {!! $bookingData->total() !!} entries</h6>
                  </div>
                    <?php } ?>
                   
                  <div class="filter grid-icon-top" title="Filter">
                    <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                      aria-controls="collapseExample">
                      <svg fill="#fff" width="26px" height="26px" viewBox="0 0 24 24" version="1" xmlns="http://www.w3.org/2000/svg"><path d="M19 6h-14c-1.1 0-1.4.6-.6 1.4l4.2 4.2c.8.8 1.4 2.3 1.4 3.4v5l4-2v-3.5c0-.8.6-2.1 1.4-2.9l4.2-4.2c.8-.8.5-1.4-.6-1.4z"/></svg>
                    </a>
                  </div>
                </div>
                 <!-- collapse -->
                 <div class="collapse filter-collapse w-100" id="collapseExample">
                     <form autocomplete="off" id="filter" method="GET" action="{{route('booking.index')}}">
                         <div class="card-filter card-body w-100 mt-3">
                             <div class="row">
                                 <div class="col-md-10">
                                      <div class="row">
                                      <div class="col-md-4 filter-from">
                                        <div class="form-item form-float-style" data-target-input="nearest">
                                          <input type="text" class="datepicker is-valids" name="booking_date" id="booking_date" value="{{ @$_GET['booking_date'] }}" class="is-valid" placeholder="DD/MM/YYYY" autocomplete="off">
                                          <label for="datepicker">Booking Date</label>
                                        </div>
                                      </div>
                                      <div class="col-md-4 filter-form">
                                        <div class="form-floating form-item mb-0">
                                          <div class="form-item form-float-style serach-rem mb-0">
                                            <div class="select top-space-rem after-drp form-float-style">
                                              <select data-live-search="true" style="width: 100%;" id="supplier" name="supplier_id" class="order-td-input selectpicker select-text height_drp is-valid">
                                                <option value="" selected disabled>Select Supplier</option>
                                                @foreach($supplierDataList as $data)
                                                <option value="{{ $data['id'] }}" @if($appliedFilter['supplier_id'] == $data['id']) selected="selected" @endif >{{ $data['name'] }}</option>
                                                @endforeach
                                              </select>
                                              <label class="select-label searchable-drp">Supplier</label>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="col-md-4 filter-form">
                                        <div class="form-floating form-item mb-0">
                                          <div class="form-item form-float-style serach-rem mb-0">
                                            <div class="select top-space-rem after-drp form-float-style">
                                              <select data-live-search="true" style="width: 100%;" id="service_id" name="service_id" class="order-td-input selectpicker select-text height_drp is-valid">
                                                <option value="" selected disabled>Select Service Type</option>
                                                @foreach($getServiceType as $data)
                                                <option value="{{ $data['id'] }}" @if($appliedFilter['service_id'] == $data['id']) selected="selected" @endif >{{ $data['name'] }}</option>
                                                @endforeach
                                              </select>
                                              <label class="select-label searchable-drp">Service Type</label>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="col-md-4 filter-form mb-0">
                                        <div class="form-floating">
                                          <div class="form-item form-float-style">
                                            <input type="text" id="first_name" class="is-valid" name="booking_id" autocomplete="off" value="{{@$_GET['booking_id']}}">
                                            <label for="booking_id">System Ref ID</label>
                                          </div>
                                        </div> 
                                      </div>

                                      <div class="col-md-4 filter-form mb-0">
                                        <div class="form-floating">
                                          <div class="form-item form-float-style">
                                            <input type="text" id="first_name" class="is-valid" name="booking_id" autocomplete="off" value="{{@$_GET['booking_id']}}">
                                            <label for="booking_id">Booking ID</label>
                                          </div>
                                        </div> 
                                      </div>

                                      <div class="col-md-4 filter-form mb-0">
                                        <div class="form-floating">
                                          <div class="form-item form-float-style">
                                            <input type="text" id="price_from" class="is-valid" name="price_from" autocomplete="off" value="{{@$_GET['price_from']}}">
                                            <label for="price_from">Price Range From</label>
                                          </div>
                                        </div> 
                                      </div>

                                      <div class="col-md-4 filter-form mb-0">
                                        <div class="form-floating">
                                          <div class="form-item form-float-style">
                                            <input type="text" id="price_to" class="is-valid" name="price_to" autocomplete="off" value="{{@$_GET['price_to']}}">
                                            <label for="price_to">Price Range To</label>
                                          </div>
                                        </div> 
                                      </div>
                                      
                                      <div class="col-md-4 filter-form mb-0">
                                        <div class="form-floating">
                                          <div class="form-item form-float-style">
                                            <input type="text" id="first_name" class="is-valid" name="booking_id" autocomplete="off" value="{{@$_GET['booking_id']}}">
                                            <label for="first_name">PNR Number</label>
                                          </div>
                                        </div> 
                                      </div>

                                      <!-- <div class="col-md-3 filter-form mb-0">
                                        <div class="form-floating">
                                          <div class="form-item form-float-style">
                                            <input type="text" name="mobile" class="is-valid" id="mobile" autocomplete="off" value="{{@$_GET['mobile']}}"  >
                                            <label for="mobile">@lang('customers.mobileNumber')</label>
                                          </div>
                                        </div>
                                      </div> -->

                                      <div class="col-md-4 filter-form mb-0">
                                        <div class="form-floating">
                                          <div class="form-item form-float-style">
                                            <input type="text" id="customer_name" class="is-valid" name="customer_name" autocomplete="off" value="{{@$_GET['customer_name']}}">
                                            <label for="customer_name">Customer Name</label>
                                          </div>
                                        </div> 
                                      </div>

                                      <div class="col-md-4 filter-form">
                                        <div class="form-floating form-item mb-0">
                                          <div class="form-item form-float-style serach-rem mb-0">
                                            <div class="select top-space-rem after-drp form-float-style ">
                                              <select data-live-search="true" id="booking_status" name="booking_status" class="order-td-input selectpicker select-text height_drp is-valid">
                                                <option value="" selected disabled>Select Status</option>
                                                <option value="pending" @if($appliedFilter['booking_status'] == 'pending') selected="selected" @endif >Pending</option>
                                                <option value="processing" @if($appliedFilter['booking_status'] == 'processing') selected="selected"  @endif>Processing</option>
                                                <option value="confirmed" @if($appliedFilter['booking_status'] == 'confirmed') selected="selected"  @endif>Confirmed</option>
                                                <option value="failed" @if($appliedFilter['booking_status'] == 'failed') selected="selected"  @endif>Failed</option>
                                                <option value="cancelled" @if($appliedFilter['booking_status'] == 'cancelled') selected="selected"  @endif>Cancelled</option>
                                              </select>
                                              <label class="select-label searchable-drp">Booking Status</label>
                                            </div>                        
                                          </div>
                                        </div>
                                      </div> 

                                      <div class="col-md-4 filter-form">
                                        <div class="form-floating form-item mb-0">
                                          <div class="form-item form-float-style serach-rem mb-0">
                                            <div class="select top-space-rem after-drp form-float-style ">
                                              <select data-live-search="true" id="booking_status" name="booking_status" class="order-td-input selectpicker select-text height_drp is-valid">
                                                <option value="" selected disabled>Select Status</option>
                                                <option value="pending" @if($appliedFilter['booking_status'] == 'pending') selected="selected" @endif >Pending</option>
                                                <option value="processing" @if($appliedFilter['booking_status'] == 'processing') selected="selected"  @endif>Processing</option>
                                                <option value="confirmed" @if($appliedFilter['booking_status'] == 'confirmed') selected="selected"  @endif>Confirmed</option>
                                                <option value="failed" @if($appliedFilter['booking_status'] == 'failed') selected="selected"  @endif>Failed</option>
                                                <option value="cancelled" @if($appliedFilter['booking_status'] == 'cancelled') selected="selected"  @endif>Cancelled</option>
                                              </select>
                                              <label class="select-label searchable-drp">Payment Status</label>
                                            </div>                        
                                          </div>
                                        </div>
                                      </div> 

                                      <div class="col-md-4 filter-form">
                                        <div class="form-floating form-item mb-0">
                                          <div class="form-item form-float-style serach-rem mb-0">
                                            <div class="select top-space-rem after-drp form-float-style">
                                              <select data-live-search="true" style="width: 100%;" id="agency_id" name="agency_id" class="order-td-input selectpicker select-text height_drp is-valid">
                                                <option value="" selected disabled>Select Agency Name</option>
                                                @foreach($getAgency as $data)
                                                <option value="{{ $data['id'] }}" @if($appliedFilter['agency_id'] == $data['id']) selected="selected" @endif >{{ $data['full_name'] }}</option>
                                                @endforeach
                                              </select>
                                              <label class="select-label searchable-drp">Agency Name</label>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                      <!-- <div class="col-md-3 filter-form">
                                        <div class="form-floating form-item mb-0">
                                          <div class="form-item form-float-style serach-rem mb-0">
                                            <div class="select top-space-rem after-drp form-float-style ">
                                              <select data-live-search="true" id="slect_finish" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                                <option value="" selected disabled>Select Status</option>
                                                <option value="active" @if($appliedFilter['status'] == 'active') selected="selected" @endif >@lang('customers.active')</option>
                                                <option value="inactive" @if($appliedFilter['status'] == 'inactive') selected="selected"  @endif>@lang('customers.inActive')</option>
                                                <option value="terminated" @if($appliedFilter['status'] == 'terminated') selected="selected"  @endif>@lang('customers.terminated')</option>
                                              </select>
                                              <label class="select-label searchable-drp">@lang('customers.status')</label>
                                            </div>                        
                                          </div>
                                        </div>
                                      </div>  -->

                                      <div class="col-md-4 filter-form mt-4">
                                        <div class="form-floating form-item mb-0">
                                          <div class="form-item form-float-style serach-rem mb-0">
                                            <div class="select top-space-rem after-drp form-float-style ">
                                              <select data-live-search="true" id="slect_finish" name="per_page" class="order-td-input selectpicker select-text height_drp is-valid">
                                                <option value="" selected disabled>Select Per Page</option>
                                                <option @if($appliedFilter['per_page'] == 5) selected="selected" @endif value="5">5</option>
                                                <option @if($appliedFilter['per_page'] == 10) selected="selected" @endif value="10">10</option>                                    
                                                <option @if($appliedFilter['per_page'] == 15) selected="selected" @endif value="15">15</option>                                    
                                                <option @if($appliedFilter['per_page'] == 20) selected="selected" @endif value="20">20</option>                                    
                                                <option @if($appliedFilter['per_page'] == 25) selected="selected" @endif value="25">25</option>                                    
                                                <option @if($appliedFilter['per_page'] == 50) selected="selected" @endif value="50">50</option>                                    
                                                <option @if($appliedFilter['per_page'] == 100) selected="selected" @endif value="100">100</option>
                                              </select>
                                              <label class="select-label searchable-drp">@lang('customers.perPageData')</label>
                                            </div>                        
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                 </div>
                                 <div class="col-md-2 filter-buttons">
                                    <button type="submit" class="submit-filter filter-btm-btn" title="Apply">
                                      <a href="">
                                      <svg fill="#ffffff" width="17" height="17" viewBox="0 0 448 512">
                                      <path
                                        d="M438.6 105.4C451.1 117.9 451.1 138.1 438.6 150.6L182.6 406.6C170.1 419.1 149.9 419.1 137.4 406.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4C21.87 220.9 42.13 220.9 54.63 233.4L159.1 338.7L393.4 105.4C405.9 92.88 426.1 92.88 438.6 105.4H438.6z" />
                                      </svg>
                                      </a>
                                    </button>
                                    <div class="refress-filter filter-btm-btn" title="Refresh">
                                      <a href="{{route('booking.index')}}">
                                        <svg fill="#ffffff" width="17" height="17" viewBox="0 0 512 512">
                                        <path
                                          d="M464 16c-17.67 0-32 14.31-32 32v74.09C392.1 66.52 327.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.89 5.5 34.88-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c50.5 0 96.26 24.55 124.4 64H336c-17.67 0-32 14.31-32 32s14.33 32 32 32h128c17.67 0 32-14.31 32-32V48C496 30.31 481.7 16 464 16zM441.8 289.6c-16.92-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-50.5 0-96.25-24.55-124.4-64H176c17.67 0 32-14.31 32-32s-14.33-32-32-32h-128c-17.67 0-32 14.31-32 32v144c0 17.69 14.33 32 32 32s32-14.31 32-32v-74.09C119.9 445.5 184.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z" />
                                        </svg>
                                      </a>
                                    </div>
                                 </div>
                             </div>
                         </div>
                     </form>
                </div>
                </div>

                <div class="row mt-3">
                  <div class="col-12">
                    <div class="table-card">
                      <!-- /.card-header -->
                      <div class="card-body table-radius table-responsive p-0">
                        <table class="table table-head-fixed text-nowrap">
                          <thead class="td-data-color">
                            <tr>
                              
                              <th> <span>@lang('customers.srNo') </span></th>
                              <th>Service <a href="{{Request::url().$queryStringConcat}}&order_by=service_id&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                                  <th>Supplier Ref Id </th>
                               
                                  <th>Booking Ref </th>
                                <th>Supplier<a href="{{Request::url().$queryStringConcat}}&order_by=supplier_id&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Customer <a href="{{Request::url().$queryStringConcat}}&order_by=customer_id&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Agency <a href="{{Request::url().$queryStringConcat}}&order_by=agency_id&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Description</th>
                              <th>Booking Date <a href="{{Request::url().$queryStringConcat}}&order_by=booking_date&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>PNR Number</th>
                              <th>Price <a href="{{Request::url().$queryStringConcat}}&order_by=sub_total&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Booking Status <a href="{{Request::url().$queryStringConcat}}&order_by=booking_status&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Supplier Currency</th>
                              <th>Customer Currency</th>
                              <th>Payment Status</th>
                              <th>Is Guest</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody class="td-data-color">
                           @forelse($bookingData as $key=>$data)
                           <?php 
                           $className = "";
                           if($data['status'] == 'deleted'){
                               $className = "softdelete";
                           }
                           ?>
                            <tr class="{{$className}}">
                             
                              <td>
                                {{++$i}}
                              </td>
                              <td>
                                {{ $data['getServiceType']['name'] ?? '' }}
                              </td>
                              <td>
                                {{ $data['supplier_booking_ref'] ?? '-' }}
                              </td>
                              
                              <td>
                                {{ $data['booking_ref'] ?? '-' }}
                              </td>
                              <td>
                                {{ $data['getSupplier']['name'] ?? '-' }}
                              </td>
                              <td>
                                {{ $data['getCustomer']['first_name'] ?? '-' }}
                              </td>
                              <td>
                                {{ $data['getAgency']['full_name'] ?? '-' }}
                              </td>
                              <td>
                                {{ $data['description'] ?? '-' }}
                              </td>
                              <td>
                                {{ getDateTimeZone($data['booking_date']) ?? '-' }}
                              </td>
                              <td>
                                {{ $data['pnr_number'] ?? '-' }}
                              </td>
                              <td>
                                SAR {{ $data['admin_sub_total'] ?? '-' }}
                              </td>
                              <td>
                                {{ $data['booking_status'] ?? '-' }}
                              </td>
                              <td>
                                {{ $data['admin_currency'] ?? '-' }}
                              </td>
                              <td>
                                {{ $data['customer_currency'] ?? '-' }}
                              </td>
                              <td>
                                {{  '-' }}
                              </td>
                              <td>
                                {{ $data['is_guest'] ?? '-' }}
                              </td>
                          
                              <td class="table-action">
                                  <a href="{{ route('booking.show',$data['id']) }}?service={{ $data['getServiceType']['name'] ?? '' }}" title="View">
                                    <?xml version="1.0" encoding="utf-8"?><svg version="1.1" fill="#2188ef" id="Layer_1"
                                      width="20" height="20" x="0px" y="0px" viewBox="0 0 122.88 83.78"
                                      style="enable-background:new 0 0 122.88 83.78" xml:space="preserve">
                                      <g>
                                        <path
                                          d="M95.73,10.81c10.53,7.09,19.6,17.37,26.48,29.86l0.67,1.22l-0.67,1.21c-6.88,12.49-15.96,22.77-26.48,29.86 C85.46,79.88,73.8,83.78,61.44,83.78c-12.36,0-24.02-3.9-34.28-10.81C16.62,65.87,7.55,55.59,0.67,43.1L0,41.89l0.67-1.22 c6.88-12.49,15.95-22.77,26.48-29.86C37.42,3.9,49.08,0,61.44,0C73.8,0,85.45,3.9,95.73,10.81L95.73,10.81z M60.79,22.17l4.08,0.39 c-1.45,2.18-2.31,4.82-2.31,7.67c0,7.48,5.86,13.54,13.1,13.54c2.32,0,4.5-0.62,6.39-1.72c0.03,0.47,0.05,0.94,0.05,1.42 c0,11.77-9.54,21.31-21.31,21.31c-11.77,0-21.31-9.54-21.31-21.31C39.48,31.71,49.02,22.17,60.79,22.17L60.79,22.17L60.79,22.17z M109,41.89c-5.5-9.66-12.61-17.6-20.79-23.11c-8.05-5.42-17.15-8.48-26.77-8.48c-9.61,0-18.71,3.06-26.76,8.48 c-8.18,5.51-15.29,13.45-20.8,23.11c5.5,9.66,12.62,17.6,20.8,23.1c8.05,5.42,17.15,8.48,26.76,8.48c9.62,0,18.71-3.06,26.77-8.48 C96.39,59.49,103.5,51.55,109,41.89L109,41.89z" />
                                      </g>
                                    </svg> 
                                  </a></span> 
                                 
                              </td>
                            </tr>
                            @endforeach
                            @if($bookingData)
                                @if($bookingData->isEmpty())
                                <tr>
                                    <td colspan="12" style="text-align: center;">No Record Found</td>
                                </tr>
                                @endif
                            @endif
                             
                          </tbody>
                        </table>

                      </div>            
                      @if($bookingData)
                        <nav class="pagination-grid" aria-label="Page  navigation example">
                          <ul class="pagination">
                              <?php if($bookingData->hasPages()) { ?>
                            {!! $bookingData->appends(Request::except('page'))->render() !!}
                              <?php } ?>
                          </ul>
                        </nav>
                      @endif
                      <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!--/. container-fluid -->
      </section>
  @endsection
  @section('js')
    <!-- /.content-wrapper -->
    <script>
    
    $("#supplier").select2();
    // $('*[value=""]').removeClass('is-valid');
 $(function() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            yearRange: '-40:+40', // Specify a range of selectable years
        });
    });
</script>
<script>
  document.getElementById('flexCheckDefault1').onclick = function () {
      
      var checkboxes = document.getElementsByName('check');
      for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
      }
    }
  </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

  @append