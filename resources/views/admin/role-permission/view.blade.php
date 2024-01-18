@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')

      <!-- Content Header (Page header) -->
      
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['heading'] }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboards </a></li>
                <li class="breadcrumb-item"><a href="{{ route('role-permission.index') }}">Roles & Permissions</a></li>

                <li class="breadcrumb-item active">View Role & Permissions</li>
              </ol>
              <div class="breadcrumb-btn @if($roleDetail['status'] == 2) d-none @endif">
                <div class="add-breadcrumb">
                  <!-- edit button -->
                  <a href="{{ route('role-permission.edit',$roleDetail->id) }}" title="Edit">
                    <?xml version="1.0"?>
                    <svg fill="#fff" viewBox="0 0 24 24" width="20" height="20">
                    <path
                       d="M 19.171875 2 C 18.448125 2 17.724375 2.275625 17.171875 2.828125 L 16 4 L 20 8 L 21.171875 6.828125 C 22.275875 5.724125 22.275875 3.933125 21.171875 2.828125 C 20.619375 2.275625 19.895625 2 19.171875 2 z M 14.5 5.5 L 3 17 L 3 21 L 7 21 L 18.5 9.5 L 14.5 5.5 z" />
                    </svg>
                   Edit
                  </a>
                </div>
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
            <div class="card pb-4 w-100 px-3 py-2">
              <div class="row view_page mb-0">
                <div class="d-flex">
                  <div class="ml-0 view_gift_data">
                    <table class="">
                      <tr>
                        <th>Role Name :</th>
                        <td>{{$roleDetail->name}}</td>
                      </tr>
                      <tr>
                        <th>Role Description:</th>
                        <td style="width: 70%;">{{$roleDetail->description}}</td>
                      </tr>
                      <tr>
                        <th>Status :</th>
                        <td>{{$roleDetail->roles_status_text}}</td>
                      </tr>
                      <tr>
                        <th>Role Type :</th>
                        <td>{{$roleDetail->roles_type_text}}</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
              <form class="form row mb-0 pt-3 validate">
                <div class="mb-3">
                  <div class="discount mt-3">
                    <div class="">
                      <div class="table-card ">
                        <!-- /.card-header -->
                        <div class="card-body table-radius table-responsive p-0">
                          <table class="table table-head-fixed text-nowrap">
                            <thead class="td-data-color">
                              <tr>
                                <th class="list-checkbox" colspan="1">
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
                                <th> {{ $module->module_name }}</th>
                                @if(!empty(json_decode(json_encode($module->modulePermissions))))
                                <?php
                                    $checkCreate = '';
                                    $checkRead = '';
                                    $checkUpdate = '';
                                    $checkDelete = '';
                                    $checkImport = '';
                                    $checkExport = '';
                                   
                                    foreach($module->modulePermissions as $permission){
                                      if($permission->create == 1 && $permission->role_code == $roleDetail['code']){
                                        $checkCreate = 'checked';
                                      }
                                      if($permission->read == 1 && $permission->role_code == $roleDetail['code']){
                                        $checkRead = 'checked';
                                      }
                                      if($permission->update == 1 && $permission->role_code == $roleDetail['code']){
                                        $checkUpdate = 'checked';
                                      }
                                      if($permission->delete == 1 && $permission->role_code == $roleDetail['code']){
                                        $checkDelete = 'checked';
                                      }
                                      if($permission->import == 1 && $permission->role_code == $roleDetail['code']){
                                        $checkImport = 'checked';
                                      }
                                      if($permission->export == 1 && $permission->role_code == $roleDetail['code']){
                                        $checkExport = 'checked';
                                      }
                                    }
                                  ?>
                                        <td class=""><label class="switch">
                                            <input type="checkbox" disabled  name="module[{{$module->module_name}}][create]" {{$checkCreate}}>
                                            <span class="slider"  ></span>
                                          </label></td>
                                        <td class=""><label class="switch">
                                            <input type="checkbox" disabled name="module[{{$module->module_name}}][read]" {{$checkRead}}>
                                            <span class="slider"></span>
                                          </label></td>
                                        <td class=""><label class="switch">
                                            <input type="checkbox" disabled  name="module[{{$module->module_name}}][update]" {{$checkUpdate}}>
                                            <span class="slider"></span>
                                          </label></td>
                                        <td class=""><label class="switch">
                                            <input type="checkbox" disabled  name="module[{{$module->module_name}}][delete]" {{$checkDelete}}>
                                            <span class="slider"></span>
                                          </label></td>
                                       
                                      @else
                               
                               <td class=""><label class="switch">
                                   <input type="checkbox" name="module[{{$module->module_name}}][create]" disabled>
                                   <span class="slider"  ></span>
                                 </label>
                                </td>
                               <td class=""><label class="switch">
                                   <input type="checkbox" name="module[{{$module->module_name}}][read]" disabled>
                                   <span class="slider"></span>
                                 </label></td>
                               <td class=""><label class="switch">
                                   <input type="checkbox" name="module[{{$module->module_name}}][update]" disabled>
                                   <span class="slider"></span>
                                 </label></td>
                               <td class=""><label class="switch">
                                   <input type="checkbox"  name="module[{{$module->module_name}}][delete]" disabled>
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
                </div>
              </form>
            </div>
            <!-- /.row -->
          </div>
          <!--/. container-fluid -->
      </section>
      <!-- /.content -->
    @endsection
 @section('js')  