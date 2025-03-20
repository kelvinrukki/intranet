<?php
// https://www.w3schools.com/php/php_mysql_prepared_statements.asp
include_once "../db_connection.php";
header('Content-Type: application/json');
$book_id = $_POST["book_id"];
$title = $_POST["title"] ?? '';
$author = $_POST["author"] ?? '';
$isbn = $_POST["isbn"] ?? '';
$published_date = $_POST["published_date"] ?? '';
$category = $_POST["category"] ?? '';
$quantity = $_POST["quantity"] ?? '';

// Find the book records by book_id
$sqlStatement = "SELECT * FROM books WHERE id=?";
$stmt = $connect->prepare($sqlStatement);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $book = $result->fetch_assoc();

    // assign update variables according to request parameters or reset the book properties
    $title = empty($title) ? $book['title'] : $title;
    $author = empty($author) ? $book['author'] : $author;
    $isbn = empty($isbn) ? $book['isbn'] : $isbn;
    $published_date = empty($published_date) ? $book['published_date'] : $published_date;
    $category = empty($category) ? $book['category'] : $category;
    $quantity = empty($quantity) ? $book['quantity'] : $quantity;

    $cover_image = $book['cover_image'];
    // Upload Image File
    if (!empty($_FILES['cover_image']['tmp_name'])) {
        $temp_file = $_FILES['cover_image']['tmp_name'];
        $image_name = $_FILES['cover_image']['name'];
        $image_name = str_replace(" ", "_", strtolower($image_name));
        $path = '../images/' . $image_name;
        if (move_uploaded_file($temp_file, $path)) {
            $cover_image = '../api/images/'  . $image_name;
        }
    }

    // Update the book record
    $sqlStatement = "UPDATE books SET title=?, author=?, isbn=?, published_date=?, category=?, quantity=?, cover_image=? WHERE id=?";
    $stmt = $connect->prepare($sqlStatement);
    $stmt->bind_param("sssssisi", $title, $author, $isbn, $published_date, $category, $quantity, $cover_image, $book_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("HTTP/1.1 200");
        echo json_encode([
            "status" => "success"
        ]);
    } else {
        header("HTTP/1.1 500");
        echo json_encode([
            "status" => "failed",
            "message" => "An error occurred. No changes was made.",
            "error" => $connect->error
        ]);
    }
} else {
    header("HTTP/1.1 404");
    echo json_encode([
        "status" => "failed",
        "message" => "Book record not found!"
    ]);
}
