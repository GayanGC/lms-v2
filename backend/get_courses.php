<?php
// backend/get_courses.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    // SQL Query: Courses ගන්න ගමන් Instructor ගේ නම (users table එකෙන්) ගන්න
    $query = "SELECT courses.id, courses.title, courses.description, courses.duration, users.name as instructor_name 
              FROM courses 
              LEFT JOIN users ON courses.instructor_id = users.id 
              ORDER BY courses.created_at DESC";
              
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Data ආවා නම් යවන්න, නැත්නම් හිස් Array එකක් යවන්න
    echo json_encode(["status" => "success", "data" => $courses]);

} catch (Exception $e) {
    // Error එකක් ආවොත් Frontend එකට කියන්න
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>