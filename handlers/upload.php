<?php
if (isset($_FILES['image'])) {
    $desiredWidth = 1200;
    $desiredHeight = 1200;
    $uploadDir = '../uploads/';
    $urls = [];

    // Ensure the upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['image']['tmp_name'] as $key => $tmpName) {
        $imageType = mime_content_type($tmpName);

        // Load the image accordingly
        switch ($imageType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($tmpName);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($tmpName);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($tmpName);
                break;
            case 'image/webp':
                $sourceImage = imagecreatefromwebp($tmpName);
                break;
            case 'image/avif':
                $sourceImage = imagecreatefromavif($tmpName);
                break;
            default:
                echo "Unsupported image format: $imageType";
                exit;
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

        // Save the resized image to the server
        $outputFilename = $uploadDir . 'resized_image_' . time() . '_' . $key . '.webp';
        imagewebp($resizedImage, $outputFilename);

        // Add the URL to the list
        $urls[] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $outputFilename;

        // Free up memory
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);
    }

    // Return the URLs of the resized images
    echo implode("\n", $urls);
} else {
    throw new Exception('No images uploaded');
}
