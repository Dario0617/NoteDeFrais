<?php
    session_start();
    if(isset ($_SESSION['id'])){
        include_once('bdd.php');
    
        $id = $_SESSION['id'];
    
        $sql = 'SELECT * FROM users WHERE id=:id';
        $reponse = $bdd->prepare($sql);
        $reponse->execute([':id'=>$id]);
    
        $valeurs = $reponse->fetch(pdo::FETCH_ASSOC);

        $profilePicture = $valeurs['image'];
        $login = $valeurs['login'];

        $profilePictureExist = false;
        if($profilePicture != null){
            $profilePictureExist = true;
        }
    }
    $_SESSION['connected'] = true;
?>

<html lang="fr">
<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css" />
    <script src="https://kit.fontawesome.com/d576863e16.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <div class="row" style="margin-top: 20px; padding-left: 20px; padding-right:20px;">
            <div class="col-8">
                <h1>Mon profile</h1>
            </div>
            <div class="col-4">
                <ul class="nav nav-tabs justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php"><i class="fa-solid fa-house"></i>&nbsp;Accueil</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link active' href='profile.php'><i class="fa-solid fa-circle-user"></i>&nbsp;Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <section class="container">
        <?php
            echo '<div class="col-12" style="float:right;display: flex;flex-direction: row-reverse;">
            <form name="accesform" method="post" action="logout.php">
            <button type="submit" class="btn btn-danger">Déconnexion</button>
            </form></div>';
        ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Login: <?=$login?></h5>
                    <div class="card-body">
                        <?php
                        if ($profilePictureExist){
                            echo '<img src="images/' . $profilePicture . '" style="height: 100px;"/>';
                        }
                        else{
                            echo '<p>Pas de photo de profil, <a aria-current="page" href="sendProfilePicture.php">Mettre une photo de profile</a></p>';
                        }
                        ?>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </section>


</body>