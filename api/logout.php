<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <script>
        // Remove localStorage item when user logs out
        localStorage.removeItem("currentuser");
        location.href = "../index.html";
    </script>
</head>
</html> 

