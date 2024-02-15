<?php
// Connexion à la base de donnÃ©es
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=dario_1;charset=utf8', 'dario', 'dab3oeP-');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}