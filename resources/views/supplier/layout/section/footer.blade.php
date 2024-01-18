<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer">
    @php
    $value = "";
    @$value = App\Models\Setting::where('config_key', 'general|site|footerText')->get('value')[0]['value'];
    @endphp
    @if($value)
    <strong>{{$value}}</strong>
    @else
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0
    </div>
    @endif

</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->


<!-- jQuery -->

<script src="{{ URL::asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ URL::asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ URL::asset('assets/dist/js/adminlte.js') }}"></script>


<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<!-- <script src="{{ URL::asset('assets/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
  <script src="{{ URL::asset('assets/plugins/raphael/raphael.min.js') }}"></script>
  <script src="{{ URL::asset('assets/plugins/jquery-mapael/jquery/mapael.min.js') }}"></script>
  <script src="{{ URL::asset('assets/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script> -->
<!-- ChartJS -->
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.min.js') }}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ URL::asset('assets/dist/js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ URL::asset('assets/dist/js/pages/dashboard2.js') }}"></script>

<!-- Select2 -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>



<!--Sweet alert -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.32/sweetalert2.min.js"></script> -->
<!--Sweet alert -->
<script src="{{ URL::asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script> -->

<!-- Jquery Validations method -->
<script src="{{ URL::asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

@yield('js')
<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    $(document).ready(function() {
        $("#dataForm").submit(function() {
            $('input[type="submit"]').attr("disabled", true);
            return true;
        });

        function formatResult(node) {
            var level = 0;
            if (node.element !== undefined) {
                level = (node.element.className);
                if (level.trim() !== '') {
                    level = (parseInt(level.match(/\d+/)[0]));
                    if (level == 0) {
                        var $result = $('<span>' + node.text + '</span>');
                    } else {
                        var $result = $('<span style="color:#595e62;padding-left:' + (20 * level) + 'px; text-transform: capitalize;">' + node.text + '</span>');
                    }
                } else {
                    var $result = $('<span>' + node.text + '</span>');
                }
            }
            //          
            return $result;
        }

        //Initialize Select2 Elements
        //        $('.select2').select2({
        //            templateResult: formatResult
        //        }).on('change', function() {
        //            if($(this).parent().closest('div').attr('class')!="dataTables_scrollHeadInner"){
        //                $(this).valid();
        //            } 
        //        });

    });



    //Initialize Select2 Elements
    /*$('.select2bs4').select2({
           theme: 'bootstrap4',
     }).on('change', function() {
         $(this).valid();
     });*/

    // allow letter and space only
    $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-zA-Z\s]*$/.test(value);
    }, "Please enter only alphabetical letters");

    jQuery.validator.addMethod("noSpace", function(value, element) {
        return value == '' || value.trim().length != 0;
    }, "Only Space are not allowed");
    jQuery.validator.addMethod("gst", function(value3, element3) {
        var gst_value = value3.toUpperCase();
        var reg = /^([0-9]{2}[a-zA-Z]{4}([a-zA-Z]{1}|[0-9]{1})[0-9]{4}[a-zA-Z]{1}([a-zA-Z]|[0-9]){3}){0,15}$/;
        if (this.optional(element3)) {
            return true;
        }
        if (gst_value.match(reg)) {
            return true;
        } else {
            return false;
        }

    }, "Please enter a valid GST Number");
</script>

