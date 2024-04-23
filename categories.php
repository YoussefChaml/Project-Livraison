<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/head.php' ?>
    <title>Liste des catégories</title>
</head>
<body>
    <?php include 'include/nav.php' ?>
    <div class="container py-2">
        <h2>Liste des catégories</h2>
        <a href="ajouter_categorie.php" class="btn btn-primary">Ajouter catégorie</a>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Nom de la catégorie</th>
                    <th>Image</th>
                    <th>Opérations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once 'include/database.php';
                $categories = $pdo->query('SELECT * FROM Categories')->fetchAll(PDO::FETCH_ASSOC);
                foreach ($categories as $category) {
                    ?>
                    <tr>
                        <td><?php echo $category['CategoryID'] ?></td>
                        <td><?php echo $category['CategoryName'] ?></td>
                        <td>
                            <?php
                            if (!empty($category['Image'])) {
                                // تحديد المسار الكامل للصورة
                                $imagePath = 'upload/categories/' . $category['Image'];
                                echo '<img src="' . $imagePath . '" alt="Image de la catégorie" style="max-width: 100px; max-height: 100px;">';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="modifier_categorie.php?CategoryID=<?php echo $category['CategoryID'] ?>" class="btn btn-primary">Modifier</a>
                            <a href="supprimer_categorie.php?CategoryID=<?php echo $category['CategoryID'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer la catégorie <?php echo $category['CategoryName'] ?> ?');" class="btn btn-danger">Supprimer</a>
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
