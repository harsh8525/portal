@extends('admin.layout.main')
@section('title', @$header['title'])
  
@section('content')
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">SMS Setting</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">SMS Setting</li>
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
        <div class="container-fluid">
          <!-- Info boxes -->
          </div>
          <!-- /.row -->
        </div>
        <!--/. container-fluid -->
      </section>
      <!-- /.content -->
@endsection

@section('js')
  <script>
    // INCLUDE JQUERY & JQUERY UI 1.12.1
    $(function () {
      $('*[value=""]').removeClass('is-valid');

      $("#datepicker").datepicker({
        dateFormat: "dd-mm-yy",
        duration: "fast"
      });
      $("#security").change(function(){
        if(this.value == "SSL")
        {
          $('#port').val('465');
        }
        else if(this.value == "TLS")
        {
          $('#port').val('587');
        }else{
          $('#port').val('');
        }
      });
      $(".smtp-enable").change(function(){
        if($(this).is(":checked") && $(this).val() === "1") {
          $("#smtpOption").show();
        } else {
          $("#smtpOption").hide();
        }
      });
      $(".smtp-enable").trigger('change');
    });
  </script>

  
  <!-- Page specific script -->
  <script>
    

    document.getElementById('flexCheckDefault1').onclick = function () {
      var checkboxes = document.getElementsByName('check');
      for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
      }
    }

    
  </script>

  <script>
    $(function(){
       //jquery Form validation
       $('#dataForm').validate({
            rules: {
              "mail|smtp|host": {
                required: true,
               },
              "mail|smtp|fromEmail": {
                required: false,
                email:true
              },
              'mail|smtp|userName': {
                required: true,
              },
              "mail|smtp|password":{
                required:true,
                minlength:8
              },
              "mail|smtp|security":{
                  required:true
              },
              "mail|smtp|port":{
                  required:true,
                  digits:true
              }
            },
            
            
            messages:{
              "mail|smtp|host":{
                    required: "Please enter a Host",
                    email: "Please enter a valid Host"
                },
                "mail|smtp|fromEmail":{
                    required: "Please enter an Email"
                },
                'mail|smtp|userName':{
                    required: "Please enter a User Name"
                },
                "mail|smtp|password":{
                    required: "Please enter a Password"
                },
                "mail|smtp|security":{
                    required: "Please select a Security"
                },
                "mail|smtp|port":{
                    required: "Please enter a Port Number"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
              error.addClass('invalid-feedback');
              element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
              $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
              $(element).removeClass('is-invalid');
            }
          });
    });
  </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append
