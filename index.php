<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PHP Website</title>
    <link rel="stylesheet" href="output.css">
</head>

<body class="w-full mx-auto h-full font-sans flex flex-col justify-between max-w-2xl">
  <div>
      <header class="p-5 bg-green-600 text-white">
          <h1 class="text-2xl font-bold">GCS Work Utilities</h1>
      </header>
    
      <nav class="p-5 bg-gray-100">
          <ul class="flex space-x-4">
              <li><a href="qrcodegen.php" class="text-green-600 hover:underline">QR Code</a></li>
              <li><a href="imageresizer.php" class="text-green-600 hover:underline">Image</a></li>
              <li><a href="products.php" class="text-green-600 hover:underline">Products</a></li>
              <li><a href="descriptions.php" class="text-green-600 hover:underline">Descriptions</a></li>
          </ul>
      </nav>
    
      <main class="p-5">
          <section class="mt-5">
              <h3 class="text-lg font-bold">Utilities</h3>
              <ul class="list-disc list-inside">
                  <li><a href="qrcodegen.php" class="text-green-600 hover:underline">QR Code</a> - Generate QR codes for various purposes.</li>
                  <li><a href="imageresizer.php" class="text-green-600 hover:underline">Image</a> - Resize and optimize images for web use.</li>
                  <li><a href="products.php" class="text-green-600 hover:underline">Products</a> - Upload and compare product lists based on SKU.</li>
                  <li><a href="descriptions.php" class="text-green-600 hover:underline">Descriptions</a> - Manage and edit product descriptions.</li>
              </ul>
          </section>
      </main>
  </div>

    <footer class="p-5 bg-gray-100 text-center">
        <p>&copy; <?php echo date("Y"); ?> GCS Work Utilities. All rights reserved.</p>
        <p>Website created by Elie Saade - 2024.</p>

    </footer>
</body>

</html>