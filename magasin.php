<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'include/head.php' ?>
    <title>Liste des magasins</title>
</head>

<body>
    <?php include 'include/nav.php' ?>
    <div class="container py-2">
        <h2>Liste des magasins</h2>
        <a href="ajouter_magasin.php" class="btn btn-primary">Ajouter magasin</a>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Nom du magasin</th>
                    <th>Catégorie</th>
                    <th>Image</th>
                    <th>Opérations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once 'include/database.php';
                $Magasins = $pdo->query("SELECT Magasins.*, Categories.CategoryName as 'CategoryName' FROM Magasins INNER JOIN Categories ON Magasins.CategoryID = Categories.CategoryID")->fetchAll(PDO::FETCH_OBJ);

                foreach ($Magasins as $Magasin) {
                    ?>
                    <tr>
                        <td><?= $Magasin->MagasinsID ?></td>
                        <td><?= $Magasin->MagasinsName ?></td>
                        <td><?= $Magasin->CategoryName ?></td>
                        <td>
                            <?php if ($Magasin->Image) : ?>
                                <img class="img-fluid" width="90" src="upload/magasin/<?= $Magasin->Image ?>" alt="<?= $Magasin->MagasinsName ?>">
                            <?php else : ?>
                                <p>Aucune image disponible</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="modifier_magasin.php?MagasinsID=<?= $Magasin->MagasinsID ?>">Modifier</a>
                            <a class="btn btn-danger" href="supprimer_magasin.php?MagasinsID=<?= $Magasin->MagasinsID ?>" onclick="return confirm('Voulez-vous vraiment supprimer le magasin <?= $Magasin->MagasinsName ?> ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
