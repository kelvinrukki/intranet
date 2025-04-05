<?php
header('Content-Type: application/json');

include_once "db_connection.php";

$email = $_POST["email"] ?? '';

if (empty($email)) {
    header("HTTP/1.1 400");
    echo json_encode([
        "status" => "failed",
        "message" => "Email is required"
    ]);
    exit;
}

// Check if the email exists in the database
$sqlStatement = "SELECT * FROM users WHERE email=?";
$stmt = $connect->prepare($sqlStatement);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // get the user record
    // generate a verification token 
    $verificationToken = base64_encode($email);
    header("HTTP/1.1 200");
    echo json_encode([
        "status" => "success",
        "message" => "Email verified and exists",
        "user" => $user,
        "verification_token" => $verificationToken
    ]);
    exit;
} else {
    header("HTTP/1.1 404");
    echo json_encode([
        "status" => "failed",
        "message" => "Email not found"
    ]);
    exit;
}
