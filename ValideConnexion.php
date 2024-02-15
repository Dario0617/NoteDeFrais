<?php 
    session_start();
    if(isset ($_POST["login"]) && isset ($_POST["password"])){
        include_once('bdd.php');

        $login = htmlentities($_POST["login"],ENT_COMPAT,"ISO-8859-1",true);
        $password = $_POST["password"];

        $sql = 'SELECT * FROM Demandeur WHERE login=:login';
        $reponse = $bdd->prepare($sql);
        $reponse->execute([':login'=>$login]);

        if ($valeurs = $reponse->fetch(pdo::FETCH_ASSOC)){
            if (sodium_crypto_pwhash_str_verify($valeurs['Password'], $password)){
                header('Location:noteDeFrais.php');
                $_SESSION['demandeur'] = $valeurs;
                $_SESSION['connected'] = true;
                $_SESSION['id'] = $valeurs['id'];
                die;
            }
            else{
                header('Location:index.php?error=1&passerror=1');
                $_SESSION['login'] = $login;
                die;
            }
        }
        else
        {
            header('Location:index.php?error=1&loginerror=1');
            $_SESSION['login'] = $login;
            die;
        }
    }
?>