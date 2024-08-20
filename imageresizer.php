<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>My Bootstrap Page</title>
</head>

<body>
    <div class="container">
        <h1>Welcome to my Bootstrap Page</h1>
        <p>This is a basic HTML page using Bootstrap.</p>

        <form action="upload.php" method="POST" id="submit" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" name="image[]" id="image" class="form-control-file" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>