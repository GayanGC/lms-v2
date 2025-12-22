<?php
// backend/delete_user.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';
require_once 'helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['user_id'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Missing user_id"]);
        exit;
    }

    $userId = (int)$input['user_id'];

    $database = new Database();
    $conn = $database->getConnection();

    // Prevent deleting admins for safety
    $roleStmt = $conn->prepare("SELECT role FROM users WHERE id = :id");
    $roleStmt->bindParam(":id", $userId, PDO::PARAM_INT);
    $roleStmt->execute();
    $role = $roleStmt->fetchColumn();
    if ($role === false) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }
    if ($role === 'admin') {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Cannot delete admin users"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Activity log: actor may be provided from frontend as actor_id (admin)
    if (isset($input['actor_id'])) {
        logActivity((int)$input['actor_id'], 'Deleted User', 'Deleted user ID: ' . $userId);
    }

    echo json_encode(["status" => "success", "message" => "User deleted"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>