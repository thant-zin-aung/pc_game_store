<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $response = [
            'status' => 'success',
            'message' => 'Data received successfully',
            'data' => $data
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        // Handle JSON decoding error
        $response = [
            'status' => 'error',
            'message' => 'Invalid JSON data'
        ];
    }
} else {
    // Handle non-POST requests
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests are allowed']);
}
?>