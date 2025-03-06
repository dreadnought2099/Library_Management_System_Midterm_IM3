<?php include 'database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Library Book Management</h2>
    <a href="add_book.php" class="btn btn-success mb-3">Add New Book</a>

    <!-- Book List -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Category</th>
                <th>ISBN</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM books");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td>
                        <?php if ($row["cover_image"]): ?>
                            <img src="<?= $row["cover_image"] ?>" width="50">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row["title"]) ?></td>
                    <td><?= htmlspecialchars($row["author"]) ?></td>
                    <td><?= $row["publication_year"] ?></td>
                    <td><?= htmlspecialchars($row["category"]) ?></td>
                    <td><?= htmlspecialchars($row["isbn"]) ?></td>
                    <td>
                        <a href="index.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php $conn->close(); ?>
