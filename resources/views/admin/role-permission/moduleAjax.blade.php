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
                          <?php

                          if(($module['module_code'] == 'GENERAL') || ($module['module_code'] == 'LOGIN_ATTEMPTS') || ($module['module_code'] == 'PASSWORD_SECURITY') || 
                              ($module['module_code'] == 'SMTP_SETTINGS') || ($module['module_code'] == 'SIGN_IN_METHOD') || ($module['module_code'] == 'MAIL_CHIMP') || 
                              ($module['module_code'] == 'NOTIFICATIONS') || ($module['module_code'] == 'SMS_SETTINGS') || ($module['module_code'] == 'MAIL_TEMPLATES') || 
                              ($module['module_code'] == 'SMS_TEMPLATES') || ($module['module_code'] == 'CMS_PAGES') || ($module['module_code'] == 'TEMPLATES')){
                            $hideElementAddSlider = "hidden";
                           }
                           else{
                           $hideElementAddSlider = "";
                           }
                           ?>
                          <td class=""><label class="switch" <?php echo $hideElementAddSlider; ?>>
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
                            <?php
                            if(($module['module_code'] == 'GENERAL') || ($module['module_code'] == 'LOGIN_ATTEMPTS')|| 
                            ($module['module_code'] == 'PASSWORD_SECURITY') || ($module['module_code'] == 'SMTP_SETTINGS')|| 
                            ($module['module_code'] == 'SIGN_IN_METHOD') || ($module['module_code'] == 'MAIL_CHIMP')|| 
                            ($module['module_code'] == 'NOTIFICATIONS') || ($module['module_code'] == 'SMS_SETTINGS')|| 
                            ($module['module_code'] == 'MAIL_TEMPLATES') || ($module['module_code'] == 'SMS_TEMPLATES')||
                             ($module['module_code'] == 'CMS_PAGES') || ($module['module_code'] == 'TEMPLATES')){
                            $hideElementDelSlider = "hidden";
                           }
                           else{
                           $hideElementDelSlider = "";
                           }
                           ?>
                          <td class=""><label class="switch" <?php echo $hideElementDelSlider; ?>>
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
            <script>
  function myCreateFunction(elem) {
    // console.warn("create function work");
    if ($('#' + elem + '_create_box').is(":checked")) {
      $('#' + elem + '_read_box').prop('checked', true);
    }

  }

  function myReadFunction(elem) {
    // console.warn("read function work");
    $('#' + elem + '_create_box').prop('checked', false);
    $('#' + elem + '_delete_box').prop('checked', false);
    $('#' + elem + '_update_box').prop('checked', false);
    $('#' + elem + '_import_box').prop('checked', false);
    $('#' + elem + '_export_box').prop('checked', false);
  }

  function myUpdateFunction(elem) {
    // console.error("update function work");
    if ($('#' + elem + '_update_box').is(":checked")) {
      $('#' + elem + '_read_box').prop('checked', true);
    }
  }

  function myDeleteFunction(elem) {
    // console.warn("delete function work");
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
  // console.log("hello");
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