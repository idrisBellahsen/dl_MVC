<?php

session_start();
require ('controller/frontend.php');
if (isset($_GET['idSousCategory']) && is_numeric($_GET['idSousCategory']) && $_GET['idSousCategory'] > 0) {
    listeSujet(trim($_GET['idSousCategory']));
} elseif (isset($_GET['idSujet']) && is_numeric($_GET['idSujet']) && $_GET['idSujet'] > 0) {
    listeMessages(trim($_GET['idSujet']));
} elseif (isset($_GET['conn']) && $_GET['conn'] == 1) {
    connection();
} elseif (isset($_POST['pseudo']) && isset($_POST['motdepass'])) {
    connection($_POST['motdepass'], $_POST['pseudo']);
} elseif (isset($_GET['deconnection'])) {
    deconnection();
} elseif (isset($_GET['inscription']) && $_GET['inscription'] == 1) {
    inscription();
} elseif (isset($_POST['nom']) && isset ($_POST['prenom']) && isset ($_POST['pseudo']) && isset ($_POST['email']) && isset ($_POST['motdepass1']) && isset ($_POST['motdepass2'])) {
    inscription($_POST['nom'], $_POST['prenom'], $_POST['pseudo'], $_POST['email'], $_POST['motdepass1'], $_POST['motdepass2'])  ;
} else {
    listeForum();
}

