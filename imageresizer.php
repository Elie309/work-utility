<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Image Resizer</title>
</head>

<body>
    <div class="container">
        <h1>Image Resizer</h1>
        <p>Upload images to resize them.</p>

        <form id="uploadForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Upload Images:</label>
                <input type="file" name="image[]" id="image" class="form-control-file" multiple>
            </div>
        </form>

        <div id="downloadLinks" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<script>
    $(document).ready(function() {
        $('#image').change(function() {
            var formData = new FormData($('#uploadForm')[0]);

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

                    $('#image').val('');

                    openLinks();
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error uploading files:', textStatus, errorThrown);
                }
            });
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