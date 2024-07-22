<?php
if (!empty($_FILES['image']['tmp_name'])) {

    // Start output buffering

    $desiredWidth = 1200;
    $desiredHeight = 1200;

    // Load the uploaded image
    // $sourceImage = imagecreatefromstring(file_get_contents($_FILES['image']['tmp_name']));


    // Calculate position to center the image
    $imageType = mime_content_type($_FILES['image']['tmp_name']);

    // Load the image accordingly
    switch ($imageType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($_FILES['image']['tmp_name']);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($_FILES['image']['tmp_name']);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($_FILES['image']['tmp_name']);
            break;
        default:
            die("Unsupported image format: $imageType");
    }

    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);

    // Create a new true color image with the desired dimensions
    $resizedImage = imagecreatetruecolor($desiredWidth, $desiredHeight);

    // Make the background transparent
    imagesavealpha($resizedImage, true);
    $transparentColor = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
    imagefill($resizedImage, 0, 0, $transparentColor);

    // Calculate resize ratio
    $resizeRatio = min($desiredWidth / $sourceWidth, $desiredHeight / $sourceHeight);
    $newWidth = (int)($sourceWidth * $resizeRatio);
    $newHeight = (int)($sourceHeight * $resizeRatio);

    // Calculate position to center the image
    $x = (int)(($desiredWidth - $newWidth) / 2);
    $y = (int)(($desiredHeight - $newHeight) / 2);

    // Resize and center the image
    imagecopyresampled($resizedImage, $sourceImage, $x, $y, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

    ob_end_clean();

    // Send the correct headers to force download the PNG image
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="resized_image.webp"');

    // Output the image
    imagepng($resizedImage);

    // Clean up
    imagedestroy($resizedImage);
} else {
    echo "Please upload an image.";
}
