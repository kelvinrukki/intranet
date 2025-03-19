<?php
include_once "../db_connection.php";
header('Content-Type: application/json');
$user_id=$_POST["user_id"];

// Find the user records by user_id
$sqlstatement="DELETE FROM users WHERE id='$user_id'";
$execDeleteQuery=$connect->query($sqlstatement);

if($execDeleteQuery){
    header("HTTP/1.1 200");
    echo json_encode([
        "status"=>"success"
    ]);
}else{
    header("HTTP/1.1 500");
    echo json_encode([
        "status"=>"failed",
        "message"=>"An error occured"
    ]);
}

