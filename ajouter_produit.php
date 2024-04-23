<?php
require_once 'include/database.php';
include 'include/nav.php';

if (isset($_POST['ajouter'])) {
    $NameTyps = $_POST['NameTyps'];
    $Nouvelle_NameTyps = $_POST['Nouvelle_NameTyps'];
    $ProduitsName = $_POST['ProduitsName'];
    $prix = $_POST['prix'];
    $discount = $_POST['discount'];
    $MagasinID = $_POST['MagasinID'];
    $image = $_FILES['image'];

    $filename = 'produit.png';
    if (!empty($image['name'])) {
        $filename = uniqid() . '_' . $image['name'];
        move_uploaded_file($image['tmp_name'], 'upload/produit/' . $filename);
    }

    if (!empty($ProduitsName) && !empty($prix) && !empty($MagasinID)) {
       
        $sqlProduits = $pdo->prepare('INSERT INTO Produits (ProduitsName, Prix, Discount, MagasinsID, Image) VALUES (?, ?, ?, ?, ?)');
        $insertedProduits = $sqlProduits->execute([$ProduitsName, $prix, $discount, $MagasinID, $filename]);

        if ($insertedProduits) {
          
            $lastInsertedID = $pdo->lastInsertId();

            
            $selectedNameTyps = !empty($NameTyps) ? $NameTyps : $Nouvelle_NameTyps;

           
            $sqlTyps = $pdo->prepare('INSERT INTO Typs_Produits (NameTyps, ProduitsID) VALUES (?, ?)');
            $insertedTyps = $sqlTyps->execute([$selectedNameTyps, $lastInsertedID]);

            if ($insertedTyps) {
                header('location: produits.php');
                exit();
            } else {
                
                ?>
                <div class="alert alert-danger" role="alert">
                    Error inserting into Typs_Produits table.
                </div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-danger" role="alert">
                Erreur de base de données (40023).
            </div>
            <?php
        }
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
            ProduitsName, prix, Magasins sont obligatoires.
        </div>
        <?php
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'include/head.php'; ?>
    <title>Ajouter produit</title>
</head>

<body>
    <div class="container py-2">
        <h4>Ajouter produit</h4>
        <form method="post" enctype="multipart/form-data">
            <label class="form-label">NameTyps</label>
            <select name="NameTyps" class="form-control">
                <option value="">Choisissez une NameTyps</option>
                <?php
                $Typs_Produits = $pdo->query('SELECT DISTINCT NameTyps FROM Typs_Produits')->fetchAll(PDO::FETCH_ASSOC);
                foreach ($Typs_Produits as $Typ_Produit) {
                    echo '<option value="' . $Typ_Produit['NameTyps'] . '">' . $Typ_Produit['NameTyps'] . '</option>';
                }
                ?>
            </select>
            <label class="form-label">Nouvelle NameTyps</label>
            <input type="text" class="form-control" name="Nouvelle_NameTyps">

            <label class="form-label">ProduitsName</label>
            <input type="text" class="form-control" name="ProduitsName" required>

            <label class="form-label">Prix</label>
            <input type="number" class="form-control" step="0.1" name="prix" min="0" required>

            <label class="form-label">Discount</label>
            <input type="range" value="0" class="form-control" name="discount" min="0" max="90" required>

            <label class="form-label">Image</label>
            <input type="file" class="form-control" name="image" accept="image/jpeg, image/png, image/gif">

            <?php
            $magasins = $pdo->query('SELECT * FROM Magasins')->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <label class="form-label">Magasins</label>
            <select name="MagasinID" class="form-control">
                <option value="">Choisissez un Magasin</option>
                <?php
                foreach ($magasins as $magasin) {
                    echo "<option value='" . $magasin['MagasinsID'] . "'>" . $magasin['MagasinsName'] . "</option>";
                }
                ?>
            </select>

            <input type="submit" value="Ajouter produit" class="btn btn-primary my-2" name="ajouter">
        </form>
    </div>
</body>

</html>
