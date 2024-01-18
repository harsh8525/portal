@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')

<style>
  .form-item input.is-valid+label,
  .form-item input:checked+label,
  .form-item #genpass:valid+label {
    font-size: 11px;
    top: -5px;
  }
</style>


<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 d-flex breadcrumb-style">
        <h1 class="m-0">Page-Edit</h1>
        <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{{ route('cms-pages.index') }}">CMS List</a></li>
          <li class="breadcrumb-item active">Edit</li>
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
      <form action="{{route('cms-pages.update',$pageDetail->id)}}" method="post" id="dataForm" name="dataForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
        <input type="hidden" name="page_id" id="page_id" value="{{$pageDetail['id']}}">
        <div class="card pb-4 pt-3 px-3 w-100">

          <!-- Content Wrapper. Contains page content -->
          <div class="col-md-12 row p-0">

            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input type="text" id="page_title_en" name="page_titles[0][page_title]" autocomplete="off" class="is-valid" required value="{{$pageDetail['pageCodeName'][0]['page_title']}}">
                <input type="hidden" id="language_code_en" name="page_titles[0][language_code]" autocomplete="off" class="is-valid" value="en">
                <input type="hidden" id="page_i18ns_en_id" name="page_titles[0][page_i18ns_id]" autocomplete="off" class="is-valid" value="{{$pageDetail['pageCodeName'][0]['id']}}">
                <label for="name">Page Title English<span class="req-star">*</span></label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input type="text" id="page_title_ar" name="page_titles[1][page_title]" dir="rtl" autocomplete="off" class="is-valid" required value="{{$pageDetail['pageCodeName'][1]['page_title']}}">
                <input type="hidden" id="language_code_ar" name="page_titles[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                <input type="hidden" id="page_i18ns_ar_id" name="page_titles[1][page_i18ns_id]" autocomplete="off" class="is-valid" value="{{$pageDetail['pageCodeName'][1]['id']}}">
                <label for="name">Page Title Arabic<span class="req-star">*</span></label>
              </div>
            </div>
          </div>
          <div class="form-item editable mb-3">
            <label for="name">Page Content English<span class="req-star">*</span></label>
            <textarea name="page_titles[0][page_content]" id="editor_en" class="is-valid">{{$pageDetail['pageCodeName'][0]['page_content']}}</textarea>

          </div>
          <div class="form-item editable mb-3">
            <label for="name">Page Content Arabic<span class="req-star">*</span></label>
            <textarea name="page_titles[1][page_content]" id="editor_ar" class="is-valid">{{$pageDetail['pageCodeName'][1]['page_content']}}</textarea>

          </div>
          <div class="discount">
            <h3 class="mb-3">SEO Meta Tags</h3>
            <div class="col-md-12 row p-0">
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="meta_title_en" name="page_titles[0][meta_title]" autocomplete="off" class="is-valid" required value="{{$pageDetail['pageCodeName'][0]['meta_title']}}">
                  <label for="name">Meta Title English<span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="meta_title_ar" name="page_titles[1][meta_title]" dir="rtl" autocomplete="off" class="is-valid" required value="{{$pageDetail['pageCodeName'][1]['meta_title']}}">
                  <label for="name">Meta Title Arabic<span class="req-star">*</span></label>
                </div>
              </div>
            </div>
            <div class="col-md-12 row p-0">
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="meta_description_en" name="page_titles[0][meta_description]" autocomplete="off" class="is-valid" required value="{{$pageDetail['pageCodeName'][0]['meta_description']}}">
                  <label for="name">Meta Description English<span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="meta_description_ar" name="page_titles[1][meta_description]" dir="rtl" autocomplete="off" class="is-valid" required value="{{$pageDetail['pageCodeName'][1]['meta_description']}}">
                  <label for="name">Meta Description Arabic<span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="keywords_en" name="page_titles[0][keywords]" autocomplete="off" class="is-valid" required value="{{$pageDetail['pageCodeName'][0]['keywords']}}">
                  <label for="name">Keyword English<span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="keywords_ar" name="page_titles[1][keywords]" dir="rtl" autocomplete="off" class="is-valid" required value="{{$pageDetail['pageCodeName'][1]['keywords']}}">
                  <label for="name">Keyword Arabic<span class="req-star">*</span></label>
                </div>
              </div>
            </div>
            <div class="col-md-12 row p-0">
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="slug_url" name="slug_url" autocomplete="off" class="is-valid" required value="{{$pageDetail['slug_url']}}">
                  <label for="name">Slug URL <span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating form-item mb-0">
                  <div class="form-item form-float-style serach-rem mb-0">
                    <div class="select top-space-rem after-drp form-float-style ">
                      <select data-live-search="true" id="slect_finish" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                        <option @if($pageDetail['status']=='1' ) selected="selected" @endif value="1" selected="">Active</option>
                        <option @if($pageDetail['status']=='0' ) selected="selected" @endif value="0">In-Active</option>
                      </select>
                      <label class="select-label searchable-drp">Status</label>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="cards-btn mt-2">
            <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
            <a type="button" href="{{ route('cms-pages.index') }}" class="btn btn-danger form-btn-danger">Cancel</a>
          </div>

        </div>

      </form>

      <textarea id="editor" style="display: none;"></textarea>
    </div>
    <!-- /.row -->
  </div>
  <!--/. container-fluid -->