<script type="text/javascript">
    // auto complete search query
    $(function() {

        //Create a array call 'auto_array'
        var auto_array = {};
        var label = '';
        $("#state-autocomplete").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: 'GET',
                    data: {
                        "search": request.term,
                        "region_type": 'Province (State)'
                    },
                    url: "{{route('customers.searchajax')}}",
                    success: function(html) {

                        var searchData = JSON.parse(JSON.stringify(html));

                        response($.map(searchData, function(item) {
                            label = item.region_name;

                            //Put the id of label in to auto_array. use the label as key in array
                            auto_array[label] = item.region_name;
                            return label;
                        }));
                    },
                    error: function(request, status, error) {}
                })
            },
            minLength: 3,
            select: function(event, ui) {},
            appendTo: '#menu-container',
        });

        $("#city-autocomplete").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: 'GET',
                    data: {
                        "search": request.term,
                        "region_type": 'City'

                    },
                    url: "{{route('customers.searchajax')}}",
                    success: function(html) {

                        var searchData = JSON.parse(JSON.stringify(html));
                        response($.map(searchData, function(item) {
                            label = item.region_name;
                            latitude = item.center_latitude;
                            longitude = item.center_longitude;
                            //Put the id of label in to auto_array. use the label as key in array
                            auto_array[label] = item.region_id;
                            return label;
                        }));
                        // console.log("auto_array" + JSON.stringify(searchData));
                        // $("#latitude").val(latitude);
                        //  $("#longitude").val(longitude);
                    },
                    error: function(request, status, error) {}
                })
            },
            minLength: 3,
            select: function(event, ui) {

                var placeId = auto_array[ui.item.value];
                $.ajax({
                    type: 'GET',
                    data: {
                        "region_id": placeId
                    },
                    url: "{{route('customers.searchajax-latitude-longitude')}}",
                    //                            url: "https://safaidaar-beta.mydemoapp.us/api/v1/google-detail-place-search",
                    success: function(html) {
                        var searchData = JSON.parse(JSON.stringify(html));
                        var latitude = searchData['center_latitude'];
                        var longitude = searchData['center_longitude'];
                        $("#latitude").val(latitude);
                        $("#longitude").val(longitude);

                    },
                    error: function(request, status, error) {}
                })
            },
            appendTo: '#menu-container',
        });

        $(".autocomplete").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: 'POST',
                    data: {
                        "search": request.term
                    },
                    url: "{{url('api/v1/google-place-search')}}",
                    //                            url: "https://safaidaar-beta.mydemoapp.us/api/v1/google-place-search",
                    success: function(html) {

                        var searchData = JSON.parse(JSON.stringify(html));
                        var finalData = searchData['data'][0]['predictions'];
                        //                                console.log(finalData);return;
                        // if(finalData.length == 0){
                        //     $('.checkBox').removeClass('d-none').addClass('d-block');
                        // }else{
                        // $('.checkBox').removeClass('d-block').addClass('d-none');
                        $('.checkBox').addClass('d-block');
                        var finalDataStatus = searchData['data'][0]['status'];
                        if (finalDataStatus == "OK") {

                            response($.map(finalData, function(item) {
                                label = item.description;

                                //Put the id of label in to auto_array. use the label as key in array
                                auto_array[label] = item.place_id;
                                return label;
                            }));
                        }
                        // }
                    },
                    error: function(request, status, error) {}
                })
            },
            minLength: 3,
            select: function(event, ui) {
                var placeId = auto_array[ui.item.value];
                $.ajax({
                    type: 'POST',
                    data: {
                        "place_id": placeId
                    },
                    url: "{{url('api/v1/google-detail-place-search')}}",
                    //                            url: "https://safaidaar-beta.mydemoapp.us/api/v1/google-detail-place-search",
                    success: function(html) {
                        var searchData = JSON.parse(JSON.stringify(html));
                        var finalDataStatus = searchData['data'][0]['status'];
                        if (finalDataStatus == "OK") {
                            var finalAddressData = searchData['data'][0]['result']['address_components'];
                            var finalGeometryData = searchData['data'][0]['result']['geometry']['location'];
                            var finalfullAddressData = searchData['data'][0]['result']['formatted_address'];

                            $("#address").val(finalfullAddressData);
                            $("#address").focus();
                            $("#lattitude").val(finalGeometryData['lat']);
                            $("#longitude").val(finalGeometryData['lng']);

                            $(finalAddressData).each(function(index, element) {
                                $(element['types']).each(function(index1, element1) {
                                    $("#place_id").val(placeId);
                                    if (element1 == "sublocality_level_1") {
                                        $("#area").val(element['long_name']);
                                        $("#area").focus();
                                    }
                                    if (element1 == "administrative_area_level_2") {
                                        $("#city").val(element['long_name']);
                                        $("#city").focus();
                                    }
                                    if (element1 == "administrative_area_level_1") {
                                        $("#state").val(element['long_name']);
                                        $("#state").focus();
                                    }
                                    if (element1 == "country") {
                                        $("#country").val(element['long_name']);
                                        $("#country").focus();
                                    }
                                    if (element1 == "postal_code") {
                                        $("#pincode").val(element['long_name']);
                                        $("#pincode").focus();
                                        $("#setting-search-add").focus();
                                    }
                                });
                            });
                        }

                    },
                    error: function(request, status, error) {}
                })
            },
            appendTo: '#menu-container',
        });

        // jquery for all input box autocomplete off
        $("input").attr("autocomplete", "off");
    });

    // auto complete working city search query
    $(function() {

        //Create a array call 'auto_array'
        var auto_array = {};
        var label = '';
        $(".workingCityAutocomplete").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: 'POST',
                    data: {
                        "search": request.term
                    },
                    url: "{{url('api/v1/google-place-search')}}",
                    //                            url: "https://safaidaar-beta.mydemoapp.us/api/v1/google-place-search",
                    success: function(html) {

                        var searchData = JSON.parse(JSON.stringify(html));
                        var finalData = searchData['data'][0]['predictions'];
                        //                                console.log(finalData);return;
                        // if(finalData.length == 0){
                        //     $('.checkBox').removeClass('d-none').addClass('d-block');
                        // }else{
                        // $('.checkBox').removeClass('d-block').addClass('d-none');
                        $('.checkBox').addClass('d-block');
                        var finalDataStatus = searchData['data'][0]['status'];
                        if (finalDataStatus == "OK") {

                            response($.map(finalData, function(item) {
                                label = item.description;

                                //Put the id of label in to auto_array. use the label as key in array
                                auto_array[label] = item.place_id;
                                return label;
                            }));
                        }
                        // }
                    },
                    error: function(request, status, error) {}
                })
            },
            minLength: 3,
            select: function(event, ui) {
                var placeId = auto_array[ui.item.value];
                $.ajax({
                    type: 'POST',
                    data: {
                        "place_id": placeId
                    },
                    url: "{{url('api/v1/google-detail-place-search')}}",
                    //                            url: "https://safaidaar-beta.mydemoapp.us/api/v1/google-detail-place-search",
                    success: function(html) {
                        var searchData = JSON.parse(JSON.stringify(html));
                        var finalDataStatus = searchData['data'][0]['status'];
                        if (finalDataStatus == "OK") {
                            var finalAddressData = searchData['data'][0]['result']['address_components'];
                            var finalGeometryData = searchData['data'][0]['result']['geometry']['location'];
                            var finalfullAddressData = searchData['data'][0]['result']['formatted_address'];

                            $("#address").val(finalfullAddressData);
                            $("#lattitude").val(finalGeometryData['lat']);
                            $("#longitude").val(finalGeometryData['lng']);

                            $(finalAddressData).each(function(index, element) {
                                $(element['types']).each(function(index1, element1) {
                                    $("#place_id").val(placeId);
                                    if (element1 == "administrative_area_level_2") {
                                        $("#working_city").val(element['long_name']);
                                        $("#working_city").focus();

                                    }
                                    if (element1 == "administrative_area_level_1") {
                                        $("#working_state").val(element['long_name']);
                                        $("#working_state").focus();
                                    }
                                });
                            });
                        }

                    },
                    error: function(request, status, error) {}
                })
            },
            appendTo: '#menu-container',
        });

        // jquery for all input box autocomplete off
        $("input").attr("autocomplete", "off");
    });
