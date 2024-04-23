<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/head.php' ?>
    <title>Liste des Commandes</title>
</head>
<body>
    <?php include 'include/nav.php' ?>
    <div class="container py-2">
        <h2>Liste des Commandes</h2>
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
                require_once 'include/database.php';
                $commandes = $pdo->query('SELECT Commande.*, Client.FullName AS client_name FROM Commande INNER JOIN Client ON Commande.idClient = Client.ClientID ORDER BY Commande.date_creation DESC')->fetchAll(PDO::FETCH_ASSOC);
                foreach ($commandes as $commande) {
                ?>
                    <tr>
                        <td><?php echo $commande['CommandeID'] ?></td>
                        <td><?php echo $commande['client_name'] ?></td>
                        <td><?php echo $commande['Sous_Total'] ?>  Dhs</td>
                        <td><?php echo $commande['Livraison'] ?>  Dhs</td>
                        <td><?php echo $commande['Total'] ?>  Dhs</i></td>
                        <td><?php echo $commande['date_creation'] ?></td>
                        <!-- Add your operation buttons here -->
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
