<?php
session_start();
?>
<html lang="fr">
<head>
    <title></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css" />
    <script src="https://kit.fontawesome.com/d576863e16.js" crossorigin="anonymous"></script>
    <title> Inscription </title>
</head>

<?php
$mess = '';
$login = '';
if( isset( $_GET['error'] ) ) {
    if( isset( $_GET['loginerror'] ) ) {
        $alert = 'alert-danger';
        $mess = 'Erreur : Vous avez déjà un compte demandeur ! </br> Veuillez vous connectez <a href="connexion.php" class="link-danger">Connexion</a>';
    }
    if( isset( $_GET['accountError'] ) ) {
        $alert = 'alert-danger';
        $mess = 'Erreur : Aucun demandeur trouver avec ce numéro de Licence et ce Nom';
    }
    if( isset( $_GET['passwordError'] ) ) {
        $alert = 'alert-danger';
        $mess = 'Erreur : Mot de passe non identiques';
    }
}

if (isset( $_SESSION['login'])){
    $login = $_SESSION['login'];
    session_destroy();
}

if( isset( $_GET['validation'] ) ){
    $alert = 'alert-success';
    $mess = 'Votre accès a était enregistré';
}
?>
<body>
    <header>
        <div class="row" style="margin-top: 20px; margin-right:0%; margin-left: 0%">
            <div class="col-8">
                <h1>Inscription en tant que demandeur</h1>
            </div>
            <div class="col-4">
                <ul class="nav nav-tabs justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fa-solid fa-circle-user"></i>&nbsp;Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="Inscription.php"><i class="fa-solid fa-user-plus"></i>&nbsp;Inscription</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <section class="container mt-5">
        <?php
        if( isset( $_GET['error'] ) || isset( $_GET['validation'] )  ) {
            echo '<div class="row"><p class="col-10 alert '.$alert.'">' . $mess .'</p></div>';
        }
        ?>
        <div class="row">
            <div class="col-12">
                <form name="accesform" method="post" action="ValideInscription.php">
                    <div class="mb-3 row">
                        <label for="inputLicenceNumber" class="col-sm-2 col-form-label">Votre n° de Licence</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputLicenceNumber" placeholder="Votre N° de licence" name="inputLicenceNumber" value="<?=$_SESSION['inputLicenceNumber']?>" required>
                        </div>
                    </div>
                    <script>
                        document.getElementById('inputLicenceNumber').addEventListener('input', function (event) {
                            let inputValue = event.target.value.replace(/\D/g, ''); // Retirer tous les caractères non numériques

                            // Formater le numéro selon le format "11 11 11 111 111"
                            let formattedValue = '';
                            for (let i = 0; i < inputValue.length; i++) {
                                if (i === 2 || i === 4 || i === 6 || i === 9 || i === 12) {
                                    formattedValue += ' ';
                                }
                                formattedValue += inputValue[i];
                            }

                            // Mettre à jour la valeur dans le champ de saisie
                            event.target.value = formattedValue.trim();
                        });
                    </script>
                    <div class="mb-3 row">
                        <label for="inputLastName" class="col-sm-2 col-form-label">Votre nom</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" style="text-transform: uppercase;" id="inputLastName" name="inputLastName" value="<?=$_SESSION['inputLastName']?>" required>
                        </div>
                    </div>
                    <?php
                        if(!isset($_SESSION['adhérentId'])) :
                    ?>
                    <div class="mb-3 row ">
                        <div class="col-sm-8">
                            <a href="index.php" class="link-info">J'ai déjà un compte demandeur</a>
                        </div>
                    </div>
                    <?php
                        else :  
                    ?>
                    <h5 style="color:red">Merci de renseigner une ligue, votre login et votre mot de passe afin de finalisez l'inscription en tant que demandeur</h3>
                    <div class="mb-3 row">
                        <label for="inputLigueNumber" class="col-sm-2 col-form-label">Votre ligue</label>
                        <div class="col-sm-4">
                            <select name="inputLigueNumber" class="form-select" id="inputLigueNumber" required>
                        <option value="" disabled selected>Choisir un club</option>
                            <?php
                                foreach ($_SESSION['ligues'] as $ligue) :
                                    
                            ?>
                                <option value='<?=$ligue['Number']?>'><?=$ligue['Name']?></option>
                            <?php
                                endforeach;
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputLogin" class="col-sm-2 col-form-label">Votre Login</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputLogin" name="inputLogin" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Mot de passe</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="inputPassword" name="inputPassword" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputConfirmPassword" class="col-sm-2 col-form-label">Confirmation mot de passe</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="inputConfirmPassword" name="inputConfirmPassword" required>
                        </div>
                    </div>
                    <?php
                        endif;                          
                    ?>
                    <div class="mb-3 row ">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-4 justify-content-end">
                            <button type="submit" class="btn btn-primary mb-3">Inscription</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer class="container"></footer>

</body>