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
                    <li class="breadcrumb-item"><a href="{{ route('mail-template.index') }}">Mail Templates</a></li>
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
                <form id="dataForm" action="{{route('mail-template.update',$mailTemplateDetail['id'])}}" class="form row pt-3 mb-0 validate" enctype="multipart/form-data" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group col-md-12">
                        <!--Errors variable used from form validation -->
                        @if($errors->any())
                        <div class="notification is-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                <li class="text-red">{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
                    <input type="hidden" value="{{ $mailTemplateDetail['id'] }}" name="mail_template_id">
                    <input type="hidden" value="{{ $mailTemplateDetail['mailCodeName'][0]['name'] }}" name="mail[0][name]">
                    <input type="hidden" value="{{ $mailTemplateDetail['mailCodeName'][1]['name'] }}" name="mail[1][name]">
                    <input type="hidden" value="{{ $mailTemplateDetail['code'] }}" name="code">
                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input name="from_email" type="text" id="from_email" autocomplete="off" required value="{{ $mailTemplateDetail['from_email'] }}" class="is-valid">
                                <label for="home-banner">From <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select class="selectpicker width-set-serc select-text height_drp is-valid" name="cc[]" multiple>
                                    <?php $selectedCc = explode(",", $mailTemplateDetail->cc); ?>
                                    @ @foreach(App\Models\User::get() as $getCC)
                                    @if($getCC->app_name == 'managerapp')
                                    <option value="{{ $getCC->email }}" {{ (in_array($getCC->email, $selectedCc)) ? 'selected' : '' }}>{{ $getCC->email }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <label for="home-banner">CC </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select class="selectpicker width-set-serc select-text height_drp is-valid" name="bcc[]" multiple>
                                    <?php $selectedBcc = explode(",", $mailTemplateDetail->bcc); ?>
                                    @foreach(App\Models\User::get() as $getBcc)
                                    @if($getBcc->app_name == 'managerapp')
                                    <option value="{{ $getBcc->email }}" {{ (in_array($getBcc->email, $selectedBcc)) ? 'selected' : '' }}>{{ $getBcc->email }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <label for="home-banner">BCC </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input name="mail[0][subject]" type="text" id="subject1" autocomplete="off" required value="{{ $mailTemplateDetail['mailCodeName'][0]['subject'] }}" class="is-valid">
                                <input name="mail[0][language_code]" type="hidden" id="language_code" autocomplete="off" required value="en" class="is-valid">
                                <label for="home-banner">Subject English<span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input name="mail[1][subject]" type="text" id="subject2" dir="rtl" autocomplete="off" required value="{{ $mailTemplateDetail['mailCodeName'][1]['subject'] }}" class="is-valid">
                                <input name="mail[1][language_code]" type="hidden" id="language_code2" autocomplete="off" required value="ar" class="is-valid">
                                <label for="home-banner">Subject Arabic<span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row">
                        <div class="col-md-12">
                            <div class="form-item form-float-style">
                                <label for="content">Description English<span class="req-star">*</span></label>
                                <textarea name="mail[0][content]" id="editor_en" minlength="1"><?= $mailTemplateDetail['mailCodeName'][0]['content'] ?></textarea>
                                <input type="hidden" value="{{ $mailTemplateDetail['mailCodeName'][0]['id'] }}" name="mail[0][mailTemplate_i18ns_id]">
                                @error('content')
                                <span id="content-error" class="error invalid-feedback ">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row">
                        <div class="col-md-12">
                            <div class="form-item form-float-style">
                                <label for="content">Description Arabic<span class="req-star">*</span></label>
                                <textarea name="mail[1][content]" id="editor_ar" minlength="1" required><?= $mailTemplateDetail['mailCodeName'][1]['content'] ?></textarea>
                                <input type="hidden" value="{{ $mailTemplateDetail['mailCodeName'][1]['id'] }}" name="mail[1][mailTemplate_i18ns_id]">
                                @error('content')
                                <span id="content-error" class="error invalid-feedback ">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                        <a href="{{ route('mail-template.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
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


    $.validator.addMethod("email_regex", function(value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, "Please enter a valid From");
</script>
<script>
    $(function() {
        //jquery Form validation
        $('*[value=""]').removeClass('is-valid');

        $('#dataForm').validate({
            ignore: [],
            rules: {
                ignore: [],
                from_email: {
                    required: true,
                    email_regex: /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i,
                    noSpace: true
                },
                cc: {
                    required: true,
                },
                bcc: {
                    required: true,
                },
                'mail[0][subject]': {
                    required: true,
                    noSpace: true,
                },
                'mail[1][subject]': {
                    required: true,
                    noSpace: true,
                },
                'mail[0][content]': {
                    required: true,
                    noSpace: true,
                },
                'mail[1][content]': {
                    required: true,
                    noSpace: true,
                },

            },

            messages: {
                from_email: {
                    required: "Please enter a From"
                },
                cc: {
                    required: "Please select a CC"
                },
                bcc: {
                    required: "Please select a BCC"
                },
                'mail[0][subject]': {
                    required: "Please enter a Subject English"
                },
                'mail[1][subject]': {
                    required: "Please enter a Subject Arabic"
                },
                'mail[0][content]': {
                    required: "Please enter a Description English",
                },
                'mail[1][content]': {
                    required: "Please enter a Description Arabic",
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