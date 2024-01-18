@extends('supplier.layout.main')
@section('title', $header['title'])
    @section('content')
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
              <h1 class="m-0">{{ $header['heading'] }}</h1>
              <div class="col-md-3 ml-auto">
                <!-- <div class="form-floating form-float-style">
                  <div class="select after-drp">
                    <select class="select-text height_drp" >
                     
                    </select>
                    <label class="select-label">SELECT DURATION *</label>
                  </div>
                </div> -->
                <div class="form-floating form-item mb-0">
                    <div class="form-item form-float-style serach-rem mb-0">
                      <div class="select top-space-rem after-drp form-float-style ">
                        <select data-live-search="true" id="duration_id" name="duration_id" class="selectpicker select-text height_drp is-valid" required>
                          <option value="today" selected>Today</option>
                          <option value="yesterday">Yesterday</option>
                          <option value="current_month">Current Month</option>
                          <option value="last_month">Last Month</option>
                          <option value="last_six_month">Last 6 Months</option>
                          <option value="this_year">This Year</option>
                        </select>
                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Duration <span class="req-star">*</span></label>
                      </div>                        
                    </div>
                </div>
              </div>
            </div><!-- /.col -->

          </div><!-- /.row -->
        </div><!-- /.container-fluid -->  
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
 
  @endsection  
  @section('js')  
  <script>
    $(document).ready(function () {
     
      $("#duration_id").change(function(){
          
            var durationId = $(this).val();
            $.ajax({
                url: '{{route("getRecords")}}',
                method: 'GET',
                async:false,
                data: durationId,
                    success: function(data) {
                        var obj = jQuery.parseJSON( data );
                        if(obj['status']==true){
                            dataIsduplicate=true;
                        }else{
                            $( obj['message'] ).each(function( message,value ) {
                                $(".duplicateMessages").append("<span style='color:#dc3545'>"+value+"</span><br>");
                            });
                            dataIsduplicate=false;
                        }
                    }
            });
            if(dataIsduplicate==false){
                return false;
            }
            return true;
        
          
        });
       
    });
  </script>
  <script>
      $("#duration_id").change(function() {
       // console.log("select duration function inside");
        selected_day = $(this).val();
          //console.log("select duration--->"+selected_day);
          var url = '{{ route("admin.get_duration") }}';
            // url = url.replace(':id', this.value );  
            $.ajax({
                type: "GET",
                url: url,
                data:{"selected_day":selected_day},
                success: function( response ) {
                  $('#total_order').html(response.total_order);
                  $('#total_sale').html(response.total_sale);
                  $('#total_product').html(response.total_product);
                  $('#total_user').html(response.total_user);
                }
            });
         });
      $("#duration_id").trigger('change');
      
      //get filtration with selected duration
    </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

  @append
      <!-- /.content -->

   

