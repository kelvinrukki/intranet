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

// Check if the book is already borrowed by the user
$sqlCheck = "SELECT * FROM borrowings WHERE book_id=? AND user_id=?";
$stmtCheck = $connect->prepare($sqlCheck);
$stmtCheck->bind_param("ii", $book_id, $user_id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    header("HTTP/1.1 400");
    echo json_encode([
        "status" => "failed",
        "message" => "Book is already being borrowed by you!"
    ]);
    exit;
}
// Check if the book is available
$sqlCheck = "SELECT quantity FROM books WHERE id=?";
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

$bookQuantity = $resultCheck->fetch_assoc()["quantity"];
if ($bookQuantity == 0) {
    header("HTTP/1.1 400");
    echo json_encode([
        "status" => "failed",
        "message" => "Book is not available"
    ]);
    exit;
}

// Record the borrowing
$borrowed_date = date("Y-m-d");
$return_date = date("Y-m-d", strtotime("+7 days"));
$sqlStatement = "INSERT INTO borrowings (book_id, user_id, borrowed_date, return_date) VALUES (?, ?, ?, ?)";
$stmtInsert = $connect->prepare($sqlStatement);
$stmtInsert->bind_param("iiss", $book_id, $user_id, $borrowed_date, $return_date);
$execInsert = $stmtInsert->execute();

if ($execInsert) {
    $sqlUpdate = "UPDATE books SET quantity=quantity-1 WHERE id=?";
    $stmtUpdate = $connect->prepare($sqlUpdate);
    $stmtUpdate->bind_param("i", $book_id);
    $execUpdate = $stmtUpdate->execute();

    if ($execUpdate) {
        header("HTTP/1.1 200");
        echo json_encode([
            "status" => "success"
        ]);
    } else {
        header("HTTP/1.1 500");
        echo json_encode([
            "status" => "failed",
            "message" => "An error occurred"
        ]);
    }
} else {
    header("HTTP/1.1 500");
    echo json_encode([
        "status" => "failed",
        "message" => "An error occurred"
    ]);
}
