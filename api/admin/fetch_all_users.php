<?php
include_once "../db_connection.php";
header('Content-Type: application/json');

$users = [];

// submit data to database
$sqlstatement="SELECT * FROM users ORDER BY full_name ASC";

$execQuery=$connect->query($sqlstatement);

if($execQuery){
    while($user = $execQuery->fetch_assoc()){
        array_push($users, $user);
    }
    header("HTTP/1.1 200");
    echo json_encode([
        "status"=>"success",
        "users" => $users
    ]);
}else{
    header("HTTP/1.1 500");
    echo json_encode([
        "status"=>"failed",
        "message"=>"An error occured"
    ]);
}