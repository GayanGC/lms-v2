<?php
// backend/get_my_courses.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';

if(isset($_GET['instructor_id'])) {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT * FROM courses WHERE instructor_id = :uid ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":uid", $_GET['instructor_id']);
    $stmt->execute();
    
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $courses]);
} else {
    echo json_encode(["status" => "error", "message" => "No instructor ID."]);
}
?>