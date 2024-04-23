<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'include/head.php'; ?>
    <title>Liste des produits</title>
</head>

<body>
    <?php include 'include/nav.php'; ?>
    <div class="container py-2">
        <h2>Liste des produits</h2>
        <a href="ajouter_produit.php" class="btn btn-primary">Ajouter produit</a>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Type de produit</th>
                    <th>Nom du produit</th>
                    <th>Prix</th>
                    <th>Discount (%)</th>
                    <th>Magasins</th>
                    <th>Image</th>
                    <th>Opérations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once 'include/database.php';

                // استعلام يجمع بين جدولي Produits وMagasins و Typs_Produits
                $query = "SELECT Produits.*, Magasins.MagasinsName as 'Magasins_Name', Typs_Produits.NameTyps 
                          FROM Produits 
                          INNER JOIN Magasins ON Produits.MagasinsID = Magasins.MagasinsID 
                          LEFT JOIN Typs_Produits ON Produits.ProduitsID = Typs_Produits.ProduitsID";

                // استرجاع البيانات
                $produits = $pdo->query($query)->fetchAll(PDO::FETCH_OBJ);

                foreach ($produits as $produit) {
                    $prix = $produit->Prix;
                    $discount = $produit->Discount;
                    $prixFinal = $prix - (($prix * $discount) / 100);
                    ?>

                    <!-- صف في الجدول -->
                    <tr>
                        <td><?= $produit->ProduitsID ?></td>
                        <td><?= $produit->NameTyps ?></td>
                        <td><?= $produit->ProduitsName ?></td>
                        <td><?= $prixFinal ?> Dhs</td>
                        <td><?= $discount ?> %</td>
                        <td><?= $produit->Magasins_Name ?></td>
                        <td>
                            <?php if ($produit->Image) : ?>
                                <img class="img-fluid" width="90" src="upload/produit/<?= $produit->Image ?>" alt="<?= $produit->ProduitsName ?>">
                            <?php else : ?>
                                <p>Aucune image disponible</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="modifier_produit.php?id=<?= $produit->ProduitsID ?>">Modifier</a>
                            <a class="btn btn-danger" href="supprimer_produit.php?ProduitsID=<?= $produit->ProduitsID ?>" onclick="return confirm('Voulez-vous vraiment supprimer le produit <?= $produit->ProduitsName ?> ?')">Supprimer</a>
                        </td>
                    </tr>
                    <!-- نهاية صف الجدول -->

                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
