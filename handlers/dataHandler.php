<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newProductsFile = $_FILES['newProducts']['tmp_name'];
    $currentProductsFile = $_FILES['currentProducts']['tmp_name'];

    $newProducts = loadSpreadsheet($newProductsFile);
    $currentProducts = loadSpreadsheet($currentProductsFile);

    $comparisonResult = differenceProducts($newProducts, $currentProducts);
    $available = availableProducts($newProducts, $currentProducts);

    // Set headers before any output
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="products.xlsx"');
    header('Cache-Control: max-age=0');

    // Create the spreadsheet and write data
    $spreadsheet = new Spreadsheet();

    //INto another sheet
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Available Products'); 
    $sheet->fromArray($available, null, 'A1');
    $sheet->getStyle('A1:A' . (count($available) + 1))->getNumberFormat()->setFormatCode('0');

    foreach (range('A', $sheet->getHighestDataColumn()) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }


    $sheet = $spreadsheet->createSheet(); // Create a new sheet
    $sheet->setTitle('Not available Products');
    $sheet->fromArray($comparisonResult, null, 'A1');
    $sheet->getStyle('A1:A' . (count($comparisonResult) + 1))->getNumberFormat()->setFormatCode('0');
    foreach (range('A', $sheet->getHighestDataColumn()) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }


    // Write the spreadsheet to output
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit; // Ensure no further output is sent

    //Redirect to upload page
    // header('Location: ../products.php');

    //Below html code will not be executed
    // Handle the comparison result as needed
    // echo "<table border='1'>";
    // $id = 1;

    // foreach ($comparisonResult as $product) {
    //     echo "<tr>";
    //     echo "<td>" . $id . "</td>";
    //     foreach ($product as $value) {
    //         echo "<td>$value</td>";
    //     }
    //     $id++;
    //     echo "</tr>";
    // }
    // echo "</table>";

    // // Handle the comparison result as needed
    // echo "<table border='1'>";
    // $id = 1;
    // foreach ($available as $product) {
    //     echo "<tr>";
    //     echo "<td>" . $id . "</td>";
    //     foreach ($product as $value) {
    //         echo "<td>$value</td>";
    //     }
    //     $id++;
    //     echo "</tr>";
    // }
    // echo "</table>";



}

function loadSpreadsheet($filePath)
{
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    $data = [];
    foreach ($worksheet->getRowIterator() as $row) {
        $rowData = [];
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue();
        }
        $data[] = $rowData;
    }
    return $data;
}

function differenceProducts($newProducts, $currentProducts)
{
    $result = [];
    foreach ($newProducts as $newProduct) {
        $found = false;
        foreach ($currentProducts as $currentProduct) {
            if ($newProduct[0] == $currentProduct[0]) { // Assuming the first column is the product ID
                $found = true;
                break;
            }
        }
        if (!$found) {
            $result[] = $newProduct;
        }
    }
    return $result;
}


function availableProducts($newProducts, $currentProducts)
{
    $result = [];
    foreach ($newProducts as $newProduct) {
        $found = false;
        foreach ($currentProducts as $currentProduct) {
            if ($newProduct[0] == $currentProduct[0]) { // Assuming the first column is the product ID
                // $result[] = $newProduct;
                //merge the two arrays of the current product and the new product at the found index
                $result[] = array_merge($newProduct, $currentProduct);
                break;
            }
        }
    }
    return $result;
}
