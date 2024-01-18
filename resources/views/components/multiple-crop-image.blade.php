<style>
    .image_area {
        position: relative;
    }

    img {
        display: block;
        max-width: 100%;
    }

    .preview {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }

    .modal-lg {
        max-width: 1000px !important;
    }

    .overlay {
        position: absolute;
        bottom: 10px;
        left: 0;
        right: 0;
        background-color: rgba(255, 255, 255, 0.5);
        overflow: hidden;
        height: 0;
        transition: .5s ease;
        width: 100%;
    }

    .image_area:hover .overlay {
        height: 50%;
        cursor: pointer;
    }

    input[type="range"].zoom-slider {
        -webkit-appearance: none;
        width: 100%;
        height: 10px;
        border-radius: 4px;
        background: #dadce0;
        outline: none;
        opacity: 0.7;
        -webkit-transition: 0.2s;
        transition: opacity 0.2s;
        margin: 10px 0;
        margin-left: 8px;
    }

    input[type="range"].zoom-slider:hover {
        opacity: 1;
    }
</style>
<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
<input type="file" id="{{ $id }}" name="{{ $name }}" class="{{ $class }}" accept="image/*">
@if($id == 'qr_code_image')
<div class="modal fade" id="one_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">

    <input type="hidden" name="type" id="type" value="{{ $name }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image Before Upload</h5>
                <button type="button" class="close cancelButtonQr" onclick="$('#one_modal').modal('hide');" ata-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img src="" id="one_sample_image" />
                        </div>
                        <div class="col-md-4">
                            <!-- Add a div for the zoom slider -->
                            <div id="zoom-label" style="color: black;margin-left: 8px;">Zoom:</div>
                            <input type="range" class="zoom-slider" id="one-zoom-slider" value="100" min="0" max="200" step="1" />

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="one_crop" onclick="displayErrorQr()" class="btn btn-primary crop_image_msg">Crop</button>
                <button type="button" class="btn btn-secondary cancelButtonQr" onclick="$('#one_modal').modal('hide');" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div>


