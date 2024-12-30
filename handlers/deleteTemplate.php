<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $templateName = $data['templateName'];

    $filePath = "../templates/{$templateName}.json";
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            http_response_code(200);
            echo json_encode(['message' => 'Template deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to delete template']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Template not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
