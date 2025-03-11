<?php
// https://www.geeksforgeeks.org/how-to-send-http-response-code-in-php/

include_once "db_connection.php";
header('Content-Type: application/json');
$username=$_POST["username"];
$password=md5($_POST["password"]);

//check if the user already exist


// submit data to database
$sqlstatement="select * from users where username='$username' and password='$password'";

$execQuery=$connect->query($sqlstatement);

if($execQuery->num_rows > 0){
    $user=$execQuery->fetch_assoc();
    header("HTTP/1.1 200");
    echo json_encode([
        "status"=>"success",
        "user"=>$user
    ]);
}else{
    header("HTTP/1.1 400");
    echo json_encode([
        "status"=>"failed",
        "message"=>"Invalid username or password"
    ]);
}
?>