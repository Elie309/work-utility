<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $templateName = $data['templateName'];
    $fields = $data['fields'];

    if (!is_dir('templates')) {
        mkdir('templates', 0777, true);
    }

    $filePath = "../templates/{$templateName}.json";
    if (file_put_contents($filePath, json_encode($fields, JSON_PRETTY_PRINT)) === false) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to save template']);
        exit;
    }

    http_response_code(200);
    echo json_encode([
        'message' => 'Template saved successfully',
        "templateName" => $templateName,
        "fields" => $fields
    ]);
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
