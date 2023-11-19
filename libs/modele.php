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



/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : Cette fonction ne retourne rien. */
function modifierPseudo($sessionIdPers, $pseudo)
{
	$sql = "UPDATE utilisateur SET pseudo='$pseudo' WHERE id='$sessionIdPers'";
	if (SQLUpdate($sql))
		$_SESSION["pseudo"] = $pseudo;
}



/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : Cette fonction ne retourne rien. */
function modifierPasse($sessionIdPers, $passe)
{
	$sql = "UPDATE `utilisateur` SET `mdp` = '$passe' WHERE `utilisateur`.`id` = '$sessionIdPers' ";
	SQLUpdate($sql);
}

function liste_couleurs($idUser)
{
	$sql = "SELECT couleur.code FROM couleur JOIN palette ON palette.couleur=couleur.code 
			JOIN utilisateur ON utilisateur.id=palette.id 
			WHERE utilisateur.id = '$idUser'";
	return parcoursRs(SQLSelect($sql));
}


?>