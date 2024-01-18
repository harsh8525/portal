@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<style>
  .text-color {
    color: #343a40;
    font-size: 15px;

  }

  .form-item input.is-valids+label {
    font-size: 11px;
    top: -5px;
  }

  .order-edit-form .form-floating,
  .order-edit-form .order-td-input {
    width: max-content;
  }

  .td-data-color td .order-td-input {
    height: 50px;
  }

  .select-text:focus~.select-label,
  .select-text:valid~.select-label {
    color: #ef3835;
    top: -5px;
    transition: 0.2s ease all;
    font-size: 12px;
    font-weight: 400;
  }

  .select-text:focus {
    border-color: #ef3835;
  }

  .select-text:valid:not(focus)~.select-label {
    color: #999;
    background: #fff;
    padding: 0 10px;
    line-height: 1;
  }

  .select-text:valid:focus~.select-label {
    color: #ef3835;
  }

  .height_drp {
    height: 50px;
  }
</style>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 d-flex breadcrumb-style">
        <h1 class="m-0">{{ $header['heading'] }}</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('general.dashboard') </a></li>
          <li class="breadcrumb-item active">Currencies</li>
        </ol>

      </div><!-- /.col -->

    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  @if (session('success'))
  <div class="alert alert-success" role="alert">
    <?php echo session('success'); ?>
  </div>
  @endif
  @if (session('error'))
  <div class="alert alert-danger" role="alert">
    {{ session('error') }}
  </div>
  @endif
  @if (isset($error))
  <div class="alert alert-danger" role="alert">
    {{ $error }}
  </div>
  @endif
  <!--Start Main Div -->
  <div class="row mb-3">
    <div class="col-md-6">
      <div class="hidden d-none"></div>
      <form method="post" id="currencyForm" name="currencyForm" class="validate" action="{{ route('currency.store') }}">
        @csrf
        <div class="mb-3 filter-form">
          <div class="form-floating form-item mb-3">
            <div class="form-item form-float-style serach-rem mb-3">
              <div class="select top-space-rem after-drp form-float-style ">
                <?php
                $currenyData =  DB::table('currencies')->get()->toArray();
                ?>
                <select data-live-search="true" name="base_currency" id="base_currency" class="js-select2 order-td-input select-text height_drp is-valid" disabled>
                  @foreach($currenyData as $data)
                  <option value="{{ $data->id }}" @if($data->is_base_currency == "1") selected='selected' @endif>{{ $data->name }}</option>
                  @endforeach


                </select>

                <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">BASE CURRENCY<span class="req-star">*</span></label>
              </div>
            </div>
          </div>
        </div>
        <div class="mb-3 filter-form">
          <div class="form-floating form-item mb-3">
            <div class="form-item form-float-style serach-rem mb-3">
              <div class="select top-space-rem after-drp form-float-style ">

                <select data-live-search="true" name="allow_currency_id[]" multiple id="allow_currency_id" class="allow_currency js-select2 order-td-input select-text height_drp is-valid">

                </select>

                <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">ALLOW CURRENCY<span class="req-star">*</span></label>
              </div>
            </div><span id="access-code-error-allow_currency" class="rsvp required-fields text-danger"></span>
          </div>
        </div>
        <div class="mb-3 filter-form">
          <div class="form-floating form-item mb-3">
            <div class="form-item form-float-style serach-rem mb-3">
              <div class="select top-space-rem after-drp form-float-style  ">
                <select data-live-search="true" name="default_display_currency_id" id="default_display_currency_id" class=" js-select2 order-td-input select-text height_drp is-valid">
                  <option value="" selected>Select Currency</option>
                </select>

                <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">DEFAULT DISPLAY CURRENCY<span class="req-star">*</span></label>
              </div>
            </div>
          </div>
        </div>
        <div class="mb-3 filter-form">
          <div class="form-floating form-item mb-3">
            <div class="form-item form-float-style serach-rem mb-3">
              <div class="select top-space-rem after-drp form-float-style  ">
                <select data-live-search="true" name="top_currency_id[]" multiple id="top_currency_id" class="js-select2 order-td-input select-text height_drp is-valid">

                </select>
                <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">TOP CURRENCY<span class="req-star">*</span></label>
              </div>
            </div>
          </div>
        </div>
        <div class="mb-3 filter-form">
          <div class="form-floating form-item mb-3">
            <div class="form-item form-float-style serach-rem mb-3">
              <div class="select top-space-rem after-drp form-float-style">
                <select data-live-search="true" name="supplier_allowed_curreny_id[]" data-type="supplier" multiple id="supplier_allowed_curreny_id" class="js-select2 order-td-input select-text height_drp is-valid">

                </select>

                <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">SUPPLIER CURRENCY<span class="req-star">*</span></label>
              </div>
            </div>
          </div><span id="access-code-error-supplier" class="rsvp required-fields text-danger"></span>
        </div>
        <div class="mb-3 filter-form">
          <div class="form-floating form-item mb-3">
            <div class="form-item form-float-style serach-rem mb-3">
              <div class="select top-space-rem after-drp form-float-style">
                <select data-live-search="true" name="b2b_allowed_curreny_id[]" data-type="b2b" multiple id="b2b_allowed_curreny_id" class="js-select2 order-td-input select-text height_drp is-valid">

                </select>

                <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">B2B CURRENCY<span class="req-star">*</span></label>
              </div>
            </div>
          </div><span id="access-code-error-b2b" class="rsvp required-fields text-danger"></span>
        </div>


        <div class="cards-btn">
          <button type="submit" class="btn btn-success form-btn-success">Submit</button>
          <a href="{{ route('currency.exchange-rate') }}" class="btn btn-warning form-btn-warning">Exchange Rate</a>
          <a href="{{ route('admin.dashboard') }}" class="btn btn-danger form-btn-danger">Cancel</a>
        </div>
    </div>
  </div>
  <!--End Main Div -->
