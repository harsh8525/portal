@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">{{ $header['title'] }}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sms-template.index') }}">SMS Templates</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="card pb-4 pt-3 px-3 w-100">
                <form id="dataForm" action="{{route('sms-template.update',$smsTemplateDetail['id'])}}" class="form row pt-3 mb-0 validate" enctype="multipart/form-data" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
                    <input type="hidden" value="{{ $smsTemplateDetail['id'] }}" name="sms_template_id">
                    <input type="hidden" value="{{ $smsTemplateDetail['smsCodeName'][0]['name'] }}" name="sms[0][name]">
                    <input type="hidden" value="{{ $smsTemplateDetail['smsCodeName'][1]['name'] }}" name="sms[1][name]">
                    <input type="hidden" value="{{ $smsTemplateDetail['smsCodeName'][0]['id'] }}" name="sms[0][smsTemplate_i18ns_id]">
                    <input type="hidden" value="{{ $smsTemplateDetail['smsCodeName'][1]['id'] }}" name="sms[1][smsTemplate_i18ns_id]">
                    <input type="hidden" value="{{ $smsTemplateDetail['code'] }}" name="code">
                    <div class="col-md-12 row">
                        <div class="col-md-12">
                            <div class="form-item form-float-style">
                                <textarea name="sms[0][content]" id="editor_en">{{ ucwords($smsTemplateDetail['smsCodeName'][0]['content']) }}</textarea>
                                <input type="hidden" id="language_code_en" name="sms[0][language_code]" autocomplete="off" class="is-valid" value="en">
                                <label for="">Description English<span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row">
                        <div class="col-md-12">
                            <div class="form-item form-float-style">
                                <textarea name="sms[1][content]" id="editor_ar">{{ ucwords($smsTemplateDetail['smsCodeName'][1]['content']) }}</textarea>
                                <input type="hidden" id="language_code_ar" name="sms[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                                <label for="">Description Arabic<span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                        <a href="{{ route('sms-template.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
    </div>
    <!--/. container-fluid -->
</section>

@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    jQuery.validator.setDefaults({
        ignore: [],
        // with this no hidden fields will be ignored E.g. ckEditor text-area
    });


    jQuery.validator.addMethod("noSpace1", function(value, element) {
        return value == '' || value.trim().length != 0;
    }, "Only Space are not allowed");
</script>
<script>
    $(function() {
        //jquery Form validation
        $('*[value=""]').removeClass('is-valid');

        $('#dataForm').validate({
            rules: {
                content: {
                    required: function() {
                        CKEDITOR.instances.content.updateElement();
                    },
                    noSpace1: true,
                },
            },

            messages: {
                content: {
                    required: "Please enter a description"
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

@append