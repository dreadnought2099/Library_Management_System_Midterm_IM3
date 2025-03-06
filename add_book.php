<?php include 'database.php'; ?>

<?php
$title = $author = $publication_year = $category = $isbn = $cover_image = "";
$editing = false;

// Check if editing
if (isset($_GET["id"])) {
    $editing = true;
    $id = $_GET["id"];
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();

    if ($book) {
        $title = $book["title"];
        $author = $book["author"];
        $publication_year = $book["publication_year"];
        $category = $book["category"];
        $isbn = $book["isbn"];
        $cover_image = $book["cover_image"];
    }
}

// Handle form submission (Add or Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $publication_year = $_POST["publication_year"];
    $category = $_POST["category"];
    $isbn = $_POST["isbn"];

    // Handle cover image upload
    if (!empty($_FILES["cover_image"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir);
        $cover_image = $target_dir . basename($_FILES["cover_image"]["name"]);
        move_uploaded_file($_FILES["cover_image"]["tmp_name"], $cover_image);
    }

    if ($editing) {
        // Update book
        $stmt = $conn->prepare("UPDATE books SET title=?, author=?, publication_year=?, category=?, isbn=?, cover_image=? WHERE id=?");
        $stmt->bind_param("ssisssi", $title, $author, $publication_year, $category, $isbn, $cover_image, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Add new book
        $stmt = $conn->prepare("INSERT INTO books (title, author, publication_year, category, isbn, cover_image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $title, $author, $publication_year, $category, $isbn, $cover_image);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $editing ? "Edit" : "Add" ?> Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="icon/books.png">

</head>
<body class="container mt-4">
    <h2><?= $editing ? "Edit" : "Add a New" ?> Book</h2>
    <a href="index.php" class="btn btn-secondary mb-3">Back to Books</a>

    <form action="" method="post" enctype="multipart/form-data" class="mb-3">
        <div class="row">
            <div class="col-md-2"><input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" placeholder="Title" required></div>
            <div class="col-md-2"><input type="text" name="author" class="form-control" value="<?= htmlspecialchars($author) ?>" placeholder="Author" required></div>
            <div class="col-md-2"><input type="number" name="publication_year" class="form-control" value="<?= $publication_year ?>" placeholder="Year" required></div>
            <div class="col-md-2"><input type="text" name="category" class="form-control" value="<?= htmlspecialchars($category) ?>" placeholder="Category" required></div>
            <div class="col-md-2"><input type="text" name="isbn" class="form-control" value="<?= htmlspecialchars($isbn) ?>" placeholder="ISBN" required></div>
            <div class="col-md-2">
                <input type="file" name="cover_image" class="form-control">
                <?php if ($cover_image): ?>
                    <small>Current: <img src="<?= $cover_image ?>" width="50"></small>
                <?php endif; ?>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2"><?= $editing ? "Update" : "Add" ?> Book</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
