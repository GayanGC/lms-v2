<?php
// backend/upload_material.php
header("Access-Control-Allow-Origin: *");
// Note: We do not set 'Content-Type: application/json' here because the frontend sends form-data (multipart/form-data).

require_once 'db.php';

// Check if ALL required data is present: Course ID, PDF File, and Instructor ID
if(isset($_POST['course_id']) && isset($_FILES['pdf_file']) && isset($_POST['instructor_id'])) {
    
    // Capture form data
    $course_id = $_POST['course_id'];
    $instructor_id = $_POST['instructor_id']; // Crucial: Required to satisfy the Foreign Key constraint
    $title = $_POST['title'];
    
    // Define target directory for uploads
    $target_dir = "../assets/uploads/";
    
    // Create the directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Generate a unique filename using the current timestamp to prevent overwriting
    $file_name = time() . "_" . basename($_FILES["pdf_file"]["name"]);
    $target_file = $target_dir . $file_name;
    
    // This is the path we will save in the database (relative path)
    $db_path = "assets/uploads/" . $file_name;

    // Attempt to move the uploaded file from the temp location to our server folder
    if (move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $target_file)) {
        try {
            // Establish database connection
            $database = new Database();
            $conn = $database->getConnection();

            // Prepare the SQL INSERT query (Now includes instructor_id)
            $query = "INSERT INTO course_materials (course_id, instructor_id, title, file_path) VALUES (:cid, :iid, :title, :path)";
            $stmt = $conn->prepare($query);
            
            // Bind the parameters to the query
            $stmt->bindParam(":cid", $course_id);
            $stmt->bindParam(":iid", $instructor_id);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":path", $db_path);

            // Execute the query
            if($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "File uploaded successfully!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Database insert failed."]);
            }
        } catch (Exception $e) {
            // Handle database exceptions
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "File upload failed (Permission or path issue)."]);
    }
} else {
    // If required data is missing, send an error
    echo json_encode(["status" => "error", "message" => "Missing data. Please Logout and Login again to refresh your session."]);
}
?>