<?php

session_start();
include_once("../libs/modele.php");
include_once("../libs/maLibForms.php");
include_once("../libs/maLibSecurisation.php");
include_once("../libs/maLibUtils.php");
include_once("../libs/maLibSQL.pdo.php");
echo (__DIR__);
//apres de nbs h de recherches, je ne comprenais pas pk le code ne 
//fonctionnait pas alors qu'il n'y avait pas d'erreurs ...
//aprs une analyses des trames dans "network", j'ai remarqué que la requete ajax marchait correctement puis j'ai decouvert
//dans la requete qui amene à ce fichier "preview" et j'ai trouvé des warmings et des err
//en effet, je n'avais pas le bon chemin d'accees pour les include_once
//j'ai donc regardé sur iternet.. et 'ai decouvert echo(__DIR__) et j'ai pu voir que le chemin d'acces n'etait pas le bon
//j'ai donc pu corriger le chemin d'acces et le code fonctionne correctement
//echo (__DIR__);

// Vérification de la présence des données envoyées par la requête AJAX
if (isset($_GET["colors"])) {
    // Récupération des couleurs envoyées depuis la requête AJAX
    $colorsString = $_GET["colors"];
    echo "Colors received: " . $colorsString;

    // Récupération de l'ID de l'utilisateur depuis la session
    $idUser = $_SESSION['idUser'];
    echo "User ID: " . $idUser;

    // Appel à une fonction pour mettre à jour les couleurs de la palette dans la base de données
    modifier_palette($idUser, $colorsString);
}

// Vérification de la présence des données envoyées par la requête AJAX
if (isset($_GET["chaine"])) {
    // Récupération de la chaine envoyée depuis la requête AJAX
    $chaine = $_GET["chaine"];
    echo "Colors received: " . $chaine;

    // Récupération de l'ID de l'utilisateur depuis la session
    $idUser = $_SESSION['idUser'];
    echo "User ID: " . $idUser;

    // Appel à une fonction pour mettre à jour les smileys créés dans la base de données
    enregistrer_smiley($idUser, $chaine);
}

// Vérification de la présence des données envoyées par la requête AJAX
if (isset($_GET["affichage"])) {
    // Récupération de la chaine envoyée depuis la requête AJAX
    $chaine = $_GET["affichage"];
    echo "Colors received: " . $chaine;

    // Récupération de l'ID de l'utilisateur depuis la session
    $idUser = $_SESSION['idUser'];
    echo "User ID: " . $idUser;

    // Appel à une fonction pour mettre à jour les smileys dans la base de données
    supprimer_smiley($idUser, $chaine);
}

// Vérification de la présence des données envoyées par la requête AJAX
if (isset($_POST["dup"])) {
    // Récupération de la chaine à duppliquer envoyée depuis la requête AJAX
    $chaineDup = $_POST["dup"];
    //on stocke la chaine dans une variable de session pour la réafficher ensuite dans l'accueil
    $_SESSION['couleurs'] = $chaineDup;
}


?>