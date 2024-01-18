<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        @page {
            margin-bottom: 4px;
            margin-top: 3px;

        }
    </style>


    <style>
        body {
            font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        @media (min-width: 1510px) {
            .dataTables_scroll {
                /* overflow: auto !important; */
                width: 100% !important;
            }

            .dataTables_scrollHead {
                overflow: unset !important;
            }

            .dataTables_scrollBody {
                overflow: unset !important;
            }
        }

        @media (max-width: 1500px) {
            .table-scrl-list .dataTables_scroll {
                min-height: 65vh;
            }

            .dataTables_scroll {
                overflow: auto;
                width: 100%;
            }

            .dataTables_scrollHead {
                overflow: unset !important;
            }

            .dataTables_scrollBody {
                overflow: unset !important;
            }
        }

        .table-ordreport {
            text-transform: uppercase;
            color: #646464;
        }

        .font-totalorder {
            font-size: 20px;
            font-weight: bold;
        }

        .filter-btn {
            float: right;
        }

        .filter-collapse {
            clear: both;
        }

        .filter-part {
            display: flex;
            justify-content: end;
        }

        .filter-comp {
            display: flex;
            justify-content: end;

        }

        .filter-comp .run-rep {
            border: 1px solid #28a745;
            margin-right: 8px;
            border-radius: 7px;
            min-width: 10%;
            text-align: center;
            color: #28a745;
            padding: 5px 0;
        }


        .filter-comp .run-rep:hover {
            background-color: #28a745;
            color: #fff;
        }

        .filter-comp .reset-rep {
            border: 1px solid #dc3545;
            border-radius: 7px;
            min-width: 10%;
            text-align: center;
            color: #dc3545;
            padding: 5px 0;

        }

        .filter-comp .reset-rep:hover {
            background-color: #dc3545;
            color: #fff;
        }

        .filter-lable label {
            color: #727272;
            font-weight: 500 !important;
        }

        table {
            border-collapse: collapse;
        }

        tbody .table-data-con:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05);
        }

        tbody .table-data-con td {
            padding: 0.5rem;
            text-align: center;
            border: 1px solid #dee2e6;
            font-size: 13px;
            font-weight: 400;
        }

        thead tr th {
            border: 1px solid #dee2e6;
            font-size: 13px;
            padding: .5rem;
        }

        .top-part {
            text-align: center !important;
        }

        .top-part .space-rem {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <section class="content">
        <div class="card">
            <div class="text-center pt-3 top-part" style="margin-top: 2rem;">
                @php
                $logoData = "";
                @$logo = App\Models\Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'];
                if($logo){
                $logoData = $logo;
                }else{
                $logoData = URL::asset('assets/images/logo.png');
                }
                @endphp
                <img src="{{@$logoData}}" class="" alt="" width="10%" height="10%">
                <p class="table-ordreport mb-0 pt-2 space-rem">NEW CUSTOMER SIGNUP REPORT</p>

                <?php if ($transactionDate != 'all_dates') { ?>
                    <p class="table-ordreport mb-0 pt-2">{{dateFunction($fromDate)}} - {{dateFunction($toDate)}}</p>
                <?php } else { ?>
                    <p class="table-ordreport mb-0 pt-2">All Dates</p>
                <?php } ?>

                
                @if(!empty(@$_GET['agency_name']))
                <p class="table-ordreport mb-0 pt-2">Agency Name: {{ucwords(@$_GET['agency_name'])}}</p>
                @endif
                @if(!empty(@$_GET['agencyType']))
                <p class="table-ordreport mb-0 pt-2">Agency Type: {{ucwords(@$_GET['agencyType'])}}</p>
                @endif
                @if(!empty(@$_GET['agency_status']))
                <p class="table-ordreport mb-0 pt-2">Agency Status: {{ucwords(@$_GET['agency_status'])}}</p>
                @endif



            </div>
            <!-- /.card-header -->
            <div class="card-body ordr-scr-perent">
                <div class="table-scrl-list">
                    <table id="dataList1" class="table table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr role="row" style="background-color: #256BB7; border: 1px solid #dee2e6; color: #fff; font-weight: 600;">
                                <th class="sorting sorting_asc table-head-notify " tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending">Sr. No.</th>
                                <th class="table-head-notify" tabindex="0" aria-controls="example2" rowspan="1" colspan="1">Created Date</th>
                                <th class="table-head-notify" tabindex="0" aria-controls="example2" rowspan="1" colspan="1">Customer Name</th>
                                <th class="table-head-notify" tabindex="0" aria-controls="example2" rowspan="1" colspan="1">Email</th>
                                <th class="table-head-notify" tabindex="0" aria-controls="example2" rowspan="1" colspan="1">Mobile Number</th>

                            </tr>
                        </thead>
                        <tbody> <?php $i = 0; ?>
                        @foreach($customerData AS $key=>$data)

                            <tr class="table-data-con">
                                <td class="text-center">{{++$i}}</td>
                                <td class="text-center">{{$data->created_at}}</td>
                                <td class="text-center">{{$data->first_name.$data->last_name}}</td>
                                <td class="text-center">{{$data->email}}</td>
                                <td class="text-center">{{$data->mobile}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="text-center pt-5 top-part">
                <p class="table-ordreport mb-2">
                    <?php
                    $getTimeZone = count(App\Models\Setting::where('config_key', 'general|site|timeZone')->get('value')) > 0 ? App\Models\Setting::where('config_key', 'general|site|timeZone')->get('value')[0]['value'] : "Asia/Kolkata";
                    date_default_timezone_set($getTimeZone);
                    $istDate = date('l, F d, Y h:i:s A', time());
                    ?>
                    <?= $istDate ?> IST GMT+5:30</p>
            </div>
        </div>
    </section>
</body>

</html>