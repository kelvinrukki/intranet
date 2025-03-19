<?php
// https://www.w3schools.com/php/php_mysql_prepared_statements.asp
include_once "../db_connection.php";
header('Content-Type: application/json');
$title = $_POST["title"];
$author = $_POST["author"];
$isbn = $_POST["isbn"];
$published_date = $_POST["published_date"];
$category = $_POST["category"];

// Check if the book already exists
$sqlCheck = "SELECT * FROM books WHERE isbn=?";
$stmtCheck = $connect->prepare($sqlCheck);
$stmtCheck->bind_param("s", $isbn);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    header("HTTP/1.1 400");
    echo json_encode([
        "status" => "failed",
        "message" => "Book already exists"
    ]);
    exit;
}

// Submit data to database
$sqlInsert = "INSERT INTO books (title, author, isbn, published_date, category) VALUES (?, ?, ?, ?, ?)";
$stmtInsert = $connect->prepare($sqlInsert);
$stmtInsert->bind_param("sssss", $title, $author, $isbn, $published_date, $category);
$execInsert = $stmtInsert->execute();

if ($execInsert) {
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
