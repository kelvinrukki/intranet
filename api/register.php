<?php
include_once "db_connection.php";
header('Content-Type: application/json');
$email=$_POST["email"];
$fullname=$_POST["full_name"];
$username=$_POST["username"];
$password=md5($_POST["password"]);
$role=$_POST["role"];

//check if the user already exist


// submit data to database
$sqlstatement="insert into users(full_name, username, email, password, role) value('$fullname',
'$username', '$email', '$password', '$role')";

$execQuery=$connect->query($sqlstatement);

if($execQuery){
    echo json_encode([
        "status"=>"success"
    ]);
}else{
    echo json_encode([
        "status"=>"failed",
        "message"=>"An error occured"
    ]);
}
?>