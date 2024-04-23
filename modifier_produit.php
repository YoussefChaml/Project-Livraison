<?php
require_once 'include/database.php';
include 'include/nav.php';

$updated = false;

if (isset($_GET['id'])) {
    $produitID = $_GET['id'];
    $produit = getProduitById($pdo, $produitID);

    if (!$produit) {
        displayErrorMessage('Produit non trouvé.');
    } else {
        if (isset($_POST['modifier'])) {
            $NameTyps = $_POST['NameTyps'];
            $ProduitsName = $_POST['ProduitsName'];
            $prix = $_POST['prix'];
            $discount = $_POST['discount'];
            $MagasinsID = $_POST['MagasinsID'];
            $image = $_FILES['image'];

            $filename = '';

            if (!empty($image['name'])) {
                $filename = uniqid() . '_' . $image['name'];
                move_uploaded_file($image['tmp_name'], 'upload/produit/' . $filename);
            }

            if (!empty($ProduitsName) && !empty($prix) && !empty($MagasinsID)) {
                // تحديث البيانات في جدول Produits
                $query = "UPDATE Produits SET ProduitsName=?, prix=?, discount=?, MagasinsID=?";
                $values = [$ProduitsName, $prix, $discount, $MagasinsID];

                // إذا كان هناك صورة، قم بإضافة الصورة إلى الجملة والقيم
                if ($filename) {
                    $query .= ", image=?";
                    $values[] = $filename;
                }

                $query .= " WHERE ProduitsID = ?";
                $values[] = $produitID;

                $sqlState = $pdo->prepare($query);
                $updated = $sqlState->execute($values);

                if ($updated) {
                    // تحديث البيانات في جدول Typs_Produits
                    $sqlTyps = $pdo->prepare('UPDATE Typs_Produits SET NameTyps=? WHERE ProduitsID = ?');
                    $sqlTyps->execute([$NameTyps, $produitID]);

                    header('location: produits.php');
                    exit;
                } else {
                    displayErrorMessage('Erreur de base de données (40023).');
                }
            } else {
                displayErrorMessage('ProduitsName, prix, Magasins sont obligatoires.');
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        
        <head>
            <?php include 'include/head.php'; ?>
            <title>Modifier produit</title>
        </head>
        
        <body>
            <div class="container py-2">
                <h4>Modifier produit</h4>
                <form method="post" enctype="multipart/form-data">
                    <label class="form-label">NameTyps</label>
                    <select name="NameTyps" class="form-control">
                        <?php
                        $NameTypsList = getNameTypsList($pdo);
                        foreach ($NameTypsList as $typeName) {
                            $selected = ($typeName == $produit['NameTyps']) ? 'selected' : '';
                            echo "<option $selected value='$typeName'>$typeName</option>";
                        }
                        ?>
                    </select>
        
                    <label class="form-label">ProduitsName</label>
                    <input type="text" class="form-control" name="ProduitsName" value="<?= $produit['ProduitsName'] ?? '' ?>">
        
                    <label class="form-label">Prix</label>
                    <input type="number" class="form-control" step="0.1" name="prix" min="0" value="<?= $produit['Prix'] ?? '' ?>">
        
                    <label class="form-label">Discount</label>
                    <input type="range" class="form-control" name="discount" min="0" max="90" value="<?= $produit['Discount'] ?? 0 ?>">
        
                    <label class="form-label">Image</label>
                    <input type="file" class="form-control" name="image">
                    <?php if ($produit && isset($produit['Image'])) : ?>
                        <img width="250" class="img img-fluid" src="upload/produit/<?= $produit['Image'] ?>" alt="<?= isset($produit['ProduitsName']) ? $produit['ProduitsName'] : '' ?>"><br>
                    <?php endif; ?>
        
                    <label class="form-label">Magasins</label>
                    <select name="MagasinsID" class="form-control">
                        <?php
                        $magasins = $pdo->query('SELECT * FROM Magasins')->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($magasins as $magasin) {
                            $selected = (isset($produit['MagasinsID']) && $produit['MagasinsID'] == $magasin['MagasinsID']) ? 'selected' : '';
                            echo "<option $selected value='" . $magasin['MagasinsID'] . "'>" . $magasin['MagasinsName'] . "</option>";
                        }
                        ?>
                    </select>
        
                    <input type="hidden" name="id" value="<?= $produit['ProduitsID'] ?? '' ?>">
                    <input type="submit" value="Modifier produit" class="btn btn-primary my-2" name="modifier">
                </form>
                <?php
                // عرض رسالة نجاح التحديث
                if ($updated) {
                    echo '<div class="alert alert-success" role="alert">تم تحديث المنتج بنجاح.</div>';
                }
                ?>
            </div>
        </body>
        
        </html>
        <?php
    }
} else {
    displayErrorMessage('Identifiant du produit manquant.');
}

function displayErrorMessage($message)
{
    echo '<div class="container py-2"><div class="alert alert-danger" role="alert">' . $message . '</div></div>';
}

function getProduitById($pdo, $produitID)
{
    $sqlState = $pdo->prepare('SELECT * FROM Produits WHERE ProduitsID=?');
    $sqlState->execute([$produitID]);
    return $sqlState->fetch(PDO::FETCH_ASSOC);
}

function getNameTypsList($pdo)
{
    $sql = 'SELECT DISTINCT NameTyps FROM Typs_Produits';
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $result;
}
?>
