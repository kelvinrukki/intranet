<?php
include_once "../db_connection.php";
header('Content-Type: application/json');
$fullname=$_POST["full_name"];
$username=$_POST["username"];
$email=$_POST["email"];
$password=md5($_POST["password"]);
$role=$_POST["role"];

//check if the user already exist
$sqlstatement2="select * from users where username='$username' or email='$email'";
$execCheckQuery=$connect->query($sqlstatement2);
if($execCheckQuery->num_rows > 0){
    header("HTTP/1.1 400");
    echo json_encode([
        "status"=>"failed",
        "message"=>"User already exists"
    ]);
    exit;
}

// submit data to database
$sqlstatement="insert into users(full_name, username, email, password, role) 
                value('$fullname', '$username', '$email', '$password', '$role')";

$execQuery=$connect->query($sqlstatement);

if($execQuery){
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