<?php
// backend/create_course.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';
require_once 'helpers.php';

$data = json_decode(file_get_contents("php://input"));

if(isset($data->title) && isset($data->instructor_id)) {
    try {
        $database = new Database();
        $conn = $database->getConnection();

        $query = "INSERT INTO courses (title, description, duration, instructor_id) VALUES (:title, :desc, :dur, :uid)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(":title", $data->title);
        $stmt->bindParam(":desc", $data->description);
        $stmt->bindParam(":dur", $data->duration);
        $stmt->bindParam(":uid", $data->instructor_id);

        if($stmt->execute()) {
            // Log activity: Instructor created a new course
            if(isset($data->instructor_id)) {
                $title = isset($data->title) ? $data->title : '';
                logActivity((int)$data->instructor_id, 'Created Course', 'Title: ' . $title);
            }
            echo json_encode(["status" => "success", "message" => "Course created."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete data."]);
}
?>