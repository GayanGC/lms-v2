<?php
// backend/login.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

$data = json_decode(file_get_contents("php://input"));

if(isset($data->email) && isset($data->password)) {
    $database = new Database();
    $conn = $database->getConnection();

    // 1. Search for user by email
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $data->email);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 2. Verify password
        if(password_verify($data->password, $row['password'])) {
            
            // 3. CHECK APPROVAL STATUS (UPDATED SECTION)
            // If the status is 'pending', stop the login process.
            if (isset($row['status']) && $row['status'] === 'pending') {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Your account is pending Admin approval. Please wait."
                ]);
            } else {
                // Account is approved: Proceed with login
                
                // Remove password from response data for security
                unset($row['password']); 

                echo json_encode([
                    "status" => "success",
                    "message" => "Login successful.",
                    "user" => $row // Important! Contains id, name, role, status
                ]);
            }

        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete data."]);
}
?>