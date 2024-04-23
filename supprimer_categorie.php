<?php
require_once 'include/database.php';

if (isset($_GET['CategoryID'])) {
    $id = $_GET['CategoryID'];

    if (is_numeric($id)) {
        try {
            $pdo->beginTransaction();

            // Check if there are dependent records in the 'magasins' table
            $checkDependency = $pdo->prepare('SELECT COUNT(*) FROM magasins WHERE CategoryID = ?');
            $checkDependency->execute([$id]);
            $dependencyCount = $checkDependency->fetchColumn();

            if ($dependencyCount > 0) {
                // Handle dependent records (you may update or delete them as needed)

                // Example: Update dependent records to set CategoryID to NULL
                $updateDependent = $pdo->prepare('UPDATE magasins SET CategoryID = NULL WHERE CategoryID = ?');
                $updateDependent->execute([$id]);
            }

            // Now, delete the category
            $sqlState = $pdo->prepare('DELETE FROM Categories WHERE CategoryID = ?');
            $supprime = $sqlState->execute([$id]);

            if ($supprime) {
                $pdo->commit();
                header('location: categories.php');
                exit();
            } else {
                $pdo->rollBack();
                echo '<div class="alert alert-danger" role="alert">Erreur lors de la suppression de la catégorie.</div>';
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo '<div class="alert alert-danger" role="alert">Erreur lors de la suppression de la catégorie: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Identifiant de catégorie non valide.</div>';
    }
} else {
    echo '<div class="alert alert-danger" role="alert">Veuillez spécifier une catégorie à supprimer.</div>';
}
?>
