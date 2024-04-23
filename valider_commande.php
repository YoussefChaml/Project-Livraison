<?php
include_once 'include/database.php';
$CommandeID = $_GET['CommandeID'];
$etat = $_GET['etat'];
$sqlState = $pdo->prepare('UPDATE Commande SET valide = ? WHERE CommandeID = ?');
$sqlState->execute([$etat, $CommandeID]);
header('location: commande.php?CommandeID=' . $CommandeID);
