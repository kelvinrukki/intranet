<?php
// https://www.w3schools.com/php/php_mysql_prepared_statements.asp

include_once "../db_connection.php";
header('Content-Type: application/json');

$user_id = $_POST["user_id"] ?? '';
if (empty($user_id)) {
    header("HTTP/1.1 400");
    echo json_encode([
        "status" => "failed",
        "message" => "User ID is required"
    ]);
    exit;
}

// Find the user records by user_id
$sqlStatement = "SELECT * FROM users WHERE id=?";
$stmt = $connect->prepare($sqlStatement);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    // assign update variables according to request parameters or reset the user properties
    $fullname = $_POST['full_name'] ?? $user['full_name'];
    $username = $_POST['username'] ?? $user['username'];
    $email = $_POST['email'] ?? $user['email'];
    $password = $_POST['password'] ? md5($_POST['password']) : $user['password'];
    $role = $_POST['role'] ?? $user['role'];

    // Update the user record
    $sqlStatement = "UPDATE users SET full_name=?, username=?, email=?, password=?, role=? WHERE id=?";
    $stmt = $connect->prepare($sqlStatement);
    $stmt->bind_param("sssssi", $fullname, $username, $email, $password, $role, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("HTTP/1.1 200");
        echo json_encode([
            "status" => "success"
        ]);
    } else {
        header("HTTP/1.1 500");
        echo json_encode([
            "status" => "failed",
            "message" => "An error occured"
        ]);
    }
} else {
    header("HTTP/1.1 404");
    echo json_encode([
        "status" => "failed",
        "message" => "User record not found!"
    ]);
}

