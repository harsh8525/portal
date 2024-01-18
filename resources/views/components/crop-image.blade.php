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
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">

    <input type="hidden" name="type" id="type" value="{{ $name }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image Before Upload</h5>
                <button type="button" class="close cancelButton" onclick="$('#modal').modal('hide');" ata-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img src="" id="sample_image" />
                        </div>
                        <div class="col-md-4">
                            <!-- Add a div for the zoom slider -->
                            <div id="zoom-label" style="color: black;margin-left: 8px;">Zoom:</div>
                            <input type="range" class="zoom-slider" id="zoom-slider" value="100" min="0" max="200" step="1" />

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="crop" class="btn btn-primary crop_image_msg">Crop</button>
                <button type="button" class="btn btn-secondary cancelButton" onclick="$('#modal').modal('hide');" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div>


</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

<script src="https://unpkg.com/cropperjs"></script>
<!-- Add Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-U1m7ZsS2rsVHjzIeb6c9EmmMbqe4rYABJf12d+XwbZI8YZbTTPQIcLVTN9T5u22j" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {

        var $modal = $('#modal');
        var image = document.getElementById('sample_image');
        var cropper;

        $('#{{$id}}').change(function(event) {
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

            var imageFor = document.getElementById('upload_banner');
            if (imageFor) {
                // aspectRatio = 3;
                // Get the width and height of the image
                var imageWidth = imageFor.naturalWidth || imageFor.width;
                var imageHeight = imageFor.naturalHeight || imageFor.height;

                // Calculate the aspect ratio based on image width and height
                aspectRatio = 3200 / 685;
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
                minCropBoxWidth: 300,
                minCropBoxHeight: 300,
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
            $('#zoom-slider').val(0);
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
            $('#{{$id}}').val('');

        });
        // Update Cropper zoom level when the slider value changes
        $('#zoom-slider').on('input', function() {
            var zoomValue = $(this).val();
            cropper.zoomTo(zoomValue / 100);
        });
        $('#crop').click(function() {
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
        $('.cancelButton').click(function() {
            var fileInput = $('#{{$id}}');
            // Clear the value of the file input
            fileInput.val('');
            $('#croppedImagePreview').val('');
        });
        $('#cancelButton').trigger('change');

    });
</script>