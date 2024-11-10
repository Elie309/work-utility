<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            font-size: x-large;
        }
        .upload-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .upload-form h2 {
            margin-bottom: 20px;
        }
        .upload-form input[type="file"] {
            margin-bottom: 10px;
        }
        .upload-form button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .upload-form button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="upload-form">
        <h2>Upload Excel Sheets</h2>
        <form action="handlers/dataHandler.php" method="post" enctype="multipart/form-data">
            <label for="newProducts">New Products:</label>
            <input type="file" name="newProducts" id="newProducts" accept=".xlsx, .xls">
            <br>
            <label for="currentProducts">Current Products:</label>
            <input type="file" name="currentProducts" id="currentProducts" accept=".xlsx, .xls">
            <br>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
