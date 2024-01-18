@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<style>

</style>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['heading'] }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                <li class="breadcrumb-item"><a href="{{ route('payment-gateway.index') }}">Payment Gateway</a></li>
                <li class="breadcrumb-item active">View</li>
              </ol>
              <div class="breadcrumb-btn">
                <div class="add-breadcrumb @if($paymentDetail['is_active'] == 2) d-none @endif">
                  <a class="" href="{{ route('payment-gateway.edit',$paymentDetail['id']) }}">
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
                <div class="d-flex">
                  <div class="viewpage_img">
                    @if($paymentDetail['logo'] != "")
                        <img width="150" height="150" src="{{ $paymentDetail['logo'] }}" alt="">
                      @else
                      <img class="viewcon-user" width="150" height="150" src="{{ URL::asset('assets/images/no-image.png') }}" alt="">
                      @endif
                  </div>
                  <div class="view_user_data">
                    <table class="">
                      <tr>
                        <th>Name :</th>
                        <td>{{$paymentDetail->name}}</td>
                      </tr>
                      <tr>
                        <th>Description:</th>
                        <td>{{$paymentDetail->description}}</td>
                      </tr>
                      <tr>
                        <th>Status :</th>
                        @if($paymentDetail['is_active'] == "1")
                         <td>Active</td>
                       @elseif($paymentDetail['is_active'] == "0")
                         <td>In-Active</td>
                       @endif
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
  </script>
@endsection