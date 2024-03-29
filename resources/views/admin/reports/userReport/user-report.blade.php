@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')

<style>
  .form-item input.is-valids+label {
    font-size: 11px;
    top: -5px;
  }
</style>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
        <h1 class="m-0">{{ $header['heading'] }}</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('order.dashboard') </a></li>
          <li class="breadcrumb-item active">User List Report</li>
        </ol>
        <div class="breadcrumb-btn">
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
            <h3 class="mt-2 mb-0">User List Report
            </h3>
            <div class="gridlist-icons">

              <div class="export grid-icon-top" title="PDF">
                <a href="{{ route('admin.master-user-report.pdf',['userData' => $userData,  'fromDate'=>$fromDate, 'toDate'=> $toDate,'user_status'=>$user_status,'user_name'=>$user_name,'agency_name'=>$agency_name,'agency_id' => $agency_id]) }}" target="_blank" class="btn btn-success float-right plush-icon"><i class="far fa-file-pdf"></i></a>
                </a>
              </div>
              <div class="export grid-icon-top" title="Export">
                <a href="{{ route('admin.master-user-report.export',['userData' => $userData,  'fromDate'=>$fromDate, 'toDate'=> $toDate,'user_status'=>$user_status,'user_name'=>$user_name,'agency_name'=>$agency_name,'agency_id' => $agency_id]) }}" class="btn btn-success float-right plush-icon"><i class="fas fa-file-export"></i></a>
                </a>
              </div>
              <div class="filter grid-icon-top d-none" title="Filter">
                <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                  <svg fill="#fff" width="26px" height="26px" viewBox="0 0 24 24" version="1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 6h-14c-1.1 0-1.4.6-.6 1.4l4.2 4.2c.8.8 1.4 2.3 1.4 3.4v5l4-2v-3.5c0-.8.6-2.1 1.4-2.9l4.2-4.2c.8-.8.5-1.4-.6-1.4z" />
                  </svg>
                </a>
              </div>
            </div>
            <!-- collapse -->
            <div class="filter-collapse w-100" id="collapseExample">
              <form autocomplete="off" id="filter" method="GET" action="{{route('reports.userReport.user-report')}}">
                <div class="card-filter card-body w-100 mt-3">
                  <div class="row">
                    <div class="col-md-9">
                      <div class="row">
                        <div class="col-md-4 filter-form">
                          <div class="form-item form-float-style serach-rem">
                            <div class="select top-space-rem after-drp form-float-style ">
                              <select data-live-search="true" id="selectSearch" name="name" class="selectSearch select-text height_drp is-valid">
                                <option value="" selected>Select User Name</option>
                                @foreach($userName as $key=>$data)
                                <option value="{{@$data['name']}}" @if($data['name']==$user_name) selected @endif>{{$data['name']}}</option>
                                @endforeach
                              </select>
                              <label class="select-label searchable-drp">User Name</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4 filter-form">
                          <div class="form-item form-float-style serach-rem">
                            <div class="select top-space-rem after-drp form-float-style ">
                              <select data-live-search="true" id="SelExample" name="agency_id" style="width: 100%;" class="selectSearch select-text height_drp is-valid">
                                <option value="" selected>Select Agency</option>
                                @foreach($agencyData as $key=>$data)
                                <option value="{{$data['id']}}" @if($data['id']==$agency_id) selected @endif>{{$data['full_name']}}</option>
                                @endforeach
                              </select>
                              <label class="select-label searchable-drp">Agency Name</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4 filter-form">
                          <div class="form-item form-float-style serach-rem">
                            <div class="select top-space-rem after-drp form-float-style ">
                              <select data-live-search="true" id="SelExample" name="status" class="selectpicker select-text height_drp is-valid">
                                <option value="" selected>Select Status</option>
                                <option @if(@$_GET['status']=="1" ) selected="selected" @endif value="1">Active</option>
                                <option @if(@$_GET['status']=="0" ) selected="selected" @endif value="0">Inactive</option>
                                <option @if(@$_GET['status']=="2" ) selected="selected" @endif value="2">Terminated</option>
                              </select>
                              <label class="select-label searchable-drp">Status</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4 filter-form">
                          <div class="form-item form-float-style serach-rem">
                            <div class="select top-space-rem after-drp form-float-style ">
                              <select data-init-plugin="select2" id="fixDate" name="transactionDate" class="selectpicker select-text height_drp is-valid" style="width:100%" data-select2-id="date_drp_select" tabindex="-1" aria-hidden="true">
                                <option value="">Select Days</option>
                                <option value="last365days">Last 365 Days</option>
                                <option value="custom">Custom</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="thisWeek">This Week</option>
                                <option value="thisQuarter">This Quarter</option>
                                <option value="thisMonth">This Month</option>
                                <option value="last30days">Last 30 Days</option>
                                <option value="thisYear">This Year</option>
                                <option value="lastWeek">Last Week</option>
                                <option value="lastQuarter">Last Quarter</option>
                                <option value="lastMonth">Last Month</option>
                                <option value="lastYear">Last Year</option>
                              </select>
                              <label class="select-label searchable-drp">Select duration</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4 filter-from">
                          <div class="form-item form-float-style" data-target-input="nearest">
                            <input type="text" class="datepicker is-valids" name="fromDate" id="fromDate" value="<?= date('d-m-Y', strtotime($fromDate)) ?>" class="is-valid" placeholder="DD/MM/YYYY" autocomplete="on">
                            <label for="datepicker">@lang('order.fromDate')</label>
                          </div>
                        </div>
                        <div class="col-md-4 filter-from">
                          <div class="form-item form-float-style" data-target-input="nearest">
                            <input type="text" class="datepicker is-valids" name="toDate" id="toDate" value="<?= date('d-m-Y', strtotime($toDate)) ?>" class="is-valid" placeholder="DD/MM/YYYY" autocomplete="on">
                            <label for="todate">@lang('order.toDate')</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 filter-buttons">
                      <div class="filter-btm-btn" title="Apply">
                        <button type="submit" class="submit-filter">
                          <svg fill="#ffffff" width="17" height="17" viewBox="0 0 448 512">
                            <path d="M438.6 105.4C451.1 117.9 451.1 138.1 438.6 150.6L182.6 406.6C170.1 419.1 149.9 419.1 137.4 406.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4C21.87 220.9 42.13 220.9 54.63 233.4L159.1 338.7L393.4 105.4C405.9 92.88 426.1 92.88 438.6 105.4H438.6z">
                            </path>
                          </svg>
                        </button>
                      </div>
                      <div class="filter-btm-btn" title="Refresh">
                        <a href="{{route('reports.userReport.user-report')}}" class="refress-filter">
                          <svg fill="#ffffff" width="17" height="17" viewBox="0 0 512 512">
                            <path d="M464 16c-17.67 0-32 14.31-32 32v74.09C392.1 66.52 327.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.89 5.5 34.88-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c50.5 0 96.26 24.55 124.4 64H336c-17.67 0-32 14.31-32 32s14.33 32 32 32h128c17.67 0 32-14.31 32-32V48C496 30.31 481.7 16 464 16zM441.8 289.6c-16.92-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-50.5 0-96.25-24.55-124.4-64H176c17.67 0 32-14.31 32-32s-14.33-32-32-32h-128c-17.67 0-32 14.31-32 32v144c0 17.69 14.33 32 32 32s32-14.31 32-32v-74.09C119.9 445.5 184.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z">
                            </path>
                          </svg>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="report_logo_cont" style="text-align: center">
            <div>
              <div class="rep_logo mb-3">
                @php
                $logoData = "";
                @$logo = App\Models\Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'];
                if($logo){
                $logoData = $logo;
                }else{
                $logoData = URL::asset('assets/images/logo.png');
                }
                @endphp
                <img src="{{@$logoData}}" class="" alt="" width="20%" height="20%">
              </div>
              <div class="" style="color:black;">
                <span>USER LIST REPORT</span>
                <span>
                  <?php if ($transactionDate != 'all_dates') { ?>
                    <p class="table-ordreport mb-0 pt-2">{{dateFunction($fromDate)}} - {{dateFunction($toDate)}}</p>
                  <?php } else { ?>
                    <p class="table-ordreport mb-0 pt-2">All Dates</p>
                  <?php } ?>
                </span>
                @if(!empty(@$agency_name))

                <span class="table-ordreport mb-0 pt-2">Agency Name : {{ucwords(@$agency_name)}}</span>

                @endif

                @if(!empty(@$user_name))

                <br><span class="table-ordreport mb-0 pt-2">User Name : {{ucwords(@$user_name)}}</span>

                @endif
                @if(!empty(@$user_status))
                <br><span class="table-ordreport mb-0 pt-2">User Status : {{ucwords(@$user_status)}}</span>
                @endif
                @if(!empty(@$person_name))

                <br><span class="table-ordreport mb-0 pt-2">Full Name : {{ucwords(@$person_name)}}</span>

                @endif
                @if(!empty(@$person_mobile))

                <span class="table-ordreport mb-0 pt-2">Mobile : {{ucwords(@$person_mobile)}}</span>

                @endif
                @if(!empty(@$order_number))

                <span class="table-ordreport mb-0 pt-2">Invoice Number : {{ucwords(@$order_number)}}</span>

                @endif
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

                        <th> <span>@lang('order.srNo') </span></th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Role</th>
                        <th>Created Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody class="td-data-color">
                      <?php

                      $color = 'grey';
                      ?>
                      @foreach($userData as $key=>$data)
                      <tr>
                        <td class="no-data-list">
                          {{++$i}}
                        </td>
                        <td>
                          {{$data['name']}}
                        </td>
                        <td>
                          {{$data['email']}}
                        </td>
                        <td class="no-data-list text-start">
                          {{$data['mobile']}}
                        </td>
                        <td>
                          {{$data['getRole']['name']??''}}
                        </td>
                        <td>
                          {{ getDateTimeZone($data->created_at) }}
                        </td>
                        <td>
                          {{$data['user_status_text']}}
                        </td>
                      </tr>
                      @endforeach
                      @if($userData)
                      @if($userData->isEmpty())
                      <tr>
                        <td colspan="12" style="text-align: center;">No Record Found</td>
                      </tr>
                      @endif
                      @endif
                    </tbody>
                  </table>

                </div>

                @if($userData)
                <nav class="pagination-grid" aria-label="Page  navigation example">
                  <ul class="pagination">
                    <?php if ($userData->hasPages()) { ?>
                      {!! $userData->appends(Request::except('page'))->render() !!}
                    <?php } ?>
                  </ul>
                </nav>
                @endif
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <div class="report_btm_cont" style="text-align: center">
              <div>

                <div class="repoty_title_on" style="color:black;">
                  <span class="">
                    <?php
                    $getTimeZone = count(App\Models\Setting::where('config_key', 'general|site|timeZone')->get('value')) > 0 ? App\Models\Setting::where('config_key', 'general|site|timeZone')->get('value')[0]['value'] : "Asia/Kolkata";
                    date_default_timezone_set($getTimeZone);
                    $istDate = date('l, F d, Y h:i:s A', time());
                    ?>
                    <?= $istDate ?> IST GMT+5:30</p>
                  </span>
                </div>
              </div>
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
<script>
  $(document).ready(function() {
    $('.selectSearch').select2();
  });
