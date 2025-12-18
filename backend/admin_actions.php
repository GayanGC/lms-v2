<?php
// backend/admin_actions.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';

// Get the action type from URL (e.g., ?action=get_pending)
$action = isset($_GET['action']) ? $_GET['action'] : '';

$database = new Database();
$conn = $database->getConnection();

// ACTION 1: Fetch all users with 'pending' status
if ($action == 'get_pending') {
    $query = "SELECT id, name, email, role, created_at FROM users WHERE status = 'pending'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $users]);
} 

// ACTION 2: Approve a specific user
else if ($action == 'approve') {
    // Get User ID from request body
    $data = json_decode(file_get_contents("php://input"));
    
    if(isset($data->user_id)) {
        // Update status to 'approved'
        $query = "UPDATE users SET status = 'approved' WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $data->user_id);
        
        if($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "User approved successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to approve user."]);
        }
    }
}
?>