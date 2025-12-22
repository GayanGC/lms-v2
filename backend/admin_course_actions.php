<?php
// backend/admin_course_actions.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();
    $input = json_decode(file_get_contents('php://input'), true);
    $action = isset($input['action']) ? $input['action'] : '';

    if ($action === 'delete_course') {
        if (!isset($input['course_id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Missing course_id"]);
            exit;
        }
        $courseId = (int)$input['course_id'];
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = :id");
        $stmt->bindParam(":id", $courseId, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Course deleted"]);
    }
    else if ($action === 'unenroll_student') {
        if (!isset($input['user_id'], $input['course_id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Missing user_id or course_id"]);
            exit;
        }
        $userId = (int)$input['user_id'];
        $courseId = (int)$input['course_id'];
        $stmt = $conn->prepare("DELETE FROM enrollments WHERE user_id = :uid AND course_id = :cid");
        $stmt->bindParam(":uid", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":cid", $courseId, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Student unenrolled"]);
    }
    else if ($action === 'list_students') {
        if (!isset($input['course_id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Missing course_id"]);
            exit;
        }
        $courseId = (int)$input['course_id'];
        $stmt = $conn->prepare(
            "SELECT u.id as user_id, u.name, u.email, e.status, e.enrollment_date 
             FROM enrollments e 
             JOIN users u ON e.user_id = u.id 
             WHERE e.course_id = :cid 
             ORDER BY e.enrollment_date DESC"
        );
        $stmt->bindParam(":cid", $courseId, PDO::PARAM_INT);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "data" => $students]);
    }
    else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid action"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>