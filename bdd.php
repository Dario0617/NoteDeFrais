<?php
// Connexion à la base de donnÃ©es
$envFilePath = dirname(__DIR__) . '/Note_de_Frais/.env';
if (file_exists($envFilePath)){
    $envData = file_get_contents($envFilePath, false);
    $tmp = explode(";", $envData);
    array_pop($tmp);
    $list = [];
    foreach($tmp as $elem){
        $e = explode('=', $elem);
        $list[$e[0]] = $e[1];                
    }
} else {
    die();
}
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=dario_1;charset=utf8', $list['DB_LOGIN'], $list['DB_PASSWORD']);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}