@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<?php app()->setLocale("en"); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">{{$header['title']}}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('airline.dashboard') </a></li>
                    <li class="breadcrumb-item active">{{$header['title']}}</li>
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
            <div class="card pb-4 pt-3 px-3 w-100">
                <form method="post" action="{{route('app-download-preference')}}" id="appDownloadPreferenceForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    <div class="brdr-btm row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input name='appDownloadPreference|titleEn' id="appDownloadPreference|titleEn" value="{{$data['titleEn']['value'] ?? ''}}" autocomplete="off" class="is-valid">
                                <label for="appDownloadPreference|titleEn">Title English<span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input name='appDownloadPreference|titleAr' id="appDownloadPreference|titleAr" value="{{$data['titleAr']['value'] ?? ''}}" autocomplete="off" class="is-valid">
                                <label for="appDownloadPreference|titleAr">Title Arabic<span class="req-star">*</span></label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 d-none" id="component_div">
                            <div class="form-item form-float-style serach-rem mb-3">
                                <div class="select top-space-rem after-drp form-float-style">
                                    @component('components.customer_state_city_select', [
                                    'name' => 'state_code',
                                    'id' => 'state_code',
                                    'class' => 'order-td-input selectpicker1 select-text height_drp is-valid component_div',
                                    'selected' => '',
                                    'placeholder' => 'Select State'
                                    ])
                                    @endcomponent
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">State <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                            <input type="hidden" id="oldQrCodeImage" name="oldQrCodeImage" class="file-upload" autocomplete="off" class="is-valid" value="{{ $data['qrCodeImage']['value'] ?? '' }}">
                                @component('components.multiple-crop-image', [
                                'name' => 'appDownloadPreference|qrCodeImage',
                                'id' => 'qr_code_image',
                                'class' => 'file-upload image is-valid'

                                ])
                                @endcomponent
                                <label for="upload-profile">Qr Code Image</label>
                            </div>


                            <p class="upload-img-des mb-0">These images are visible in the customer page.
                                Support jpg, jpeg, or png files.
                            </p>
                            <div id='profile_image_section'>
                                @if(isset($data['qrCodeImage']))
                                <img data-toggle="popover" id="croppedImagePreview" height="150" width="150" src="{{ $data['qrCodeImage']['value'] ?? ''}}" alt="{{ $data['qrCodeImage']['value'] ?? '' }}">
                                @else
                                <img data-toggle="popover" id="croppedImagePreview" height="150" width="150" src="" alt="no-image" style="display: none;">
                                @endif
                                <label for="upload-profile">Qr Code Image</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                            <input type="hidden" id="oldBannerImageEn" name="oldBannerImageEn" class="file-upload" autocomplete="off" class="is-valid" value="{{ $data['bannerImageEn']['value'] ?? '' }}">
                                @component('components.multiple-crop-image', [
                                'name' => 'appDownloadPreference|bannerImageEn',
                                'id' => 'banner_image_en',
                                'class' => 'file-upload image is-valid'
                                ])
                                @endcomponent
                                <label for="upload-profile">Download Banner Image English</label>
                            </div>


                            <p class="upload-img-des mb-0">These images are visible in the customer page.
                                Support jpg, jpeg, or png files.
                            </p>
                            <div id='profile_image_section'>
                                @if(isset($data['bannerImageEn']) && $data['bannerImageEn']['value'] != NULL)
                                <img data-toggle="popover" id="secondCroppedImagePreview" height="300" width="500" src="{{ $data['bannerImageEn']['value'] ?? ''}}" alt="{{ $data['bannerImageEn']['value'] ?? '' }}">
                                @else
                                <img data-toggle="popover" id="secondCroppedImagePreview" height="150" width="150" src="" alt="no-image" style="display: none;">
                                @endif
                                <label for="upload-profile">Download Banner Image English</label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                            <input type="hidden" id="oldBannerImageAr" name="oldBannerImageAr" class="file-upload" autocomplete="off" class="is-valid" value="{{ $data['bannerImageAr']['value'] ?? '' }}">
                                @component('components.multiple-crop-image', [
                                'name' => 'appDownloadPreference|bannerImageAr',
                                'id' => 'banner_image_ar',
                                'class' => 'file-upload image is-valid'
                                ])
                                @endcomponent
                                <label for="upload-profile">Download Banner Image Arabic</label>
                            </div>
                            <p class="upload-img-des mb-0">These images are visible in the customer page.
                                Support jpg, jpeg, or png files.
                            </p>
                            <div id='profile_image_section'>
                                @if(isset($data['bannerImageAr']) && $data['bannerImageAr']['value'] != NULL)
                                <img data-toggle="popover" id="thirdCroppedImagePreview" height="300" width="500" src="{{ $data['bannerImageAr']['value'] ?? ''}}" alt="{{ $data['bannerImageAr']['value'] ?? '' }}">
                                @else
                                <img data-toggle="popover" id="thirdCroppedImagePreview" height="150" width="150" src="" alt="no-image" style="display: none;">
                                @endif
                                <label for="upload-profile">Download Banner Image Arabic</label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input name='appDownloadPreference|googlePlaystoreURL' id="appDownloadPreference|googlePlaystoreURL" value="{{$data['googlePlaystoreURL']['value'] ?? ''}}" autocomplete="off" class="is-valid">
                                <label for="appDownloadPreference|googlePlaystoreURL">Google Play store URL <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input name='appDownloadPreference|appStoreURL' id="appDownloadPreference|appStoreURL" value="{{$data['appStoreURL']['value'] ?? ''}}" autocomplete="off" class="is-valid">
                                <label for="appDownloadPreference|appStoreURL">App Store URL <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input name='appDownloadPreference|HUAWEIStoreURL' id="appDownloadPreference|HUAWEIStoreURL" value="{{$data['HUAWEIStoreURL']['value'] ?? ''}}" autocomplete="off" class="is-valid">
                                <label for="appDownloadPreference|HUAWEIStoreURL">HUAWEI Store URL <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="croppedImage" name="appDownloadPreference|qrCodeImage" value="">
                    <input type="hidden" id="secondCroppedImage" name="appDownloadPreference|bannerImageEn" value="">
                    <input type="hidden" id="thirdCroppedImage" name="appDownloadPreference|bannerImageAr" value="">
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('airline.submit')</button>
                    </div>
                </form>

            </div>
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
</section>
@endsection
@section('js')
<!-- Page specific script -->
<script>
    $("#one_crop").click(function(){
        $(this).blur(); // This will remove focus from the file input
        if ($(this).valid()) {
            // Create a success message element
            var successMessage = $('<div class="success-message text-success">The file accepted for upload.</div>');
            // Append the success message after the file input
            $('input[id="qr_code_image"]').after(successMessage);
            $("#one_crop").attr("disabled", true);
            // Set a timeout to remove the success message after a few seconds (optional)
            setTimeout(function() {
                successMessage.remove();
                $("#one_crop").attr("disabled", false);
            }, 3000); // Remove the message after 3 seconds (adjust as needed)
        }
    });
    $("#second_crop").click(function(){
        $(this).blur(); // This will remove focus from the file input
        if ($(this).valid()) {
            // Create a success message element
            var successMessage = $('<div class="success-message text-success">The file accepted for upload.</div>');
            // Append the success message after the file input
            $('input[id="banner_image_en"]').after(successMessage);
            $("#second_crop").attr("disabled", true);
            // Set a timeout to remove the success message after a few seconds (optional)
            setTimeout(function() {
                successMessage.remove();
                $("#second_crop").attr("disabled", false);
            }, 3000); // Remove the message after 3 seconds (adjust as needed)
        }
    });
    $("#third_crop").click(function(){
        $(this).blur(); // This will remove focus from the file input
        if ($(this).valid()) {
            // Create a success message element
            var successMessage = $('<div class="success-message text-success">The file accepted for upload.</div>');
            // Append the success message after the file input
            $('input[id="banner_image_ar"]').after(successMessage);
            $("#third_crop").attr("disabled", true);
            // Set a timeout to remove the success message after a few seconds (optional)
            setTimeout(function() {
                successMessage.remove();
                $("#third_crop").attr("disabled", false);
            }, 3000); // Remove the message after 3 seconds (adjust as needed)
        }
    });
