@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')
<style>
    #editor {
        width: 100%;
        height: 500px;
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
                    <li class="breadcrumb-item active">Translate</li>
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
            <div class="card pb-4 w-100 px-3 py-2 mb-3">
                <form id="dataForm" name="dataForm" class="form row mb-0 pt-3 validate" action="{{ route('languages.translate.store.json') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id" value="{{$id}}" />
                    <div class="col-md-12 row"><!-- Start col-md-12 row  Div -->
                        <div class="editable mb-3">
                            <label for="">Json Editor</label>
                            <div id="editor"></div>
                            <input type="hidden" name="lang_file_json" id="lang_file_json" value="">
                        </div>
                    </div><!-- End col-md-12 row Div -->
                    <div class="cards-btn">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success save_json_file">Submit</button>
                    </div>
                </form>
            </div><!--End Card  div-->
        </div> <!-- /.row -->
    </div> <!--/. container-fluid -->

</section>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
<script>
    const localeUrl = '{{ route("language.getFileContents",$id) }}';
    fetch(localeUrl) // Replace with the actual path if different
        .then(response => response.json())
        .then(data => {
            var getData = JSON.parse(data.contents);

            var editor = ace.edit("editor");
            editor.setTheme("ace/theme/monokai");
            editor.getSession().setMode("ace/mode/json");

            editor.setValue(JSON.stringify(getData, null, 2));

            var getValue = JSON.parse(editor.getValue());
            console.log(getValue);
            $("#lang_file_json").val(JSON.stringify(getValue));

            editor.getSession().on('change', function() {
                var code = editor.getSession().getValue();
                document.getElementById('lang_file_json').value = code;
            });
        })
        .catch(error => console.error('Error:', error));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append