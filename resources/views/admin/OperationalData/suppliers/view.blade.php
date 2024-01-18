@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">Supliers- {{ ucwords($supplierDetail['name']) }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
                <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
                  <li class="breadcrumb-item active">View</li>
              </ol>
              <div class="breadcrumb-btn">
                <div class="add-breadcrumb">
                  <a href="{{ route('suppliers.edit',$supplierDetail['id']) }}">
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
                    <div class="d-flex">
                      <div class="viewpage_img">
                        @if($supplierDetail['cover_image'] != "")
                            <img width="150" height="150" src="{{ $supplierDetail['cover_image'] }}" alt="">
                          @else
                          <img class="viewcon-user" width="150" height="150" src="{{ URL::asset('assets/images/no-image.png') }}" alt="">
                          @endif
                      </div>
                      <div class="view_user_data">
                        <table class="">
                              <tr>
                              <th>Supplier Name :</th>
                              <td>{{ ucwords($supplierDetail['name']) }}</td>
                            </tr>
                            <tr>
                              <th>Code :</th>
                              <td>{{ ucwords($supplierDetail['code']) }}</td>
                            </tr>
                            <tr>
                              <th>Service Type :</th>
                              <td>{{ ucwords($supplierDetail['service_name']) }}</td>
                            </tr>
                            <tr>
                              <th>Status :</th>
                              <td>{{ ucwords($supplierDetail['supplier_type_status_text']) }}</td>
                            </tr>
                          </table>
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

@append