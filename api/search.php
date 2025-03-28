<?php
include_once "db_connection.php";
header('Content-Type: application/json');

$search = $_GET["search"] ?? '';

$sqlStatement = "SELECT * FROM books";
if (!empty($search)) {
    $search = "%{$search}%";
    $sqlStatement .= " WHERE title LIKE ? OR isbn LIKE ? OR author LIKE ? OR category LIKE ?";
}

$stmt = $connect->prepare($sqlStatement);

if (!empty($search)) {
    $stmt->bind_param("ssss", $search, $search, $search, $search);
}

$stmt->execute();
$result = $stmt->get_result();

$books = [];
if ($result->num_rows > 0) {
    while($book = $result->fetch_assoc()){
        array_push($books, $book);
    }
    header("HTTP/1.1 200");
    echo json_encode([
        "status"=>"success",
        "books" => $books
    ]);
} else {
    header("HTTP/1.1 404");
    echo json_encode([
        "status"=>"failed",
        "message"=>"No books found"
    ]);
}

