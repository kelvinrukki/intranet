<?php
header('Content-Type: application/json');

include_once "db_connection.php";
$token = $_POST["token"] ?? '';
$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';

if (empty($token)) {
    header("HTTP/1.1 401");
    echo json_encode([
        "status" => "failed",
        "message" => "Unauthorized access"
    ]);
    exit;
}

$tokenDecoded = base64_decode($token);
if ($tokenDecoded !== $email) {
    header("HTTP/1.1 401");
    echo json_encode([
        "status" => "failed",
        "message" => "Invalid access token detected"
    ]);
    exit;
}

if (empty($email) || empty($password)) {
    header("HTTP/1.1 400");
    echo json_encode([
        "status" => "failed",
        "message" => "Could not reset password. Detected missing parameters"
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
    $user = $result->fetch_assoc();
    $newPassword = md5($password);
    $sqlStatement = "UPDATE users SET password=? WHERE id=?";
    $stmt = $connect->prepare($sqlStatement);
    $stmt->bind_param("si", $newPassword, $user['id']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("HTTP/1.1 200");
        echo json_encode([
            "status" => "success",
            "message" => "Password updated successfully"
        ]);
    } else {
        header("HTTP/1.1 500");
        echo json_encode([
            "status" => "failed",
            "message" => "An error occured while updating password"
        ]);
    }
} else {
    header("HTTP/1.1 404");
    echo json_encode([
        "status" => "failed",
        "message" => "Email not found"
    ]);
}

