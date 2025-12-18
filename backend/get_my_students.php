<?php
// backend/get_my_students.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

if(isset($_GET['instructor_id'])) {
    try {
        $database = new Database();
        $conn = $database->getConnection();

        // Query: Get Student Name, Email, Course Title based on Instructor ID
        $query = "SELECT users.name as student_name, users.email, courses.title as course_title, enrollments.enrolled_at 
                  FROM enrollments 
                  JOIN courses ON enrollments.course_id = courses.id 
                  JOIN users ON enrollments.user_id = users.id 
                  WHERE courses.instructor_id = :uid 
                  ORDER BY enrollments.enrolled_at DESC";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":uid", $_GET['instructor_id']);
        $stmt->execute();

        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "data" => $students]);

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Instructor ID missing."]);
}
?>