<?php

require_once('model/Requete.php');
require_once('model/Connection.php');
require_once('model/Inscription.php');

function listeForum() {
    $listCategorieSoucategrie = Requete::listCategorieSoucategrie();
    require ('view/listeCategoryView.php');
}

function listeSujet($idSousCategory) {
    $listsujets = Requete::getResultSelect("sujet", "id_sujet ,nom_sujet ", "id_sous_catégorie= {$idSousCategory}");
    $nomCatego = Requete::getResultSelect("sous_categorie", "nom_sous_catégorie", "id_sous_catégorie = {$idSousCategory}");
    if ($listsujets && $nomCatego) {
        require ('view/listeSujetsView.php');
    }
}

function listeMessages($idSujet) {
    $listeMessages = Requete::getResultSelect("message_forum", "contenue", "id_sujet = {$idSujet}");
    $nomSujet = Requete::getResultSelect("sujet", "nom_sujet", "id_sujet = {$idSujet}");
    if ($listeMessages && $nomSujet) {
        require ('view/listeMessagesView.php');
    }
}

function connection($motdepass = "", $pseudo = "") {

    if ($pseudo && $motdepass) {
        $connection = new Connection($motdepass, $pseudo);

        if ($connection->conn()) {

            //creation de variable de session 
            $_SESSION['idUser'] = $connection->getIdUser();
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['time'] = time();
            require ('view/aceuille.php');
        } else {

            $error = $connection->getError();


            require ('view/connectionView.php');
        }
    } else {
        require ('view/connectionView.php');
    }
}

function deconnection() {
    if (isset($_SESSION)) {
        $speudo = $_SESSION['pseudo'];
        session_destroy();
        $_SESSION = NULL;
        require ('view/aceuille.php');
    } else {
        require ('view/aceuille.php');
    }
}

function inscription($nom = "", $prenom = "", $pseudo = "", $email = "", $motPass1 = "", $motPass2 = "") {
    $nom = trim($nom);
    $prenom = trim($prenom);
    $pseudo = trim($pseudo) ;
    $email = trim($email) ;
    $syntaxe = '<^[A-Za-z0-9]*$>';
    if ($nom && $prenom && $pseudo && $email && $motPass1 && $motPass2) {

        if (preg_match($syntaxe, $nom) && preg_match($syntaxe, $prenom) && preg_match($syntaxe, $pseudo)) {
            $inscription = new Inscription($nom, $prenom, $pseudo, $email, $motPass1, $motPass2);
            $resultat = $inscription->insertion_User_ou_DL_afpa();
            if ($resultat) {
                require ('view/aceuille.php');
            } else {
                $error = $inscription->getError();
                require ('view/inscriptionView.php');
            }
        } else {
            $error = "pas de caracter speciaux";
            require ('view/inscriptionView.php');
        }
    } else {
        require ('view/inscriptionView.php');
    }
}
