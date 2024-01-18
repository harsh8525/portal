@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">Service Type - {{ ucwords($serviceDetail['name']) }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
                <li class="breadcrumb-item"><a href="{{ route('service-type.index') }}">Service Type </a></li>
                  <li class="breadcrumb-item active">View</li>
              </ol>
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
                        @if($serviceDetail['image'] != "")
                            <img width="150" height="150" src="{{ $serviceDetail['image'] }}" alt="">
                          @else
                          <img class="viewcon-user" width="150" height="150" src="{{ URL::asset('assets/images/no-image.png') }}" alt="">
                          @endif
                      </div>
                      <div class="view_user_data">
                        <table class="">
                            <tr>
                              <th>Service Name :</th>
                              <td>{{ ucwords($serviceDetail['name']) }}</td>
                            </tr>
                            <tr>
                              <th>Service Code :</th>
                              <td>{{ ucwords($serviceDetail['code']) }}</td>
                            </tr>
                            <tr>
                              <th>Description:</th>
                              <td>{{ ucwords($serviceDetail['description']) }}</td>
                            </tr>
                            <tr>
                              <th>Guideline</th>
                              <td>{{ ucwords($serviceDetail['guideline']) }}</td>
                            </tr>
                            <tr>
                              <th>Status:</th>
                              <td>{{ ucwords($serviceDetail['service_type_status_text']) }}</td>
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