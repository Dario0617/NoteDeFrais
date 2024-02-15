<?php
session_start();
?>
<html lang="fr">
<head>
    <title>Frais de déplacement !</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css" />
    <script src="https://kit.fontawesome.com/d576863e16.js" crossorigin="anonymous"></script>
</head>

<?php
    $style = "";
    if( isset( $_SESSION['connected'] ) ){
        $style = "style='display:none'";
    }
    $login= "";
    if (isset( $_SESSION['demandeur'])){
        $demandeur = $_SESSION['demandeur'];
        $login = $demandeur['Login'];

        include_once('bdd.php');
        $sql = 'SELECT * FROM Adhérent WHERE Id = :id ';
        $reponse = $bdd->prepare( $sql );
        $reponse->execute( [':id'=>$demandeur['AdherentId']]);
        $adherent = $reponse->fetch(PDO::FETCH_ASSOC);
        $_SESSION['adherent'] = $adherent;

        $sql = 'SELECT * FROM Ligue WHERE Number = :id ';
        $reponse = $bdd->prepare( $sql );
        $reponse->execute( [':id'=>$demandeur['LigueNumber']]);
        $ligue = $reponse->fetch(PDO::FETCH_ASSOC);
        $_SESSION['ligue'] = $ligue;
    }
?>

<body>
    <header>
        <div class="row" style="margin-top: 20px; margin-right:0%; margin-left: 0%">
            <div class="col-3">
                <h1>Note de frais</h1>
            </div>
            <div class="col-9">
                <ul class="nav nav-tabs justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="noteDeFrais.php">
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

    <section class="container">
        <?php
            if( isset( $_SESSION['connected'] ) ){
                echo '<div class="row" style="margin-bottom: 10px"><div class="col-9"><h4>Bienvenue ' . 
                $adherent['FirstName']. ' ' . $adherent['LastName'] . '</h4></div>';
            }
        ?>
        <div class="row">
            <a href="bordereau.php" class="btn btn-primary">Voir le bordereau</a>
            <div class="col-12">
                    <?php
                        if (isset($_GET['sucess'])){
                            echo '<br><div class="alert alert-success" role="alert">Ligne de frais enregistrée avec succès!</div>';
                        }
                    ?> 
                    <?php
                        if (isset($_GET['sucessDelete'])){
                            echo '<br><div class="alert alert-success" role="alert">Ligne de frais supprimée avec succès!</div>';
                        }
                        if (isset($_GET["error"])){
                            echo '<br><div class="alert alert-danger" role="alert">ID non spécifié pour la suppression.</div>';
                        }
                    ?> 
                <div class="container mt-5">
                    <h2 class="mb-4">Formulaire de Frais</h2>
                    <form action="insert_Frais.php" method="post">
                        <div class="form-group">
                            <label for="date">Date :</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>

                        <div class="form-group">
                            <label for="motif">Motif :</label>
                            <select class="form-control" name="motif" required>
                            <option value="" disabled selected>Choisir un Motif</option>
                                <?php
                                $motifQuery = $bdd->query("SELECT * FROM Motif");
                                while ($motif = $motifQuery->fetch(PDO::FETCH_ASSOC)) :
                                ?>
                                    <option value='<?=$motif['Id']?>'><?=$motif['Libelle']?></option>
                                <?php
                                endwhile;
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="startCity">Ville de départ :</label>
                            <input type="text" class="form-control" name="startCity" required>
                        </div>

                        <div class="form-group">
                            <label for="endCity">Ville d'arrivée :</label>
                            <input type="text" class="form-control" name="endCity" required>
                        </div>

                        <div class="form-group">
                            <label for="km">Kilomètres :</label>
                            <input type="number" step="0.01" class="form-control" name="km" required>
                        </div>

                        <div class="form-group">
                            <label for="costToll">Coût de péage :</label>
                            <input type="number" step="0.01" class="form-control" name="costToll" required>
                        </div>

                        <div class="form-group">
                            <label for="costMeal">Coût repas :</label>
                            <input type="number" step="0.01" class="form-control" name="costMeal" required>
                        </div>

                        <div class="form-group">
                            <label for="costAccommodation">Coût hébergement :</label>
                            <input type="number" step="0.01" class="form-control" name="costAccommodation" required>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
                <div class="container mt-5">
                    <h2 class="mb-4">Liste des Frais</h2>
                    <?php
                    // Récupération des lignes de frais depuis la table Lignes-Frais
                    $fraisQuery = $bdd->prepare("SELECT L.*, M.Libelle AS MotifLibelle FROM `Lignes-Frais` L JOIN Motif M ON L.Motif = M.Id WHERE L.UserId = ?");
                    $fraisQuery->execute([$_SESSION['demandeur']['Id']]);
                    if ($fraisQuery->rowCount() > 0) :
                    ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Motif</th>
                                    <th scope="col">Trajet</th>
                                    <th scope="col">Kilomètres</th>
                                    <th scope="col">Coût de péage</th>
                                    <th scope="col">Coût repas</th>
                                    <th scope="col">Coût hébergement</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php 
                        while ($row = $fraisQuery->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <th scope="row"><?=$row['Id']?></th>
                                <td><?=$row['Date']?></td>
                                <td><?=$row['MotifLibelle']?></td>
                                <td><?=$row['Route']?></td>
                                <td><?=$row['Km']?></td>
                                <td><?=$row['CostToll']?></td>
                                <td><?=$row['CostMeal']?></td>
                                <td><?=$row['CostAccomodation']?> </td>
                                <td><a href="delete_Frais.php?id=<?=$row['Id']?>" class="btn btn-danger">Supprimer</a></td>
                            </tr>
                        <?php
                        endwhile;
                        ?>
                            </tbody>
                        </table>
                    <?php
                    else :
                    ?>
                        <p>Aucune ligne de frais disponible.</p>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>