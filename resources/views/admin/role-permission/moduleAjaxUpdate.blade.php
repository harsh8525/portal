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
            @foreach($getModuleList['module_list'] as $module)

            <tr>
              <th>{{ $module->module_name }}</th>
              @if(!empty(json_decode(json_encode($module->modulePermissions))))
              <?php
              $checkCreate = '';
              $checkRead = '';
              $checkUpdate = '';
              $checkDelete = '';
              $checkImport = '';
              $checkExport = '';
              foreach ($module->modulePermissions as $permission) {
                if ($permission->create == 1 && $permission->role_code == $getModuleList['role_code']) {
                  $checkCreate = 'checked';
                }
                if ($permission->read == 1 && $permission->role_code == $getModuleList['role_code']) {
                  $checkRead = 'checked';
                }
                if ($permission->update == 1 && $permission->role_code == $getModuleList['role_code']) {
                  $checkUpdate = 'checked';
                }
                if ($permission->delete == 1 && $permission->role_code == $getModuleList['role_code']) {
                  $checkDelete = 'checked';
                }
                if ($permission->import == 1 && $permission->role_code == $getModuleList['role_code']) {
                  $checkImport = 'checked';
                }
                if ($permission->export == 1 && $permission->role_code == $getModuleList['role_code']) {
                  $checkExport = 'checked';
                }
              }
              ?>

              <td class=""><label class="switch">
                  <input type="checkbox" name="module[{{$module->module_code}}][create]" class="{{$module->group_code}}_create_box" id="{{$module->module_code}}_create_box" {{$checkCreate}} data-module="{{$module->module_code}}" onchange="myCreateFunction($(this).attr('data-module'));">
                  <span class="slider"></span>
                </label>
              </td>
              <td class=""><label class="switch">
                  <input type="checkbox" name="module[{{$module->module_code}}][read]" class="{{$module->group_code}}_read_box" id="{{$module->module_code}}_read_box" {{$checkRead}} data-read="{{$module->module_code}}" onchange="myReadFunction($(this).attr('data-read'));">
                  <span class="slider"></span>
                </label></td>
              <td class=""><label class="switch">
                  <input type="checkbox" name="module[{{$module->module_code}}][update]" class="{{$module->group_code}}_update_box" id="{{$module->module_code}}_update_box" {{$checkUpdate}} data-module="{{$module->module_code}}" onchange="myUpdateFunction($(this).attr('data-module'));">
                  <span class="slider"></span>
                </label></td>
              <td class=""><label class="switch">
                  <input type="checkbox" name="module[{{$module->module_code}}][delete]" class="{{$module->group_code}}_delete_box" id="{{$module->module_code}}_delete_box" {{$checkDelete}} data-module="{{$module->module_code}}" onchange="myDeleteFunction($(this).attr('data-module'));">
                  <span class="slider"></span>
                </label></td>
              
              @else

              <td class=""><label class="switch">
                  <input type="checkbox" name="module[{{$module->module_code}}][create]" class="{{$module->group_code}}_create_box" id="{{$module->module_code}}_create_box" data-module="{{$module->module_code}}" onchange="myCreateFunction($(this).attr('data-module'));">
                  <span class="slider"></span>
                </label>
              </td>
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
              
              @endif
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
    } else {
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
  
  /* START DEPOSIT DATA */
$("#DEPOSIT_read_box").click(function() {
   console.warn("readbox");
    if ($(this).is(":checked")) {
      $('.DEPOSIT_read_box').prop('checked', true);
    } else {
      $('.DEPOSIT_read_box').prop('checked', false);
      $('.DEPOSIT_create_box').prop('checked', false);
      $('.DEPOSIT_update_box').prop('checked', false);
      $('.DEPOSIT_delete_box').prop('checked', false);
    }
  });


  
  $("#DEPOSIT_create_box").click(function() {
    if ($(this).is(":checked")) {
      $('.DEPOSIT_create_box').prop('checked', true);
      $('.DEPOSIT_read_box').prop('checked', true);
    } else {
      $('.DEPOSIT_create_box').prop('checked', false);
    }
  });
  $("#DEPOSIT_update_box").click(function() {
    if ($(this).is(":checked")) {
      $('.DEPOSIT_update_box').prop('checked', true);
      $('.DEPOSIT_read_box').prop('checked', true);
    } else {
      $('.DEPOSIT_update_box').prop('checked', false);
    }
  });
  $("#DEPOSIT_delete_box").click(function() {
    if ($(this).is(":checked")) {
      $('.DEPOSIT_delete_box').prop('checked', true);
      $('.DEPOSIT_read_box').prop('checked', true);
    } else {
      $('.DEPOSIT_delete_box').prop('checked', false);
    }
  });
  /* END DEPOSIT DATA */
  </script>