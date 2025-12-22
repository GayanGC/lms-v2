<?php
// backend/get_all_courses.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT c.id, c.title, c.description, c.created_at, u.name AS instructor_name, u.id AS instructor_id,
                     (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) AS student_count
              FROM courses c
              LEFT JOIN users u ON c.instructor_id = u.id
              ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $courses]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>