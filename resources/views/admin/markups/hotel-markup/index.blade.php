@extends('admin.layout.main')
@section('title', $header['title'])


@section('content')

      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">Hotel Markup</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard')}}">@lang('adminUser.dashboard')</a></li>
                <li class="breadcrumb-item active">Hotel Markup Rules List</li>
              </ol>
              <div class="breadcrumb-btn">
                <div class="add-breadcrumb">
                 <?php $default_markup_id = App\Models\DefaultMarkup::where('service_type_id',$appliedFilter['service_type_id'])->value('id'); 
                 ?>
                  @if($default_markup_id)
                  <a href="{{ route('default-flight-markups-edit.edit',$default_markup_id)}}?service_type={{$appliedFilter['service_type']}}" title="Edit">
                    <?xml version="1.0" encoding="utf-8"?><svg fill="#ffffff" height="15" width="15" version="1.1"
                      id="Layer_1" x="0px" y="0px" width="122.881px" height="122.88px" viewBox="0 0 122.881 122.88"
                      enable-background="new 0 0 122.881 122.88" xml:space="preserve">
                      <g>
                        <path
                          d="M56.573,4.868c0-0.655,0.132-1.283,0.37-1.859c0.249-0.6,0.61-1.137,1.056-1.583C58.879,0.545,60.097,0,61.44,0 c0.658,0,1.287,0.132,1.863,0.371c0.012,0.005,0.023,0.011,0.037,0.017c0.584,0.248,1.107,0.603,1.543,1.039 c0.881,0.88,1.426,2.098,1.426,3.442c0,0.03-0.002,0.06-0.006,0.089v51.62l51.619,0c0.029-0.003,0.061-0.006,0.09-0.006 c0.656,0,1.285,0.132,1.861,0.371c0.014,0.005,0.025,0.011,0.037,0.017c0.584,0.248,1.107,0.603,1.543,1.039 c0.881,0.88,1.428,2.098,1.428,3.441c0,0.654-0.133,1.283-0.371,1.859c-0.248,0.6-0.609,1.137-1.057,1.583 c-0.445,0.445-0.98,0.806-1.58,1.055v0.001c-0.576,0.238-1.205,0.37-1.861,0.37c-0.029,0-0.061-0.002-0.09-0.006l-51.619,0.001 v51.619c0.004,0.029,0.006,0.06,0.006,0.09c0,0.656-0.133,1.286-0.371,1.861c-0.006,0.014-0.012,0.025-0.018,0.037 c-0.248,0.584-0.602,1.107-1.037,1.543c-0.883,0.882-2.1,1.427-3.443,1.427c-0.654,0-1.283-0.132-1.859-0.371 c-0.6-0.248-1.137-0.609-1.583-1.056c-0.445-0.444-0.806-0.98-1.055-1.58h-0.001c-0.239-0.575-0.371-1.205-0.371-1.861 c0-0.03,0.002-0.061,0.006-0.09V66.303H4.958c-0.029,0.004-0.059,0.006-0.09,0.006c-0.654,0-1.283-0.132-1.859-0.371 c-0.6-0.248-1.137-0.609-1.583-1.056c-0.445-0.445-0.806-0.98-1.055-1.58H0.371C0.132,62.726,0,62.097,0,61.44 c0-0.655,0.132-1.283,0.371-1.859c0.249-0.6,0.61-1.137,1.056-1.583c0.881-0.881,2.098-1.426,3.442-1.426 c0.031,0,0.061,0.002,0.09,0.006l51.62,0l0-51.62C56.575,4.928,56.573,4.898,56.573,4.868L56.573,4.868z" />
                      </g>
                    </svg>
                    Default Markup
                  </a>
                    @else
                    <a href="{{ route('markups.addDefaultMarkup',['service_type'=>$appliedFilter['service_type']]) }}" title="Add">
                    <?xml version="1.0" encoding="utf-8"?><svg fill="#ffffff" height="15" width="15" version="1.1"
                      id="Layer_1" x="0px" y="0px" width="122.881px" height="122.88px" viewBox="0 0 122.881 122.88"
                      enable-background="new 0 0 122.881 122.88" xml:space="preserve">
                      <g>
                        <path
                          d="M56.573,4.868c0-0.655,0.132-1.283,0.37-1.859c0.249-0.6,0.61-1.137,1.056-1.583C58.879,0.545,60.097,0,61.44,0 c0.658,0,1.287,0.132,1.863,0.371c0.012,0.005,0.023,0.011,0.037,0.017c0.584,0.248,1.107,0.603,1.543,1.039 c0.881,0.88,1.426,2.098,1.426,3.442c0,0.03-0.002,0.06-0.006,0.089v51.62l51.619,0c0.029-0.003,0.061-0.006,0.09-0.006 c0.656,0,1.285,0.132,1.861,0.371c0.014,0.005,0.025,0.011,0.037,0.017c0.584,0.248,1.107,0.603,1.543,1.039 c0.881,0.88,1.428,2.098,1.428,3.441c0,0.654-0.133,1.283-0.371,1.859c-0.248,0.6-0.609,1.137-1.057,1.583 c-0.445,0.445-0.98,0.806-1.58,1.055v0.001c-0.576,0.238-1.205,0.37-1.861,0.37c-0.029,0-0.061-0.002-0.09-0.006l-51.619,0.001 v51.619c0.004,0.029,0.006,0.06,0.006,0.09c0,0.656-0.133,1.286-0.371,1.861c-0.006,0.014-0.012,0.025-0.018,0.037 c-0.248,0.584-0.602,1.107-1.037,1.543c-0.883,0.882-2.1,1.427-3.443,1.427c-0.654,0-1.283-0.132-1.859-0.371 c-0.6-0.248-1.137-0.609-1.583-1.056c-0.445-0.444-0.806-0.98-1.055-1.58h-0.001c-0.239-0.575-0.371-1.205-0.371-1.861 c0-0.03,0.002-0.061,0.006-0.09V66.303H4.958c-0.029,0.004-0.059,0.006-0.09,0.006c-0.654,0-1.283-0.132-1.859-0.371 c-0.6-0.248-1.137-0.609-1.583-1.056c-0.445-0.445-0.806-0.98-1.055-1.58H0.371C0.132,62.726,0,62.097,0,61.44 c0-0.655,0.132-1.283,0.371-1.859c0.249-0.6,0.61-1.137,1.056-1.583c0.881-0.881,2.098-1.426,3.442-1.426 c0.031,0,0.061,0.002,0.09,0.006l51.62,0l0-51.62C56.575,4.928,56.573,4.898,56.573,4.868L56.573,4.868z" />
                      </g>
                    </svg>
                    Default Markup
                  </a>
                    @endif
                </div>
                <div class="add-breadcrumb">
                  <a href="{{ route('hotel-markups.create',['service_type'=>$appliedFilter['service_type']]) }}" title="Add">
                    <?xml version="1.0" encoding="utf-8"?><svg fill="#ffffff" height="15" width="15" version="1.1"
                      id="Layer_1" x="0px" y="0px" width="122.881px" height="122.88px" viewBox="0 0 122.881 122.88"
                      enable-background="new 0 0 122.881 122.88" xml:space="preserve">
                      <g>
                        <path
                          d="M56.573,4.868c0-0.655,0.132-1.283,0.37-1.859c0.249-0.6,0.61-1.137,1.056-1.583C58.879,0.545,60.097,0,61.44,0 c0.658,0,1.287,0.132,1.863,0.371c0.012,0.005,0.023,0.011,0.037,0.017c0.584,0.248,1.107,0.603,1.543,1.039 c0.881,0.88,1.426,2.098,1.426,3.442c0,0.03-0.002,0.06-0.006,0.089v51.62l51.619,0c0.029-0.003,0.061-0.006,0.09-0.006 c0.656,0,1.285,0.132,1.861,0.371c0.014,0.005,0.025,0.011,0.037,0.017c0.584,0.248,1.107,0.603,1.543,1.039 c0.881,0.88,1.428,2.098,1.428,3.441c0,0.654-0.133,1.283-0.371,1.859c-0.248,0.6-0.609,1.137-1.057,1.583 c-0.445,0.445-0.98,0.806-1.58,1.055v0.001c-0.576,0.238-1.205,0.37-1.861,0.37c-0.029,0-0.061-0.002-0.09-0.006l-51.619,0.001 v51.619c0.004,0.029,0.006,0.06,0.006,0.09c0,0.656-0.133,1.286-0.371,1.861c-0.006,0.014-0.012,0.025-0.018,0.037 c-0.248,0.584-0.602,1.107-1.037,1.543c-0.883,0.882-2.1,1.427-3.443,1.427c-0.654,0-1.283-0.132-1.859-0.371 c-0.6-0.248-1.137-0.609-1.583-1.056c-0.445-0.444-0.806-0.98-1.055-1.58h-0.001c-0.239-0.575-0.371-1.205-0.371-1.861 c0-0.03,0.002-0.061,0.006-0.09V66.303H4.958c-0.029,0.004-0.059,0.006-0.09,0.006c-0.654,0-1.283-0.132-1.859-0.371 c-0.6-0.248-1.137-0.609-1.583-1.056c-0.445-0.445-0.806-0.98-1.055-1.58H0.371C0.132,62.726,0,62.097,0,61.44 c0-0.655,0.132-1.283,0.371-1.859c0.249-0.6,0.61-1.137,1.056-1.583c0.881-0.881,2.098-1.426,3.442-1.426 c0.031,0,0.061,0.002,0.09,0.006l51.62,0l0-51.62C56.575,4.928,56.573,4.898,56.573,4.868L56.573,4.868z" />
                      </g>
                    </svg>
                    Add
                  </a>
                </div>
                <div class="delete-breadcrumb">
                  <a data-url="{{route('delete.markups',['delete_markups_id'=>''])}}" title="Delete" class="deleteAllConfirmation btn-hover-del">
                    <svg fill="#ffffff" height="15" width="15" viewBox="0 0 24 24" width="24px" height="24px">
                      <path
                        d="M 10 2 L 9 3 L 5 3 C 4.4 3 4 3.4 4 4 C 4 4.6 4.4 5 5 5 L 7 5 L 17 5 L 19 5 C 19.6 5 20 4.6 20 4 C 20 3.4 19.6 3 19 3 L 15 3 L 14 2 L 10 2 z M 5 7 L 5 20 C 5 21.1 5.9 22 7 22 L 17 22 C 18.1 22 19 21.1 19 20 L 19 7 L 5 7 z M 9 9 C 9.6 9 10 9.4 10 10 L 10 19 C 10 19.6 9.6 20 9 20 C 8.4 20 8 19.6 8 19 L 8 10 C 8 9.4 8.4 9 9 9 z M 15 9 C 15.6 9 16 9.4 16 10 L 16 19 C 16 19.6 15.6 20 15 20 C 14.4 20 14 19.6 14 19 L 14 10 C 14 9.4 14.4 9 15 9 z" />
                    </svg>
                    @lang('travellers.delete')
                  </a>
                </div>
              </div>

              </div>
              <!-- /.col -->
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
                  <h3>Hotel Markup Rules List</h3>
                  <div class="gridlist-icons">
                  <?php if(isset($_GET['per_page'])  && $_GET['per_page'] !== ""){
                      ?>
                    <div>
                      <h6>Showing {{ $markupsData->firstItem() ? $markupsData->firstItem() :'0' }} to {{ $markupsData->lastItem() ? $markupsData->lastItem() :'0' }}
                          of total {!! $markupsData->total() !!} entries (filtered from {{ $markupsDataCount }} total entries) </h6>
                    </div>
                    <?php } else {?> 
                    <div>
                       <h6>Showing {{ $markupsData->firstItem() ? $markupsData->firstItem() :'0' }} to {{ $markupsData->lastItem() ? $markupsData->lastItem() :'0' }}
                          of total {!! $markupsData->total() !!} entries</h6>
                    </div>
                    <?php } ?>
                    <!-- Add Class d-none to hide element of filter -->
                    <div class="filter grid-icon-top" title="Filter">
                      <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                        aria-controls="collapseExample">
                        <svg fill="#fff" width="26px" height="26px" viewBox="0 0 24 24" version="1"
                          xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M19 6h-14c-1.1 0-1.4.6-.6 1.4l4.2 4.2c.8.8 1.4 2.3 1.4 3.4v5l4-2v-3.5c0-.8.6-2.1 1.4-2.9l4.2-4.2c.8-.8.5-1.4-.6-1.4z" />
                          </svg>
                      </a>
                    </div>
                  </div> 
                    <!-- collapse -->
                  <div class="collapse filter-collapse w-100" id="collapseExample">
                    <form autocomplete="off" id="filter" method="GET" action="{{route('hotel-markups.index')}}">
                    <input type="hidden" name="service_type" value="{{ $serviceType ?? '' }}">
                      <div class="card-filter card-body w-100 mt-3">
                        <div class="row">
                          <div class="col-md-10">
                            <div class="row">

                            <div class="col-md-4 filter-form mb-3">
                              <div class="form-floating">
                                <div class="form-item form-float-style">
                                  <input type="text" id="rule_name" name="rule_name" autocomplete="off" value="{{@$_GET['rule_name']}}" class="is-valid">
                                  <label for="sort">Rule Name</label>
                                </div>
                              </div>
                            </div>

                              <div class="col-md-4 filter-form mb-3">
                                <div class="form-floating">
                                  <div class="select top-space-rem after-drp form-float-style">
                                  <select data-live-search="true" id="channel" name="channel" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;">
                                    <option value="">Select Channel</option>
                                            <option value="back_office" @if($appliedFilter['channel'] == 'back_office') selected="selected" @endif>BackOffice</option>
                                            <option value="b2c" @if($appliedFilter['channel'] == 'b2c') selected="selected" @endif>B2C</option>
                                            <option value="b2b" @if($appliedFilter['channel'] == 'b2b') selected="selected" @endif>B2B</option>
                                            <option value="mobile" @if($appliedFilter['channel'] == 'mobile') selected="selected" @endif>Mobile</option>
                                        </select>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Channel</label>
                                 </div>
                              </div>
                            </div>
                              <div class="col-md-4 filter-form mb-3">
                                <div class="form-floating">
                                  <div class="select top-space-rem after-drp form-float-style">
                                  <select data-live-search="true" id="destinationCriteria" name="destinationCriteria" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;">
                                  <option value="">Select Destination Criteria</option>
                                            <option value="all" @if($appliedFilter['destinationCriteria'] == 'all') selected="selected" @endif>All</option>
                                            <option value="country" @if($appliedFilter['destinationCriteria'] == 'country') selected="selected" @endif>Country</option>
                                            <option value="city" @if($appliedFilter['destinationCriteria'] == 'city') selected="selected" @endif>City</option>
                                        </select>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Destination Criteria</label>
                                 </div>
                              </div>
                            </div>
                              
                              <div class="col-md-4 filter-form mb-3">
                                <div class="form-floating">
                                  <div class="select top-space-rem after-drp form-float-style">
                                  <select data-live-search="true" style="width: 100%;" id="commMarkupOn" name="commMarkupOn" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option disabled selected>Select Markup On</option>
                                            <option value="base_fare" @if($appliedFilter['commMarkupOn'] == 'base_fare') selected="selected" @endif>Base Fare</option>
                                            <option value="base_fare_yq" @if($appliedFilter['commMarkupOn'] == 'base_fare_yq') selected="selected" @endif>Base Fare+YQ</option>
                                            <option value="net_fare" @if($appliedFilter['commMarkupOn'] == 'net_fare') selected="selected" @endif>Net Fare</option>
                                            <option value="total_fare" @if($appliedFilter['commMarkupOn'] == 'total_fare') selected="selected" @endif>Total Fare</option>
                                        </select>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Comm On/Markup On </label>
                                 </div>
                              </div>
                            </div>
                            <div class="col-md-4 filter-form">
                              <div class="form-item form-float-style">
                                <input type="text" class="datepicker is-valid" name="from_booking_date" id="from_booking_date" value="{{ @$_GET['from_booking_date'] }}" placeholder="DD/MM/YYYY" autocomplete="off">
                                <label for="datepicker">From Booking Date</label>
                              </div>
                            </div>
                            <div class="col-md-4 filter-form">
                              <div class="form-item form-float-style">
                                <input type="text" class="datepicker is-valid" name="to_booking_date" id="to_booking_date" value="{{ @$_GET['to_booking_date'] }}" placeholder="DD/MM/YYYY" autocomplete="off">
                                <label for="datepicker">To Booking Date</label>
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
                                <a href="{{route('hotel-markups.index')}}">
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

                </div>

                <div class="row mt-3">
                  <div class="col-12">
                    <div class="table-card">
                      <!-- /.card-header -->
                      <div class="card-body table-radius table-responsive p-0">
                        <table class="table table-head-fixed text-nowrap">
                          <thead class="td-data-color">
                            <tr>
                            <th class="list-checkbox">
                                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1">
                                </th>
                              <th><span>Sr. No.</span></th>
                              <th>Rule ID</th>
                              <th>Rule Name<a href="{{Request::url().$queryStringConcat}}&order_by=rule_name&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                                  <th>Priority<a href="{{Request::url().$queryStringConcat}}&order_by=priority&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                                  <th>Channel<a href="{{Request::url().$queryStringConcat}}&order_by=channel&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                  shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Service Type</th>
                              <th>Supplier</th>
                              <!-- <th>Origin Criteria<a href="{{Request::url().$queryStringConcat}}&order_by=originCriteria&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Origin</th> -->
                              <th>Destination Criteria<a href="{{Request::url().$queryStringConcat}}&order_by=destinationCriteria&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Destination</th>
                              <!-- <th>Booking Class<a href="{{Request::url().$queryStringConcat}}&order_by=booking_class&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Cabin Class<a href="{{Request::url().$queryStringConcat}}&order_by=cabin_class&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th> -->
                              <th>Booking Date</th>
                              <th>CheckIn Date</th>
                              <th>Star Category</th>
                              <th>Fare Type<a href="{{Request::url().$queryStringConcat}}&order_by=fare_type&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                                  <th>Amount</th>
                              <th>B2C Markup Type</th>
                              <th>B2C Value<a href="{{Request::url().$queryStringConcat}}&order_by=b2c_markup&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                              shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                              image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                              viewBox="0 0 322 511.21">
                              <path fill-rule="nonzero"
                              d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                            </svg></a></th>
                            <th>B2B Markup Type</th>
                            <th>B2B Value<a href="{{Request::url().$queryStringConcat}}&order_by=b2b_markup&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                    d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Markup On<a href="{{Request::url().$queryStringConcat}}&order_by=comm_markup_on&sorting={{($appliedFilter['sorting']=='desc') ? 'asc' : 'desc'}}"><svg width="12" height="12" fill="#ffffff"
                                    shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                                    image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
                                    viewBox="0 0 322 511.21">
                                    <path fill-rule="nonzero"
                                      d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z" />
                                  </svg></a></th>
                              <th>Agent Name</th>
                              <th>Agent Group</th>
                              
                              <th>Action</th>
                            
                            </tr>
                          </thead>
                          <tbody class="td-data-color">
                            @foreach($markupsData as $key => $data)
                                <tr>
                                <th class="list-checkbox">
                                <input class="form-check-input" name="check" type="checkbox" value="{{$data['id']}}"
                                id="flexCheckDefault">
                              </th>
                                <td class="no-data-list">
                                  {{ ++$i }}
                                </td>
                                  <td class="">
                                    {{ $data['id'] ?? '' }}
                                  </td>
                                  <td>   
                                    {{ $data['rule_name'] ?? '' }}
                                  </td>
                                  <td>
                                    {{ $data->priority ?? '' }}
                                  </td>
                                  <td>
                                    @forelse($data->getChannel as $channel)
                                    {{$channel['channel']}} <br>
                                    @endforeach
                                  </td>
                                  <td>
                                    {{$data->getServiceType->code ?? ''}}
                                  </td>
                                  <td>
                                  @foreach($data->getSupplier as $supplier)
                                    {{$supplier->getMarkupsSupplier->code}} <br>
                                  @endforeach
                                  </td>
                                  
                                  <td>
                                    {{ $data->destination_criteria ?? '' }}
                                  </td>
                                  <td>
                                  @if($data->destination_criteria == 'country')
                                    @php
                                    $getCountry = \App\Models\Country::with('countryCode')->where('iso_code', $data->destination_name)->first();
                                    @endphp
                                    
                                        @if($getCountry)
                                            @foreach($getCountry->countryCode as $country_code)
                                                {{ $country_code->country_name }} <br>
                                            @endforeach
                                        @elseif($data->destination_name == 'all')
                                            {{ 'All' }}
                                        @else
                                            {{ '' }}
                                        @endif
                                    @elseif($data->destination_criteria == 'city')
                                        @php
                                            $getCity = \App\Models\City::with('cityCode')->where('iso_code', $data->destination_name)->first();
                                        @endphp
                                        @if($getCity)
                                            @foreach($getCity->cityCode as $city_code)
                                                {{ $city_code->city_name }} <br>
                                            @endforeach
                                        @elseif($data->destination_name == 'all')
                                            {{ 'All' }}
                                        @else
                                            {{ '' }}
                                        @endif
                                    @elseif($data->destination_criteria == 'airport')
                                        @php
                                            $getAirport = \App\Models\Airport::with('airportName')->where('id', $data->destination_name)->first();
                                        @endphp
                                        @if($getAirport)
                                            @foreach($getAirport->airportName as $airport)
                                                {{ $airport->airport_name }} <br>
                                            @endforeach
                                        @elseif($data->destination_name == 'all')
                                            {{ 'All' }}
                                        @else
                                            {{ '' }}
                                        @endif
                                    @elseif($data->destination_criteria == 'all')
                                        {{ 'All' }}
                                    @endif

                                  </td>
                                  
                                  <td>
                                    {{ $data->from_booking_date ?? '' }} <br>
                                    {{ $data->to_booking_date ?? '' }}
                                  </td>
                                  <td>
                                    {{ $data->from_check_in_date ?? '' }} <br>
                                    {{ $data->to_check_in_date ?? '' }}
                                  </td>
                                  <td>
                                  {{ $data->star_category ?? '' }}
                                  </td>
                                  <td>
                                    {{ $data->fare_type ?? '' }}
                                  </td> 
                                  <td>
                                    {{ $data->from_price_range ?? '' }} to <br>
                                    {{ $data->to_price_range ?? '' }}
                                  </td>
                                  <td>
                                    @if($data->b2c_markup_type == 'percentage')
                                    Percentage(%)
                                    @elseif($data->b2c_markup_type == 'fixed_amount')
                                    Fixed
                                    @endif
                                  </td>
                                  <td>
                                    {{ $data->b2c_markup ?? '' }}
                                  </td>
                                  <td>
                                    @if($data->b2b_markup_type == 'percentage')
                                    Percentage(%)
                                    @elseif($data->b2b_markup_type == 'fixed_amount')
                                    Fixed
                                    @endif
                                  </td>
                                  <td>
                                    {{ $data->b2b_markup ?? '' }}
                                  </td>
                                  <td>
                                    {{ str_replace('_', ' ', $data->comm_markup_on) }}
                                  </td>
                                  <td>
                                    @if($data->getAgent)
                                    @forelse($data->getAgent as $agent)
                                      {{$agent['getAgentName']['full_name'] ?? ''}} <br>
                                    @endforeach
                                    @endif
                                  </td>
                                 
                                  <td>
                                  </td>
                                  
                                  <td class="table-action">
                                  @if($data['deleted_at'] == null)
                                   <span>
                                    <a href="{{ route('hotel-markups.show',$data['id']) }}?service_type={{ $data['getServiceType']['name'] ?? '' }}" title="View">
                                      <?xml version="1.0" encoding="utf-8"?><svg version="1.1" fill="#2188ef" id="Layer_1"
                                      width="20" height="20" x="0px" y="0px" viewBox="0 0 122.88 83.78"
                                      style="enable-background:new 0 0 122.88 83.78" xml:space="preserve">
                                      <g>
                                        <path
                                        d="M95.73,10.81c10.53,7.09,19.6,17.37,26.48,29.86l0.67,1.22l-0.67,1.21c-6.88,12.49-15.96,22.77-26.48,29.86 C85.46,79.88,73.8,83.78,61.44,83.78c-12.36,0-24.02-3.9-34.28-10.81C16.62,65.87,7.55,55.59,0.67,43.1L0,41.89l0.67-1.22 c6.88-12.49,15.95-22.77,26.48-29.86C37.42,3.9,49.08,0,61.44,0C73.8,0,85.45,3.9,95.73,10.81L95.73,10.81z M60.79,22.17l4.08,0.39 c-1.45,2.18-2.31,4.82-2.31,7.67c0,7.48,5.86,13.54,13.1,13.54c2.32,0,4.5-0.62,6.39-1.72c0.03,0.47,0.05,0.94,0.05,1.42 c0,11.77-9.54,21.31-21.31,21.31c-11.77,0-21.31-9.54-21.31-21.31C39.48,31.71,49.02,22.17,60.79,22.17L60.79,22.17L60.79,22.17z M109,41.89c-5.5-9.66-12.61-17.6-20.79-23.11c-8.05-5.42-17.15-8.48-26.77-8.48c-9.61,0-18.71,3.06-26.76,8.48 c-8.18,5.51-15.29,13.45-20.8,23.11c5.5,9.66,12.62,17.6,20.8,23.1c8.05,5.42,17.15,8.48,26.76,8.48c9.62,0,18.71-3.06,26.77-8.48 C96.39,59.49,103.5,51.55,109,41.89L109,41.89z" />
                                      </g>
                                    </svg>
                                  </a></span>
                                    <span>
                                      <a href="{{ route('hotel-markups.edit',$data['id']) }}?service_type={{ $data['getServiceType']['name'] ?? '' }}" title="Edit">
                                        <?xml version="1.0"?><svg fill="#198754" viewBox="0 0 24 24" width="20" height="20">
                                          <path
                                            d="M 19.171875 2 C 18.448125 2 17.724375 2.275625 17.171875 2.828125 L 16 4 L 20 8 L 21.171875 6.828125 C 22.275875 5.724125 22.275875 3.933125 21.171875 2.828125 C 20.619375 2.275625 19.895625 2 19.171875 2 z M 14.5 5.5 L 3 17 L 3 21 L 7 21 L 18.5 9.5 L 14.5 5.5 z" />
                                        </svg></a>
                                    </span>
                                    @endif
                                    @if($data['deleted_at'] != null)
                                    <span>
                                        <a href="" type="button" title="Restore"
                                        class="btn-hover-del"><?xml version="1.0" ?><svg fill="#035efc" height="20" viewBox="0 0 48 48" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h48v48h-48z" fill="none"/><path d="M25.99 6c-9.95 0-17.99 8.06-17.99 18h-6l7.79 7.79.14.29 8.07-8.08h-6c0-7.73 6.27-14 14-14s14 6.27 14 14-6.27 14-14 14c-3.87 0-7.36-1.58-9.89-4.11l-2.83 2.83c3.25 3.26 7.74 5.28 12.71 5.28 9.95 0 18.01-8.06 18.01-18s-8.06-18-18.01-18zm-1.99 10v10l8.56 5.08 1.44-2.43-7-4.15v-8.5h-3z"/>
                                      </svg></a>
                                    </span>
                                    @endif
                                    <span>
                                        <a data-url="{{route('delete.markups',['delete_markups_id'=>$data['id']])}}" type="button" title="Delete"
                                        class="deleteConfirmation btn-hover-del"><svg fill="#ee3137" viewBox="0 0 24 24"
                                          width="20" height="20">
                                          <path
                                            d="M 10 2 L 9 3 L 5 3 C 4.4 3 4 3.4 4 4 C 4 4.6 4.4 5 5 5 L 7 5 L 17 5 L 19 5 C 19.6 5 20 4.6 20 4 C 20 3.4 19.6 3 19 3 L 15 3 L 14 2 L 10 2 z M 5 7 L 5 20 C 5 21.1 5.9 22 7 22 L 17 22 C 18.1 22 19 21.1 19 20 L 19 7 L 5 7 z M 9 9 C 9.6 9 10 9.4 10 10 L 10 19 C 10 19.6 9.6 20 9 20 C 8.4 20 8 19.6 8 19 L 8 10 C 8 9.4 8.4 9 9 9 z M 15 9 C 15.6 9 16 9.4 16 10 L 16 19 C 16 19.6 15.6 20 15 20 C 14.4 20 14 19.6 14 19 L 14 10 C 14 9.4 14.4 9 15 9 z" />
                                        </svg></a>
                                    </span>
                                  </td>
                                </tr>
                              @endforeach
                              @if($markupsData->isEmpty())
                              <tr>
                                <td colspan="12" style="text-align: center;">No Record Found</td>
                              </tr>
                              @endif
                              </tbody>
                        </table>

                      </div>
                      <nav class="pagination-grid" aria-label="Page  navigation example">
                        <ul class="pagination">
                            <?php if($markupsData->hasPages()) { ?>
                          {!! $markupsData->appends(Request::except('page'))->render() !!}
                            <?php } ?>
                        </ul>
                      </nav>
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
  <!-- Page specific script -->
  <script>
$("#commMarkupOn").select2();
$("#destinationCriteria").select2();
$("#cabinClass").select2();
$("#originCriteria").select2();
$("#channel").select2();
$('#origin,#destination').select2({
        ajax: {
            url: "{{ route('markups.fetchOrigin') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page || 1,
                    "_token": '{{ csrf_token() }}'
                };
            },
            processResults: function (data) {
                var mappedData = $.map(data, function (airport) {
                    return {
                        id: airport.id,
                        text: airport.airname
                    };
                });

                return {
                    results: mappedData,
                    pagination: {
                        more: mappedData.length >= 10
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 0
    });

    document.getElementById('flexCheckDefault1').onclick = function () {
      
      var checkboxes = document.getElementsByName('check');
      for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
      }
    }

  </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

  @append 