</section>
<!-- /.content -->

@endsection
@section('js')

<script>
  $(document).ready(function() {



    var url = '{{ route("admin.get-allow_currency") }}';
    $.ajax({
      type: "GET",
      url: url,
      success: function(data) {

        var obj = JSON.parse(data);
        $('#allow_currency_id').html('');

        $.each(obj['is_allowed'], function(key, value) {
          optionText = value['name'];
          optionValue = value['id'];

          selected = "";

          $.each(obj['allowed_true'], function(index1, value1) {
            if (optionValue == value1['id']) {
              selected = "selected";
            }
          });

          $('#allow_currency_id').append(`<option ${selected} value="${optionValue}">
                            ${optionText}
                        </option>`);
        });

        $.each(obj['allowed_true'], function(key, value) {
          optionText = value['name'];
          optionValue = value['id'];

          selectedDefault = selectedTop = selectedSupplier = selectedB2B = selectedB2BCurrency = "";

          //append selected default currency
          $.each(obj['default_currency'], function(index1, value1) {
            if (optionValue == value1['id']) {
              selectedDefault = "selected";
            }
          });

          $('#default_display_currency_id').append(`<option ${selectedDefault} value="${optionValue}">
                            ${optionText}
                        </option>`);

          //append selected top currency
          $.each(obj['top_currency'], function(index1, value1) {
            if (optionValue == value1['id']) {
              selectedTop = "selected";
            }
          });

          $('#top_currency_id').append(`<option ${selectedTop} value="${optionValue}">
                            ${optionText}
                        </option>`);

          //append selected supplier currency
          $.each(obj['supplier_currency'], function(index1, value1) {
            if (optionValue == value1['id']) {
              selectedSupplier = "selected";
            }
          });

          $('#supplier_allowed_curreny_id').append(`<option ${selectedSupplier} value="${optionValue}">
                            ${optionText}
                        </option>`);

          //append selected B2B currency
          $.each(obj['b2b_currency'], function(index1, value1) {
            if (optionValue == value1['id']) {
              selectedB2B = "selected";
            }
          });
          //disabled existed currency into B2B agency
          $.each(obj['agencyCurrency'], function(index1, value1) {
            if (optionValue == value1['currency_id']) {
              selectedB2BCurrency = "disabled";
            }
          });

          $('#b2b_allowed_curreny_id').append(`<option ${selectedB2B} ${selectedB2BCurrency} value="${optionValue}">
                            ${optionText}
                        </option>`);

        });
      }

    });


  });

  //get defult currency data
  $("#allow_currency_id").change(function() {

    var url = '{{ route("admin.get-default_currency") }}';
    var selectedValues = $(this).val();

    $.ajax({
      type: "POST",
      url: url,
      data: {
        "selectedValues": selectedValues,
        "_token": "{{ csrf_token() }}"
      },

      success: function(data) {
        var obj = JSON.parse(data);
        $('#default_display_currency_id').html('');
        $('#top_currency_id').html('');
        $('#supplier_allowed_curreny_id').html('');
        $('#b2b_allowed_curreny_id').html('');
        $.each(obj['currency'], function(key, value) {
          optionText = value['name'];
          optionValue = value['id'];

          selectedDefault = selectedTop = selectedSupplier = selectedB2B = "";
          //append default currency
          $.each(obj['default_currency'], function(index1, value1) {
            if (optionValue == value1['id']) {
              selectedDefault = "selected";
            }
          });
          $('#default_display_currency_id').append(`<option ${selectedDefault} value="${optionValue}">
                            ${optionText}
                        </option>`);

          //append top currency

          $.each(obj['top_currency'], function(index1, value1) {
            if (optionValue == value1['id']) {
              selectedTop = "selected";
            }
          });
          $('#top_currency_id').append(`<option ${selectedTop} value="${optionValue}">
                            ${optionText}
                        </option>`);

          //append supplier currency
          $.each(obj['supplier_currency'], function(index1, value1) {
            if (optionValue == value1['id']) {
              selectedSupplier = "selected";
            }
          });
          $('#supplier_allowed_curreny_id').append(`<option ${selectedSupplier} value="${optionValue}">
                            ${optionText}
                        </option>`);

          //append B2B currency
          $.each(obj['b2b_currency'], function(index1, value1) {
            if (optionValue == value1['id']) {
              selectedB2B = "selected";
            }
          });
          $('#b2b_allowed_curreny_id').append(`<option ${selectedB2B} value="${optionValue}">
                            ${optionText}
                        </option>`);
        });

      }

    });
  });
