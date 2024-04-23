<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'include/database.php';

$category = [];

if (isset($_GET['CategoryID'])) {
    $CategoryID = $_GET['CategoryID'];

    $sqlState = $pdo->prepare('SELECT * FROM Categories WHERE CategoryID = ?');
    $sqlState->execute([$CategoryID]);
    $category = $sqlState->fetch(PDO::FETCH_ASSOC) ?: [];
}

if (isset($_POST['modifier'])) {
    $CategoryName = $_POST['CategoryName'];

    $filename = '';
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $filename = uniqid() . '_' . $image;

        $uploadPath = 'upload/categories/' . $filename;

        $imageFileType = strtolower(pathinfo($uploadPath, PATHINFO_EXTENSION));

        $validExtensions = ['jpeg', 'jpg', 'png', 'gif'];

        // Check if the file is a valid image file
        if (in_array($imageFileType, $validExtensions)) {
            // Move the uploaded image to the specified directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                // Update the database with the new image path
                $sqlState = $pdo->prepare('UPDATE Categories
                                            SET CategoryName = ?,
                                                Image = ?
                                            WHERE CategoryID = ?');
                if ($sqlState->execute([$CategoryName, $filename, $CategoryID])) {
                    // Redirect to categories.php after successful update
                    header('location: categories.php');
                    exit();
                } else {
                    echo "Error updating database: " . print_r($pdo->errorInfo(), true);
                }
            } else {
                echo "Error moving uploaded file";
            }
        } else {
            echo "Invalid file type. Allowed types: jpeg, jpg, png, gif";
        }
    } else {
        // No new image uploaded, update only the CategoryName
        $sqlState = $pdo->prepare('UPDATE Categories
                                    SET CategoryName = ?
                                    WHERE CategoryID = ?');
        if ($sqlState->execute([$CategoryName, $CategoryID])) {
            // Redirect to categories.php after successful update
            header('location: categories.php');
            exit();
        } else {
            echo "Error updating database: " . print_r($pdo->errorInfo(), true);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/head.php'; ?>
    <title>Modifier catégorie</title>
</head>
<body>
    <?php include 'include/nav.php'; ?>
    <div class="container py-2">
        <h4>Modifier catégorie</h4>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" class="form-control" name="CategoryID" value="<?php echo htmlspecialchars($category['CategoryID'] ?? ''); ?>">
            <label class="form-label">CategoryName</label>
            <input type="text" class="form-control" name="CategoryName" value="<?php echo htmlspecialchars($category['CategoryName'] ?? ''); ?>">
            <label class="form-label">Image</label>
            <input type="file" class="form-control" name="image">
            <?php
            if (!empty($category['Image'])) {
                echo '<p>Current Image:</p>';
                echo '<img src="' . htmlspecialchars($category['Image']) . '" alt="Current Image" style="max-width: 100px; max-height: 100px;">';
            } else {
                echo '<p>Aucun fichier choisi</p>';
            }
            ?>
            <input type="submit" value="Modifier catégorie" class="btn btn-primary my-2" name="modifier">
        </form>
    </div>
</body>
</html>
