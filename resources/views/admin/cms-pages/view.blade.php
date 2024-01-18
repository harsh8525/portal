@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')
<style>
  .editor2 ul {
    list-style-position: inside;
  }
</style>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
        <h1 class="m-0">Page-View</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{{ route('cms-pages.index') }}">CMS List </a></li>
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
      <div class="card pb-4 pt-2 px-3 w-100">
        <div class="row view_page mb-0">

          <div class="description_view brdr-btm discount">
            <h3 class="view_destitle mb-2">{{ $pageData['page_title'] }}</h3>
            <p>{{ strip_tags($pageData['page_content']) }}</p>
          </div>
          <div class="seo_view pt-4 discount">
            <h3 class="view_seo mb-2">seo meta tags</h3>
            <div class="seo_data_list">
              <table class="">
                <tr>
                  <th>Page Title English :</th>
                  <td>{{$pageData['pageCodeName'][0]['page_title']}}</td>
                </tr>
                <tr style="text-align: right;">
                  <th>Page Title Arabic :</th>
                  <td>{{$pageData['pageCodeName'][1]['page_title']}}</td>
                </tr>
                <tr>
                  <th>Page Content English :</th>
                  <td>
                    <p name="" id="">{!! $pageData['pageCodeName'][0]['page_content'] !!}</p>
                  </td>
                </tr>
                <tr class="editor2" style="text-align: right;">
                  <th>Page Content Arabic:</th>
                  <td>
                    <p name="" id="" dir="rtl">{!! $pageData['pageCodeName'][1]['page_content'] !!}</p>
                  </td>
                </tr>
                <tr>
                  <th>Meta Title English :</th>
                  <td>{{$pageData['pageCodeName'][0]['meta_title']}}</td>
                </tr>
                <tr style="text-align: right;">
                  <th>Meta Title Arabic :</th>
                  <td>{{$pageData['pageCodeName'][1]['meta_title']}}</td>
                </tr>
                <tr>
                  <th>Slug URL :</th>
                  <td>{{ $pageData['slug_url'] }}</td>
                </tr>
                <tr>
                  <th>Meta Description English :</th>
                  <td>{{$pageData['pageCodeName'][0]['meta_description']}}</td>
                </tr>
                <tr style="text-align: right;">
                  <th>Meta Description Arabic :</th>
                  <td>{{$pageData['pageCodeName'][1]['meta_description']}}</td>
                </tr>
                <tr>
                  <th>Keywords English :</th>
                  <td>{{$pageData['pageCodeName'][0]['keywords']}}</td>
                </tr>
                <tr style="text-align: right;">
                  <th>Keywords Arabic :</th>
                  <td>{{$pageData['pageCodeName'][1]['keywords']}}</td>
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