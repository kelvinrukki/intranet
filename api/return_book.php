<?php
include_once "db_connection.php";
header('Content-Type: application/json');

$book_id = $_POST["book_id"] ?? '';
$user_id = $_POST["user_id"] ?? '';

if (empty($book_id) || empty($user_id)) {
    header("HTTP/1.1 400");
    echo json_encode([
        "status" => "failed",
        "message" => "Book ID and User ID are required"
    ]);
    exit;
}

// Check if the book record exists
$sqlCheck = "SELECT * FROM books WHERE id=?";
$stmtCheck = $connect->prepare($sqlCheck);
$stmtCheck->bind_param("i", $book_id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows == 0) {
    header("HTTP/1.1 404");
    echo json_encode([
        "status" => "failed",
        "message" => "Book record not found!"
    ]);
    exit;
}

// Check if the user record exists
$sqlCheck = "SELECT * FROM users WHERE id=?";
$stmtCheck = $connect->prepare($sqlCheck);
$stmtCheck->bind_param("i", $user_id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows == 0) {
    header("HTTP/1.1 404");
    echo json_encode([
        "status" => "failed",
        "message" => "User record not found!"
    ]);
    exit;
}

// Update book quantity
$sqlUpdate = "UPDATE books SET quantity=quantity+1 WHERE id=?";
$stmtUpdate = $connect->prepare($sqlUpdate);
$stmtUpdate->bind_param("i", $book_id);
$execUpdate = $stmtUpdate->execute();

if ($execUpdate) {
    // Delete the borrowed record
    $sqlDelete = "DELETE FROM borrowings WHERE book_id=? AND user_id=?";
    $stmtDelete = $connect->prepare($sqlDelete);
    $stmtDelete->bind_param("ii", $book_id, $user_id);
    $execDelete = $stmtDelete->execute();

    if ($execDelete) {
        header("HTTP/1.1 200");
        echo json_encode([
            "status" => "success"
        ]);
    } else {
        header("HTTP/1.1 500");
        echo json_encode([
            "status" => "failed",
            "message" => "An error occurred while deleting borrowed record"
        ]);
    }
} else {
    header("HTTP/1.1 500");
    echo json_encode([
        "status" => "failed",
        "message" => "An error occurred while updating book quantity"
    ]);
}
