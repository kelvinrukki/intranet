<?php
// https://www.w3schools.com/php/php_mysql_prepared_statements.asp
include_once "../db_connection.php";
header('Content-Type: application/json');
$book_id = $_POST["book_id"];

// Delete the book record
$sqlStatement = "DELETE FROM books WHERE id=?";
$stmt = $connect->prepare($sqlStatement);
$stmt->bind_param("i", $book_id);
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
        "message" => "An error occurred"
    ]);
}