</script>
<script>
    // Listen for file input change event

    $('.select-validate').on('change', function() {
        if ($(this).valid()) {
            // If the file is valid, remove the 'is-invalid' class
            $(this).removeClass('is-invalid');
            // Remove the 'invalid-feedback' element
            $(this).next('.invalid-feedback').remove();
        }
    });
    $(function() {
        $('#appDownloadPreferenceForm').validate({
            onkeyup: false, //turn off auto validate whilst typing
            rules: {
                'appDownloadPreference|titleEn': {
                    required: true,
                    noSpace: true,
                },
                'appDownloadPreference|titleAr': {
                    required: true,
                    noSpace: true,
                },
                'appDownloadPreference|qrCodeImage': {
                    accept: "image/jpg,image/jpeg,image/png",
                    maxsize: 1000000,
                },
                'appDownloadPreference|bannerImageEn': {
                    accept: "image/jpg,image/jpeg,image/png",
                    maxsize: 1000000,
                },
                'appDownloadPreference|bannerImageAr': {
                    accept: "image/jpg,image/jpeg,image/png",
                    maxsize: 1000000,
                },
                'appDownloadPreference|googlePlaystoreURL': {
                    required: true,
                    noSpace: true,
                },
                'appDownloadPreference|appStoreURL': {
                    required: true,
                    noSpace: true,
                },
                'appDownloadPreference|HUAWEIStoreURL': {
                    required: true,
                    noSpace: true,
                }
            },
            messages: {
                'appDownloadPreference|titleEn': {
                    required: "Please enter Title English"   
                },
                'appDownloadPreference|titleAr': {
                    required: "Please enter Title Arabic"   
                },
                'appDownloadPreference|qrCodeImage': {
                    accept: "Please select image format must be .jpg, .jpeg or .png.",
                    maxsize: "Please upload image size less than 1MB"
                },
                'appDownloadPreference|bannerImageEn': {
                    accept: "Please select image format must be .jpg, .jpeg or .png.",
                    maxsize: "Please upload image size less than 1MB"
                },
                'appDownloadPreference|bannerImageAr': {
                    accept: "Please select image format must be .jpg, .jpeg or .png.",
                    maxsize: "Please upload image size less than 1MB"
                },
                'appDownloadPreference|googlePlaystoreURL': {
                    required: "Please enter Google Play Store URL"   
                },
                'appDownloadPreference|appStoreURL': {
                    required: "Please enter App Store URL"   
                },
                'appDownloadPreference|HUAWEIStoreURL': {
                    required: "Please enter HUAWEI Store URL"   
                }
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
            highlight: function(element, errorClass) {
                $(element).removeClass(errorClass); //prevent class to be added to selects
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