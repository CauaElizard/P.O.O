<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../models/UserSettings.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$user_id = $input['user_id'] ?? null;
$new_email = $input['new_email'] ?? null;

if (!$user_id || !$new_email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'User ID and new email are required']);
    exit;
}

if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

try {
    $userSettings = new UserSettings();
    $result = $userSettings->updateEmail($user_id, $new_email);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Email updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update email']);
    }
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'já está em uso') !== false) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Internal server error']);
    }
}
?>
