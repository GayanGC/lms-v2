<?php
// backend/admin_analytics.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';

$database = new Database();
$conn = $database->getConnection();

$response = [];

try {
    // 1. Get Pending Users List (For the Table)
    // We fetch only users who are waiting for approval
    $pendingQuery = "SELECT id, name, email, role, created_at FROM users WHERE status = 'pending' ORDER BY created_at DESC";
    $stmt = $conn->prepare($pendingQuery);
    $stmt->execute();
    $response['pending_users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Get Daily Growth (Last 30 Days) - Approved Users Only
    // Grouped by Date and Role (Student/Instructor)
    $dailyQuery = "SELECT DATE(created_at) as date, role, COUNT(*) as count 
                   FROM users 
                   WHERE status = 'approved' AND created_at >= DATE(NOW()) - INTERVAL 30 DAY 
                   GROUP BY DATE(created_at), role 
                   ORDER BY date ASC";
    $stmt = $conn->prepare($dailyQuery);
    $stmt->execute();
    $response['daily_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Get Monthly Growth (Last 12 Months)
    $monthlyQuery = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, role, COUNT(*) as count 
                     FROM users 
                     WHERE status = 'approved' AND created_at >= DATE(NOW()) - INTERVAL 12 MONTH 
                     GROUP BY month, role 
                     ORDER BY month ASC";
    $stmt = $conn->prepare($monthlyQuery);
    $stmt->execute();
    $response['monthly_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Get Yearly Growth
    $yearlyQuery = "SELECT YEAR(created_at) as year, role, COUNT(*) as count 
                    FROM users 
                    WHERE status = 'approved' 
                    GROUP BY year, role 
                    ORDER BY year ASC";
    $stmt = $conn->prepare($yearlyQuery);
    $stmt->execute();
    $response['yearly_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $response]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>