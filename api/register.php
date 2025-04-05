<?php
include_once "db_connection.php";
header('Content-Type: application/json');
$email=$_POST["email"];
$fullname=$_POST["full_name"];
$username=$_POST["username"];
$password=md5($_POST["password"]);

//check if this is the first user
$sqlstatement="select * from users";
$execQuery=$connect->query($sqlstatement);
if ($execQuery->num_rows==0){
    $role='admin';
}
else{
    $role='user';
}

//check if the user already exist
$sqlstatement="select * from users where email='$email'";
$execQuery=$connect->query($sqlstatement);
if ($execQuery->num_rows > 0){
    echo json_encode([
        "status"=>"failed",
        "message"=>"User already exist"
    ]);
    exit;
}

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