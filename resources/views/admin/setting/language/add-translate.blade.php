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
                    <li class="breadcrumb-item"><a href="{{ route('language.index') }}">Language </a></li>
                    <li class="breadcrumb-item active">Translate</li>
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
            <div class="card pb-4 w-100 px-3 py-2 mb-3">
                <form id="dataForm" name="dataForm" class="form row mb-0 pt-3 validate" action="{{ route('languages.translate.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id" value="{{ $id }}" />
                    <div class="col-md-12 row"><!-- Start col-md-12 row  Div -->
                        <div class="col-md-4">
                            <div class="form-item form-float-style form-group">
                                <input name="key" type="text" id="key" autocomplete="off" required value="">
                                <label for="key">Key<span class="req-star"></span>*</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-item form-float-style form-group">
                                <input name="value" type="text" id="value" autocomplete="off" required value="">
                                <label for="value">Value<span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div><!-- End col-md-12 row  Div -->
                    <div class="cards-btn">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                        <!-- <a href="{{ route('language.index') }}" class="btn btn-danger form-btn-danger">Cancel</a> -->
                    </div>
                </form>
            </div><!--End Card  div-->
        </div> <!-- /.row -->
    </div> <!--/. container-fluid -->
</section>
@endsection
@section('js')
<script>
    $(function() {
        $('*[value=""]').removeClass('is-valid');
        $('#dataForm').validate({
            rules: {
                key: {
                    required: true,
                    noSpace: true,
                    remote: {
                        url: "{{route('admin.language.checkExistKey')}}",
                        type: "post",
                        data: {
                            key: function() {
                                return $("#key").val();
                            },
                            id: function() {
                                return $("#id").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                value: {
                    required: true,
                    noSpace: true,
                },
            },
            messages: {
                key: {
                    required: "Please enter a key",
                    remote: "key already exist"
                },
                value: {
                    required: "Please enter a value",
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