@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">{{$header['title']}}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('airline.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('social-media-link.index') }}">Social Media Link</a></li>
                    <li class="breadcrumb-item active">{{$header['method']}}</li>
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
                <form method="post" action="{{route('social-media-link.update',$socialMediaLinkDetail['id'])}}" id="socialmedialinkForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
                    <input type="hidden" name="social_media_link_id" id="social_media_link_id" value="{{$socialMediaLinkDetail['id']}}" />
                    
                    <div class="brdr-btm row">
                        <!-- <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="name" name="name" autocomplete="off" class="is-valid" required value="{{ $socialMediaLinkDetail['name'] ?? '' }}">
                                <label for="name">Name <span class="req-star">*</span></label>
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="name" name="name" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;">
                                            <option value="">Select Social Media</option>
                                            <option value="google" @if($socialMediaLinkDetail['name'] == 'google') selected @endif>Google</option>
                                            <option value="facebook" @if($socialMediaLinkDetail['name'] == 'facebook') selected @endif>Facebook</option>
                                            <option value="twitter" @if($socialMediaLinkDetail['name'] == 'twitter') selected @endif>Twitter</option>
                                            <option value="snapchat" @if($socialMediaLinkDetail['name'] == 'snapchat') selected @endif>Snapchat</option>
                                            <option value="instagram" @if($socialMediaLinkDetail['name'] == 'instagram') selected @endif>Instagram</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Name <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="link" name="link" autocomplete="off" class="is-valid" required value="{{ $socialMediaLinkDetail['link'] ?? '' }}">
                                <label for="link">Link<span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style ">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option @if($socialMediaLinkDetail['status']=='active' ) selected="selected" @endif value="active" selected="">@lang('airline.active')</option>
                                            <option @if($socialMediaLinkDetail['status']=='inactive' ) selected="selected" @endif value="inactive">@lang('airline.inActive')</option>
                                        </select>
                                        <label class="select-label searchable-drp">Status</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('airline.submit')</button>
                        <a href="{{ route('social-media-link.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
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
<script> //jquery Form validation
        $('#socialmedialinkForm').validate({
            onkeyup: false, //turn off auto validate whilst typing
            rules: {
                "name": {
                    required: true,
                    noSpace: true,
                },
                link: {
                    required: true,
                    noSpace: true,
                },
            },
            messages: {
                "name": {
                    required: "Please enter Name"
                },
                link: {
                    required: "Please enter Link"   
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
            highlight: function(element, errorClass) {
                $(element).removeClass(errorClass); //prevent class to be added to selects
            },
            submitHandler: function(form) {
                $("#disBtn").attr("disabled", true);
                form.submit();
            }
        });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append