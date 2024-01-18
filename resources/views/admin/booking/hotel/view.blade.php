@extends('admin.layout.main')
@section('title', $header['title'])
  
@section('content')
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['title'] }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('customers.dashboard') </a></li>
                <li class="breadcrumb-item"><a href="{{ route('booking.index') }}">Booking</a></li>
                <li class="breadcrumb-item active">@lang('customers.view')</li>
              </ol>
              </div>
            </div><!-- /.col -->

          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

     

@endsection