</script>
<script>
  // INCLUDE JQUERY & JQUERY UI 1.12.1

  // valid in input materila js
  const textareas = document.querySelectorAll("textarea");

  textareas.forEach((textarea) => {
    textarea.addEventListener("blur", (event) => {
      if (event.target.value) {
        textarea.classList.add("is-valid");
      } else {
        textarea.classList.remove("is-valid");
      }
    });
  });
</script>

<script>
  $(document).ready(function() {
    $(".js-select2").select2({
      placeholder: "Select Currency",
      theme: "material"
    });
    $('.js-select2').on('select2:unselecting', function(e) {

      var allowRemoval = true; // Default condition value
      var id = e.params.args.data.id;
      var code = $(this).attr('data-type');
      $.ajax({
        url: '{{ route("admin.agency-currency.checkExist") }}', // Your server endpoint to check existence
        data: {
          value: id,
          code: code,
          "_token": "{{ csrf_token() }}"
        },
        method: 'GET',
        async: false, // E
        success: function(response) {
          console.log(response);
          $('#access-code-error-b2b').html('');
          $('#access-code-error-supplier').html('');
          if (response.exists) {
            allowRemoval = false;
            if (response.agency_type == "supplier") {
              $('#access-code-error-supplier').html('Currency exist in Agency.It can not remove');
            } else {
              $('#access-code-error-b2b').html('Currency exist in Agency.It can not remove');
            }
          }
        }
      });
      if (!allowRemoval) {
        e.preventDefault();
        allowRemoval = true; // Reset the condition for the next interaction

      }
    });
    $(".select2-selection__arrow")
      .addClass("material-icons")
      .html("arrow_drop_down");
  });
</script>
<script>
  $(document).ready(function() {
    $(".allow_currency").select2({
      placeholder: "Select Currency",
      theme: "material"
    });
    $('.allow_currency').on('select2:unselecting', function(e) {

      var allowRemoval = true; // Default condition value
      var id = e.params.args.data.id;
      console.log(id);
      $.ajax({
        url: '{{ route("admin.check-allow_currency") }}', // Your server endpoint to check existence
        data: {
          value: id,
          "_token": "{{ csrf_token() }}"
        },
        method: 'GET',
        async: false, // E
        success: function(response) {
          console.log(response);
          $('#access-code-error-allow_currency').html('');
          if (response.exists) {
            allowRemoval = false;
            // return false;
            $('#access-code-error-allow_currency').html('Currency exist in Agency.It can not remove');
          }
        }
      });
      if (!allowRemoval) {
        e.preventDefault();
        allowRemoval = true; // Reset the condition for the next interaction
      }
    });
    $(".select2-selection__arrow")
      .addClass("material-icons")
      .html("arrow_drop_down");
  });
</script>
<script>
  $(function() {
    //jquery Form validation
    $('#currencyForm').validate({
      rules: {
        "allow_currency_id[]": {
          required: true,
        },
        "default_display_currency_id": {
          required: true,
        },
        "top_currency_id[]": {
          required: true,
        },
        "supplier_allowed_curreny_id[]": {
          required: true,
        },
        "b2b_allowed_curreny_id[]": {
          required: true,
        },
      },
      messages: {
        "allow_currency_id[]": {
          required: "Please select an Allowed Currencies",

        },
        "default_display_currency_id": {
          required: "Please select a Default Allowed Currency"
        },
        "top_currency_id[]": {
          required: "Please select a Top Currencies"
        },
        "supplier_allowed_curreny_id[]": {
          required: "Please select a Suppliers Allowed Currencies"
        },
        "b2b_allowed_curreny_id[]": {
          required: "Please select a B2B Allowed Currencies"
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
      }
    });
  });

  $(document).ready(function() {
    $('.select2-selection__choice__remove').click(function() {
      alert("hello");
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@append