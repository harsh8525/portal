<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="card pb-4 w-100 px-3 py-2 mb-3">
                <form id="dataForm" name="dataForm" class="form row mb-0 pt-3 validate" action="{{ route('mark-ups.store') }}" enctype="multipart/form-data" method="post">
                    @csrf

                    <div class="col-md-12 row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="supplier" name="supplier" class="order-td-input selectpicker select-text height_drp is-valid" placeholder="Select Supplier" style="width:100%;"></select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Suppliers<span class="req-star"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 row mb-3">
                        <div class="col-md-3">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="b2c_markup_type" name="b2c_markup_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option selected value="b2c_percentage">%Percentage</option>
                                            <option value="b2c_fixed_amount">Fixed amount</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">B2C Markup Type<span class="req-star"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-item form-float-style form-group">
                                <input type="number" name="b2c_markup" id="b2c_markup" autocomplete="off" required step="any">
                                <label for="b2c_markup">B2C Markup <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 row mb-3">
                        <div class="col-md-3">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="b2b_markup_type" name="b2b_markup_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option selected value="b2b_percentage">%Percentage</option>
                                            <option value="b2b_fixed_amount">Fixed amount</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">B2B Markup Type<span class="req-star"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-item form-float-style form-group">
                                <input type="number" name="b2b_markup" id="b2b_markup" autocomplete="off" required step="any">
                                <label for="b2b_markup">B2B Markup <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>


                    <div class="cards-btn">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                        <a href="{{ route('markups.service_types',['service_type'=>$serviceTypes]) }}" class="btn btn-danger form-btn-danger">Cancel</a>
                    </div>

                </form>



            </div>
        </div>
        <!-- /.row -->
    </div>
    <!--/. container-fluid -->
</section>
@section('js')
<script>
    $(document).ready(function domReady() {
        $(".js-select2").select2({
            placeholder: "Select Airlines",
            theme: "material"
        });

        $(".select2-selection__arrow")
            .addClass("material-icons")
            .html("arrow_drop_down");
    });
</script>
<script>
    $(function() {

        $('*[value=""]').removeClass('is-valid');

        $('#dataForm').validate({
            rules: {
                "supplier": {
                    required: true,
                },
                "b2c_markup_type": {
                    required: true,
                },
                "b2c_markup": {
                    required: true,
                },
                "b2b_markup_type": {
                    required: true,
                },
                "b2b_markup": {
                    required: true,
                },
            },

            messages: {
                "supplier": {
                    required: "Please select a Suppliers",
                },
                "b2c_markup_type": {
                    required: "Please select a B2C Markup Type",
                },
                "b2c_markup": {
                    required: "Please Enter a B2C Markup",
                },
                "b2b_markup_type": {
                    required: "Please select a B2B Markup Type",
                },
                "b2b_markup": {
                    required: "Please Enter a B2B Markup",
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
<script>
    $('#supplier').select2({
        ajax: {
            url: "{{ route('markups.fetchSupplier') }}",
            type: "get",
            delay: 250,
            data: function(params) {
                return {
                    q: params.term || '',
                    page: params.page || 1,
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