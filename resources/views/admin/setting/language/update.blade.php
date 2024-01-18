@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
                <h1 class="m-0">{{ $header['heading'] }}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('language.index') }}">Language</a></li>
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
            <div class="card pb-4 w-100 px-3 py-2">
                <form class="form row mb-0 pt-3 validate" action="{{route('language.update',$languageDetail['id'])}}" enctype="multipart/form-data" method="post" id="dataForm" name="dataForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
                    <input type="hidden" name="language_id" id="language_id" value="{{ $languageDetail['id'] }}" />
                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style form-group">
                                <input name="language_code" type="text" id="language_code" class="is-valid" autocomplete="off" required value="{{$languageDetail['language_code']}}">
                                <label for="language_code">Language Code <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style form-group">
                                <input name="language_name" class="is-valid" required value="{{$languageDetail['language_name']}}" />
                                <label for="language_name">Language Name <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style ">
                                        <select data-live-search="true" id="language_type" name="language_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option @if($languageDetail['language_type']=='LTR' ) selected="selected" @endif value="LTR">LTR</option>
                                            <option @if($languageDetail['language_type']=='RTL' ) selected="selected" @endif value="RTL">RTL</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Language Type <span class="req-star"></span></label>
                                        <p style="color: black;font-size: 13px;font-family: system-ui;font-style:italic">language type [i.e LTR(Left To Right), RTL(Right To Left)]</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style form-group">
                                <input name="sort_order" type="text" id="sort_order" class="is-valid" autocomplete="off" required value="{{$languageDetail['sort_order']}}">
                                <label for="sort_order">Sort Order <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style ">
                                        <select data-live-search="true" id="is_default" name="is_default" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option @if($languageDetail['is_default']=='1' ) selected="selected" @endif value="1">True</option>
                                            <option @if($languageDetail['is_default']=='0' ) selected="selected" @endif value="0">False</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Is Default<span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style ">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option @if($languageDetail['status']=='1' ) selected="selected" @endif value="1">Active</option>
                                            <option @if($languageDetail['status']=='0' ) selected="selected" @endif value="0">In-Active</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Status <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cards-btn">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                        <a href="{{ route('language.index') }}" class="btn btn-danger form-btn-danger">Cancel</a>
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
    $(function() {

        $('*[value=""]').removeClass('is-valid');

        $('#dataForm').validate({
            rules: {
                language_code: {
                    required: true,
                    lettersonly: true,
                    noSpace: true,
                    maxlength: 100,
                    remote: {
                        url: "{{route('admin.language.checkExistCode')}}",
                        type: "post",
                        data: {
                            code: function() {
                                return $("#language_code").val();
                            },
                            language_id: function() {
                                return $("#language_id").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    },
                },
                language_name: {
                    required: true,
                    lettersonly: true,
                    noSpace: true,
                    maxlength: 100,
                },
                language_type: {
                    required: true,
                },
                sort_order: {
                    required: true,
                    digits: true,
                },
            },
            messages: {
                language_code: {
                    required: "Please enter a Language Code",
                    maxlength: "Please Enter only 100 characters",
                    remote: "Language Code already exist"
                },
                language_name: {
                    required: "Please enter a Language Name",
                    maxlength: "Please Enter only 100 characters",
                },
                language_type: {
                    required: "Please select a Language Type",
                },
                sort_order: {
                    required: "Please enter a numeric value",
                    digits: "Please enter only Numeric Value",
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
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