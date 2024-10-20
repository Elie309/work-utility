<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Image Resizer</title>
    <style>
        #uploadArea {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
            position: relative;
            height: 250px;
            width: 100%;
            
        }

        #uploadArea.dragging {
            border-color: #007bff;
            background-color: #ccc;
        }

        #image {
            width: 100%;
            height: 250px;
            font-size: 1.5em;
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            cursor: pointer;

        }

        #uploadArea::before {
            content: 'Click, paste, or drag and drop images here';
            font-size: 1.5em;
            left: 0;
            right: 0;
            top: 50%;
            position: absolute;
            transform: translateY(-50%);

            color: #aaa;
        }

        #loadingText {
            display: none;
            font-size: 1.5em;
            color: #007bff;
            margin-top: 20px;
        }
    </style>
</head>


<body>
    <div class="container">
        <h1>Image Resizer</h1>
        <p>Upload or paste images to resize them.</p>

        <form id="uploadForm" enctype="multipart/form-data">
            <div id="uploadArea">
                <input type="file" name="image[]" id="image" class="form-control-file" multiple>
            </div>
        </form>
        <div id="loadingText">Uploading...</div>
        <div id="downloadLinks" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<script>
    $(document).ready(function() {
        function uploadFiles(formData) {
            $('#loadingText').show();
            $.ajax({
                url: 'upload.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var downloadLinks = '';
                    var urls = response.split('\n');
                    urls.forEach(function(url) {
                        if (url.trim() !== '') {
                            downloadLinks += `<a 
                            class="links-download"
                            onClick="removeElement(this)"
                            href="${url}" download>
                            Download ${url.split('/').pop() }</a><br>`;
                        }
                    });
                    $('#downloadLinks').html(downloadLinks);
                    $('#loadingText').hide();
                    $('#image').val('');
                    openLinks();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loadingText').hide();
                    console.error('Error uploading files:', textStatus, errorThrown);
                }
            });
        }

        $('#image').change(function() {
            var formData = new FormData($('#uploadForm')[0]);
            uploadFiles(formData);
        });

        $('#uploadArea').on('paste', function(event) {
            var items = (event.clipboardData || event.originalEvent.clipboardData).items;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    var blob = items[i].getAsFile();
                    var formData = new FormData();
                    formData.append('image[]', blob);
                    uploadFiles(formData);
                }
            }
        });

        // Drag and drop functionality
        $('#uploadArea').on('dragover', function(event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).addClass('dragging');
        });

        $('#uploadArea').on('dragleave', function(event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).removeClass('dragging');
        });

        $('#uploadArea').on('drop', function(event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).removeClass('dragging');

            var files = event.originalEvent.dataTransfer.files;
            var formData = new FormData();
            for (var i = 0; i < files.length; i++) {
                formData.append('image[]', files[i]);
            }
            uploadFiles(formData);
        });
    });

    function removeElement(element) {
        element.remove();
    }

    function openLinks() {
        var links = document.querySelectorAll('.links-download');
        links.forEach(function(link) {
            link.click();
        });
    }
</script>