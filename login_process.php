<?php
session_start();

$host = "chapalang-database.clupov6r97vc.us-east-1.rds.amazonaws.com";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$identifier = $_POST['identifier']; 
$password = $_POST['password']; 

$sql = "SELECT * FROM customer WHERE cust_email = ? OR cust_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $identifier, $identifier);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($user['status'] == 0) {
        $stmt->close();
        $conn->close();

        echo json_encode([
            'status' => 'error',
            'field' => 'identifier',
            'message' => 'Your account is inactive.'
        ]);
        exit(); 
    }

    if (password_verify($password, $user['cust_psw'])) {
        // Save session data
        $_SESSION['user_id'] = $user['customer_id']; 
        $_SESSION['username'] = $user['cust_username']; 
        $_SESSION['email'] = $user['cust_email'];

        $stmt->close();
        $conn->close();

        echo json_encode([
            'status' => 'success',
            'username' => $user['cust_username'],
            'redirect' => 'homepage.php'
        ]);
        exit(); 
    } else {
        $stmt->close();
        $conn->close();

        echo json_encode([
            'status' => 'error',
            'field' => 'password',
            'message' => 'Incorrect password.'
        ]);
        exit();
    }
} else {
    $stmt->close();
    $conn->close();

    echo json_encode([
        'status' => 'error',
        'field' => 'identifier',
        'message' => 'No account found with that email or username.'
    ]);
    exit(); 
}
?>
