<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
         @page {
    size: landscape !important;
  }
    </style>


    <style>
        * {
            margin: 0;
            padding: 0;
            outline: none;
            box-sizing: border-box;
            list-style: none;
        }

        body {
            font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        .wrapper {
            max-width: 95%;
            overflow-x: hidden;
            margin: 0 auto;
        }

        .table-ordreport {
            text-transform: uppercase;
            color: #646464;
        }

        table {
            border-collapse: collapse;
        }
        
        tbody{
            width: 100%;
            float: left;
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

        thead tr, tbody tr{
            width: 100%;
        }

        thead tr th {
            border: 1px solid #dee2e6;
            font-size: 10px;
            padding: .5rem;
            width: max-content;
            min-height: 40px;
            float: left;
            vertical-align: middle;
        }

        tbody tr td{
            font-size: 10px !important;
            padding: 5px 0;            
            text-align: center;
        }

        .top-part {
            text-align: center !important;
        }

        .top-part .space-rem {
            margin-bottom: 0;
        }
        .content .card{
            width: 100%;
            overflow: hidden;
            display: block;
        }
    </style>
</head>

<body>
    <section class="content">
        <div class="wrapper">
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
                    <p class="table-ordreport mb-0 pt-2 space-rem" style="margin-top: 20px">BACKEND LOG REPORT</p>

                    <?php if ($transactionDate != 'all_dates') { ?>
                        <p class="table-ordreport mb-0 pt-2" style="margin-bottom: 20px; margin-top: 10px">{{dateFunction($fromDate)}} - {{dateFunction($toDate)}}</p>
                    <?php } else { ?>
                        <p class="table-ordreport mb-0 pt-2" style="margin-bottom: 20px; margin-top: 10px">All Dates</p>
                    <?php } ?>


                   
                </div>
                <!-- /.card-header -->
                <div class="card-body ordr-scr-perent">
                    <div class="table-scrl-list">
                        <table id="dataList1" class="table table-bordered table-striped" style="width: 100%;">
                            <thead>
                                <tr role="row" style="background-color: #256BB7; border: 1px solid #dee2e6; color: #fff; font-weight: 600;">
                                    <th class="sorting sorting_asc table-head-notify" aria-sort="ascending">Sr. No.</th>
                                    <th class="table-head-notify">User Name</th>
                                    <th class="table-head-notify">Device ID</th>
                                    <th class="table-head-notify">Browser Name</th>
                                    <th class="table-head-notify">Country</th>
                                    <th class="table-head-notify">City</th>
                                    <th class="table-head-notify">Request URL</th>
                                    <th class="table-head-notify">Request</th>
                                    <th class="table-">Response</th>
                                    <th class="table-head-notify">Created Date</th>

                                </tr>
                            </thead>
                            <tbody <?php $i = 0; ?> @foreach($logData AS $key=>$data)
                                <tr class="table-data-condd">
                                    <td class="text-center">{{ ++$i }}</td>
                                    <td class="text-center">{{ $data->user_id }}</td>
                                    <td class="text-center">{{ $data->device_id }}</td>
                                    <td class="text-center">{{ $data->browser_name }}</td>
                                    <td class="text-center">{{ $data->country }}</td>
                                    <td class="text-center">{{ $data->city }}</td>
                                    <td class="text-center">{{ $data->request_url }}</td>
                                    <td class="text-center">{{ $data->request }}</td>
                                    <td class="text-center">{{ $data->response }}</td>
                                    <td class="text-center">{{ getDateTimeZone($data->created_at) }}</td>
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
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

</body>

</html>