<?php
require_once 'include/database.php';

// Check if the 'ProduitsID' parameter is present in the URL
if (isset($_GET['ProduitsID'])) {
    // Get the product ID from the URL
    $id = $_GET['ProduitsID'];

    try {
        $pdo->beginTransaction();

        // Check if there are dependent records in the 'typs_produits' table
        $checkDependency = $pdo->prepare('SELECT COUNT(*) FROM typs_produits WHERE ProduitsID = ?');
        $checkDependency->execute([$id]);
        $dependencyCount = $checkDependency->fetchColumn();

        if ($dependencyCount > 0) {
            // Handle dependent records (you may update or delete them as needed)

            // Example: Update dependent records to set ProduitsID to NULL
            $updateDependent = $pdo->prepare('UPDATE typs_produits SET ProduitsID = NULL WHERE ProduitsID = ?');
            $updateDependent->execute([$id]);
        }

        // Now, delete the product
        $sqlState = $pdo->prepare('DELETE FROM Produits WHERE ProduitsID = ?');
        $supprime = $sqlState->execute([$id]);

        if ($supprime) {
            $pdo->commit();
            // Redirect to the 'Produits.php' page after successful deletion
            header('location: Produits.php');
        } else {
            $pdo->rollBack();
            // Handle the case when deletion fails
            echo "Error: Product deletion failed.";
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        // Handle any SQL errors
        echo "Error deleting product: " . $e->getMessage();
    }
} else {
    // Handle the case when 'ProduitsID' is not present in the URL
    echo "Invalid request. Product ID is missing.";
}
?>
