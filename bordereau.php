<?php
// Démarrer la session
session_start();
include_once('bdd.php');

// Vérifier si les informations nécessaires sont présentes en session
if (isset($_SESSION['demandeur']) && isset($_SESSION['adherent']) && isset($_SESSION['ligue'])) {
    // Requête pour récupérer les lignes de frais du demandeur
    $fraisQuery = $bdd->prepare("SELECT L.*, M.Libelle AS MotifLibelle FROM `Lignes-Frais` L JOIN Motif M ON L.Motif = M.Id WHERE L.UserId = ?");
    $fraisQuery->execute([$_SESSION['demandeur']['Id']]);

    // Initialiser les totaux
    $totalCostToll = 0;
    $totalCostMeal = 0;
    $totalCostAccommodation = 0;
    $totalCostKm = 0;

    $costOneKm = 0.28;
?>
<html lang="fr">
<head>
    <title>Frais de déplacement !</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css" />
    <script src="https://kit.fontawesome.com/d576863e16.js" crossorigin="anonymous"></script>
    <style>
        @media print {
            body {
                margin: 1cm; /* Ajoute une marge à l'impression */
            }
            .print-link {
                display: none; /* Masquer le lien à l'impression */
            }            
        }   
    </style>
</head>
<body>
    <header class="print-link">
        <div class="row" style="margin-top: 20px; margin-right:0%;">
            <div class="col-12">
                <ul class="nav nav-tabs justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="noteDeFrais.php">
                            <i class="fa-solid fa-house"></i>&nbsp;Accueil
                        </a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' style='color:red' href='logout.php'>
                            <i class='fa-solid fa-door-open'></i>&nbsp;Déconnexion
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Note de frais des bénévoles</h1>

        <p>Je soussigné(e) : <b><?= $_SESSION['adherent']['FirstName'] ?> <?= $_SESSION['adherent']['LastName'] ?></b></p>
        <p>Demeurant : <b><?= $_SESSION['adherent']['Adress'] ?></b></p>
        <p>Certifie renoncer au remboursement des frais ci-dessous et les laisser au club : <b><?= $_SESSION['ligue']['Name'] ?> N°<?= $_SESSION['ligue']['Number'] ?>, président du club : <?= $_SESSION['ligue']['President'] ?></b></p>

        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Motif</th>
                        <th>Trajet</th>
                        <th>Kilomètres</th>
                        <th>Coût kilométrique</th>
                        <th>Coût de péage</th>
                        <th>Coût repas</th>
                        <th>Coût hébergement</th>
                    </tr>
                </thead>
                <tbody>
                    <div style="display: flex;align-items: center;justify-content: space-between;">
                    <h2>Frais de déplacement</h2>
                    <p style="margin: 0";>Tarif kilométrique appliqué pour le remboursement : <?= $costOneKm ?>€</p>
                    </div>
                    <?php
                    while ($row = $fraisQuery->fetch(PDO::FETCH_ASSOC)) {
                        // Calcul du coût kilométrique
                        $costKm = $row['Km'] * $costOneKm;
                        $formattedDate = date('d/m/Y', strtotime($row['Date']));
                        echo '<tr>
                                <td>' . $formattedDate . '</td>
                                <td>' . $row['MotifLibelle'] . '</td>
                                <td>' . $row['Route'] . '</td>
                                <td>' . $row['Km'] . '</td>
                                <td>' . $costKm . '€</td>
                                <td>' . $row['CostToll'] . '€</td>
                                <td>' . $row['CostMeal'] . '€</td>
                                <td>' . $row['CostAccomodation'] . '€</td>
                            </tr>';

                        // Mettre à jour les totaux
                        $totalCostToll += $row['CostToll'];
                        $totalCostMeal += $row['CostMeal'];
                        $totalCostAccommodation += $row['CostAccomodation'];
                        $totalCostKm += $costKm;
                    }
                    ?>

                </tbody>
            </table>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <h5>Total des coûts kilométriques :</h5>
                <p class="font-weight-bold"><?= $totalCostKm ?>€</p>
            </div>
            <div class="col-md-6">
                <h5>Total des coûts de péage :</h5>
                <p class="font-weight-bold"><?= $totalCostToll ?>€</p>
            </div>
            
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <h5>Total des coûts de repas :</h5>
                <p class="font-weight-bold"><?= $totalCostMeal ?>€</p>
            </div>
            <div class="col-md-6">
                <h5>Total des coûts d'hébergement :</h5>
                <p class="font-weight-bold"><?= $totalCostAccommodation ?>€</p>
            </div>
        </div>

        <div class="mt-3">
            <h4>Total général :</h4>
            <p class="font-weight-bold"><?= $totalCostToll + $totalCostMeal + $totalCostAccommodation + $totalCostKm ?>€</p>
        </div>
        <br>
        <button class="btn btn-primary print-link" onclick="window.print()">Imprimer</button>
        <br>
        <br>
    </div>
    <?php
    } else {
        echo "<p>Les informations nécessaires ne sont pas disponibles en session.</p>";
    }
    ?>
</body>
