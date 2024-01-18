@extends('admin.layout.main')
@section('title', $header['title'])
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
        <h1 class="m-0">{{ $header['heading'] }}</h1>

      </div><!-- /.col -->

    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->

@endsection
@section('js')
<script>
  $(document).ready(function() {

    $("#duration_id").change(function() {

      var durationId = $(this).val();
      $.ajax({
        url: '{{route("getRecords")}}',
        method: 'GET',
        async: false,
        data: durationId,
        success: function(data) {
          var obj = jQuery.parseJSON(data);
          if (obj['status'] == true) {
            dataIsduplicate = true;
          } else {
            $(obj['message']).each(function(message, value) {
              $(".duplicateMessages").append("<span style='color:#dc3545'>" + value + "</span><br>");
            });
            dataIsduplicate = false;
          }
        }
      });
      if (dataIsduplicate == false) {
        return false;
      }
      return true;


    });

  });
</script>
<script>
  $("#duration_id").change(function() {
    selected_day = $(this).val();
    var url = '{{ route("admin.get_duration") }}';
    $.ajax({
      type: "GET",
      url: url,
      data: {
        "selected_day": selected_day
      },
      success: function(response) {
        $('#total_order').html(response.total_order);
        $('#total_sale').html(response.total_sale);
        $('#total_product').html(response.total_product);
        $('#total_user').html(response.total_user);
      }
    });
  });
  $("#duration_id").trigger('change');
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append