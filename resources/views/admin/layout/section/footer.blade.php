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
<!-- <script src="https://cdn.tiny.cloud/1/t77u2xolrgt4v0tex0cgrerfx7m2wtiwl0vuhanx974vo9ir/tinymce/6/tinymce.min.js"></script>
  -->
<script src="{{ asset('assets/tinymce/tinymce/tinymce.min.js') }}"></script>

<!-- ChartJS -->
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.min.js') }}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ URL::asset('assets/dist/js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ URL::asset('assets/dist/js/pages/dashboard2.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script src="{{ URL::asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>


<!-- Jquery Validations method -->
<script src="{{ URL::asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

@yield('js')
<script>
  
    $("#crop").click(function(){
    //   alert('hello');
        $(this).blur(); // This will remove focus from the file input
        if ($(this).valid()) {
            // Create a success message element
            var successMessage = $('<div class="success-message text-success">The file accepted for upload.</div>');
            // Append the success message after the file input
            $('input[type="file"]').after(successMessage);
            $("#crop").attr("disabled", true);
            // Set a timeout to remove the success message after a few seconds (optional)
            setTimeout(function() {
                successMessage.remove();
                $("#crop").attr("disabled", false);
            }, 3000); // Remove the message after 3 seconds (adjust as needed)
        }
    });
    $(function() {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '-40:+40',
            changeMonth: true,
            changeYear: true,

        });

    });

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
    });




    // allow letter and space only
    $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-zA-Z\s]*$/.test(value);
    }, "Please enter only alphabetical letters");

    jQuery.validator.addMethod("noSpace", function(value, element) {
        return value == '' || value.trim().length != 0;
    }, "Only Space are not allowed");
    //allow image with validation of resolution 300*300
    $.validator.addMethod("resolution", function(value, element) {
        // Get the selected file
        var file = element.files[0];

        // Create a new image element
        var img = new Image();

        // Set the source of the image element to the selected file
        img.src = window.URL.createObjectURL(file);

        // Check if the image has the desired resolution
        if (img.width === 300 && img.height === 300) {
            return true;
        }

        return false;
    }, "Please select an image with a resolution of 300x300 pixels.");
    $.validator.addMethod('dimention', function(value, element, param) {
        if (element.files.length == 0) {
            return true;
        }
        var width = $(element).data('imageWidth');
        var height = $(element).data('imageHeight');
        if (width == param[0] && height == param[1]) {
            return true;
        } else {
            return false;
        }
    }, 'Please upload an image with 300 x 300 pixels dimension');



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
        

        //country autocomplete code for agency module
        

        $(".autocomplete").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: 'POST',
                    data: {
                        "search": request.term
                    },
                    url: "{{url('api/v1/google-place-search')}}",

                    success: function(html) {

                        var searchData = JSON.parse(JSON.stringify(html));
                        var finalData = searchData['data'][0]['predictions'];
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

                    success: function(html) {

                        var searchData = JSON.parse(JSON.stringify(html));
                        var finalData = searchData['data'][0]['predictions'];

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

<!-- Start Tiny Mce Editor for content english and arabic -->
<script>
    $(document).ready(function() {
        tinymce.init({
            selector: 'textarea#editor_en',
            menu: {
                edit: {
                    title: 'Edit',
                    items: 'undo redo | cut copy | selectall | searchreplace'
                }
            },
            plugins: 'help code powerpaste casechange searchreplace autolink directionality visualblocks visualchars image link media mediaembed codesample table charmap pagebreak nonbreaking anchor tableofcontents insertdatetime advlist lists checklist wordcount tinymcespellchecker editimage help formatpainter permanentpen charmap linkchecker emoticons advtable export autosave advcode fullscreen',
            toolbar: 'undo redo print spellcheck formatpainter | fontselect fontsizeselect | bold italic underline forecolor backcolor | link image | alignleft aligncenter alignright alignjustify',
            help_tabs: [
                'shortcuts' // the default shortcuts tab
            ],
            height: '400px',
            directionality: "ltr",
            branding: false,
            help_accessibility: false,
            //language: 'en',
            convert_urls: false,
            paste_data_images: true,
            promotion: false,
            images_upload_url: '/uploadCmsFile',
            images_upload_handler: function(blobInfo) {
                return new Promise((resolve, reject) => {
                    var formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                    $.ajax({
                        url: '/uploadCmsFile',
                        type: 'post',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            resolve(response.location);
                        },
                        error: function(error) {
                            reject('Image upload failed');
                        }
                    });
                });
            }
        });
    });

    $(document).ready(function() {
        tinymce.init({
            selector: 'textarea#editor_ar',
            menu: {
                edit: {
                    title: 'Edit',
                    items: 'undo redo | cut copy | selectall | searchreplace'
                }
            },
            plugins: 'help code directionality powerpaste casechange searchreplace autolink directionality visualblocks visualchars image link media mediaembed codesample table charmap pagebreak nonbreaking anchor tableofcontents insertdatetime advlist lists checklist wordcount tinymcespellchecker editimage help formatpainter permanentpen charmap linkchecker emoticons advtable export autosave advcode fullscreen',
            toolbar: 'undo redo print spellcheck formatpainter | fontselect fontsizeselect | bold italic underline forecolor backcolor | link image | alignleft aligncenter alignright alignjustify',
            help_tabs: [
                'shortcuts' // the default shortcuts tab
            ],
            height: '400px',
            directionality: "rtl",
            branding: false,
            //language: 'ar',
            convert_urls: false,
            paste_data_images: true,
            promotion: false,
            images_upload_url: '/uploadCmsFile',
            images_upload_handler: function(blobInfo) {
                return new Promise((resolve, reject) => {
                    var formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                    $.ajax({
                        url: '/uploadCmsFile',
                        type: 'post',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            resolve(response.location);
                        },
                        error: function(error) {
                            reject('Image upload failed');
                        }
                    });
                });
            }
        });
    });
    $(document).ready(function() {
        tinymce.init({
            selector: 'textarea.editor',
            menu: {
                edit: {
                    title: 'Edit',
                    items: 'undo redo | cut copy | selectall | searchreplace'
                }
            },
            plugins: 'help code directionality powerpaste casechange searchreplace autolink directionality visualblocks visualchars image link media mediaembed codesample table charmap pagebreak nonbreaking anchor tableofcontents insertdatetime advlist lists checklist wordcount tinymcespellchecker editimage help formatpainter permanentpen charmap linkchecker emoticons advtable export autosave advcode fullscreen',
            toolbar: 'undo redo print spellcheck formatpainter | fontselect fontsizeselect | bold italic underline forecolor backcolor | link image | alignleft aligncenter alignright alignjustify',
            help_tabs: [
                'shortcuts' // the default shortcuts tab
            ],
            height: '400px',
            directionality: "rtl",
            branding: false,
            //language: 'ar',
            convert_urls: false,
            paste_data_images: true,
            promotion: false,
            images_upload_url: '/uploadCmsFile',
            images_upload_handler: function(blobInfo) {
                return new Promise((resolve, reject) => {
                    var formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                    $.ajax({
                        url: '/uploadCmsFile',
                        type: 'post',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            resolve(response.location);
                        },
                        error: function(error) {
                            reject('Image upload failed');
                        }
                    });
                });
            }
        });
    });
