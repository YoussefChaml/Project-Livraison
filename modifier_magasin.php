<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/head.php' ?>
    <title>Modifier magasin</title>
</head>
<body>
    <?php include 'include/nav.php' ?>
    <div class="container py-2">
        <h4>Modifier magasin</h4>
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        require_once 'include/database.php';

        $magasin = [];

        if (isset($_GET['MagasinsID'])) {
            $MagasinsID = $_GET['MagasinsID'];

            $sqlState = $pdo->prepare('SELECT * FROM Magasins WHERE MagasinsID = ?');
            $sqlState->execute([$MagasinsID]);
            $magasin = $sqlState->fetch(PDO::FETCH_ASSOC) ?: [];
        }

        if (isset($_POST['modifier'])) {
            $MagasinsName = $_POST['MagasinsName'];

            $filename = '';
            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['name'];
                $filename = uniqid() . '_' . $image;

                $uploadPath = 'upload/magasin/' . $filename;

                $imageFileType = strtolower(pathinfo($uploadPath, PATHINFO_EXTENSION));

                $validExtensions = ['jpeg', 'jpg', 'png', 'gif'];

                // Check if the file is a valid image file
                if (in_array($imageFileType, $validExtensions)) {
                    // Move the uploaded image to the specified directory
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        // Update the database with the new image path
                        $sqlState = $pdo->prepare('UPDATE Magasins
                                                SET MagasinsName = ?,
                                                    Image = ?
                                                WHERE MagasinsID = ?');
                        if ($sqlState->execute([$MagasinsName, $filename, $MagasinsID])) {
                            // Redirect to magasins.php after successful update
                            header('location: magasin.php');
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
                // No new image uploaded, update only the MagasinsName
                $sqlState = $pdo->prepare('UPDATE Magasins
                                            SET MagasinsName = ?
                                            WHERE MagasinsID = ?');
                if ($sqlState->execute([$MagasinsName, $MagasinsID])) {
                    // Redirect to magasins.php after successful update
                    header('location: magasin.php');
                    exit();
                } else {
                    echo "Error updating database: " . print_r($pdo->errorInfo(), true);
                }
            }
        }
        ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" class="form-control" name="MagasinsID" value="<?php echo htmlspecialchars($magasin['MagasinsID'] ?? ''); ?>">
            <label class="form-label">MagasinsName</label>
            <input type="text" class="form-control" name="MagasinsName" value="<?php echo htmlspecialchars($magasin['MagasinsName'] ?? ''); ?>">
            <label class="form-label">Image</label>
            <input type="file" class="form-control" name="image">
           
            <input type="submit" value="Modifier magasin" class="btn btn-primary my-2" name="modifier">
        </form>
    </div>
</body>
</html>
