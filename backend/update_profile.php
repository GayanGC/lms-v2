<?php
// backend/update_profile.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    // Expect multipart/form-data
    $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    if ($userId <= 0) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Missing user_id"]);
        exit;
    }

    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;
    $address = isset($_POST['address']) ? trim($_POST['address']) : null;
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : null;

    $database = new Database();
    $conn = $database->getConnection();

    // Handle file upload
    $profilePicPath = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/uploads/profiles/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $tmpName = $_FILES['profile_pic']['tmp_name'];
        $origName = basename($_FILES['profile_pic']['name']);
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $allowed)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Invalid image type"]);
            exit;
        }
        $filename = 'user_' . $userId . '_' . time() . '.' . $ext;
        $dest = $uploadDir . $filename;
        if (!move_uploaded_file($tmpName, $dest)) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Failed to save image"]);
            exit;
        }
        // Web path
        $profilePicPath = 'assets/uploads/profiles/' . $filename;
    }

    // Build update query dynamically
    $fields = [];
    $params = [':id' => $userId];
    if ($name !== null) { $fields[] = 'name = :name'; $params[':name'] = $name; }
    if ($email !== null) { $fields[] = 'email = :email'; $params[':email'] = $email; }
    if ($phone !== null) { $fields[] = 'phone = :phone'; $params[':phone'] = $phone; }
    if ($address !== null) { $fields[] = 'address = :address'; $params[':address'] = $address; }
    if ($bio !== null) { $fields[] = 'bio = :bio'; $params[':bio'] = $bio; }
    if ($profilePicPath !== null) { $fields[] = 'profile_pic = :pic'; $params[':pic'] = $profilePicPath; }

    if (empty($fields)) {
        echo json_encode(["status" => "error", "message" => "No fields to update"]);
        exit;
    }

    $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
    $stmt = $conn->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->execute();

    // Log activity
    $changed = array_keys(array_diff_key($params, [':id' => true]));
    logActivity($userId, 'Updated Profile', 'Fields: ' . implode(', ', array_map(function($f){return str_replace(':','',$f);} , $changed)));

    echo json_encode(["status" => "success", "message" => "Profile updated", "profile_pic" => $profilePicPath]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>