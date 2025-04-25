<?php
header('Content-Type: application/json');

// Process the request
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['field1']) && isset($data['field2'])) {

        $response['status'] = 'success';
        $response['message'] = 'Data processed successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid input data';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

// Send the response
echo json_encode($response);
exit;
?>