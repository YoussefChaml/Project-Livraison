<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/head.php'; ?>
    <title>Ajouter catégorie</title>
</head>
<body>
    <?php include 'include/nav.php'; ?>
    <div class="container py-2">
        <h4>Ajouter catégorie</h4>
        <?php
        if (isset($_POST['ajouter'])) {
            $CategoryName = htmlspecialchars($_POST['CategoryName']);
            $image = $_FILES['image'];

            if (!empty($CategoryName) && $image['error'] == 0) {
                require_once 'include/database.php';

                // Validate image type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $fileInfo = getimagesize($image['tmp_name']);

                if ($fileInfo && in_array($fileInfo['mime'], $allowedTypes)) {
                    // Upload the image with a unique name
                    $filename = uniqid() . '_' . $image['name'];
                    $target_path = 'upload/categories/' . $filename;

                    if (move_uploaded_file($image['tmp_name'], $target_path)) {
                        // Insert data into the database
                        try {
                            $sqlState = $pdo->prepare('INSERT INTO Categories(CategoryName, image) VALUES (?, ?)');
                            $sqlState->execute([$CategoryName, $filename]);

                            ?>
                            <div class="alert alert-success" role="alert">
                                Catégorie ajoutée avec succès!
                            </div>
                            <?php
                        } catch (PDOException $e) {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                Erreur lors de l'ajout de la catégorie dans la base de données.
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            Type de fichier non autorisé. Veuillez télécharger une image JPEG, PNG ou GIF.
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        Veuillez télécharger une image valide (JPEG, PNG ou GIF).
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="alert alert-danger" role="alert">
                    Le nom de la catégorie et l'image sont obligatoires.
                </div>
                <?php
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <label class="form-label">Nom de la catégorie</label>
            <input type="text" class="form-control" name="CategoryName">
            <label class="form-label">Image</label>
            <input type="file" class="form-control" name="image">
            <input type="submit" value="Ajouter catégorie" class="btn btn-primary my-2" name="ajouter">
        </form>
    </div>
</body>
</html>