</script>
<script>
  <?php if (isset($_GET['transactionDate']) && $_GET['transactionDate'] != "") { ?>
    <?php if ($_GET['transactionDate'] != 'all_days') { ?>
      $("#fixDate").val('<?= $_GET['transactionDate'] ?>');
      $("#fixDate").change();
    <?php } ?>
  <?php } else { ?>
    $("#fixDate").val('today');
    $("#fixDate").change();
  <?php } ?>
  $(document).ready(function() {

    $("#fixDate").change(function() {
      const selectedValue = $(this).val();
      const currentDate = new Date();

      let fromDate, toDate;

      switch (selectedValue) {
        case 'last365days':
          fromDate = new Date(currentDate);
          fromDate.setDate(currentDate.getDate() - 365);
          toDate = new Date(currentDate);
          break;
        case 'custom':
          // Customize this part for custom date range input
          // Example: fromDate = new Date(2022, 0, 1); toDate = new Date(2022, 11, 31);
          break;
        case 'today':
          fromDate = new Date(currentDate);
          toDate = new Date(currentDate);
          break;
        case 'yesterday':
          fromDate = new Date(currentDate);
          fromDate.setDate(currentDate.getDate() - 1);
          toDate = new Date(currentDate);
          toDate.setDate(currentDate.getDate() - 1);
          break;
        case 'thisWeek':
          var thisWeekStartDate = new Date(currentDate);
          thisWeekStartDate.setDate(currentDate.getDate() - currentDate.getDay());
          var thisWeekEndDate = new Date(currentDate);
          thisWeekEndDate.setDate(currentDate.getDate() + (6 - currentDate.getDay()));
          fromDate = thisWeekStartDate;
          toDate = thisWeekEndDate;
          break;
        case 'thisMonth':
          fromDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
          toDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
          break;
        case 'thisQuarter':
          const currentMonth = currentDate.getMonth();
          const quarterStartMonth = Math.floor(currentMonth / 3) * 3;
          fromDate = new Date(currentDate.getFullYear(), quarterStartMonth, 1);
          toDate = new Date(currentDate.getFullYear(), quarterStartMonth + 3, 0);
          break;
        case 'last30days':
          fromDate = new Date(currentDate);
          fromDate.setDate(currentDate.getDate() - 30);
          toDate = new Date(currentDate);
          break;
        case 'lastQuarter':
          const lastQuarterStartMonth = Math.floor((currentDate.getMonth() - 3) / 3) * 3;
          fromDate = new Date(currentDate.getFullYear(), lastQuarterStartMonth, 1);
          toDate = new Date(currentDate.getFullYear(), lastQuarterStartMonth + 3, 0);
          break;
        case 'thisYear':
          fromDate = new Date(currentDate.getFullYear(), 0, 1);
          toDate = new Date(currentDate.getFullYear(), 11, 31);
          break;
        case 'lastWeek':
          var lastWeekStartDate = new Date(currentDate);
          lastWeekStartDate.setDate(currentDate.getDate() - currentDate.getDay() - 7);

          // End of the last week (Saturday)
          var lastWeekEndDate = new Date(currentDate);
          lastWeekEndDate.setDate(currentDate.getDate() - currentDate.getDay() - 1);

          fromDate = lastWeekStartDate;
          toDate = lastWeekEndDate;
          break;
        case 'lastMonth':
          fromDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
          toDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0);
          break;
        case 'lastQuarter':
          // Customize this part for last quarter date range input
          break;
        case 'lastYear':
          fromDate = new Date(currentDate.getFullYear() - 1, 0, 1);
          toDate = new Date(currentDate.getFullYear() - 1, 11, 31);
          break;
        case 'all_dates':
          fromDate = "";
          toDate = "";
          break;
        default:
          break;
      }

      $("#fromDate").datepicker("setDate", fromDate);
      $("#toDate").datepicker("setDate", toDate);

    });

    $("#fixDate").trigger('change');
  });
</script>
<script src="{{ URL::asset('assets/plugins/reports/report.js')}}"></script>

<script>
  $('*[value=""]').removeClass('is-valid');
  // INCLUDE JQUERY & JQUERY UI 1.12.1
  $(function() {
    $(".datepicker").datepicker({
      dateFormat: "dd-mm-yy",
      duration: "fast"
    });
  });
</script>


<script>
  $('*[value=""]').removeClass('is-valid');
  // INCLUDE JQUERY & JQUERY UI 1.12.1
  $(function() {
    $(".datepicker").datepicker({
      dateFormat: "dd-mm-yy",
      duration: "fast"
    });
  });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script src="{{ URL::asset('assets/plugins/reports/report.js')}}"></script>
@append