</script>


<script>
    // Keep floating labels active when form inputs and textareas are not empty
    // $('textarea').blur(function() {
    //     if ($(this).val() !== '' && !$(this).hasClass('not-empty')) {
    //         $(this).addClass('not-empty');
    //     } else if ($(this).val() === '') {
    //         $(this).removeClass('not-empty');
    //     }
    // });

    // $(document).ready(function () {
    //     $('textarea').change(function () {
    //         if ($.trim($('textarea').val()).length > 1) {

    //             $("textarea").addClass("not-empty");

    //         } else {



    //         }
    //     });
    // });

    // $(function(){
    //     $("textarea").each(function(){
    //         if ($(this).val().length < 0){
    //             // $("textarea").removeClass('is-valid');
    //             alert('no');
    //         }
    //         else{
    //             alert('yes');
    //         }
    //     });
    // });

    // $(function () {

    // $(".collapse").trigger("click");



    $(function() {
        $('textarea').each(function() {
            var txt = $('#meta_description');
            if (txt.val() != null && txt.val() != '') {
                $('#meta_description').addClass('is-valid');
            }
        });
    });

    $(function() {
        $('textarea').each(function() {
            var txt = $('#modal_description');
            if (txt.val() != null && txt.val() != '') {
                $('#modal_description').addClass('is-valid');
            }
        });
    });

    $(function() {
        $('textarea').each(function() {
            var txt = $('#meta_description_one');
            if (txt.val() != null && txt.val() != '') {
                $('#meta_description_one').addClass('is-valid');
            }
        });
    });

    $(function() {
        $('textarea').each(function() {
            var txt = $('#meta_description_two');
            if (txt.val() != null && txt.val() != '') {
                $('#meta_description_two').addClass('is-valid');
            }
        });
    });

    $(function() {
        $('textarea').each(function() {
            var txt = $('#order_update_cmt');
            if (txt.val() != null && txt.val() != '') {
                $('#order_update_cmt').addClass('is-valid');
            }
        });
    });

    $(function() {
        $('textarea').each(function() {
            var txt = $('#unique_textarea');
            if (txt.val() != null && txt.val() != '') {
                $('#unique_textarea').addClass('is-valid');
            }
        });
    });




    //script to remove is-valid class for empty value
    $('*[value=""]').removeClass('is-valid');
    // $('*[value=""]').removeClass('not-empty');

    //script for autofocus text and placeholders
    const inputs = document.querySelectorAll("input");

    inputs.forEach((input) => {
        input.addEventListener("blur", (event) => {
            if (event.target.value) {
                input.classList.add("is-valid");
            } else {
                input.classList.remove("is-valid");
            }
        });
    });

    const textareas = document.querySelectorAll("textarea");

    textareas.forEach((textarea) => {
        textarea.addEventListener("blur", (event) => {
            if (event.target.value) {
                textarea.classList.add("is-valid");
            } else {
                textarea.classList.remove("is-valid");
            }
        });
    });

    //script for number type
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    $(document).on("click", ".deleteConfirmation", function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = $(this).attr("data-url");
            }
        })
    });
    $(document).on("click", ".deleteAllConfirmation", function() {
        var checked = $(".table input[type=checkbox]:checked").length;
        var allCheckedCheckboxes = $(".table input[type=checkbox]:checked");

        if (checked > 0) {
            //                alert(checked + " CheckBoxe(s) are checked.");
            var selectedIds = "";
            allCheckedCheckboxes.each(function(index, element) {
                if (element.value != "")
                    selectedIds += element.value + ",";
            });
            selectedIds = selectedIds.slice(0, -1);

            if (selectedIds != "") {
                var data_url = $(this).attr("data-url");

                if (data_url.slice(-1) != "=") {
                    data_url = data_url.slice(0, -1);
                }
                data_url += selectedIds;

                $(this).attr("data-url", data_url);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = $(this).attr("data-url");
                    }
                });
            } else {
                alert("No records for delete.");
                return false;
            }
            return true;
        } else {
            alert("Please select CheckBoxe(s).");
            return false;
        }


    });

    $(document).ready(function() {

        $(".select2").select2();

        $('[data-toggle="popover"]').popover({
            //trigger: 'focus',
            trigger: 'hover',
            html: true,
            content: function() {
                return '<img class="img-fluid popover_img" src="' + $(this).attr('src') + '" />';
            },
        })

    });
