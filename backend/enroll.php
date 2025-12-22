<?php
// backend/enroll.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';
require_once 'helpers.php';

$data = json_decode(file_get_contents("php://input"));

if(isset($data->user_id) && isset($data->course_id)) {
    try {
        $database = new Database();
        $conn = $database->getConnection();

        // 1. කලින් Enroll වෙලාද බලන්න (Duplicate Check)
        $checkQuery = "SELECT * FROM enrollments WHERE user_id = :uid AND course_id = :cid";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(":uid", $data->user_id);
        $checkStmt->bindParam(":cid", $data->course_id);
        $checkStmt->execute();

        if($checkStmt->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "Already enrolled!"]);
        } else {
            // 2. Enroll වෙලා නැත්නම් Enroll කරන්න
            $query = "INSERT INTO enrollments (user_id, course_id) VALUES (:uid, :cid)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":uid", $data->user_id);
            $stmt->bindParam(":cid", $data->course_id);

            if($stmt->execute()) {
                // Log activity for the student
                logActivity((int)$data->user_id, 'Enrolled Course', 'Course ID: ' . (int)$data->course_id);
                echo json_encode(["status" => "success", "message" => "Enrollment successful!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Enrollment failed."]);
            }
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete data."]);
}
?>