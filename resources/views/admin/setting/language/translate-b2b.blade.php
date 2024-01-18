@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')
<style>
    .invalid-feedback {
        text-transform: initial;
    }

    textarea {
        width: 100%;
        min-height: 50px;
        border: 1px solid #ccc;
        padding: 10px;
        box-sizing: border-box;
        font-size: 14px;
        font-family: Arial, sans-serif;
        line-height: 1.5;
        resize: none;
        overflow-y: hidden;
    }

    textarea#language_code {
        height: auto;
        /* This will allow the textarea to adjust its height based on content */
        overflow: hidden;
        /* This prevents scrollbars from appearing */
    }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
                <h1 class="m-0">{{ $header['heading'] }}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('language.index') }}">Language </a></li>
                    <li class="breadcrumb-item active">B2B Translate</li>
                </ol>
                <div class="breadcrumb-btn">
                </div>
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
            <div class="card pb-3 pt-3 px-3 w-100">
                <div class="discount">
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
                    @if(session('failures'))
                    <div class="alert alert-danger">
                        <h4>Validation Failures:</h4>
                        <ul>
                            @foreach(session('failures') as $failure)
                            <li>Row {{ $failure->row() }}: {{ implode(", ", $failure->errors()) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="grid-list-head py-2">
                        <h3>Language - B2B Translate</h3>
                        <div class="gridlist-icons">
                            <div class="filter grid-icon-top" title="Filter">
                                <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    <svg fill="#fff" width="26px" height="26px" viewBox="0 0 24 24" version="1" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 6h-14c-1.1 0-1.4.6-.6 1.4l4.2 4.2c.8.8 1.4 2.3 1.4 3.4v5l4-2v-3.5c0-.8.6-2.1 1.4-2.9l4.2-4.2c.8-.8.5-1.4-.6-1.4z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <!-- collapse -->
                        <div class="collapse filter-collapse w-100" id="collapseExample">
                            <form autocomplete="off" id="filter" method="GET" action="{{ route('language.translate.b2b',$id) }}">
                                <div class="card-filter card-body w-100 mt-3">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-3 filter-form mb-3">
                                                    <div class="form-floating">
                                                        <div class="form-item form-float-style">
                                                            <input type="text" id="search_key" name="search_key" autocomplete="off" value="{{@$_GET['search_key']}}" class="is-valid">
                                                            <label for="sort">Search By Key</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 filter-form mb-3">
                                                    <div class="form-floating">
                                                        <div class="form-item form-float-style">
                                                            <input type="text" id="search_value" name="search_value" autocomplete="off" value="{{@$_GET['search_value']}}" class="is-valid">
                                                            <label for="sort">Search By Value</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 filter-buttons">
                                            <button type="submit" class="submit-filter filter-btm-btn" title="Apply">
                                                <a href="">
                                                    <svg fill="#ffffff" width="17" height="17" viewBox="0 0 448 512">
                                                        <path d="M438.6 105.4C451.1 117.9 451.1 138.1 438.6 150.6L182.6 406.6C170.1 419.1 149.9 419.1 137.4 406.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4C21.87 220.9 42.13 220.9 54.63 233.4L159.1 338.7L393.4 105.4C405.9 92.88 426.1 92.88 438.6 105.4H438.6z" />
                                                    </svg>
                                                </a>
                                            </button>
                                            <div class="refress-filter filter-btm-btn" title="Refresh">
                                                <a href="{{route('language.translate.b2b',$id)}}">
                                                    <svg fill="#ffffff" width="17" height="17" viewBox="0 0 512 512">
                                                        <path d="M464 16c-17.67 0-32 14.31-32 32v74.09C392.1 66.52 327.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.89 5.5 34.88-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c50.5 0 96.26 24.55 124.4 64H336c-17.67 0-32 14.31-32 32s14.33 32 32 32h128c17.67 0 32-14.31 32-32V48C496 30.31 481.7 16 464 16zM441.8 289.6c-16.92-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-50.5 0-96.25-24.55-124.4-64H176c17.67 0 32-14.31 32-32s-14.33-32-32-32h-128c-17.67 0-32 14.31-32 32v144c0 17.69 14.33 32 32 32s32-14.31 32-32v-74.09C119.9 445.5 184.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <form id="dataForm" name="dataForm" class="form row mb-0 pt-3 validate" action="{{ route('languages.updateLangTranslatorB2B') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id" name="id" value="{{$id}}" />
                    <div class="col-md-12 row"><!-- Start col-md-12 row  Div -->
                        @if($content)
                        @php $i=0; @endphp
                        <table class="table table-head-fixed" id="addMore">
                            <thead class="td-data-color">
                                <tr>
                                    <th>#</th>
                                    <th>Key</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody class="td-data-color">
                                @foreach($content as $data =>$values)
                                @php $i++; @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td style="width: 50%;">
                                        <input name="key[]" type="hidden" class="key" id="key" autocomplete="off" value="{{$data}}">
                                        <label for="language_code" name="key" value="{{$data}}" style="text-transform: none;">{{$data}}<span class="req-star"></span></label>
                                    </td>
                                    <td>
                                        @if($langCode == 'en')
                                        <textarea name="value[]" type="text" id="language_code" autocomplete="off">{{$values}}</textarea>
                                        @elseif($langCode == 'ar')
                                        <textarea name="value[]" type="text" id="language_code" autocomplete="off" dir="rtl">{{$values}}</textarea>
                                        @else
                                        <textarea name="value[]" type="text" id="language_code" autocomplete="off">{{$values}}</textarea>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <table class="table table-head-fixed" id="addMore">
                            <thead class="td-data-color">
                                <tr>
                                    <th>#</th>
                                    <th>Key</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody class="td-data-color">
                            </tbody>
                        </table>
                        @endif
                        <div class="d-flex justify-content-between">
                            <button type="button" id="disBtn" class="btn btn-primary add_more" onclick="addMore()" data-inc="{{$i ?? 0}}">Add More</button>
                            <button type="button" id="saveJson" class="btn btn-success form-btn-success">Submit</button>
                        </div>
                    </div><!-- End col-md-12 row Div -->
                </form>
            </div><!--End Card  div-->
        </div> <!-- /.row -->
    </div> <!--/. container-fluid -->
    <div id="counter"></div>
</section>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('textarea').on('input', function() {
            this.style.height = 'auto'; // Reset the height to auto
            this.style.height = (this.scrollHeight) + 'px'; // Set the height based on content
        }).trigger('input'); // Trigger input event to set initial height
    });
    function validateInput(input) {
        if (input.val() === '') {
            return false;
        }
        return true;
    }
    var inc = $(".add_more").data("inc");
    var counter = inc + 1;
    function addMore() {
        var table = document.getElementById("addMore");
        var newRow = table.insertRow(table.rows.length);
        var add_more_key = $('#add_more_key').val();

        var isValid = true;
        var match = true;
        $('.add_more_validation').each(function() {
            var value = $(this).val();
            if (value === '') {
                $(this).addClass('is-invalid');
                isValid = false;
                return false;
            } else {
                var foundMatch = false;
                $('.key').each(function() {
                    if ($(this).val() === value) {
                        console.log($(this).val() + ':' + value);
                        $(this).addClass('is-invalid');
                        $('.d-none').addClass('check');
                        $('.exits-key').removeClass('d-none');
                        $('.invalid-feedback').hide();
                        match = false;
                        foundMatch = true;
                        return false; // Exit the loop
                    }
                });
                if (foundMatch) {
                    $('.exits-key').html('This key is already exits');
                    return false;
                }
                $(this).removeClass('is-invalid');
            }
        });
        if (isValid && match) {
            $('.check').removeClass('exits-key');
            $('.check').addClass('d-none');

            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            var cell3 = newRow.insertCell(2);

            // Create an input element
            var inputElement_cell2 = document.createElement("input");
            inputElement_cell2.type = "text"; // Set the input type
            inputElement_cell2.name = "key[]"; // Set the input name
            inputElement_cell2.autocomplete = "off"; // Set the input autocomplete
            inputElement_cell2.id = "add_more_key"; // Set the input id
            inputElement_cell2.classList.add('add_more_validation'); // Set the input class
            inputElement_cell2.placeholder = "key"; // Set the input placeholder

            var inputElement_cell3 = document.createElement("div");
            inputElement_cell3.classList.add('invalid-feedback'); // Set the input class
            inputElement_cell3.textContent = 'Please enter a Key';

            var inputElement_cell6 = document.createElement("div");
            inputElement_cell6.classList.add('d-none'); // Set the input class
            inputElement_cell6.classList.add('exits-key'); // Set the input class
            inputElement_cell6.classList.add('text-red'); // Set the input class
            inputElement_cell6.textContent = 'This key is already exits';

            var inputElement_cell4 = document.createElement("input");
            inputElement_cell4.type = "text"; // Set the input type
            inputElement_cell4.name = "value[]"; // Set the input name
            inputElement_cell4.autocomplete = "off"; // Set the input autocomplete
            inputElement_cell4.id = "add_more_value"; // Set the input id
            inputElement_cell4.placeholder = "value"; // Set the input placeholder

            // Append the input element to the cell
            cell1.innerHTML = counter;
            cell2.appendChild(inputElement_cell2);
            cell2.appendChild(inputElement_cell3);
            cell2.appendChild(inputElement_cell6);
            cell3.appendChild(inputElement_cell4);
            counter++;
        }
        // counter++;
    };

    $(document).ready(function() {
        $("#saveJson").click(function() {

            var isValid = true;
            var match = true;
            $('.add_more_validation').each(function() {
                var value = $(this).val();
                if (value === '') {
                    $(this).addClass('is-invalid');
                    isValid = false;
                    return false;
                } else {
                    var foundMatch = false;
                    $('.key').each(function() {
                        if ($(this).val() === value) {
                            console.log($(this).val() + ':' + value);
                            $(this).addClass('is-invalid');
                            $('.d-none').addClass('check');
                            $('.exits-key').removeClass('d-none');
                            $('.invalid-feedback').hide();
                            match = false;
                            foundMatch = true;
                            return false; // Exit the loop
                        }
                    });

                    if (foundMatch) {
                        $('.exits-key').html('This key is already exits');
                        return false;
                    }
                    $(this).removeClass('is-invalid');
                }
            });
            if (isValid && match) {
                $("#dataForm").submit();
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append