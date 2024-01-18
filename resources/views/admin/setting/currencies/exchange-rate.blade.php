@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')

<style>
    .form-item input.is-valids+label {
        font-size: 11px;
        top: -5px;
    }

    .set-width {
        height: 43px !important;
        width: 230px !important;
    }

    .input-container {
        display: -ms-flexbox;/ IE10 / display: flex;
        width: 100%;
        margin-bottom: 15px;
    }

    .icon {
        padding: 10px;
        background: #ebe1e1;
        color: black;
        min-width: 25px;
        text-align: center;
        font-weight: 600
    }

    .input-field {
        width: 100%;
        padding: 10px;
        outline: none;
    }

    .input-field:focus {
        border: 2px solid dodgerblue;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
                <h1 class="m-0">Exchange Rate</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('currency.index') }}">Currency</a></li>
                    <li class="breadcrumb-item active">Exchange Rate</li>
                </ol>
                <div class="breadcrumb-btn">
                </div>
            </div><!-- /.col -->

        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<section class="content">
    <div class="card mb-3">
        <div class="col-md-6 discount">
            <form method="post" id="dataForm" name="currencyForm" class="validate" action="{{route('admin.get-currency_apply_margin')}}">
                @csrf
                <div class="form-item form-float-style input-container mt-3">
                    <input class="set-width is-valid" type="text" value="" id="marginall" name="margin" autocomplete="off" maxlength="3" onkeypress="return isNumber(event)" placeholder="100" required>
                    <label for="Margin">Overall Margin <span class="req-star">*</span></label>
                    <i class="fas fa-percentage icon" aria-hidden="true" style="width: 29px!important;"></i>
                    <button type="submit" class="btn btn-link" id="disBtn">Apply Margin</button>
                </div>
            </form>
            <div>
                <button type="submit" class="btn-sm btn-info" id="exchange_btn">Refresh Exchange Rate</button>
            </div>
            <div>
                <a href="#" style="margin-left: 8px;margin-top: 4px;font-size: 13px;">Source: https://www.google.com/finance/</a>
            </div>
        </div>
    </div>
        <?php
        // echo "<pre>";print_r($appliedFilter);die;
        $customerDetail = App\Models\Currency::select('code', 'name')->where('is_allowed', 1)->get();
        ?>
        <div class="col-md-12 discount mb-3" style="padding: 0px;">
            <form autocomplete="off" id="filter" method="GET" action="{{route('currency.exchange-rate')}}">
                <div class="card-filter card-body w-100 mt-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row col-md-12 d-flex">
                                <div class="col-md-3 filter-form mb-3">
                                    <div class="form-floating">
                                        <div class="form-item form-float-style">
                                            <div class="select top-space-rem after-drp form-float-style ">
                                                <select data-live-search="true" id="currency_code" name="currency_code" class="order-td-input selectpicker select-text height_drp is-valid">
                                                    <option value="" selected>Select Currency</option>
                                                    @foreach($customerDetail AS $currencyCode)
                                                    <option @if($appliedFilter['currency_code']==$currencyCode['code']) selected="selected" @endif value="{{ $currencyCode['code'] }}">{{$currencyCode['name'].' ('.$currencyCode['code'].')' }}</option>
                                                    @endforeach
                                                </select>
                                                <label class="select-label searchable-drp">Allowed Currency</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 filter-form">
                                    <div class="form-floating form-item mb-0">
                                        <div class="form-item form-float-style serach-rem mb-0">
                                            <div class="select top-space-rem after-drp form-float-style ">
                                                <select data-live-search="true" id="slect_finish" name="per_page" class="order-td-input selectpicker select-text height_drp is-valid">
                                                    <option value="" selected disabled>Select Per Page</option>
                                                    <option @if($appliedFilter['per_page']==5) selected="selected" @endif value="5">5</option>
                                                    <option @if($appliedFilter['per_page']==10) selected="selected" @endif value="10">10</option>
                                                    <option @if($appliedFilter['per_page']==15) selected="selected" @endif value="15">15</option>
                                                    <option @if($appliedFilter['per_page']==20) selected="selected" @endif value="20">20</option>
                                                    <option @if($appliedFilter['per_page']==25) selected="selected" @endif value="25">25</option>
                                                    <option @if($appliedFilter['per_page']==50) selected="selected" @endif value="50">50</option>
                                                    <option @if($appliedFilter['per_page']==100) selected="selected" @endif value="100">100</option>
                                                </select>
                                                <label class="select-label searchable-drp">@lang('coupons.perPageData')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 filter-buttons">
                                    <button type="submit" class="submit-filter filter-btm-btn" title="Apply">
                                        <a href="">
                                            <svg fill="#ffffff" width="17" height="17" viewBox="0 0 448 512">
                                                <path d="M438.6 105.4C451.1 117.9 451.1 138.1 438.6 150.6L182.6 406.6C170.1 419.1 149.9 419.1 137.4 406.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4C21.87 220.9 42.13 220.9 54.63 233.4L159.1 338.7L393.4 105.4C405.9 92.88 426.1 92.88 438.6 105.4H438.6z" />
                                            </svg>
                                        </a>
                                    </button>
                                    <div class="refress-filter filter-btm-btn" title="Refresh">
                                        <a href="{{route('currency.exchange-rate')}}">
                                            <svg fill="#ffffff" width="17" height="17" viewBox="0 0 512 512">
                                                <path d="M464 16c-17.67 0-32 14.31-32 32v74.09C392.1 66.52 327.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.89 5.5 34.88-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c50.5 0 96.26 24.55 124.4 64H336c-17.67 0-32 14.31-32 32s14.33 32 32 32h128c17.67 0 32-14.31 32-32V48C496 30.31 481.7 16 464 16zM441.8 289.6c-16.92-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-50.5 0-96.25-24.55-124.4-64H176c17.67 0 32-14.31 32-32s-14.33-32-32-32h-128c-17.67 0-32 14.31-32 32v144c0 17.69 14.33 32 32 32s32-14.31 32-32v-74.09C119.9 445.5 184.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.get-currency_apply_single_margin')}}" method="post" class='Formdata' id="Formdata">
                @csrf
                <table id="dataList" class="table table-head-fixed text-nowrap">
                    <thead class="td-data-color">
                        <tr>
                            <th class="table-head-notify">MARGIN</th>
                            <th class="table-head-notify">RATE</th>
                            <th class="table-head-notify">LAST UPDATED</th>
                            <th class="table-head-notify">UPDATE TYPE</th>
                            <th class="table-head-notify">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="td-data-color">
                        <tr>
                            @forelse($currencyTypeData as $key=>$data)
                            <form action="{{route('admin.get-currency_apply_single_margin')}}" method="post" class="Formdata">
                                <td class="table-data-notify">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$data['id']}}">
                                    <div class="form-item form-float-style input-container">
                                        <input class="set-width is-valid validate" type="text" value="{{ $data['margin']}}" id="margin" onkeypress="return isNumber(event)" name="margin" autocomplete="off" placeholder="100" maxlength="3" required>
                                        <label for="Margin">Margin <span class="req-star">*</span></label>
                                        <i class="fas fa-percentage icon" aria-hidden="true" style="width: 29px!important;"></i>
                                    </div>
                                </td>
                                <td class="table-data-notify">
                                    <div class="form-item form-float-style input-container">
                                        <i class="icon" aria-hidden="true" style="width: 54px !important;height: 41px !important;">1 {{ $data['from_currency_code'] }}=</i>
                                        <input class="set-width is-valid" type="text" value="{{ $data['exchange_rate'] }}" id="exchange_rate" name="exchange_rate" onkeypress="return isNumber(event)" autocomplete="off" placeholder="0.246501" required>
                                        <label for="Rate" style="left: 59px !important;">Rate <span class="req-star">*</span></label>
                                        <i class="icon" aria-hidden="true" style="width: 38px !important;height: 41px!important;">{{ $data['to_currency_code'] }}</i>
                                    </div>
                                </td>
                                <td class="table-data-notify" style="text-transform: none">
                                    {{getDateTimeZone($data['created_at'])}} {{getTimeZone(($data['created_at']))}}
                                </td>
                                <td class="table-data-notify">
                                    <?php
                                    if ($data['update_type'] == 1) {
                                        echo "AUTO UPDATE";
                                    } elseif ($data['update_type'] == 2) {
                                        echo "MANUAL UPDATE";
                                    }
                                    ?>
                                </td>
                                <td class="table-data-notify">
                                    <button type="submit" class="btn btn-link" id="Btndis">Apply</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
            </form>
            </table>
            @if($currencyTypeData)
            <nav class="pagination-grid" aria-label="Page  navigation example">
                <ul class="pagination">
                    <?php if ($currencyTypeData->hasPages()) { ?>
                        {!! $currencyTypeData->appends(Request::except('page'))->render() !!}
                    <?php } ?>
                </ul>
            </nav>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
