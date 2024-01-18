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
          <li class="breadcrumb-item"><a href="{{ route('language.index') }}">Language</a></li>
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
            <div class="view_user_data">
              <table class="">
                <tr>
                  <th>Language Code :</th>
                  <td>{{ $languageDetail->language_code }}</td>
                </tr>
                <tr>
                  <th>Language Name:</th>
                  <td>{{ ucwords($languageDetail->language_name) }}</td>
                </tr>
                <tr>
                  <th>Language Type:</th>
                  <td>{{ ucwords($languageDetail->language_type) }}</td>
                </tr>
                <tr>
                  <th>Sort Order:</th>
                  <td>{{ ucwords($languageDetail->sort_order) }}</td>
                </tr>
                <tr>
                  <th>Status :</th>
                  @if($languageDetail['status'] == "1")
                  <td>Active</td>
                  @elseif($languageDetail['status'] == "0")
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


@endsection