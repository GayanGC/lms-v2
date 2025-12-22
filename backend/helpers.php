<?php
// backend/helpers.php
require_once __DIR__ . '/db.php';

function logActivity($user_id, $action, $details = '') {
    try {
        $database = new Database();
        $conn = $database->getConnection();
        $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (:uid, :action, :details, NOW())");
        $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $stmt->bindParam(':details', $details, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        // Swallow logging errors to not block main flow
        return false;
    }
}
?>