</section>

@endsection
@section('js')


<!-- Page specific script -->
<script>
  jQuery.validator.addMethod("noSpace", function(value, element) {
    return value == '' || value.trim().length != 0;
  }, "Only Space are not allowed");
</script>
<script>
  $(document).ready(function() {
    $('*[value=""]').removeClass('is-valid');

    $('#dataForm').validate({
      ignore: "",
      debug: false,
      rules: {
        'page_titles[0][page_title]': {
          required: true,
          noSpace: true,
          remote: {
            url: "{{route('admin.cms-pages-title.checkExist')}}",
            type: "post",
            data: {
              page_title: function() {
                return $("#page_title_en").val();
              },
              id: function() {
                return $("#page_id").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'page_titles[1][page_title]': {
          required: true,
          noSpace: true,
          remote: {
            url: "{{route('admin.cms-pages-title.checkExist')}}",
            type: "post",
            data: {
              page_title: function() {
                return $("#page_title_ar").val();
              },
              id: function() {
                return $("#page_id").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'page_titles[0][meta_title]': {
          required: true,
          noSpace: true
        },
        'page_titles[1][meta_title]': {
          required: true,
          noSpace: true
        },
        'page_titles[0][page_content]': {
          required: true,
          noSpace: true
        },
        'page_titles[1][page_content]': {
          required: true,
          noSpace: true
        },
        'slug_url': {
          required: true,
          noSpace: true,
          pattern: /^[A-Za-z\d-.]+$/,
          remote: {
            url: "{{route('admin.cms-pages-SlugURL.checkExist')}}",
            type: "post",
            data: {
              slug_url: function() {
                return $("#slug_url").val();
              },
              id: function() {
                return $("#page_id").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'page_titles[0][meta_description]': {
          required: true,
          noSpace: true
        },
        'page_titles[1][meta_description]': {
          required: true,
          noSpace: true
        },
        'page_titles[0][keywords]': {
          required: true,
          noSpace: true
        },
        'page_titles[1][keywords]': {
          required: true,
          noSpace: true
        },

      },
      messages: {
        'page_titles[0][page_title]': {
          required: "Please enter a Page Title English",
          remote: "Page Title English already Exists"
        },
        'page_titles[1][page_title]': {
          required: "Please enter a Page Title Arabic",
          remote: "Page Title Arabic already Exists"
        },
        'page_titles[0][meta_title]': {
          required: "Please enter a Meta Title English"
        },
        'page_titles[1][meta_title]': {
          required: "Please enter a Meta Title Arabic"
        },
        'page_titles[0][page_content]': {
          required: "Please enter a Page Content English"
        },
        'page_titles[1][page_content]': {
          required: "Please enter a Page Content Arabic"
        },
        slug_url: {
          required: "Please enter a Slug URL",
          remote: "Slug URL already Exists",
          pattern: "Please enter in Valid Format"
        },
        'page_titles[0][meta_description]': {
          required: "Please enter a Meta Description English"
        },
        'page_titles[1][meta_description]': {
          required: "Please enter a Meta Description Arabic"
        },
        'page_titles[0][keywords]': {
          required: "Please enter a Keyword English"
        },
        'page_titles[1][keywords]': {
          required: "Please enter a Keyword Arabic"
        },


      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-item').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {

      },
      submitHandler: function(form) {
        $("#disBtn").attr("disabled", true);
        form.submit();
      }
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@append