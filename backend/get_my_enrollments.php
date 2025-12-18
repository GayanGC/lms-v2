<?php
// backend/get_my_enrollments.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

// Check if the 'user_id' parameter is passed in the URL
if(isset($_GET['user_id'])) {
    try {
        // Initialize Database connection
        $database = new Database();
        $conn = $database->getConnection();

        // FIXED QUERY: Added 'courses.id' to the SELECT list.
        // NOTE: This ID is crucial for the "View Materials" button to work. 
        // Without it, the frontend doesn't know which course's files to load.
        $query = "SELECT courses.id, courses.title, courses.description, courses.duration, users.name as instructor_name 
                  FROM enrollments 
                  JOIN courses ON enrollments.course_id = courses.id 
                  LEFT JOIN users ON courses.instructor_id = users.id 
                  WHERE enrollments.user_id = :uid 
                  ORDER BY enrollments.enrolled_at DESC";

        $stmt = $conn->prepare($query);
        // Bind the user ID securely to prevent SQL injection
        $stmt->bindParam(":uid", $_GET['user_id']);
        $stmt->execute();

        // Fetch all matching records
        $enrolledCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the list of courses as JSON
        echo json_encode(["status" => "success", "data" => $enrolledCourses]);

    } catch (Exception $e) {
        // Handle database errors
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    // Return error if user_id is missing
    echo json_encode(["status" => "error", "message" => "User ID missing."]);
}
?>