<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use QRcode;

require_once './vendor/autoload.php';
require_once './phpqrcode/qrlib.php';

$base_link = "https://frontendtesting.gcslb.com";


// Function to generate QR code
function generateQRCode($text, $outputPath)
{
    QRcode::png($text, $outputPath, QR_ECLEVEL_L, 10);
}

// Function to process Excel file
function processExcel($filePath)
{
    global $base_link;
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();

    foreach ($worksheet->getRowIterator() as $row) {
        $name = str_replace('"', '', $worksheet->getCell('A' . $row->getRowIndex())->getValue()); // Replace spaces with dashes
        $name = str_replace(' ', '-', $name);
        $name = str_replace(':', '-', $name);
        $url = $base_link . $worksheet->getCell('B' . $row->getRowIndex())->getValue();
        $outputPath = "outputs/{$name}.png";
        generateQRCode($url, $outputPath);
    }
}

// Example usage
$excelFilePath = 'data.xlsx';
processExcel($excelFilePath);


function renderQRCodeImages($outputPath)
{
    // Ensure the path ends with a directory separator
    $outputPath = rtrim($outputPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $outputFiles = glob($outputPath . '*.png');

    foreach ($outputFiles as $file) {
        // Assuming the script is executed in a context where the browser can access the images relative to the script's URL
        $webPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
        echo "<div class='container'>";
        echo "<div class='image-container'><img class='logo' src='../public/gcslogo.png' alt='GCS Logo' />";
        echo "<img class='image' src='{$webPath}' alt='QR Code' /></div>";
        $name = str_replace('-', ' ', basename($file, '.png'));
        echo "<p class='text'>{$name}</p>";
        echo "</div>";
    }
}

$outputPath = 'outputs';

// Example usage
renderQRCodeImages($outputPath);
?>

<style>
    .image-container {
        display: flex;
        justify-content: top;
        flex-direction: row;
        align-items: center;
        margin-bottom: 0;
    }

    .container {
        display: flex;
        justify-content: top;
        align-items: center;
        flex-direction: column;
        border: 1px solid #000;
        width: 410px;

    }

    .logo {
        width: 200px;
        height: auto;
        max-height: 150px;
    }

    .image {
        width: 200px;
        height: 200px;
        position: relative;

    }

    .text {
        font-size: 11px;
        font-weight: bold;
        margin-top: 0;
        text-align: center;
    }

    @media print {
        body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
        }

        .container {
            box-sizing: border-box;
            width: 410px;
            float: left;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .text {
            text-align: center;
            margin-top: 5mm;
        }

        .container:nth-of-type(4n) {
            page-break-after: always;
        }
    }
</style>