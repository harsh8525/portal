@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">Home Banner - {{ ucwords($bannerDetail['banner_name']) }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
                <li class="breadcrumb-item"><a href="{{ route('home-banner.index') }}">Home Banners </a></li>

                <li class="breadcrumb-item active">View</li>
              </ol>
              <div class="breadcrumb-btn">
                <div class="add-breadcrumb">
                  <a href="{{ route('home-banner.edit',$bannerDetail['id']) }}">
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
              @if($bannerDetail['media_type'] == "image")
              <h3>{{ ucwords($bannerDetail['banner_type']) }} Home Banner</h3>
                @if($bannerDetail['banner_image'] == NULL)
                <div class="discount d-none">
                  <div class="banner_images">
                      <img src="{{ $bannerDetail['banner_image'] }}" alt="">
                  </div>
                </div>
                @else
                <div class="discount ">
                  <div class="banner_images">
                      <img src="{{ $bannerDetail['banner_image'] }}" alt="">
                  </div>
                </div>
                @endif
              @endif
                <div class="discount right_partbanner">
                  <div class=" view_banner_data">
                    <table class="">
                      <tr>
                        <th>Home Banner Name :</th>
                        <td>{{ ucwords($bannerDetail['banner_name']) }}</td>
                      </tr>
                      @if($bannerDetail['video_link'] != NULL && $bannerDetail['media_type'] == "video")
                      <tr>
                        <th>Video Link :</th>
                        <td><a href="{{ $bannerDetail['video_link'] }}" target="_blank">{{ $bannerDetail['video_link'] }}</a></td>
                        
                      </tr>
                      @endif
                      <tr>
                        <th>From Date :</th>
                        <td>{{ date('d-m-Y',strtotime($bannerDetail['from_date'])) }}</td>
                      </tr>
                      <tr>
                        <th>To Date :</th>
                        <td>{{ date('d-m-Y',strtotime($bannerDetail['to_date'])) }}</td>
                      </tr>
                      <tr>
                        <th>Created Date :</th>
                        <td>{{ date('d-m-Y',strtotime($bannerDetail['created_at'])) }}</td>
                      </tr>
                      <tr>
                        <th>Updated Date :</th>
                        <td>{{ date('d-m-Y',strtotime($bannerDetail['updated_at'])) }}</td>
                      </tr>
                      <tr>
                        <th>Sort Order :</th>
                        <td>{{ $bannerDetail['sort_order'] }}</td>
                      </tr>
                      <tr>
                        <th>Status :</th>
                        <td>{{ $bannerDetail['banner_status_text'] }}</td>
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