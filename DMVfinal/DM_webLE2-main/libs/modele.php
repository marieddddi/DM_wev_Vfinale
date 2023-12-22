<?php

include_once "libs/maLibSQL.pdo.php";

/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre site web.
*/


/********* fonctions pour utiliser la base de données *********/



/* Crée un compte utilisateur
 * Paramètres :
 * - $nom : string - Le nom de l'utilisateur
 * - $prenom : string - Le prenom de l'utilisateur
 * - $mail : string - L'adresse e-mail de l'utilisateur
 * - $mdp : string - Le mot de passe de l'utilisateur
 * Retour :  void */
function creer_compte($nom, $prenom, $pseudo, $mdp)
{
	$sql = "INSERT INTO utilisateur (nom, prenom, pseudo, mdp)
		  VALUES ('$nom', '$prenom', '$pseudo', '$mdp')";
	SQLInsert($sql);
}


/* Vérifie si un pseudo existe dans la base de données
 * Paramètres :
 * - $pseudo : string - Le pseudo à vérifier
 * Retour :  string - Le pseudo si trouvé, sinon false */
function veriflogin($pseudo)
{
	$sql = "SELECT pseudo
		  FROM utilisateur
		  WHERE pseudo = '$pseudo'";
	return SQLGetChamp($sql);
}



/* Vérifie l'identité d'un utilisateur dans la base de données
 * Paramètres :
 * - $pseudo : string - Le pseudo de l'utilisateur
 * - $mot_de_passe : string - Le mot de passe de l'utilisateur
 * Retour :  int|string - L'ID de l'utilisateur si succès, sinon false */
function verifUserBdd($pseudo, $mdp)
{
	$SQL = "SELECT id FROM utilisateur WHERE pseudo='$pseudo' AND mdp='$mdp'";
	return SQLGetChamp($SQL);
}

//recupere la palette de l'utilisateur selon son id
function palette_mon_compte($idUser)
{
	$sql = "SELECT palette.couleur FROM palette
			WHERE palette.id = '$idUser'";
	return SQLGetChamp($sql);
}

//modifie la palette de l'utilisateur selon son id et ses nouveaux choix
function modifier_palette($idUser, $colorsString)
{
	$sql = "UPDATE palette
			SET couleur = '$colorsString'
			WHERE id = '$idUser'";
	SQLUpdate($sql);

}

//recupere les smileys de l'utilisateur selon son id
function smileys_mon_compte($idUser)
{
	$sql = "SELECT smiley.chaine FROM smiley
	JOIN utilisateur ON utilisateur.id=smiley.id_user
			WHERE utilisateur.id = '$idUser'";
	return parcoursRS(SQLSelect($sql));
}

//supprime un smiley de l'utilisateur selon son id et la chaine du smiley
function supprimer_smiley($idUser, $chaine)
{
	$sql = "DELETE FROM smiley
			WHERE id_user = '$idUser' AND chaine = '$chaine'";
	SQLDelete($sql);
}

//enregistre un smiley de l'utilisateur selon son id et la chaine du smiley
function enregistrer_smiley($idUser, $chaine)
{
	$sql = "INSERT INTO smiley (id_user, chaine) VALUES ('$idUser', '$chaine')";
	SQLInsert($sql);

}



?>