@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<style>
  .blurIcon{
  background-color: #dfdfe9 !important;
    color: black !important;
  }
  .table-responsive {
    max-height:300px;
}
</style>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['heading'] }}</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">@lang('adminUser.moduleHeading')</a></li>
                <li class="breadcrumb-item active">@lang('adminUser.view')</li>
              </ol>
              <div class="breadcrumb-btn">
                @if($userDetail['status'] != '1')
                <div class="add-breadcrumb" style="width:200px;">
                  <a href="{{ route('admin.user.activationEmail',$userDetail['id']) }}" title="Resend Activation Link" style="width:196px;">
                    <?xml version="1.0" encoding="utf-8"?><svg fill="#fff" viewBox="0 0 8 6" xmlns="http://www.w3.org/2000/svg">
                    <path d="m0 0h8v6h-8zm.75 .75v4.5h6.5v-4.5zM0 0l4 3 4-3v1l-4 3-4-3z"/>
                  </svg>
                    Resend Activation Link
                  </a>
                </div>
                @endif
                <div class="add-breadcrumb @if($userDetail['status'] == 2) d-none @endif">
                  <a class="" href="{{ route('user.edit',$userDetail['id']) }}">
                    <?xml version="1.0"?>
                    <svg fill="#fff" viewBox="0 0 24 24" width="20" height="20">
                    <path
                        d="M 19.171875 2 C 18.448125 2 17.724375 2.275625 17.171875 2.828125 L 16 4 L 20 8 L 21.171875 6.828125 C 22.275875 5.724125 22.275875 3.933125 21.171875 2.828125 C 20.619375 2.275625 19.895625 2 19.171875 2 z M 14.5 5.5 L 3 17 L 3 21 L 7 21 L 18.5 9.5 L 14.5 5.5 z" />
                    </svg>
                    @lang('adminUser.edit')
                  </a>
                </div>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->

      
      <!-- BUTTON --> 
      <div class="mb-1 ml-3">
        <button class="btn btn-info" id="profileButton">Profile</button>
        <button id="activityButton" class="btn btn-info">Activity</button>
        <!-- <button id="permissionButton" class="btn btn-info">Permission</button> -->
      </div>
      <!-- END BUTTON -->
      <section class="content" id="profile"><!--Start Profile Div -->
        <div class="container-fluid">
          <!-- Info boxes -->
          <div class="row">
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
            <div class="card pb-4 pt-4 px-3 w-100">
              <div class="row view_page mb-0">
                <div class="d-flex">
                  <div class="viewpage_img">
                    @if($userDetail['profile_image'] != "")
                        <img width="150" height="150" src="{{ $userDetail['profile_image'] }}" alt="">
                      @else
                      <img class="viewcon-user" width="150" height="150" src="{{ URL::asset('assets/images/no-image.png') }}" alt="">
                      @endif
                  </div>
                  <div class="view_user_data">
                    <table class="">
                      <tr>
                      <th>Full Name :</th>
                        <td>{{$userDetail->name}}</td>
                      </tr>
                      <tr>
                        <th>Agency Name :</th>
                        
                        <td> <?php 
                        $value = "";
                        $value = App\Models\Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                          if($userDetail['agency_id'] == 0){
                            echo $value;
                          }
                          else if($userDetail['agency_id'] > 0)
                          {
                              echo  $userDetail['agancy_name'];
                          } 
                        ?>
                      </td>
                      </tr>
                      <tr>
                        <th>@lang('adminUser.mobile') :</th>
                        <td>{{$userDetail->isd." ".$userDetail->mobile}}</td>
                      </tr>
                      <tr>
                        <th>@lang('adminUser.email') :</th>
                        <td>{{$userDetail->email}}</td>
                      </tr>
                      <tr>
                        <th>@lang('adminUser.role') :</th>
                        <td>{{count(App\Models\Role::where('code', $userDetail->role_code)->get('name')) > 0 ? App\Models\Role::where('code', $userDetail->role_code)->get('name')[0]['name'] : "-"}}</td>
                      </tr>
                      <tr>
                        <th>Status :</th>
                         @if($userDetail['status'] == "1")
                        <td>Active</td>
                        @elseif($userDetail['status'] == "0")
                        <td>In-Active</td>
                        @elseif($userDetail['status'] == "2")
                        <td>Terminated</td>
                        @endif

                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.row -->
          </div>
          <!--/. container-fluid -->
      </section><!-- End Profile div -->

