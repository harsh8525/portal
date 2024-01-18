@extends('admin.layout.main')
@section('title', @trans('rolePermission.addRolePermission'))

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
        <h1 class="m-0">@lang('rolePermission.addRolePermission')</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('rolePermission.dashboard') </a></li>
          <li class="breadcrumb-item"><a href="{{ route('role-permission.index') }}">@lang('rolePermission.moduleHeading')</a></li>
          <li class="breadcrumb-item active">@lang('rolePermission.add')</li>
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
      <div class="card pb-4 w-100 px-3 py-2">
        <form method="post" action="{{route('role-permission.store')}}" class="form row mb-0 pt-3 validate" id="dataForm" enctype="multipart/form-data">
          @csrf
          <div class="col-md-6">
            <div class="">
              <div class="form-item form-float-style">
                <input type="text" id="name" name="name" autocomplete="off" required value="">
                <label for="rolname">Role Name<span class="req-star">*</span></label>
              </div>
            </div>
            <div class="">
              <div class="form-floating form-float-style form-group required mb-3">
                <div class="form-item form-float-style serach-rem mb-3">
                  <div class="select top-space-rem after-drp form-float-style ">
                    <select data-live-search="true" name="status" id="status" class="order-td-input selectpicker select-text height_drp is-valid">
                      <option value="1" selected>@lang('rolePermission.active')</option>
                      <option value="0">@lang('rolePermission.inActive')</option>
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">@lang('rolePermission.status')</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="">
              <div class="form-floating form-float-style form-group required mb-3">
                <div class="form-item form-float-style serach-rem mb-3">
                  <div class="select top-space-rem after-drp form-float-style ">
                    <select data-live-search="true" name="role_type" id="role_type" class="order-td-input selectpicker select-text height_drp is-valid select-validate">
                      <option value="" selected disabled>Select Role Type</option>
                      <option value="manager">@lang('rolePermission.manager')</option>
                      <option value="supplier">@lang('rolePermission.supplier')</option>
                      <option value="b2b">@lang('rolePermission.b2b')</option>
                    </select>
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">@lang('rolePermission.role_type')</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="">
              <div class="form-item form-float-style mb-3">
                <textarea type="text" id="meta_description" name="description" autocomplete="off" required=""></textarea>
                <label for="comment">@lang('rolePermission.roleDescription') <span class="req-star">*</span></label>
              </div>
            </div>
          </div>
          <div class="mb-3" id="module_table">
            <div class="discount mt-3">
              <div class="">
                <div class="table-card ">
                  <!-- /.card-header -->
                  <div class="card-body table-radius table-responsive p-0">
                    <table class="table table-head-fixed text-nowrap">
                      <thead class="td-data-color">
                        <tr>
                          <th class="">
                            <span>Module</span>
                          </th>
                          <th>@lang('rolePermission.add')</th>
                          <th>@lang('rolePermission.view')</th>
                          <th>@lang('rolePermission.edit')</th>
                          <th>@lang('rolePermission.delete')</th>
                        </tr>
                      </thead>
                      <tbody class="td-data-color th-data-color">
                        @foreach($getModuleList as $module)
                        <tr>
                          <th class="">
                            {{ $module->module_name }}
                          </th>
                          <td class=""><label class="switch">
                              <input type="checkbox" name="module[{{$module->module_code}}][create]" class="{{$module->group_code}}_create_box" id="{{$module->module_code}}_create_box" data-module="{{$module->module_code}}" onchange="myCreateFunction($(this).attr('data-module'));">
                              <span class="slider"></span>
                            </label></td>
                          <td class=""><label class="switch">
                              <input type="checkbox" name="module[{{$module->module_code}}][read]" class="{{$module->group_code}}_read_box" id="{{$module->module_code}}_read_box" data-read="{{$module->module_code}}" onchange="myReadFunction($(this).attr('data-read'));">
                              <span class="slider"></span>
                            </label></td>
                          <td class=""><label class="switch">
                              <input type="checkbox" name="module[{{$module->module_code}}][update]" class="{{$module->group_code}}_update_box" id="{{$module->module_code}}_update_box" data-module="{{$module->module_code}}" onchange="myUpdateFunction($(this).attr('data-module'));">
                              <span class="slider"></span>
                            </label></td>
                          <td class=""><label class="switch">
                              <input type="checkbox" name="module[{{$module->module_code}}][delete]" class="{{$module->group_code}}_delete_box" id="{{$module->module_code}}_delete_box" data-module="{{$module->module_code}}" onchange="myDeleteFunction($(this).attr('data-module'));">
                              <span class="slider"></span>
                            </label></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
          </div>
          <div class="cards-btn">
            <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('rolePermission.submit')</button>
            <a href="{{ route('role-permission.index') }}" class="btn btn-danger form-btn-danger">@lang('rolePermission.cancel')</a>
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
<script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
<script>
  function myCreateFunction(elem) {
    if ($('#' + elem + '_create_box').is(":checked")) {
      $('#' + elem + '_read_box').prop('checked', true);
    }

  }

  function myReadFunction(elem) {
    $('#' + elem + '_create_box').prop('checked', false);
    $('#' + elem + '_delete_box').prop('checked', false);
    $('#' + elem + '_update_box').prop('checked', false);
    $('#' + elem + '_import_box').prop('checked', false);
    $('#' + elem + '_export_box').prop('checked', false);
  }

  function myUpdateFunction(elem) {
    if ($('#' + elem + '_update_box').is(":checked")) {
      $('#' + elem + '_read_box').prop('checked', true);
    }
  }

  function myDeleteFunction(elem) {
    if ($('#' + elem + '_delete_box').is(":checked")) {
      $('#' + elem + '_read_box').prop('checked', true);
    }
  }

  /* START CUSTOMERS_LIST DATA */
  $("#CUSTOMERS_read_box").click(function() {
    if ($(this).is(":checked")) {
      $('.CUSTOMERS_read_box').prop('checked', true);
    } else {
      $('.CUSTOMERS_read_box').prop('checked', false);
      $('.CUSTOMERS_create_box').prop('checked', false);
      $('.CUSTOMERS_update_box').prop('checked', false);
      $('.CUSTOMERS_delete_box').prop('checked', false);
    }
  });

  $("#CUSTOMERS_create_box").click(function() {
    if ($(this).is(":checked")) {
      $('.CUSTOMERS_create_box').prop('checked', true);
      $('.CUSTOMERS_read_box').prop('checked', true);
    } else {
      $('.CUSTOMERS_create_box').prop('checked', false);
    }
  });
  $("#CUSTOMERS_update_box").click(function() {
    if ($(this).is(":checked")) {
      $('.CUSTOMERS_update_box').prop('checked', true);
      $('.CUSTOMERS_read_box').prop('checked', true);
    } else {
      $('.CUSTOMERS_update_box').prop('checked', false);
    }
  });
  $("#CUSTOMERS_delete_box").click(function() {
    if ($(this).is(":checked")) {
      $('.CUSTOMERS_delete_box').prop('checked', true);
      $('.CUSTOMERS_read_box').prop('checked', true);
    } else {
      $('.CUSTOMERS_delete_box').prop('checked', false);
    }
  });
  /* END CUSTOMERS DATA */

  /* START USERS DATA */
  $("#USERS_read_box").click(function() {
    if ($(this).is(":checked")) {
      $('.USERS_read_box').prop('checked', true);
      $('.USERS_read_box').prop('checked', false);
      $('.USERS_create_box').prop('checked', false);
      $('.USERS_update_box').prop('checked', false);
      $('.USERS_delete_box').prop('checked', false);
    }
  });


  $("#USERS_create_box").click(function() {
    if ($(this).is(":checked")) {
      $('.USERS_create_box').prop('checked', true);
      $('.USERS_read_box').prop('checked', true);
    } else {
      $('.USERS_create_box').prop('checked', false);
    }
  });
  $("#USERS_update_box").click(function() {
    if ($(this).is(":checked")) {
      $('.USERS_update_box').prop('checked', true);
      $('.USERS_read_box').prop('checked', true);
    } else {
      $('.USERS_update_box').prop('checked', false);
    }
  });
  $("#USERS_delete_box").click(function() {
    if ($(this).is(":checked")) {
      $('.USERS_delete_box').prop('checked', true);
      $('.USERS_read_box').prop('checked', true);
    } else {
      $('.USERS_delete_box').prop('checked', false);
    }
  });
  /* END USERS DATA */


  /* START PREFERENCES CODE */
  $("#PREFERENCES_read_box").click(function() {
    if ($(this).is(":checked")) {
      $('.PREFERENCES_read_box').prop('checked', true);
    } else {
      $('.PREFERENCES_read_box').prop('checked', false);
      $('.PREFERENCES_create_box').prop('checked', false);
      $('.PREFERENCES_update_box').prop('checked', false);
      $('.PREFERENCES_delete_box').prop('checked', false);
    }
  });
  $("#PREFERENCES_create_box").click(function() {
    if ($(this).is(":checked")) {
      $('.PREFERENCES_create_box').prop('checked', true);
      $('.PREFERENCES_read_box').prop('checked', true);
    } else {
      $('.PREFERENCES_create_box').prop('checked', false);
    }
  });
  $("#PREFERENCES_update_box").click(function() {
    if ($(this).is(":checked")) {
      $('.PREFERENCES_update_box').prop('checked', true);
      $('.PREFERENCES_read_box').prop('checked', true);
    } else {
      $('.PREFERENCES_update_box').prop('checked', false);
    }
  });
  $("#PREFERENCES_delete_box").click(function() {
    if ($(this).is(":checked")) {
      $('.PREFERENCES_delete_box').prop('checked', true);
      $('.PREFERENCES_read_box').prop('checked', true);
    } else {
      $('.PREFERENCES_delete_box').prop('checked', false);
    }
  });
  /* END PREFERENCES CODE */

  /* START OPERATIONAL DATA */
  $("#OPERATIONAL_DATA_read_box").click(function() {
    if ($(this).is(":checked")) {
      $('.OPERATIONAL_DATA_read_box').prop('checked', true);
    } else {
      $('.OPERATIONAL_DATA_read_box').prop('checked', false);
      $('.OPERATIONAL_DATA_create_box').prop('checked', false);
      $('.OPERATIONAL_DATA_update_box').prop('checked', false);
      $('.OPERATIONAL_DATA_delete_box').prop('checked', false);
    }
  });

  $("#OPERATIONAL_DATA_create_box").click(function() {
    if ($(this).is(":checked")) {
      $('.OPERATIONAL_DATA_create_box').prop('checked', true);
      $('.OPERATIONAL_DATA_read_box').prop('checked', true);
    } else {
      $('.OPERATIONAL_DATA_create_box').prop('checked', false);
    }
  });
  $("#OPERATIONAL_DATA_update_box").click(function() {
    if ($(this).is(":checked")) {
      $('.OPERATIONAL_DATA_update_box').prop('checked', true);
      $('.OPERATIONAL_DATA_read_box').prop('checked', true);
    } else {
      $('.OPERATIONAL_DATA_update_box').prop('checked', false);
    }
  });
  $("#OPERATIONAL_DATA_delete_box").click(function() {
    if ($(this).is(":checked")) {
      $('.OPERATIONAL_DATA_delete_box').prop('checked', true);
      $('.OPERATIONAL_DATA_read_box').prop('checked', true);
    } else {
      $('.OPERATIONAL_DATA_delete_box').prop('checked', false);
    }
  });
  /* END OPERATIONAL DATA */

  /* START TEMPLATES DATA */
  $("#TEMPLATES_read_box").click(function() {
    if ($(this).is(":checked")) {
      $('.TEMPLATES_read_box').prop('checked', true);
    } else {
      $('.TEMPLATES_read_box').prop('checked', false);
      $('.TEMPLATES_create_box').prop('checked', false);
      $('.TEMPLATES_update_box').prop('checked', false);
      $('.TEMPLATES_delete_box').prop('checked', false);
    }
  });
  $("#TEMPLATES_create_box").click(function() {
    if ($(this).is(":checked")) {
      $('.TEMPLATES_create_box').prop('checked', true);
      $('.TEMPLATES_read_box').prop('checked', true);
    } else {
      $('.TEMPLATES_create_box').prop('checked', false);
    }
  });
  $("#TEMPLATES_update_box").click(function() {
    if ($(this).is(":checked")) {
      $('.TEMPLATES_update_box').prop('checked', true);
      $('.TEMPLATES_read_box').prop('checked', true);
    } else {
      $('.TEMPLATES_update_box').prop('checked', false);
    }
  });
  $("#TEMPLATES_delete_box").click(function() {
    if ($(this).is(":checked")) {
      $('.TEMPLATES_delete_box').prop('checked', true);
      $('.TEMPLATES_read_box').prop('checked', true);
    } else {
      $('.TEMPLATES_delete_box').prop('checked', false);
    }
  });
  /* END TEMPLATES DATA */

  /* START GEOGRAPHY DATA */
  $("#GEOGRAPHY_read_box").click(function() {
    if ($(this).is(":checked")) {
      $('.GEOGRAPHY_read_box').prop('checked', true);
    } else {
      $('.GEOGRAPHY_read_box').prop('checked', false);
      $('.GEOGRAPHY_create_box').prop('checked', false);
      $('.GEOGRAPHY_update_box').prop('checked', false);
      $('.GEOGRAPHY_delete_box').prop('checked', false);
    }
  });

  $("#GEOGRAPHY_create_box").click(function() {
    if ($(this).is(":checked")) {
      $('.GEOGRAPHY_create_box').prop('checked', true);
      $('.GEOGRAPHY_read_box').prop('checked', true);
    } else {
      $('.GEOGRAPHY_create_box').prop('checked', false);
    }
  });
  $("#GEOGRAPHY_update_box").click(function() {
    if ($(this).is(":checked")) {
      $('.GEOGRAPHY_update_box').prop('checked', true);
      $('.GEOGRAPHY_read_box').prop('checked', true);
    } else {
      $('.GEOGRAPHY_update_box').prop('checked', false);
    }
  });
  $("#GEOGRAPHY_delete_box").click(function() {
    if ($(this).is(":checked")) {
      $('.GEOGRAPHY_delete_box').prop('checked', true);
      $('.GEOGRAPHY_read_box').prop('checked', true);
    } else {
      $('.GEOGRAPHY_delete_box').prop('checked', false);
    }
  });
  /* END GEOGRAPHY DATA */

  /* START B2C DATA */
  $("#B2C_read_box").click(function() {
    if ($(this).is(":checked")) {
      $('.B2C_read_box').prop('checked', true);
    } else {
      $('.B2C_read_box').prop('checked', false);
      $('.B2C_create_box').prop('checked', false);
      $('.B2C_update_box').prop('checked', false);
      $('.B2C_delete_box').prop('checked', false);
    }
  });

  $("#B2C_create_box").click(function() {
    if ($(this).is(":checked")) {
      $('.B2C_create_box').prop('checked', true);
      $('.B2C_read_box').prop('checked', true);
    } else {
      $('.B2C_create_box').prop('checked', false);
    }
  });
  $("#B2C_update_box").click(function() {
    if ($(this).is(":checked")) {
      $('.B2C_update_box').prop('checked', true);
      $('.B2C_read_box').prop('checked', true);
    } else {
      $('.B2C_update_box').prop('checked', false);
    }
  });
  $("#B2C_delete_box").click(function() {
    if ($(this).is(":checked")) {
      $('.B2C_delete_box').prop('checked', true);
      $('.B2C_read_box').prop('checked', true);
    } else {
      $('.B2C_delete_box').prop('checked', false);
    }
  });
  /* END B2C DATA */
