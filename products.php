<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Products</title>
    <link rel="stylesheet" href="output.css">
</head>

<body class="font-sans">
    <div class="container mx-auto max-w-2xl">
        <header class="p-5 bg-green-600 text-white">
            <a href="index.php" class="font-bold text-white underline">Back</a>
            <h1 class="text-2xl font-bold">Products Comparison</h1>
        </header>
        <p class="text-green-600 text-center my-2">Products comparison based on their SKU</p>
        <form action="handlers/dataHandler.php" method="post" enctype="multipart/form-data" class="space-y-4">
            <div class="border-2 border-dashed border-gray-300 p-5 rounded-lg text-center relative">
                <label for="newProducts" class="text-gray-500 mt-2 select-none">Upload New Products</label>
                <input type="file" name="newProducts" id="newProducts" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                <p class="text-gray-500 mt-2">Drag and drop files here or click to upload</p>
            </div>
            <div class="border-2 border-dashed border-gray-300 p-5 rounded-lg text-center cursor-pointer relative">
                <label for="currentProducts" class="text-gray-500 mt-2 select-none">Current List of Products</label>
                <input type="file" name="currentProducts" id="currentProducts" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                <p class="text-gray-500 mt-2 select-none">Drag and drop files here or click to upload</p>
            </div>
            <div class="text-end">
                <button type="submit" class="w-40 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Upload</button>
                
            </div>
    </form>
    </div>
</body>

</html>