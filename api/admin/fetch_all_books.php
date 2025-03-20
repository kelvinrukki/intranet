<?php
include_once "../db_connection.php";
header('Content-Type: application/json');

$books = [];

// submit data to database
$sqlstatement = "SELECT * FROM books ORDER BY title ASC";

$execQuery = $connect->query($sqlstatement);

if($execQuery){
    while($book = $execQuery->fetch_assoc()){
        array_push($books, $book);
    }
    header("HTTP/1.1 200");
    echo json_encode([
        "status"=>"success",
        "books" => $books
    ]);
}else{
    header("HTTP/1.1 500");
    echo json_encode([
        "status"=>"failed",
        "message"=>"An error occured"
    ]);
}