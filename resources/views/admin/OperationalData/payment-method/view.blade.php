@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">Payment Methods - {{ ucwords($paymentDetail['name']) }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
                <li class="breadcrumb-item"><a href="{{ route('paymentmethod.index') }}">Payment Methods </a></li>

                <li class="breadcrumb-item active">View</li>
              </ol>
              <div class="breadcrumb-btn @if($paymentDetail['is_active'] == 2) d-none @endif">
                <div class="add-breadcrumb">
                  <a href="{{ route('paymentmethod.edit',$paymentDetail['id']) }}">
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
              <div class="row view_page view_banner mb-0">
              @if($paymentDetail['media_type'] == "image")
              <h3>{{ ucwords($paymentDetail['name']) }} Payment Method</h3>
              @endif
                <div class="discount right_partbanner">
                  <div class=" view_agency_data">
                    <table class="">
                      <tr>
                        <th>Payment Method :</th>
                        <td>{{ ucwords($paymentDetail['name']) }}</td>
                      </tr>
                      <tr>
                        <th>Description:</th>
                        <td>{{ ucwords($paymentDetail['description']) }}</td>
                      </tr>
                      <tr>
                        <th>Status:</th>
                        <td>{{ ucwords($paymentDetail['payment_type_status_text']) }}</td>
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
 
 @section('js')
@append