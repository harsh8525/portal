@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')


<style>

</style>


<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 d-flex breadcrumb-style">
        <h1 class="m-0">Page-Add</h1>
        <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{{ route('cms-pages.index') }}">CMS List</a></li>
          <li class="breadcrumb-item active">Add</li>
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
      <form action="{{route('cms-pages.store')}}" class="form row pt-3 mb-0 validate" method="post" id="dataForm" name="dataForm" enctype="multipart/form-data">
        @csrf
        <div class="card pb-4 pt-3 px-3 w-100">

          <!-- Content Wrapper. Contains page content -->
          <div class="col-md-12 row p-0">

            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input type="text" id="page_title_en" name="page_titles[0][page_title]" autocomplete="off" class="is-valid" required value="">
                <input type="hidden" id="language_code_en" name="page_titles[0][language_code]" autocomplete="off" class="is-valid" value="en">
                <label for="name">Page Title English<span class="req-star">*</span></label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input type="text" id="page_title_ar" name="page_titles[1][page_title]" dir="rtl" autocomplete="off" class="is-valid" required value="">
                <input type="hidden" id="language_code_ar" name="page_titles[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                <label for="name">Page Title Arabic<span class="req-star">*</span></label>
              </div>
            </div>
          </div>
          <textarea id="editor" style="display: none;">test</textarea>
          <div class="form-item editable mb-3">
            <label for="name">Page Content English<span class="req-star">*</span></label>
            <textarea name="page_titles[0][page_content]" id="editor_en" class="is-valid editor"></textarea>
            <input type="hidden" id="language_code_en" name="page_titles[0][language_code]" autocomplete="off" class="is-valid" value="en">
          </div>
          <div class="form-item editable mb-3">
            <label for="name">Page Content Arabic<span class="req-star">*</span></label>
            <textarea name="page_titles[1][page_content]" id="editor_ar" class="is-valid editor"></textarea>
            <input type="hidden" id="language_code_ar" name="page_titles[1][language_code]" autocomplete="off" class="is-valid" value="ar">
          </div>
          <div class="discount">
            <h3 class="mb-3">SEO Meta Tags</h3>
            <div class="col-md-12 row p-0">
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="meta_title_en" name="page_titles[0][meta_title]" autocomplete="off" class="is-valid" required value="">
                  <input type="hidden" id="language_code_en" name="page_titles[0][language_code]" autocomplete="off" class="is-valid" value="en">
                  <label for="name">Meta Title English<span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="meta_title_ar" name="page_titles[1][meta_title]" dir="rtl" autocomplete="off" class="is-valid" required value="">
                  <input type="hidden" id="language_code_ar" name="page_titles[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                  <label for="name">Meta Title Arabic<span class="req-star">*</span></label>
                </div>
              </div>

            </div>
            <div class="col-md-12 row p-0">
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="meta_description_en" name="page_titles[0][meta_description]" autocomplete="off" class="is-valid" required value="">
                  <input type="hidden" id="language_code_en" name="page_titles[0][language_code]" autocomplete="off" class="is-valid" value="en">
                  <label for="name">Meta Description English<span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="meta_description_ar" name="page_titles[1][meta_description]" dir="rtl" autocomplete="off" class="is-valid" required value="">
                  <input type="hidden" id="language_code_ar" name="page_titles[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                  <label for="name">Meta Description Arabic<span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="keywords_en" name="page_titles[0][keywords]" autocomplete="off" class="is-valid" required value="">
                  <input type="hidden" id="language_code_en" name="page_titles[0][language_code]" autocomplete="off" class="is-valid" value="en">
                  <label for="name">Keyword English<span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="keywords_ar" name="page_titles[1][keywords]" dir="rtl" autocomplete="off" class="is-valid" required value="">
                  <input type="hidden" id="language_code_ar" name="page_titles[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                  <label for="name">Keyword Arabic<span class="req-star">*</span></label>
                </div>
              </div>
            </div>
            <div class="col-md-12 row p-0">
              <div class="col-md-6">
                <div class="form-item form-float-style">
                  <input type="text" id="slug_url" name="slug_url" autocomplete="off" class="is-valid" required value="">
                  <label for="name">Slug URL <span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating mb-0">
                  <div class="form-float-style serach-rem mb-0">
                    <div class="form-item select top-space-rem after-drp form-float-style">
                      <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                        <option value="1" selected>Active</option>
                        <option value="0">In-Active</option>
                      </select>
                      <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Status</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="cards-btn mt-2">
            <button type="submit" class="btn btn-success form-btn-success" id="disBtn">Submit</button>
            <a type="button" href="{{ route('cms-pages.index') }}" class="btn btn-danger form-btn-danger">Cancel</a>
          </div>

        </div>

      </form>

    </div>
    <!-- /.row -->
  </div>
  <!--/. container-fluid -->
</section>

@endsection
@section('js')
<script>
  jQuery.validator.addMethod("noSpace1", function(value, element) {
    return value == '' || value.trim().length != 0;
  }, "Only Space are not allowed");


  $.validator.addMethod("email_regex", function(value, element, regexpr) {
    return this.optional(element) || regexpr.test(value);
  }, "Please enter a valid From");
</script>
<script>
  jQuery.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z ]+$/i.test(value);
  }, "Letters only please");
  $(function() {
    //jquery Form validation
    $('*[value=""]').removeClass('is-valid');

    $('#dataForm').validate({
      ignore: "",
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
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'page_titles[0][meta_title]': {
          required: true,
          noSpace: true,
        },
        'page_titles[1][meta_title]': {
          required: true,
          noSpace: true,
        },
        'page_titles[0][page_content]': {
          required: true,
          noSpace: true,
        },
        'page_titles[1][page_content]': {
          required: true,
          noSpace: true,
        },
        slug_url: {
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
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'page_titles[0][meta_description]': {
          required: true,
          noSpace: true,
        },
        'page_titles[1][meta_description]': {
          required: true,
          noSpace: true,
        },
        'page_titles[0][keywords]': {
          required: true,
          noSpace: true,
        },
        'page_titles[1][keywords]': {
          required: true,
          noSpace: true,
        },

      },
      messages: {
        'page_titles[0][page_title]': {
          required: "Please enter a Page Title English",
          remote: "Page Title English already Exists",
        },
        'page_titles[1][page_title]': {
          required: "Please enter a Page Title Arabic",
          remote: "Page Title Arabic already Exists",
        },
        'page_titles[0][meta_title]': {
          required: "Please enter a Meta Title English"
        },
        'page_titles[1][meta_title]': {
          required: "Please enter a Meta Title Arabic"
        },
        'page_titles[0][page_content]': {
          required: "Please enter a Page Content English",
        },
        'page_titles[1][page_content]': {
          required: "Please enter a Page Content Arabic",
        },
        slug_url: {
          required: "Please enter a Slug URL",
          remote: "Slug URL already Exists",
          pattern: "Please enter in Valid Format",
        },
        'page_titles[0][meta_description]': {
          required: "Please enter a Meta Description English",
        },
        'page_titles[1][meta_description]': {
          required: "Please enter a Meta Description Arabic",
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
        $(element).removeClass('is-invalid');
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