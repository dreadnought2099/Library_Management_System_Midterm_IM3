<?php include 'database.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $publication_year = $_POST["publication_year"];
    $category = $_POST["category"];
    $isbn = $_POST["isbn"];

    // File Upload Handling
    $cover_image = "";
    if (!empty($_FILES["cover_image"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir);
        $cover_image = $target_dir . basename($_FILES["cover_image"]["name"]);
        move_uploaded_file($_FILES["cover_image"]["tmp_name"], $cover_image);
    }

    // Secure Insert
    $stmt = $conn->prepare("INSERT INTO books (title, author, publication_year, category, isbn, cover_image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisss", $title, $author, $publication_year, $category, $isbn, $cover_image);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Add a New Book</h2>
    <a href="index.php" class="btn btn-secondary mb-3">Back to Books</a>

    <form action="" method="post" enctype="multipart/form-data" class="mb-3">
        <div class="row">
            <div class="col-md-2"><input type="text" name="title" class="form-control" placeholder="Title" required></div>
            <div class="col-md-2"><input type="text" name="author" class="form-control" placeholder="Author" required></div>
            <div class="col-md-2"><input type="number" name="publication_year" class="form-control" placeholder="Year" required></div>
            <div class="col-md-2"><input type="text" name="category" class="form-control" placeholder="Category" required></div>
            <div class="col-md-2"><input type="text" name="isbn" class="form-control" placeholder="ISBN" required></div>
            <div class="col-md-2"><input type="file" name="cover_image" class="form-control"></div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Add Book</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
