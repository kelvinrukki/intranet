<?php
include_once "../db_connection.php";
header('Content-Type: application/json');

$users = [];

$sqlstatement = "SELECT DISTINCT user_id FROM borrowings";
$execQuery = $connect->query($sqlstatement);

if ($execQuery->num_rows > 0) {
    while ($row = $execQuery->fetch_assoc()) {
        $user_id = $row["user_id"];
        $sqlstatement = "SELECT * FROM users WHERE id='$user_id'";
        $execQuery = $connect->query($sqlstatement);
        $user = $execQuery->fetch_assoc();
        $books = [];
        $sqlstatement = "SELECT b.id, b.title, b.author, b.isbn, b.published_date, b.category, b.cover_image, bo.borrowed_date, bo.return_date
                         FROM borrowings bo 
                         LEFT JOIN books b ON bo.book_id = b.id 
                         WHERE bo.user_id = '$user_id'";
        $execQuery = $connect->query($sqlstatement);
        if ($execQuery) {
            while ($book = $execQuery->fetch_assoc()) {
                array_push($books, $book);
            }
        }
        $user["borrowed_books"] = $books;
        array_push($users, $user);
    }
} else {
    header("HTTP/1.1 404");
    $response = [
        "status" => "failed",
        "message" => "No users found"
    ];
    echo json_encode($response);
    exit;
}

header("HTTP/1.1 200");
$response = [
    "status" => "success",
    "users" => $users
];
echo json_encode($response);
exit;
