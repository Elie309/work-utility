<?php
if (isset($_FILES['image'])) {

    // Start output buffering

    $desiredWidth = 1200;
    $desiredHeight = 1200;

    $totalFiles = count($_FILES['image']['tmp_name']);

    if ($totalFiles == 1) {

        $tmpName = $_FILES['image']['tmp_name'][0];
        $imageType = mime_content_type($tmpName);
        

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

        header('Content-Type: image/webp');
        header('Content-Disposition: attachment; filename="resized_image.webp"');

        imagewebp($resizedImage);



        // Free up memory
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);
    } else {

        $zip = new ZipArchive();
        $zipFilename = 'resized_images.zip';

        if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            exit("Cannot open <$zipFilename>\n");
        }

        // Load the uploaded image

        foreach ($_FILES['image']['tmp_name'] as $key => $tmpName) {

            $imageType = mime_content_type($tmpName);

            // Load the image accordingly
            switch ($imageType) {
                case 'image/jpeg':
                    $sourceImage = imagecreatefromjpeg($_FILES['image']['tmp_name'][$key]);
                    break;
                case 'image/png':
                    $sourceImage = imagecreatefrompng($_FILES['image']['tmp_name'][$key]);
                    break;
                case 'image/gif':
                    $sourceImage = imagecreatefromgif($_FILES['image']['tmp_name'][$key]);
                    break;
                case 'image/webp':
                    $sourceImage = imagecreatefromwebp($_FILES['image']['tmp_name'][$key]);
                    break;
                case 'image/avif':
                    $sourceImage = imagecreatefromavif($_FILES['image']['tmp_name'][$key]);
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

            ob_start();
            imagewebp($resizedImage);
            $imageData = ob_get_clean();

            $zip->addFromString("resized_image_$key.webp", $imageData);

            // Free up memory
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);
        }

        // Close the ZIP archive
        $zip->close();

        // Send the correct headers to force download the ZIP file
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="resized_images.zip"');
        header('Content-Length: ' . filesize($zipFilename));

        // Read the ZIP file
        readfile($zipFilename);

        // Delete the ZIP file from the server
        unlink($zipFilename);
    }
} else {
    echo "Please upload an image.";
}
