<?php
    require_once 'include/database.php';
    $id = $_GET['MagasinsID'];
    $sqlState = $pdo->prepare('DELETE FROM Magasins WHERE MagasinsID=?');
    $supprime = $sqlState->execute([$id]);
    header('location: magasin.php');
?>
