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

// Function to render QR code images
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

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['excelFile']['name']);

    if (move_uploaded_file($_FILES['excelFile']['tmp_name'], $uploadFile)) {
        processExcel($uploadFile);
        $outputPath = 'outputs';
        renderQRCodeImages($outputPath);
    } else {
        echo "File upload failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <link rel="stylesheet" href="output.css">
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

        #excelFile {
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
            content: 'Click, paste, or drag and drop Excel file here';
            font-size: 1.5em;
            left: 0;
            right: 0;
            top: 50%;
            position: absolute;
            transform: translateY(-50%);
            color: #aaa;
        }

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
</head>
<body class="w-full mx-auto font-sans flex flex-col max-w-2xl">
    <header class="no-print p-5 bg-green-600 text-white">
        <a href="index.php" class="font-bold text-white underline">Back</a>
        <h1 class="text-2xl font-bold">QR Code Generator</h1>
    </header>
    <div class="">
        <p class="mt-4 text-center no-print text-green-600">Upload or paste an Excel file to generate QR codes.</p>

        <form id="uploadForm" enctype="multipart/form-data" class="no-print mt-4">
            <div id="uploadArea" class="border rounded">
                <input type="file" name="excelFile" id="excelFile" class="form-control-file" accept=".xlsx, .xls">
            </div>
        </form>
        <div id="loadingText" class="no-print text-green-600 hidden bold">Uploading...</div>
        <div id="qrcodes" class="mt-3">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
                renderQRCodeImages($outputPath);
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            function uploadFiles(formData) {
                $('#loadingText').show();
                $.ajax({
                    url: '',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#loadingText').hide();
                        $('#excelFile').val('');
                        $('#qrcodes').html($(response).find('#qrcodes').html());
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#loadingText').hide();
                        console.error('Error uploading files:', textStatus, errorThrown);
                    }
                });
            }

            $('#excelFile').change(function() {
                var formData = new FormData($('#uploadForm')[0]);
                uploadFiles(formData);
            });

            $('#uploadArea').on('paste', function(event) {
                var items = (event.clipboardData || event.originalEvent.clipboardData).items;
                for (var i = 0; i < items.length; i++) {
                    if (items[i].type.indexOf('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') !== -1 || items[i].type.indexOf('application/vnd.ms-excel') !== -1) {
                        var blob = items[i].getAsFile();
                        var formData = new FormData();
                        formData.append('excelFile', blob);
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
                    formData.append('excelFile', files[i]);
                }
                uploadFiles(formData);
            });
        });
    </script>
</body>
</html>