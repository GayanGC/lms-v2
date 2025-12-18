<?php
// backend/register.php
header('Content-Type: application/json');
require_once 'db.php';

// Function to validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// 1. Validate input fields
if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['role'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

// 2. Validate email format
if (!validateEmail($data['email'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
    exit;
}

// 3. Validate role (Only student and instructor allowed)
$allowed_roles = ['student', 'instructor'];
if (!in_array(strtolower($data['role']), $allowed_roles)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid role specified']);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    // 4. Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $check_email->bindParam(':email', $data['email']);
    $check_email->execute();

    if ($check_email->rowCount() > 0) {
        http_response_code(409); // Conflict
        echo json_encode(['status' => 'error', 'message' => 'Email already registered']);
        exit;
    }

    // 5. Hash the password
    $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);

    // 6. Insert new user with 'pending' status (UPDATED SECTION)
    // We explicitly add 'pending' to the status column here.
    $query = "INSERT INTO users (name, email, password, role, status) VALUES (:name, :email, :password, :role, 'pending')";
    
    $stmt = $conn->prepare($query);
    
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $data['role']);

    if ($stmt->execute()) {
        http_response_code(201); // Created
        // UPDATED MESSAGE: Inform user about approval requirement
        echo json_encode([
            'status' => 'success', 
            'message' => 'Registration successful! Please wait for Admin approval.'
        ]);
    } else {
        throw new Exception('Failed to register user');
    }

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred during registration. Please try again.'
    ]);
    error_log("Registration Error: " . $e->getMessage());
}
?>