</script>

<!--notification in header display-->
<script>
    getNotificationList();

    function getNotificationList() {
        $.ajax({
            url: '{{ route("admin.notificationEntity.getNotification") }}',
            type: 'GET',
            dataType: 'JSON',
            success: function(response) {

                $(".notificaionCount").html(response['unreadNotificationCount']);
                $(".readAllNotification").attr("disabled", false);
                if (response['unreadNotificationCount'] == "0") {
                    $(".readAllNotification").attr("disabled", true);
                }
                $('.notificationHeaderList').html('');

                $(response['data']).each(function(index, notification) {
                    var notificationStatus = "notificationread";
                    var read_color = "text-secondary";
                    var read_status_text = "Unread";
                    var read_status_icon = "eye-slash";
                    if (notification["read_status"] == "0") {
                        var notificationStatus = "notificationunread";
                        var read_color = "text-primary";
                        var read_status_text = "Read";
                        var read_status_icon = "eye";
                    }
                    var html = '<div class="dropdown-item-notify">' +
                        '<div class="media">' +
                        '<img src="' + notification["image"] + '" alt="User Avatar" class="img-size-50 img-circle mr-3">' +
                        '<div class="media-body">' +
                        '<h3 class="dropdown-item-title">' +
                        '<p class="text-sm">' + notification["web_text"] + '</p>' +
                        '<span title="' + read_status_text + '" class="float-right text-sm ' + read_color + ' ' + notificationStatus + ' " data-id=' + notification["notification_id"] + '><i class="fas fa-' + read_status_icon + '"></i></span>' +
                        '</h3>' +
                        '<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>' + notification["time"] + '</p>' +
                        '</div>' +
                        '</div>' +
                        '</div><div class="dropdown-divider"></div>';
                    /*html += '<audio autoplay>'+
  '<source src="https://assets.mixkit.co/sfx/preview/mixkit-wrong-answer-fail-notification-946.mp3" type="audio/mpeg">'+
'Your browser does not support the audio element.'+
'</audio>'*/
                    $('.notificationHeaderList').append(html);

                });
            },
            error: function(error) {
                console.log(error);
            },
        });
    }

    $(document).on("click", ".notificationunread,.notificationread,.readAllNotification", function() {
        $.ajax({
            url: '{{ route("admin.notificationEntity.readNotification") }}',
            type: 'POST',
            data: {
                notification_id: $(this).attr('data-id'),
                _token: "{{csrf_token()}}"
            },
            dataType: 'JSON',
            success: function(response) {
                getNotificationList();
            },
            error: function(error) {
                console.log(error);
            },
        });

        if ($(this).find('i.fas').hasClass("fa-eye")) {
            $(this).find('i.fas')
                .removeClass('fa-eye')
                .addClass('fa-eye-slash');

            $(this).closest('.timeline-item').addClass('unread-box');
            $(this).addClass("btn-warning");
        } else {
            $(this).find('i.fas')
                .removeClass('fa-eye-slash')
                .addClass('fa-eye');
            $(this).closest('.timeline-item').removeClass('unread-box');
            $(this).removeClass("btn-warning");
        }
        if ($(this).attr('data-id') == "all") {
            var Toast1 = Swal.mixin({
                showCloseButton: true,
                showConfirmButton: false,
            });
            Toast1.fire({
                icon: 'success',
                title: "<strong>Success</strong>",
                text: "All Notification Read Successfully",
            }).then(function() {
                location.reload();
            });
        }

    });

    $(document).on('click', '.dropdown-item-notify', function(e) {
        e.stopPropagation();
    });
