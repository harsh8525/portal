@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')

<style>
    .form-item input.is-valids+label {
        font-size: 11px;
        top: -5px;
    }
</style>
<style>
    textarea.select2-search__field {
        width: 40.50em !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #000 !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
    margin-left: 5px;
}
</style>
@php
@endphp
<?php
 $serviceType = $_GET['service_type'];
 $serviceTypeIds = App\Models\ServiceType::where('code',$serviceType)->value('id'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">{{"Default ". $serviceType ." Markup - Add"}}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
                    <li class="breadcrumb-item"><a href="{{ route(''. strtolower($serviceType) .'-markups.index') }}">{{$serviceType." Markups List"}}</a></li>
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
            <div class="card pb-4 pt-3 px-3 w-100">
                <form id="dataForm" name="dataForm" class="form row mb-0 pt-3 validate" action="{{ route('default-flight-markups.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <input type="hidden" name="service_type_id" value="{{ $serviceTypeIds ?? '' }}">

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="serviceType" name="serviceType" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;" disabled>
                                            <option value="flight" @if($serviceType=='Flight' ) selected @endif>Flight</option>
                                            <option value="hotel" @if($serviceType=='Hotel' ) selected @endif>Hotel</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Service Type </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="supplier" name="supplier[]" class="order-td-input selectpicker select-text height_drp is-valid" placeholder="Select Supplier" style="width:100%;" multiple></select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Suppliers <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="b2c_markup_type" name="b2c_markup_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option selected value="percentage">%Percentage</option>
                                            <option value="fixed_amount">Fixed amount</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select B2C Markup Type <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="number" name="b2c_markup" id="b2c_markup" autocomplete="off" required step="any">
                                <label for="b2c_markup">B2C Markup Value <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="b2b_markup_type" name="b2b_markup_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option selected value="percentage">%Percentage</option>
                                            <option value="fixed_amount">Fixed amount</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select B2B Markup Type<span class="req-star"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 row mb-3">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="number" name="b2b_markup" id="b2b_markup" autocomplete="off" step="any">
                                <label for="b2b_markup">B2B Markup Value</label>
                            </div>
                        </div>
                    </div>

                    <div class="cards-btn">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                        <a href="{{ route(''. strtolower($serviceType) .'-markups.index') }}" class="btn btn-danger form-btn-danger">Cancel</a>
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
    
    $('.select-validate').on('change', function() {
        if ($(this).valid()) {
            // If the file is valid, remove the 'is-invalid' class
            $(this).removeClass('is-invalid');
            // Remove the 'invalid-feedback' element
            $(this).next('.invalid-feedback').remove();
        }
    });
    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please");
    $(function() {
        //jquery Form validation
        $('*[value=""]').removeClass('is-valid');

        $('#dataForm').validate({
            rules: {
                "supplier[]": {
                    required: true,
                },
                "b2c_markup_type": {
                    required: true,
                },
                "b2c_markup": {
                    required: true,
                },
            },

            messages: {
                "supplier[]": {
                    required: "Please select a Supplier",
                },
                "b2c_markup_type": {
                    required: "Please select a B2C Markup Type",
                },
                "b2c_markup": {
                    required: "Please Enter a B2C Markup",
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

    $('#supplier').select2({
        ajax: {
            url: "{{ route('markups.fetchSupplier') }}",
            // dataType: 'json',
            type: "get",
            delay: 250,
            data: function(params) {
                return {
                    q: params.term || '',
                    page: params.page || 1,
                    serviceType: '{{$serviceType}}',
                    "_token": '{{ csrf_token() }}'
                };
            },
            processResults: function(data) {
                var results = [];
                console.log('supplier data :', data);

                data.forEach(function(option) {
                    results.push({
                        id: option.id,
                        text: option.name
                    });
                });

                return {
                    results: results,
                    pagination: {
                        more: data.length >= 10 // Adjust based on your pagination logic
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 0
    });
</script>

@append