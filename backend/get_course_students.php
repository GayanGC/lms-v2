<?php
// backend/get_course_students.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . '/db.php';

try {
    $courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
    if ($courseId <= 0) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Missing course_id"]);
        exit;
    }

    $database = new Database();
    $conn = $database->getConnection();

    $sql = "SELECT e.user_id, u.name, u.email, e.status
            FROM enrollments e
            INNER JOIN users u ON u.id = e.user_id
            WHERE e.course_id = :cid
            ORDER BY u.name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cid', $courseId, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $rows]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>