</script>

<!--fire base push notification-->
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>

<script>
    var firebaseConfig = {
        apiKey: "AIzaSyAKvs0B2TDVwz5xsB_pHl5SOVJ3FN0oCRU",
        authDomain: "laravel-push-notificatio-6d6b8.firebaseapp.com",
        projectId: "laravel-push-notificatio-6d6b8",
        storageBucket: "laravel-push-notificatio-6d6b8.appspot.com",
        messagingSenderId: "777154052192",
        appId: "1:777154052192:web:bb3c41df8af1a567e54305",
        measurementId: "G-RNSMLRGBKT"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    startFCM();

    var navigator_info = window.navigator;
    var screen_info = window.screen;
    var uid = navigator_info.mimeTypes.length;
    uid += navigator_info.userAgent.replace(/\D+/g, '');
    uid += navigator_info.plugins.length;
    uid += screen_info.height || '';
    uid += screen_info.width || '';
    uid += screen_info.pixelDepth || '';
    console.log(uid);

    function startFCM() {
        messaging
            .requestPermission()
            .then(function() {
                return messaging.getToken()
            })
            .then(function(response) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("admin.fcm_token_update") }}',
                    type: 'POST',
                    data: {
                        token: response,
                        deviceId: uid,
                        _token: "{{csrf_token()}}"
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        //                        alert('Token stored.');
                    },
                    error: function(error) {
                        //alert(error);
                    },
                });
            }).catch(function(error) {
                //alert(error);
            });
    }

    var modals = [];

    messaging.onMessage((payload) => {
        console.log('onMessage Load....')
        if (!swal.isVisible()) {
            modals = [];
        }
        modals.push({
            title: payload['notification']['title'],
            html: payload['data']['web_text'],
            // text: payload['data']['web_text'],
            icon: 'info',
            // showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Cancel',
        });
        Swal.queue(modals);
        // Swal.fire(modals);
        $('.navbar').trigger('click');
        getNotificationList();
        playSound();
        console.log('Message received. ', payload);

    });

    function playSound() {
        html = '<audio autoplay>' +
            '<source src="{{ URL::asset('
        assets / audio / notification.mp3 ') }}" type="audio/mpeg">' +
            'Your browser does not support the audio element.' +
            '</audio>';
        $('.navbar').click();
        $('.navbar').append(html);

    }
</script>

</body>

</html>