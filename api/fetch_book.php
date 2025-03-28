<?php
include_once "db_connection.php";
header('Content-Type: application/json');

$book_id = $_GET["book_id"] ?? '';

if (empty($book_id)) {
    header("HTTP/1.1 400");
    echo json_encode([
        "status" => "failed",
        "message" => "Book ID is required"
    ]);
    exit;
}

// Fetch book by ID
$sqlStatement = "SELECT * FROM books WHERE id=?";
$stmt = $connect->prepare($sqlStatement);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();

    // Fetch related books (same category)
    $sqlStatement = "SELECT * FROM books WHERE category=? AND id!=?";
    $stmt = $connect->prepare($sqlStatement);
    $stmt->bind_param("si", $book['category'], $book_id);
    $stmt->execute();
    $related_books = $stmt->get_result()->fetch_assoc();

    header("HTTP/1.1 200");
    echo json_encode([
        "status" => "success",
        "book" => $book,
        "related_books" => $related_books
    ]);
} else {
    header("HTTP/1.1 404");
    echo json_encode([
        "status" => "failed",
        "message" => "Book record not found!"
    ]);
}
