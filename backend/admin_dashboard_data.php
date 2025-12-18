<?php
// backend/admin_dashboard_data.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';

$database = new Database();
$conn = $database->getConnection();

$response = [];

// 1. Pending Users
$stmt = $conn->prepare("SELECT id, name, email, role, created_at FROM users WHERE status = 'pending'");
$stmt->execute();
$response['pending_users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Counts
$stmt = $conn->prepare("SELECT 
    (SELECT COUNT(*) FROM users WHERE role='student') as total_students,
    (SELECT COUNT(*) FROM users WHERE role='instructor') as total_instructors,
    (SELECT COUNT(*) FROM users WHERE status='pending') as total_pending");
$stmt->execute();
$response['stats'] = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Chart Data (Last 7 Days)
$query = "SELECT DATE(created_at) as date, role, COUNT(*) as count 
          FROM users 
          WHERE created_at >= DATE(NOW()) - INTERVAL 7 DAY 
          GROUP BY DATE(created_at), role";
$stmt = $conn->prepare($query);
$stmt->execute();
$response['chart_data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["status" => "success", "data" => $response]);
?>