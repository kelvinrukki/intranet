<?php
include_once "db_connection.php";
header('Content-Type: application/json');

$user_id = $_GET["user_id"] ?? '';

if (empty($user_id)) {
    header("HTTP/1.1 400");
    echo json_encode([
        "status" => "failed",
        "message" => "User ID is required"
    ]);
    exit;
}

// Fetch borrowed books by user
$sqlStatement = "SELECT books.*, borrowings.borrowed_date, borrowings.return_date FROM borrowings JOIN books ON borrowings.book_id = books.id WHERE borrowings.user_id=?";
$stmt = $connect->prepare($sqlStatement);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$borrowedBooks = [];
if ($result->num_rows > 0) {
    while ($book = $result->fetch_assoc()) {
        array_push($borrowedBooks, $book);
    }
    header("HTTP/1.1 200");
    echo json_encode([
        "status" => "success",
        "borrowedBooks" => $borrowedBooks
    ]);
} else {
    header("HTTP/1.1 404");
    echo json_encode([
        "status" => "failed",
        "message" => "No borrowed books found for this user"
    ]);
}

?>

