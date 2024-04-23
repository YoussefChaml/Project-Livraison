<?php
require_once 'include/database.php';
$CommandeID = $_GET['CommandeID'];
$sqlState = $pdo->prepare('SELECT Commande.*,utilisateur.login as "login" FROM Commande 
            INNER JOIN utilisateur ON Commande.ClientID = utilisateur.id 
                                               WHERE Commande.ClientID = ?
                                               ORDER BY Commande.date_creation DESC');
$sqlState->execute([$ClientID]);
$Commande = $sqlState->fetch(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="en">
<head>
    <?php include 'include/head.php' ?>
    <title>Commande | Numéro <?= $Commande['ClientID'] ?> </title>
</head>
<body>
<?php include 'include/nav.php' ?>
<div class="container py-2">
    <h2>Détails Commandes</h2>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#ID</th>
            <th>Client</th>
            <th>Sous-Total</th>
            <th>Livraison</th>
            <th>Total</th>
            <th>Date</th>
            <th>Opérations</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sqlStateCommandeDetails = $pdo->prepare('SELECT CommandeDetails.*,Produits.ProduitsName,Produits.image from CommandeDetails
                                                        INNER JOIN Produits ON CommandeDetails.ProduitsID = Produits.ProduitsID
                                                        WHERE CommandeID = ?
                                                        ');
        $sqlStateCommandeDetails->execute([$CommandeID]);
        $CommandeDetails = $sqlStateCommandeDetails->fetchAll(PDO::FETCH_OBJ);
        ?>
        <tr>
            <td><?php echo $Commande['CommandeID'] ?></td>
            <td><?php echo $Commande['ClientID'] ?></td>
            <td><?php echo $Commande['Sous_Total'] ?></td>
            <td><?php echo $Commande['Livraison'] ?></td>
            <td><?php echo $Commande['total'] ?> <i class="fa fa-solid fa-dollar"></i></td>
            <td><?php echo $Commande['date_creation'] ?></td>
            <td>
                <?php if ($Commande['valide'] == 0) : ?>
                    <a class="btn btn-success btn-sm" href="valider_commande.php?id=<?= $Commande['CommandeID']?>&etat=1">Valider la commande</a>
                <?php else: ?>
                    <a class="btn btn-danger btn-sm" href="valider_commande.php?id=<?= $Commande['CommandeID']?>&etat=0">Annuler la commande</a>
                <?php endif; ?>
            </td>
            <td>
            </td>
        </tr>
        <?php
        ?>
        </tbody>
    </table>
    <hr>
    <h2>Produits : </h2>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#ID</th>
            <th>Produit</th>
            <th>Catégorie</th>
            <th>Magasin</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($CommandeDetails as $CommandeDetails) : ?>
            <tr>
                <td><?php echo $CommandeDetails->ProduitsID ?></td>
                <td><?php echo $CommandeDetails->ProduitsName ?></td>
                <td><?php echo $CommandeDetails->CategoryName ?></td>
                <td><?php echo $CommandeDetails->MagasinsName ?></td>
                <td><?php echo $CommandeDetails->prix ?> <i class="fa fa-solid fa-dollar"></i></td>
                <td>x <?php echo $CommandeDetails->quantite ?></td>
                <td><?php echo $CommandeDetails->total ?> <i class="fa fa-solid fa-dollar"></i></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>