</section>
<?php //print_r($data); 
?>
@endsection

@section('js')
<!-- Bootstrap Switch -->
<script src="{{ URL::asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

<!-- Bootstrap Select -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function() {
        $(".multipleUsers").selectpicker();
    });
    $(document).ready(function() {
        $("#exchange_btn").click(function() {
            var margin = $("#margin").val();
            var from_currency_code = $("#from_currency_code").val();
            var to_currency_code = $("#to_currency_code").val();
            var exchange_rate = $("#exchange_rate").val();
            var formData = $(this).serialize();
            $.ajax({
                url: "{{route('admin.get-currency_exchange_rate')}}",
                method: 'POST',
                dataType: 'json',
                data: {
                    margin: margin,
                    from_currency_code: from_currency_code,
                    to_currency_code: to_currency_code,
                    exchange_rate: exchange_rate,
                    _token: "{{csrf_token()}}",
                },

                beforeSend: function() {
                    $(".preloader").css('height', '100%');
                    $(".preloader").css('display', 'inline-flex');
                    $(".animation__wobble").css('display', 'block');
                },
                success: function(response) {
                    window.location.reload();

                },
                complete: function(data) {}
            });
        });
    });

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please");
    $(function() {
        //jquery Form validation
        $('*[value=""]').removeClass('is-valid');
        $('#dataForm').validate({
            rules: {
                marginall: {
                    required: true,
                    noSpace: true,
                },
            },
            messages: {
                marginall: {
                    required: "Enter Over All Margin"
                },
                margin: {
                    required: "Enter Over All Margin"
                },
                exchange_rate: {
                    required: "Enter Margin"
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

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please");
    $(function() {
        //jquery Form validation
        $('*[value=""]').removeClass('is-valid');
        $('#Formdata').validate({
            rules: {
                margin: {
                    required: true,
                    noSpace: true,
                },
                exchange_rate: {
                    required: true,
                    noSpace: true,
                },
            },
            messages: {
                margin: {
                    required: "Enter Margin"
                },
                exchange_rate: {
                    required: "Enter Exchange_Rate"
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
                $("#Btndis").attr("disabled", false);
                form.submit();
            }
        });
    });
</script>
@append