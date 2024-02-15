<?php
session_start();
include_once('bdd.php');

if (isset($_GET['id'])) {
    // Suppression de la ligne de frais
    $id = $_GET['id'];
    $deleteQuery = $bdd->prepare("DELETE FROM `Lignes-Frais` WHERE Id = ?");
    $deleteQuery->execute([$id]);

    header('Location:noteDeFrais.php?sucessDelete=1');
    die;
} else {
    header('Location:noteDeFrais.php?error=1');
    die;
}
?>