</div>
@elseif($id === 'banner_image_en')
<div class="modal fade" id="second_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <input type="hidden" name="type" id="type" value="{{ $name }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image Before Upload</h5>
                <button type="button" class="close cancelSecondButton" onclick="$('#second_modal').modal('hide');" ata-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img src="" id="second_sample_image" />
                        </div>
                        <div class="col-md-4">
                            <!-- Add a div for the zoom slider -->
                            <div id="zoom-label" style="color: black;margin-left: 8px;">Zoom:</div>
                            <input type="range" class="zoom-slider" id="second-zoom-slider" value="100" min="0" max="200" step="1" />

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
                <button type="button" id="second_crop" onclick="displayErrorSec()" class="btn btn-primary crop_image_msg">Crop</button>
                <button type="button" class="btn btn-secondary cancelSecondButton" onclick="$('#second_modal').modal('hide');" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div>
</div>
@elseif($id === 'banner_image_ar')
<div class="modal fade" id="third_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image Before Upload</h5>
                <button type="button" class="close cancelButtonAr" onclick="$('#third_modal').modal('hide');" ata-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img src="" id="third_sample_image" />
                        </div>
                        <div class="col-md-4">
                            <!-- Add a div for the zoom slider -->
                            <div id="zoom-label" style="color: black;margin-left: 8px;">Zoom:</div>
                            <input type="range" class="zoom-slider" id="third-zoom-slider" value="100" min="0" max="200" step="1" />

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
                <button type="button" id="third_crop" class="btn btn-primary crop_image_msg">Crop</button>
                <button type="button" class="btn btn-secondary cancelButtonAr" onclick="$('#third_modal').modal('hide');" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endif

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

<script src="https://unpkg.com/cropperjs"></script>
<!-- Add Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-U1m7ZsS2rsVHjzIeb6c9EmmMbqe4rYABJf12d+XwbZI8YZbTTPQIcLVTN9T5u22j" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>

  function displayErrorQr() {
            // Create a success message element
            var successMessage = $('<div class="success-message text-success">The file accepted for upload.</div>');
            // Append the success message after the file input
            $('input[id="qr_code_image"]').after(successMessage);
            $("#one_crop").attr("disabled", true);
            // Set a timeout to remove the success message after a few seconds (optional)
            setTimeout(function() {
                successMessage.remove();
                $("#one_crop").attr("disabled", false);
            }, 3000); // Remove the message after 3 seconds (adjust as needed)
        }
    function displayErrorSec() {
            // Create a success message element
            var successMessage = $('<div class="success-message text-success">The file accepted for upload.</div>');
            // Append the success message after the file input
            $('input[id="banner_image_en"]').after(successMessage);
            $("#second_crop").attr("disabled", true);
            // Set a timeout to remove the success message after a few seconds (optional)
            setTimeout(function() {
                successMessage.remove();
                $("#second_crop").attr("disabled", false);
            }, 3000); // Remove the message after 3 seconds (adjust as needed)
        }

function initializeCropper(id) {

    var check_id = id;
    if (check_id == 'qr_code_image') {
        var $modal = $('#one_modal');
        var image = document.getElementById('one_sample_image');
    }else if (check_id == 'banner_image_en') {
        var $modal = $('#second_modal');
        var image = document.getElementById('second_sample_image');
    }else if (check_id == 'banner_image_ar') {
        var $modal = $('#third_modal');
        var image = document.getElementById('third_sample_image');
    }
    
    var cropper;

    $('#' + id).change(function(event) {
        var files = event.target.files;
        var filename = files[0].name;
        var extension = files[0].type;
        var fileSize = files[0].size;

        console.log(fileSize);
        // return false;
        if (fileSize < 1000000) {

            if (extension == 'image/jpeg' || extension == 'image/jpg' || extension == 'image/png') {
                var done = function(url) {
                    image.src = url;
                    $modal.modal('show');
                };
            } else {
                return false;
            }
        }

        if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(event) {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $modal.on('shown.bs.modal', function() {
         var imageFor = document.getElementById('banner_image_en');
            if (imageFor) {
                // Get the width and height of the image
                var imageWidth = imageFor.naturalWidth || imageFor.width;
                var imageHeight = imageFor.naturalHeight || imageFor.height;

                // Calculate the aspect ratio based on image width and height
                aspectRatio = imageWidth / imageHeight;
            } else {
                aspectRatio = 1;
            }
            cropper = new Cropper(image, {
                dragMode: 'move',
                aspectRatio: aspectRatio,
                // Set the viewMode to 1 to cover the whole image initially
                viewMode: 1, // 0: free, 1: cover, 2: contain
                autoCropArea: 1, // 1: cover the whole canvas
                zoomable: true,
                minCropBoxWidth: 800,
                minCropBoxHeight: 500,
                ready: function() {
                    // Automatically adjust the crop box based on image dimensions
                    var imageWidth = cropper.getImageData().naturalWidth;
                    var imageHeight = cropper.getImageData().naturalHeight;
                    var minCropBoxSize = Math.min(imageWidth, imageHeight);
                    cropper.setCropBoxData({
                        width: minCropBoxSize,
                        height: minCropBoxSize,
                    });

                    // Enable zoom after the cropper is ready
                    cropper.setZoom(1);
                },
                zoomOnTouch: true,
                zoomOnWheel: false,
                cropBoxMovable: false, // Make crop box not movable
                cropBoxResizable: false, // Make crop box not resizable
                toggleDragModeOnDblclick: false,
                data: { // Define crop box size
                    width: 300,
                    height: 300,
                },
            });
            // Initialize the zoom slider
            if (check_id == 'qr_code_image') {
                $('#one-zoom-slider').val(0);
            }else if (check_id == 'banner_image_en') {
                $('#second-zoom-slider').val(0);
            }else if(check_id == 'banner_image_ar'){
                $('#third-zoom-slider').val(0);
            }
            
    }).on('hidden.bs.modal', function() {
        cropper.destroy();
        cropper = null;
        $('#' + id).val('');

        $('#one_crop').off('click');
        $('#second_crop').off('click');
        $('#third_crop').off('click');
    });

    // Update Cropper zoom level when the slider value changes
    if (check_id == 'qr_code_image') {
        $('#one-zoom-slider').on('input', function() {
            var zoomValue = $(this).val();
            cropper.zoomTo(zoomValue / 100);
        });
    }else if (check_id == 'banner_image_en') {
        $('#second-zoom-slider').on('input', function() {
            var zoomValue = $(this).val();
            cropper.zoomTo(zoomValue / 100);
        });
    }else if(check_id == 'banner_image_ar'){
        $('#third-zoom-slider').on('input', function() {
            var zoomValue = $(this).val();
            cropper.zoomTo(zoomValue / 100);
        });
    }
    if (check_id === 'qr_code_image') {
        $('#one_crop').click(function() {
            var $croppedImagePreview = $('#croppedImagePreview');
            var canvas = cropper.getCroppedCanvas();
            var $previewImage = $('<img />');
            $("#croppedImagePreview").attr('src', canvas.toDataURL());
            // Set the desired width and height
            $previewImage.attr('width', 300);
            $previewImage.attr('height', 300);
            $croppedImagePreview.html($previewImage);
            canvas.toBlob(function(blob) {
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $.ajax({
                        url: '/cropped-image',
                        method: 'POST',
                        data: {
                            image: base64data,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            $modal.modal('hide');
                            $('#croppedImage').val(data[0]);
                        }
                    });
                };
            });
        });


        $('#croppedImagePreview').on('load', function() {
            $(this).show();
        });

        // Event handler for cancel button click
        $('.cancelButtonQr').click(function() {
            var fileInput = $('#{{$id}}');
            // Clear the value of the file input
            fileInput.val('');
            $('#croppedImagePreview').val('');
            $modal.modal('hide');
        });
        $('#cancelButton').trigger('change');
    }else if (check_id === 'banner_image_en') {
        $('#second_crop').click(function() {
            var $croppedImagePreview = $('#secondCroppedImagePreview');
            var canvas = cropper.getCroppedCanvas();
            var $previewImage = $('<img />');
            $("#secondCroppedImagePreview").attr('src', canvas.toDataURL());
            // Set the desired width and height
            $previewImage.attr('width', 300);
            $previewImage.attr('height', 300);
            $croppedImagePreview.html($previewImage);
            canvas.toBlob(function(blob) {
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $.ajax({
                        url: '/cropped-image',
                        method: 'POST',
                        data: {
                            image: base64data,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            $modal.modal('hide');
                            $('#secondCroppedImage').val(data[0]);
                        }
                    });
                };
            });
        });

        $('#secondCroppedImagePreview').on('load', function() {
            $(this).show();
        });

        // Event handler for cancel button click
        $('.cancelSecondButton').click(function() {
            var fileInput = $('#{{$id}}');
            // Clear the value of the file input
            fileInput.val('');
            $('#secondCroppedImagePreview').val('');
            $modal.modal('hide');
        });
        $('#cancelSecondButton').trigger('change');

    
    } else if (check_id === 'banner_image_ar') {
        $('#third_crop').click(function() {
            var $croppedImagePreview = $('#thirdCroppedImagePreview');
            var canvas = cropper.getCroppedCanvas();
            var $previewImage = $('<img />');
            $("#thirdCroppedImagePreview").attr('src', canvas.toDataURL());
            // Set the desired width and height
            $previewImage.attr('width', 300);
            $previewImage.attr('height', 300);
            $croppedImagePreview.html($previewImage);
            canvas.toBlob(function(blob) {
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $.ajax({
                        url: '/cropped-image',
                        method: 'POST',
                        data: {
                            image: base64data,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            $modal.modal('hide');
                            $('#thirdCroppedImage').val(data[0]);
                        }
                    });
                };
            });
        });


        $('#thirdCroppedImagePreview').on('load', function() {
            $(this).show();
        });

        // Event handler for cancel button click
        $('.cancelButtonAr').click(function() {
            var fileInput = $('#{{$id}}');
            // Clear the value of the file input
            fileInput.val('');
            $('#thirdCroppedImagePreview').val('');
            $modal.modal('hide');
        });
        $('#cancelButtonAr').trigger('change');
    }
}

// Initialize cropper for different elements
var imageId = "{{$id}}";
if (imageId === 'qr_code_image' || imageId === 'banner_image_en' || imageId === 'banner_image_ar') {
    initializeCropper(imageId);
}
</script>