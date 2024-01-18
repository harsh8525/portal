@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<?php app()->setLocale("en"); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">Instagram Feed</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('airline.dashboard') </a></li>
                    <li class="breadcrumb-item active">Instagram Feed</li>
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
                <form method="post" action="{{route('instagram-feed')}}" id="instagramFeedForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    <div class="brdr-btm row">
                        <div class="col-md-8">
                            <div class="form-item form-float-style">
                                <textarea name='instagramFeed' id="instagramFeed" autocomplete="off" class="is-valid">{{$instaFeedData->value ?? ''}}</textarea>
                                <label for="instagramFeed">Instagram Feed <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
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
        $('#instagramFeedForm').validate({
            onkeyup: false, //turn off auto validate whilst typing
            rules: {
                instagramFeed: {
                    required: true,
                    noSpace: true,
                }
            },
            messages: {
                instagramFeed: {
                    required: "Please enter Instagram Feed"   
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