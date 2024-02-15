<!DOCTYPE html>
<head>
    <meta charset="ISO-8859"/>
    <link rel="stylesheet" href="css/bootstrap.css" />
    <script src="https://kit.fontawesome.com/d576863e16.js" crossorigin="anonymous"></script>
    <title> Connexion </title>
</head>

<?php
session_start();
$mess = "";
$login= "";
if (isset($_GET["passerror"])){
    $mess = "Erreur : Votre mot de passe est non valide";
}
if (isset($_GET["loginerror"])){
    $mess = 'Erreur : Votre login est non valide ! </br> Avez-vous un compte ? </br> Créer vous un compte ici <a href="Inscription.php" class="link-danger">Inscription</a>';
}

if (isset( $_SESSION['login'])){
    $login = $_SESSION['login'];
    session_destroy();
}
?>

<body>
    <header>
        <div class="row" style="margin-top: 20px; margin-right:0%; margin-left: 0%">
            <div class="col-8">
                <h1>Connexion a mon compte demandeur</h1>
            </div>
            <div class="col-4">
                <ul class="nav nav-tabs justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php"><i class="fa-solid fa-circle-user"></i>&nbsp;Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Inscription.php"><i class="fa-solid fa-user-plus"></i>&nbsp;Inscription</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <section class="container mt-5">
        <div class="row">
            <div class="col-12">
                <form name="accesform" method="post" action="ValideConnexion.php">
                    <?php
                        if (isset($_GET["error"])){
                            echo '<div class="alert alert-danger" role="alert">' . $mess .'</div>';
                        }
                        if (isset($_GET['isRegister'])){
                            echo '<div class="alert alert-success" role="alert">Votre compte est créer <br> Veuillez vous connecter</div>';
                        }
                    ?>  
                    <div class="mb-3 row">
                        <label for="login" class="col-sm-2 col-form-label">Login</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="login" value="<?=$login?>" placeholder="Votre login" name="login" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Mot de passe</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="inputPassword" name="password" required>
                        </div>
                    </div>
                    <div class="mb-3 row ">
                        <div class="col-sm-8">
                            <a href="Inscription.php" class="link-info">Je n'ai pas de compte</a>
                        </div>
                        <div class="col-sm-4 justify-content-end">
                            <button type="submit" class="btn btn-primary mb-3">Connexion</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

</body>
</html>