<section class="content" id="activity"><!--Start Activity Div -->
  <div class="container-fluid">  <!-- container-fluid -->
    <div class="row"> <!--Start row boxes -->
      <div class="card pb-4 pt-4 px-3 w-100"><!--Start card div -->
        <div class="table-responsive"><!--Start Table Responsive div -->
          <table class="table table-head-fixed text-wrap">
            <tbody class="td-data-color">
              
            @foreach($result as $data)
              <tr>
                <td style="width: 0px;">
                <img style="border: 1px solid #dfdfdf;border-radius: 7px;height: 45px;width: 45px;"data-toggle="popover" src="{{ $userDetail['profile_image'] ? $userDetail['profile_image'] : URL::asset('assets/images/no-image.png')}}" alt="">                      
                </td>
                <td class="no-data-list text-nowrap" style="text-align:left;font-weight: 600;font-size: 14px;">
                  {{ $data['user_name'] }}  {{$data['description']}}  {{ str_replace('_', ' ',$data['log_name']) }} <a title='<?php echo json_encode(json_decode($data['properties'],true),JSON_PRETTY_PRINT);?>' class="view-btn-modal" id="btn_view" ca-id="{{ $data['id'] }}">View</a>
                </td>
                <td class="no-data-list dataList" style="text-align:left;">
                <div class="json-div d-none show-json-{{$data['id']}} ml-2">
                 <span><?php echo "<pre>"; echo json_encode(json_decode($data['properties'],true),JSON_PRETTY_PRINT); echo "</pre>"; ?></span>
                </div>
                </td>
                <td class="no-data-list dataList" style="text-align:right;">
                  {{ getDateTimeZone($data['updated_at'])}} 
                  {{getTimeZone(($data['updated_at']))}}
                </td>
              </tr>
              <tr>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div><!--End Table Responsive div -->
      </div><!--End card div -->
    </div><!--End row boxes -->
  </div><!--/. container-fluid -->
</section><!-- End Activity div -->

      <section class="content" id="permission"><!--Start Permission Div -->
        <div class="container-fluid"><!--Start Container Div -->
          <div class="row"><!--Start Row Div -->
            <div class="card pb-4 w-100 px-3 py-2 mb-3"><!--Start Card Div -->
               <div class="col-md-12 row"><!-- Start col-md-12 row  Div -->
                    <div class="col-md-6">
                      <div class="form-check" style="color:#5a5151;margin-top: 10px">
                        <input type="checkbox" id="" name="stop_by" class="form-check-input" disabled value="">
                        <label class="" for="">Allow Only View</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-check" style="color:#5a5151;margin-top: 10px">
                        <input type="checkbox" id="" name="" class="form-check-input" disabled value="">
                        <label class="" for="">Allow Only View</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-check" style="color:#5a5151;margin-top: 10px">
                        <input type="checkbox" id="" name="" class="form-check-input" disabled value="">
                        <label class="" for="">Allow Only View</label>
                      </div>
                    </div>
                  </div><!-- End col-md-12 row Div -->
              </div><!--End Card div-->
        </div> <!-- End row div-->
    </div> <!--End Container -->
  </section><!--End Permission div -->
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <!-- Page specific script -->
  <script>
  $(function(){
      
      $("#activity").hide();
      $("#permission").hide();
      $("#activityButton").addClass('blurIcon');
      $("#permissionButton").addClass('blurIcon');

      $("#profileButton").click(function(){
        $("#profileButton").removeClass('blurIcon');
        $("#activityButton").addClass('blurIcon');
        $("#permissionButton").addClass('blurIcon');
        $("#activity").hide();
        $("#permission").hide();
        $("#profile").show();
      });

        $("#activityButton").click(function(){
          $("#activityButton").removeClass('blurIcon');
          $("#profileButton").addClass('blurIcon');
          $("#permissionButton").addClass('blurIcon');
          $("#permission").hide();
          $("#profile").hide();
          $("#activity").show();
        });

        $("#permissionButton").click(function(){
          $("#permissionButton").removeClass('blurIcon');
          $("#profileButton").addClass('blurIcon');
          $("#activityButton").addClass('blurIcon');
          $("#activity").hide();
          $("#profile").hide();
          $("#permission").show();
        });
  });
  </script>

<script>
  $(document).ready(function(){
    $(".view-btn-modal").click(function(e){
      console.log(e);
      var id = e.target.attributes[3]['nodeValue'];
      $(".show-json-"+id).toggle();
      $(".show-json-"+id).removeClass('d-none');
   });
});
</script>

@endsection