</script>
<script>
  document.getElementById('flexCheckDefault1').onclick = function() {
    var checkboxes = document.getElementsByName('check');
    for (var checkbox of checkboxes) {
      checkbox.checked = this.checked;
    }
  }
</script>
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
    $('#dataForm').validate({
      onkeyup: false, //turn off auto validate whilst typing
      rules: {
        name: {
          required: true,
          noSpace: true,

          remote: {
            url: "{{route('admin.role-permission.checkExist')}}",
            type: "post",
            data: {
              name: function() {
                return $("#name").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }

        },
        role_type: {
          required: true
        },
        description: {
          required: true,
          maxlength: 300
        },
      },
      messages: {
        name: {
          required: "Please Enter Role Name",
          remote: "Role Name is already taken"

        },
        role_type: {
          required: "Please Select Role Type",
        },
        description: {
          required: "Please Enter Description",
          maxlength: "Description must be no more than 300 characters."
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
  (function($) {
    $(document).ready(function() {
      var slider = $("#range"),
        output = $("#output");

      output.text(slider.val());
      slider.on("input", function() {
        output.text(slider.val());
      });
    });
  })(jQuery);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
  $(document).ready(function() {
    $("#module_table").html('');
    $('#role_type').on('change', function() {
      var idrole_type = this.value;
      $.ajax({
        url: "{{route('admin.role-permission.fetchModules')}}",
        type: "POST",
        data: {
          idrole_type: idrole_type,
          _token: '{{csrf_token()}}'
        },
        //dataType: 'json',
        success: function(result) {
          $('#module_table').html(result);
        }
      });
    });
  });
</script>
@append