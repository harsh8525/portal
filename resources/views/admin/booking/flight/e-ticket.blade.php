<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Destination</title>
  <link rel="stylesheet" href="indexone.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* FONT FAMILY FROM GOOGLE FONTS */
    @page {
      margin: 0.5cm 0.5cm 0.5cm 1.5cm;
    }

    * {
      padding: 0 !important;
      margin: 0 auto !important;
      box-sizing: border-box;
      list-style: none;
      outline: none;
    }

    body {
      font-family: 'Roboto', sans-serif;
    }

    /* ================================================ ONE */
    .one {
      float: left;
      width: 100%;
      margin-top: 50px;
      padding-bottom: 5px;
      border-bottom: 3px solid #4A7EBB;
    }

    .left-one {
      float: left;
      width: 40%;
      line-height: 24px;
      padding-top: 15px;
    }

    .left-one h3 {
      margin-bottom: 0 !important;
    }

    .left-one ul {
      padding: 0;
      list-style: none;
    }

    .left-one ul li {
      list-style: none;
    }

    .center-one {
      float: left;
      width: 12%;
      padding: 30px 0;
      text-align: center;
    }

    .center-one img {
      width: 20%;
    }

    .right-one {
      text-align: right;
    }

    .ro-first {
      font-size: 20px;
      font-weight: bold;
    }

    .ro-second {
      font-size: 18px;
      font-weight: bold;
    }

    .ro-third {
      font-size: 24px;
      font-weight: bold;
    }

    .ro-fourth {
      font-weight: bold;
      font-size: 18px;
    }

    .date-text {
      float: right;
      width: 100%;
      font-size: 16px;
      color: gray;
      text-align: right;
      padding: 6px 1px;
    }

    .zero-text {
      color: black;
      font-weight: bold;
    }

    .date-text-arb {
      padding-left: 8px;
      font-family: 'Noto Sans Arabic', sans-serif !important;

    }

    /* ================================================ TWO */
    .two {
      width: 100%;
      float: left;
      font-weight: 600;
      padding-bottom: 10px;
      border-bottom: 3px solid #4A7EBB;
    }

    .left-two {
      float: left;
      width: 50%;
      line-height: 23px;

    }

    .lt-one {
      float: left;
      list-style: none;
      width: 40%;
    }

    .lt-one ul,
    .rt-one ul,
    .lt-two ul,
    .rt-two ul {
      padding: 0;
      list-style: none;
    }

    .lt-two ul li span,
    .rt-two ul li span {
      font-weight: 700 !important;
    }

    .rt-one {
      float: left;
      list-style: none;
      width: 40%;
    }

    .lt-two {
      float: left;
      list-style: none;
      width: 60%;
    }

    .rt-two {
      float: left;
      list-style: none;
      width: 60%;
    }

    .z-text {
      font-weight: 900;
    }

    .lt-one p {
      color: gray;
      font-weight: bold;
      font-size: 14px;
    }

    .rt-one p {
      color: gray;
      font-weight: bold;
      font-size: 14px;
    }

    .right-two {
      float: left;
      width: 50%;
      display: flex;
      justify-content: space-between;
      line-height: 23px;

    }

    .e-ticket {
      float: left;
      width: 100%;
      color: gray;
      text-align: center;
      padding: 8px 1px;
      line-height: 35px;
    }

    .e-ticket-eng {
      font-size: 24px;
      color: black;
      margin: 0;
      text-shadow: 1px 1px 1px gray;
    }


    .airline-text {
      float: left;
      width: 100%;
      color: gray;
      font-size: 20px;
      font-weight: 500;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .red-text {
      color: red;
      font-weight: bold;
      font-size: 22px;
    }

    /* ========================================== THREE */
    .three {
      float: left;
      width: 100%;
    }

    .three h4 {
      font-size: 20px;
      color: gray;
      margin-top: 0;
      padding-top: 10px;
      text-align: center;
      letter-spacing: 1px;
    }

    .info-arb-text {
      margin-left: 10px;
      font-family: 'Noto Sans Arabic', sans-serif !important;

    }

    .first-table {
      width: 100%;
    }

    /* ======================================== FIRST - TABLE */
    .f-table {
      width: 100%;
    }

    .first-table {
      width: 100%;
    }

    .first-table,
    td {
      border-collapse: collapse;
      text-align: center;
      font-weight: 600;
      padding: 2px 0px 2px 8px
    }

    .first-table {
      border: 1px solid black;
    }

    .ft-head-one,
    .ft-head-two,
    .ft-head-three,
    .ft-head-four,
    .ft-head-five {
      border: 1px solid black;
    }

    .ft-head-one {
      width: 40%;
    }

    .ft-head-one span {
      font-size: 18px;
      color: gray;
    }

    .name-eng-text,
    .name-arb-text,
    .ticket-eng-text,
    .ticket-arb-text {
      color: grey;
      padding-left: 10px;
    }

    .ft-head-two {
      width: 20%;
    }

    .ft-head-two span {
      font-size: 18px;
      color: gray;
    }

    .ticket-arb-text {
      padding-left: 10px;
      font-family: 'Noto Sans Arabic', sans-serif !important;

    }

    .ft-head-three {
      width: 20%;
    }

    .fre-eng-text {
      color: gray;
      font-size: 12px;
    }

    .fre-arb-text {
      color: gray;
      font-size: 16px;
      font-family: 'Noto Sans Arabic', sans-serif !important;

    }

    .ft-head-four {
      width: 10%;
    }

    .seat-eng-text {
      color: gray;
      font-size: 12px;
    }

    .seat-arb-text {
      color: gray;
      font-size: 16px;
      font-family: 'Noto Sans Arabic', sans-serif !important;

    }

    .ft-head-five {
      width: 10%;
    }

    .bag-eng-text {
      color: gray;
      font-size: 12px;
    }

    .bag-arb-text {
      color: gray;
      font-size: 16px;
      font-family: 'Noto Sans Arabic', sans-serif !important;

    }

    .ft-head-one {
      text-align: start;
    }

    .first-table td {
      border-right: 1px solid black;
      border-collapse: collapse;
    }

    .tl-left {
      text-align: start;
    }

    /* =============================================== FOUR */
    .four {
      width: 100%;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    .four p {
      font-size: 18px;
      color: gray;
      padding-top: 10px;
      text-align: center;
      letter-spacing: 1px;
      font-weight: bold;
    }

    .fli-arb-text {
      margin-left: 20px;
      font-family: 'Noto Sans Arabic', sans-serif !important;

    }

    /* ========================================== SECOND TABLE */
    .second-table {
      width: 100%;
    }

    .second-table th {
      padding: 4px 1px;
    }

    .st-head-one {
      width: 12%;
    }

    .st-head-one h5 {
      color: gray;
    }

    .st-head-two {
      width: 12%;
    }

    .st-head-two h5 {
      color: gray;
    }

    .st-heead-three {
      width: 35%;
    }

    /* ====================== FROM and TO */
    .three-ft {
      width: 100%;
      color: gray;
      font-size: 14px;
      display: flex;
      justify-content: space-between;
    }

    .from {
      width: 40%;
    }

    .to {
      width: 40%;
    }

    .st-head-four {
      width: 12%;
    }

    .st-head-four h6 {
      color: gray;
    }

    .st-head-five {
      width: 8%;
    }

    .st-head-five h5 {
      color: gray;
    }

    .st-head-six {
      width: 12%;
    }

    .st-head-six h5 {
      color: gray;
    }

    .st-head-seven {
      width: 10%;
    }

    .st-head-seven h5 {
      color: gray;
    }

    .second-table,
    td {
      border-collapse: collapse;
      text-align: center;
    }

    .second-table,
    th {
      border: 1px solid black;
    }

    .second-table td {
      border-right: 1px solid black;
      border-collapse: collapse;
    }

    .a-text {
      text-align: start;
    }

    .td-one {
      width: 100%;
      float: left;
    }

    .td-img {
      width: 15%;
      float: left;
      display: inline-table;
    }

    .sv-text {
      float: left;
      width: 60%;
      display: inline-table;
    }

    .c-text {
      text-align: start;
    }

    .f-text {
      text-align: start;
      line-height: 22px;
    }

    .g-text h5 {
      color: #00B050;
      font-weight: 800;
    }

    .td-main {
      width: 100%;
      float: left;
      padding-top: 5px;
      padding-bottom: 5px;
    }

    .l-td {
      float: left;
      width: 50%;
    }

    .l-td h5 {
      padding-left: 8px;
    }

    .l-td h6 {
      padding-left: 8px;
      padding-top: 5px;
    }

    .l-td span {
      padding-left: 50px;
    }

    .l-td p {
      font-size: 8px;
    }

    .c-td {
      float: left;
      width: 20%;
      text-align: center;
    }

    .c-td img {
      width: 50%;
    }

    .r-td {
      float: left;
      width: 50%;
    }

    .r-td h5 {
      padding-left: 8px;
    }

    .r-td h6 {
      padding-left: 8px;
      padding-top: 5px;
    }

    .r-td span {
      padding-left: 50px;
    }

    .dur-text {
      padding-left: 8px;
      padding-top: 4px;
      padding-bottom: 6px;
    }

    .dur-text h4 {
      font-size: 13px;
    }

    .gg-text {
      color: gray;
    }

    .t-table {
      width: 100%;
      margin-top: 50px;
      margin-bottom: 50px;
    }


    /* ================================================= FIVE */
    .five {
      width: 100%;
      float: left;
    }

    .left-five {
      float: left;
      width: 40%;
      margin-left: 5px;
    }

    .left-five p {
      color: gray;
      line-height: 25px;
      margin-top: 10px;
      margin-bottom: 0 !important;
    }

    .right-five {
      float: right;
      width: 40%;
      text-align: right;
      margin-right: 5px;
    }

    .right-five h3 {
      font-family: 'Noto Sans Arabic', sans-serif !important;

    }

    .right-five p {
      color: gray;
      letter-spacing: 1px;
      line-height: 24px;
      margin-top: 10px;
      font-family: 'Noto Sans Arabic', sans-serif !important;
      margin-bottom: 0 !important;
    }

    /* ============================================== SIX */
    .six {
      float: left;
      width: 100%;
      margin-top: 20px;
      margin-bottom: 20px;
    }

    .left-six {
      float: left;
      width: 45%;
      text-align: center;
    }

    .left-six h4 {
      color: red;
      padding: 8px 10px;
      line-height: 24px;
      font-weight: bold;
    }

    .right-six {
      float: right;
      width: 45%;
      text-align: center;
    }

    .right-six h3 {
      color: red;
      padding: 8px 20px;
      line-height: 26px;
      font-weight: bold;
      font-family: 'Noto Sans Arabic', sans-serif !important;

    }
  </style>
</head>

<body>
  <div class="container">

    <!-- ========================= ONE -->
    <div class="one">
      <div class="left-one">
        <h3>{{ $getAgencyDetails['billingCompanyNameEn'] }}</h3>
        <ul>
          <li>
            Tel: {{ $getAgencyDetails['sitePhoneNo'] }}
          </li>
          <li>
            {{ $getAgencyDetails['addressEn'] }}
          </li>
          <li>
            {{ $getAgencyDetails['siteEmail'] }}
          </li>
        </ul>
      </div>
      <div class="center-one">
        <img src="/assets/images/rehlte-ar-en-img.jpg" alt="" />
      </div>
      <div class="right-one left-one">
        <h3>{{ $getAgencyDetails['billingCompanyNameAr'] }}</h3>
        <ul>
          <li>Tel: {{ $getAgencyDetails['sitePhoneNo'] }}</li>
          <li>{{ $getAgencyDetails['addressAr'] }}</li>
          <li>{{ $getAgencyDetails['siteEmail'] }}</li>
        </ul>
      </div>
    </div>

    <!-- ====================== DATE - TEXT -->
    <h3 class="date-text">
      Date : <span class="zero-text">{{ $bookingDetail['bookingDetail']['booking_date'] }}</span>
      <span class="date-text-arb">:تاريخ</span>
    </h3>

    <!-- ============================= TWO -->
    <div class="two">
      <div class="left-two">
        <div class="lt-one">
          <ul>
            <li>Agency Information</li>
            <li>Agency IATA Number</li>
            <li>City - Country</li>
            <li>GDS Booking Reference</li>
          </ul>
        </div>
        <div class="lt-two">
          <ul>
            <li><b>: {{ $getAgencyDetails['billingCompanyNameEn'] }}</b></li>
            <li><b>: {{ $getAgencyDetails['agencyIATANumber'] }}</b></li>
            <li><b>: {{$getAgencyDetails['cityName']}} - {{$getAgencyDetails['countryName']}}</b></li>
            <li class="z-text"><b>: {{$bookingDetail['bookingDetail']['pnr_number']}}</b></li>
          </ul>
        </div>
      </div>
      <div class="right-two">
        <div class="rt-one">
          <ul>
            <li>Voucher No</li>
            <li>Print Date</li>
            <li>Branch</li>
            <li>Sales agent</li>
          </ul>
        </div>
        <div class="rt-two">
          <ul>
            <li><b>: {{$bookingDetail['bookingDetail']['booking_ref']}}</b></li>
            <li><b>: {{ date('d/m/Y') }}</b></li>
            <li><b>: Branch</b></li>
            <li><b>: {{ App\Models\Suppliers::where('id',$bookingDetail['bookingDetail']['supplier_id'])->value('name') ?? '' }}</b></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- =================================== E - TICKET -->
    <div class="e-ticket">
      <h3 class="e-ticket-eng">Traveler e - Ticket</h3>
      <h3 class="e-ticket-eng">تذكرة المسافر الإلكترونية</h3>
    </div>
    <?php
    $associatedRecords = collect($bookingDetail['data']['associatedRecords'])->firstWhere('originSystemCode', 'GDS');
    ?>
    <h5 class="airline-text">
      Airline Booking Reference : <span class="red-text">{{ $associatedRecords['reference'] }}</span>
    </h5>

    <!-- ===================================== THREE -->
    <div class="three">
      <h4>
        Travelers Information
        <span class="info-arb-text"> معلومات المسافرين </span>
      </h4>
    </div>

    <!-- ================================ FIRST_TABLE -->
    <div class="f-table">
      <table class="first-table">
        <tr>
          <th class="ft-head-one">
            <span class="name-eng-text">Name</span><span class="name-arb-text">اسم</span>
          </th>
          <th class="ft-head-two">
            <span class="ticket-eng-text">Ticket No.</span><span class="ticket-arb-text">تذكرة رقم</span>
          </th>
          <th class="ft-head-three">
            <span class="fre-eng-text">Frequent Flyer number</span><br /><span class="fre-arb-text">رقم المسافر الدائم</span>
          </th>
          <th class="ft-head-four">
            <span class="seat-eng-text">Seat No.</span><br /><span class="seat-arb-text">رقم المقعد.</span>
          </th>
          <th class="ft-head-five">
            <span class="bag-eng-text">Baggage</span><br /><span class="bag-arb-text">أمتعة</span>
          </th>
        </tr>

        @foreach($bookingDetail['data']['travelers'] as $traveller)
        <tr>
          <td class="tl-left"><b>{{ $traveller['id']}}- {{ $traveller['name']['firstName'] }}</b></td>
          <td><b>--</b></td>
          <td><b>--</b></td>
          <td><b>--</b></td>
          @foreach($bookingDetail['data']['flightOffers'] as $flifgtOffersData)
          <td>
            <b>
              {{ $flifgtOffersData['travelerPricings'][0]['fareDetailsBySegment'][0]['includedCheckedBags']['weight']}} {{ $flifgtOffersData['travelerPricings'][0]['fareDetailsBySegment'][0]['includedCheckedBags']['weightUnit']}}
            </b>
          </td>
          @endforeach
        </tr>
        @endforeach
      </table>
    </div>

    <!-- ======================================== FOUR -->
    <div class="four">
      <p>
        Flight Information <span class="fli-arb-text">معلومات الرحلة</span>
      </p>
    </div>
    <!-- =============================== SECOND TABLE -->
    @foreach($bookingDetail['data']['flightOffers'] as $flightDetails)
    @foreach($flightDetails['itineraries'] as $getflightDetails)
    @foreach($getflightDetails['segments'] as $getflightSegments)
    <div class="s-table">
      <table class="second-table">
        <tr>
          <th class="st-head-one">
            <h5>Flight Number</h5>
          </th>
          <th class="st-head-two">
            <h5>Date</h5>
          </th>
          <th class="st-head-three">
            <!-- FROM and TO -->
            <div class="three-ft">
              <div class="from">From</div>
              <div class="to">To</div>
            </div>
          </th>
          <th class="st-head-four">
            <h6>Departure - Arrival</h6>
          </th>
          <th class="st-head-five">
            <h5>Terminal</h5>
          </th>
          <th class="st-head-six">
            <h5>Booking Class</h5>
          </th>
          <th class="st-head-seven">
            <h5>Status</h5>
          </th>
        </tr>

        <!-- C-text Alignment left aapelu che -->
        <tr>
          <td class="a-text">
            <div class="td-one">
              <?php 
              
              $airlineLogo = collect($bookingDetail['airlineList'])->firstWhere('code',$getflightSegments['carrierCode']);
               ?>
              
              <div class="td-img">
                <img src="{{$airlineLogo['logo']}}" width="40" height="40" alt="" />
              </div>
              <div class="sv-text">
                <h4>{{ $getflightSegments['aircraft']['code'] }}</h4>
              </div>
            </div>
          </td>
          <td class="b-text">{{ getDateTimeZone($getflightSegments['departure']['at']) }}</td>
          <td>
            <div style="float: left; width: 100%;">
              <span style="float: left; width: 50%; display:inline-table">
                <?php
                $airportNameD = App\Models\Airport::query();
                $airportNameD->select('airports.id', 'airport_i18ns.airport_name as airportName');
                $airportNameD->join('airport_i18ns', 'airports.id', '=', 'airport_i18ns.airport_id');
                $airportNameD->where('airports.iata_code', $getflightSegments['departure']['iataCode']);
                $airportNameD->where('airport_i18ns.language_code', 'en');
                $airportNameD = $airportNameD->value('airportName');
                ?>
                <h5>{{ $getflightSegments['departure']['iataCode'] }}<span class="arbarb-text"></span></h5>
                <h6>{{ $airportNameD }}</br>
                  Airport</h6>
              </span>
              <div class="c-td" style="display:inline-table">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" x="0" y="0" viewBox="0 0 64 64" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                  <g>
                    <path d="M50.3 14.39c-.41.09-.81.23-1.2.4L16 30l-8-5-4 3 5 5.9c-1.08 1.07-1.33 2.29-1 3.08 1.11 2.63 10.53-1.28 10.53-1.28l37.55-16.73c2.56-1.14 1.82-2.89 1.82-2.89-1.19-2.81-5.83-2.08-7.61-1.68z" fill="#000000" opacity="1" data-original="#000000"></path>
                    <path d="m36 22-14.38-5.19L16 20l9 7zM37 27l-6 17-7 2 4-15zM63 52H1c-.55 0-1-.45-1-1s.45-1 1-1h62c.55 0 1 .45 1 1s-.45 1-1 1z" fill="#000000" opacity="1" data-original="#000000"></path>
                  </g>
                </svg>
              </div>
              <span style="float: left; width: 50%; display:inline-table">
                <?php
                $airportNameA = App\Models\Airport::query();
                $airportNameA->select('airports.id', 'airport_i18ns.airport_name as airportName');
                $airportNameA->join('airport_i18ns', 'airports.id', '=', 'airport_i18ns.airport_id');
                $airportNameA->where('airports.iata_code', $getflightSegments['arrival']['iataCode']);
                $airportNameA->where('airport_i18ns.language_code', 'en');
                $airportNameA = $airportNameA->value('airportName');
                ?>
                <h5>{{ $getflightSegments['arrival']['iataCode'] }} <span class="arbarb-text"> - </span></h5>
                <h6>
                  {{$airportNameA}}</br>
                  Airport
                </h6>
              </span>
            </div>
            <div class="dur-text">
              <h4>
                <span class="gg-text">Duration</span> {{getHourMinute($getflightSegments['duration'])}}
                <span class="gg-text">Hours</span> (Non Stop)
              </h4>
              <h4><span class="gg-text">Flight Meal:</span> Meal</h4>
            </div>
          </td>
          <td class="d-text">{{ timeFunction($getflightSegments['departure']['at']) }} - {{ timeFunction($getflightSegments['arrival']['at']) }}</td>
          <td class="e-text">{{ $getflightSegments['departure']['terminal'] }}</td>

          <?php
          foreach ($flightDetails['travelerPricings'] as $travelerPricings) {
            $fareDetails = collect($travelerPricings['fareDetailsBySegment'])->firstWhere('segmentId', $getflightSegments['id']);
          }
          ?>
          <td class="f-text">
            <h4>{{$fareDetails['class']}}</h4>
            <h5>({{$fareDetails['cabin']}})</h5>
          </td>
          <td class="g-text">
            <h5>Confirmed</h5>
          </td>
        </tr>
      </table>
    </div>
    @endforeach
    @endforeach
    @endforeach



    <!-- ====================================== FIVE -->
    <div class="five">
      <div class="left-five">
        <h3>Dear Traveler,</h3>
        <p><?php echo $getAgencyDetails['termsAndConditionsEn']; ?></p>
      </div>
      <div class="right-five">
        <h3>عزيزي المسافر،</h3>
        <p><?php echo $getAgencyDetails['termsAndConditionsAr']; ?></p>
      </div>
    </div>

    <!-- ======================================= SIX -->
    <div class="six">
      <div class="left-six">
        <h4><?php echo $getAgencyDetails['notesEn'] ?></h4>
      </div>
      <div class="right-six">
        <h3><?php echo $getAgencyDetails['notesAr'] ?></h3>
      </div>
    </div>
  </div>
</body>

</html>