</script>

<!-- End Tiny Mce Editor for content english and arabic -->
<script>
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
                    } else if (result.dismiss === Swal.DismissReason.cancel) {

                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    title: 'No records for delete.',
                    icon: 'warning'
                });
                return false;
            }
            return true;
        } else {
            Swal.fire({
                title: 'Please select CheckBoxe(s).',
                icon: 'warning'
            });
            return false;
        }


    });

    $(document).ready(function() {
        $('[data-toggle="popover"]').popover({
            trigger: 'hover',
            html: true,
            content: function() {
                return '<img class="img-fluid popover_img" src="' + $(this).attr('src') + '" />';
            },
        })

    });
</script>

<!--notification in header display-->


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
    console.log(messaging);
    startFCM();

    var navigator_info = window.navigator;
    var screen_info = window.screen;
    var uid = navigator_info.mimeTypes.length;
    uid += navigator_info.userAgent.replace(/\D+/g, '');
    uid += navigator_info.plugins.length;
    uid += screen_info.height || '';
    uid += screen_info.width || '';
    uid += screen_info.pixelDepth || '';

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
                        //console.log(response);
                    },
                    error: function(error) {

                    },
                });
            }).catch(function(error) {

            });
    }

    var modals = [];

    messaging.onMessage((payload) => {
        console.log(payload);
        console.log('onMessage Load....')
        if (!payload || !payload.notification || !payload.data) {
            console.error('Invalid payload:', payload);
            return;
        }

        if (!swal.isVisible()) {
            modals = [];
        }
        modals.push({
            title: payload['notification']['title'],
            html: payload['data']['web_text'],

            icon: 'info',

            confirmButtonColor: '#d33',
            confirmButtonText: 'Cancel',
        });
        Swal.queue(modals);

        $('.navbar').trigger('click');
        getNotificationList();
        playSound();
        console.log('Message received. ', payload);

    });

    function playSound() {
        html = '<audio autoplay>' +
            '<source src="{{ URL::asset("assets/audio/notification.mp3") }}" type="audio/mpeg">' +
            'Your browser does not support the audio element.' +
            '</audio>';
        $('.navbar').click();
        $('.navbar').append(html);

    }
</script>

</body>

</html>