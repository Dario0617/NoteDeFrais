<?php
    session_start();

    if (isset($_POST['inputLogin']) && isset($_POST['inputPassword']) && isset($_POST['inputLigueNumber'])){
        $login = htmlentities($_POST['inputLogin'],ENT_COMPAT,"ISO-8859-1",true);
        $password = htmlentities($_POST['inputPassword'],ENT_COMPAT,"ISO-8859-1",true);
        $confirmPassword = htmlentities($_POST['inputConfirmPassword'],ENT_COMPAT,"ISO-8859-1",true);
        $ligueNumber = htmlentities($_POST['inputLigueNumber'],ENT_COMPAT,"ISO-8859-1",true);

        if ($password != $confirmPassword){
            header('Location:Inscription.php?error=1&passwordError=1');
            die;
        }

        $password = sodium_crypto_pwhash_str($password, SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE, 
        SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE);
        include_once('bdd.php');
        $sql = 'INSERT INTO Demandeur (Login, Password, AdherentId, LigueNumber) VALUES (:login, :password, :adherentId, :ligueNumber)';
        $reponse = $bdd->prepare( $sql );
        $reponse->execute(array(':login'=>$login, ':password'=>$password, ':adherentId'=>$_SESSION['adhérentId'], ':ligueNumber'=>$ligueNumber));
        if (!$reponse){
            echo "Erreur lors de l'enregistrement";
            die;
        }
        session_destroy();
        header('Location:index.php?isRegister=1)');
        die;
    }

    if( isset( $_POST['inputLastName'] ) && isset( $_POST['inputLicenceNumber'] )) {
        $licenceNumber = htmlentities($_POST['inputLicenceNumber'],ENT_COMPAT,"ISO-8859-1",true);
        $lastName = htmlentities($_POST['inputLastName'],ENT_COMPAT,"ISO-8859-1",true);
        include_once('bdd.php');
        $sql = 'SELECT Id FROM Adhérent WHERE Adhérent.LicenceNumber=:LicenceNumber AND Adhérent.LastName=:LastName';
        $reponse = $bdd->prepare( $sql );
        $reponse->execute( [':LicenceNumber'=>$licenceNumber, ':LastName'=>$lastName] );
        if($acces = $reponse->fetch(PDO::FETCH_ASSOC)){
            $adherentId = $acces['Id'];
        }else{
            header('Location:Inscription.php?error=1&accountError=1');
             die;
        }

        $sql = 'SELECT Login FROM Demandeur WHERE AdherentId=:adherentId';
        $reponse2 = $bdd->prepare( $sql );
        $reponse2->execute( [':adherentId'=>$adherentId] );
        
        if($reponse2->fetch(PDO::FETCH_ASSOC)) {            
            header('Location:Inscription.php?error=1&loginerror=1');
            die;
        } else {
            $_SESSION['inputLastName'] = $lastName;
            $_SESSION['inputLicenceNumber'] = $licenceNumber;
            $_SESSION['adhérentId'] = $adherentId;

            $sql = 'SELECT Number, Name FROM Ligue';
            $reponse3 = $bdd->prepare( $sql );
            $reponse3->execute( [] );
            $data = [];
            $ligues = $reponse3->fetchAll(PDO::FETCH_ASSOC);
            
            $_SESSION['ligues'] = $ligues;
            header('Location:Inscription.php');
            die;
        }
    }
?>