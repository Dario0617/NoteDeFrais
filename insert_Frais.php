<?php
session_start();
include_once('bdd.php');

// Récupération des données du formulaire
$date = $_POST['date'];
$motifId = $_POST['motif'];
$startCity = $_POST['startCity'];
$endCity = $_POST['endCity'];
$km = $_POST['km'];
$costToll = $_POST['costToll'];
$costMeal = $_POST['costMeal'];
$costAccommodation = $_POST['costAccommodation'];
$userId = $_SESSION['demandeur']['Id'];

// Concaténation des villes de départ et d'arrivée
$route = $startCity . '-' . $endCity;


// Insertion des données dans la table Lignes-Frais
$insertQuery = $bdd->prepare("INSERT INTO `Lignes-Frais` (Date, Motif, Route, Km, CostToll, CostMeal, CostAccomodation, UserId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$insertQuery->execute([$date, $motifId, $route, $km, $costToll, $costMeal, $costAccommodation, $userId]);

header('Location:noteDeFrais.php?sucess=1');
die;
?>
