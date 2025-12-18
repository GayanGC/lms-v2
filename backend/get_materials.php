<?php
// backend/get_materials.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

// Check if 'course_id' is provided in the URL parameters
if(isset($_GET['course_id'])) {
    try {
        // Initialize Database connection
        $database = new Database();
        $conn = $database->getConnection();

        // FIXED: Used 'upload_date' instead of 'uploaded_at'
        // According to your database screenshot, the correct column name is 'upload_date'.
        $query = "SELECT * FROM course_materials WHERE course_id = :cid ORDER BY upload_date DESC";
        
        $stmt = $conn->prepare($query);
        // Bind the course_id parameter safely
        $stmt->bindParam(":cid", $_GET['course_id']);
        $stmt->execute();

        // Fetch all results as an associative array
        $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return success response with the list of materials
        echo json_encode(["status" => "success", "data" => $materials]);

    } catch (Exception $e) {
        // If a database error occurs, return it as JSON to avoid crashing the frontend
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    // Return error if course_id is missing from the request
    echo json_encode(["status" => "error", "message" => "Course ID missing."]);
}
?>