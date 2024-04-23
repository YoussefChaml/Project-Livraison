<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'include/head.php'; ?>
    <title>Ajouter magasin</title>
</head>

<body>
    <?php include 'include/nav.php'; ?>
    <div class="container py-2">
        <h4>Ajouter magasin</h4>
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        require_once 'include/database.php';

        if (isset($_POST['ajouter'])) {
            $MagasinName = $_POST['MagasinName'];
            $image = $_FILES['image'];
            $CategoryID = $_POST['CategoryID'];

            if (!empty($MagasinName) && $image['error'] == 0 && !empty($CategoryID)) {
                // Téléchargement de l'image avec un nom unique
                $filename = uniqid() . '_' . $image['name'];
                $target_path = __DIR__ . '/upload/magasin/' . $filename;

                if (move_uploaded_file($image['tmp_name'], $target_path)) {
                    // Insérer les données dans la base de données (excluding Date de création)
                    $sqlState = $pdo->prepare('INSERT INTO Magasins(MagasinsName, Image, CategoryID) VALUES (?, ?, ?)');
                    if ($sqlState->execute([$MagasinName, $filename, $CategoryID])) {
                        // Rediriger vers la page des magasins après l'insertion
                        header('location: magasin.php');
                        exit();
                    } else {
                        echo "Error updating database: " . print_r($pdo->errorInfo(), true);
                    }
                } else {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        Erreur lors du téléchargement de l'image.
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="alert alert-danger" role="alert">
                    MagasinName, Image et CategoryID sont obligatoires
                </div>
                <?php
            }
        }

        // Récupérer la liste des catégories
        $sqlCategories = $pdo->query('SELECT * FROM Categories')->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <form method="post" enctype="multipart/form-data">
            <label class="form-label">MagasinName</label>
            <input type="text" class="form-control" name="MagasinName">
            
            <label class="form-label">Image</label>
            <input type="file" class="form-control" name="image">

            <label class="form-label">Categorie</label>
            <select name="CategoryID" class="form-control">
                <option value="">Choisissez une Catégorie</option>
                <?php
                foreach ($sqlCategories as $category) {
                    echo "<option value='" . $category['CategoryID'] . "'>" . $category['CategoryName'] . "</option>";
                }
                ?>
            </select>

            <input type="submit" value="Ajouter magasin" class="btn btn-primary my-2" name="ajouter">
        </form>
    </div>
</body>

</html>
