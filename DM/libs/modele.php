<?php

include_once "libs/maLibSQL.pdo.php";

/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre site web.
*/


/********* fonctions pour utiliser la base de données *********/



/* Récupère l'image souhaitée associée à une recette
 * Paramètres :
 * - $id_recette : int - identifiant de la recette
 * - $num : int - Le numéro d'image à récupérer :
 * 1: image de présentaiton, 2: image du premier ingrédient, 3: image du deuxième ingrédient, 4: image finale 
 * Retour : string - Le lien de l'image */
function image_recette($id_recette, $num)
{
	switch ($num) {
		case 1:
			$sql = "SELECT lien_presentation 
				FROM ILLUSTRATIONS
				WHERE id_page_recette = '$id_recette'";
			break;
		case 2:
			$sql = "SELECT INGREDIENTS.lien_photo
				FROM INGREDIENTS 
				JOIN ILLUSTRATIONS ON INGREDIENTS.id_ingredient = ILLUSTRATIONS.id_ingredient1
				WHERE ILLUSTRATIONS.id_page_recette = '$id_recette'";
			break;
		case 3:
			$sql = "SELECT INGREDIENTS.lien_photo
				FROM INGREDIENTS 
				JOIN ILLUSTRATIONS ON INGREDIENTS.id_ingredient = ILLUSTRATIONS.id_ingredient2
				WHERE ILLUSTRATIONS.id_page_recette = '$id_recette'";
			break;
		case 4:
			$sql = "SELECT lien_final
				FROM ILLUSTRATIONS
				WHERE id_page_recette = '$id_recette'";
			break;
		default:
			$sql = "SELECT lien_presentation 
				FROM ILLUSTRATIONS
				WHERE id_page_recette = '$id_recette'";
			break;
	}
	return SQLGetChamp($sql);
}




/* Récupère le titre d'une recette
 * Paramètres :
 * - $id_recette : int - identifiant de la recette
 * Retour :  string - Le titre de la recette */
function titre_recette($id_recette)
{
	$sql = "SELECT nom
		  FROM PAGE_RECETTES
		  WHERE id_page_recette = '$id_recette'";
	return SQLGetChamp($sql);
}



/* Crée un compte utilisateur
 * Paramètres :
 * - $pseudo : string - Le pseudo de l'utilisateur
 * - $mdp : string - Le mot de passe de l'utilisateur
 * - $mail : string - L'adresse e-mail de l'utilisateur
 * Retour :  void */
function creer_compte($pseudo, $mdp, $mail)
{
	$sql = "INSERT INTO PERSONNE (pseudo, adresse_mail, mot_de_passe)
		  VALUES ('$pseudo', '$mail', '$mdp')";
	SQLInsert($sql);
}


/* Vérifie si un pseudo existe dans la base de données
 * Paramètres :
 * - $pseudo : string - Le pseudo à vérifier
 * Retour :  string - Le pseudo si trouvé, sinon false */
function veriflogin($pseudo)
{
	$sql = "SELECT pseudo
		  FROM PERSONNE
		  WHERE pseudo = '$pseudo'";
	return SQLGetChamp($sql);
}



/* Vérifie si un pseudo existe dans la base de données
 * Paramètres :
 * - $pseudo : string - Le pseudo à vérifier
 * Retour :  string - Le pseudo si trouvé, sinon false */
function verifmail($mail)
{
	$sql = "SELECT adresse_mail
			FROM PERSONNE
			WHERE adresse_mail = '$mail'";
	return SQLGetChamp($sql);
}



/* Vérifie l'identité d'un utilisateur dans la base de données
 * Paramètres :
 * - $pseudo : string - Le pseudo de l'utilisateur
 * - $mot_de_passe : string - Le mot de passe de l'utilisateur
 * Retour :  int|string - L'ID de l'utilisateur si succès, sinon false */
function verifUserBdd($pseudo, $mot_de_passe)
{
	$SQL = "SELECT idUser FROM PERSONNE WHERE pseudo='$pseudo' AND mot_de_passe='$mot_de_passe'";
	return SQLGetChamp($SQL);
}

/* Vérifie l'adresse mail d'un utilisateur dans la base de données
 * Paramètres :
 * - $pseudo : string - Le pseudo de l'utilisateur
 * - $mot_de_passe : string - Le mot de passe de l'utilisateur
 * Retour :  string - L'adresse mail de l'utilisateur si succès, sinon false */
function verifMailBdd($pseudo, $mot_de_passe)
{
	$SQL = "SELECT adresse_mail FROM PERSONNE WHERE pseudo='$pseudo' AND mot_de_passe='$mot_de_passe'";
	return SQLGetChamp($SQL);
}


/* Valide une adresse e-mail
 * Paramètres :
 * - $email : string - L'adresse e-mail à valider
 * Retour : bool - true si l'adresse est valide, sinon false */
function validateMail($email)
{
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}


/* Récupère le titre d'une recette en fonction de son type
 * Paramètres :
 * - $id_recette : int - L'identifiant de la recette
 * - $type : string - Le type de la recette
 * Retour :  string - Le titre de la recette */
function titre_recette_fete($id_recette, $type)
{
	$sql = "SELECT nom
		FROM PAGE_RECETTES
		WHERE id_page_recette = '$id_recette' AND type = '$type'";
	return SQLGetChamp($sql);
}



/* Récupère une image associée à une recette en fonction de son type
 * Paramètres :
 * - $id_recette : int - L'identifiant de la recette
 * - $type : string - Le type de la recette
 * Retour : string - Le lien de l'image */
function image_recette_fete($id_recette, $type)
{
	$sql = "SELECT lien_presentation 
		FROM ILLUSTRATIONS
		JOIN PAGE_RECETTES ON ILLUSTRATIONS.id_page_recette = PAGE_RECETTES.id_page_recette
		WHERE ILLUSTRATIONS.id_page_recette = '$id_recette' AND PAGE_RECETTES.type = '$type'";
	return SQLGetChamp($sql);
}



/* Récupère la liste des numéros de semaine associés à un utilisateur
 * Paramètres :
 * - $idUser : int - L'identifiant de l'utilisateur
 * Retour : array - La liste des numéros de semaine */
function liste_planning($idUser)
{
	$sql = "SELECT num_semaine
		FROM PERSONNE JOIN LISTE_PLANNING ON PERSONNE.id_liste_planning= LISTE_PLANNING.id_liste_planning
		JOIN PLANNING ON PLANNING.id_planning = LISTE_PLANNING.id_planning
		WHERE idUser = '$idUser'";

	return parcoursRS(SQLSelect($sql));
}

/* Récupère l'identifiant d'un planning en fonction de l'utilisateur et du numéro de semaine
 * Paramètres :
 * - $idUser : int - L'identifiant de l'utilisateur
 * - $num_semaine : int - Le numéro de semaine
 * Retour : int - L'identifiant du planning */
function idPlanning($idUser, $num_semaine)
{
	$sql = "SELECT PLANNING.id_planning
		FROM PERSONNE JOIN LISTE_PLANNING ON PERSONNE.id_liste_planning= LISTE_PLANNING.id_liste_planning
		JOIN PLANNING ON PLANNING.id_planning = LISTE_PLANNING.id_planning
		WHERE PERSONNE.idUser = '$idUser' AND PLANNING.num_semaine = '$num_semaine'";

	return SQLGetChamp($sql);
}

/* Récupère la liste des recettes associées à un planning
 * Paramètres :
 * - $id_planning : int - L'identifiant du planning
 * Retour : array - La liste des identifiants des recettes */
function recup_recettes_planning($id_planning)
{
	$sql = "SELECT PAGE_RECETTES.id_page_recette
		FROM PLANNING JOIN LISTE_RECETTES ON PLANNING.id_liste = LISTE_RECETTES.id_liste
		JOIN PAGE_RECETTES ON PAGE_RECETTES.id_page_recette = LISTE_RECETTES.id_page_recette
		WHERE PLANNING.id_planning = '$id_planning'";

	return parcoursRS(SQLSelect($sql));
}

/* Récupère le temps de préparation associé à une recette.
 * Paramètres :
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : string - Le temps de préparation */
function tempsPreparation($id_recette)
{
	$sql = "SELECT temps_prep 
	FROM PAGE_RECETTES
	WHERE id_page_recette='$id_recette' ";
	return SQLGetChamp($sql);
}


/* Récupère le temps de cuisson associé à une recette.
 * Paramètres :
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : string - Le temps de cuisson */
function tempsCuisson($id_recette)
{
	$sql = "SELECT temps_cuisson 
	FROM PAGE_RECETTES
	WHERE id_page_recette='$id_recette' ";
	return SQLGetChamp($sql);
}


/* Récupère la note associée à une recette.
 * Paramètres :
 * - $id_recette : int - L'identifiant de la recette
 * Retour : int - La note de la recette. */
function note($id_recette)
{
	$sql = "SELECT note 
	FROM PAGE_RECETTES
	WHERE id_page_recette='$id_recette' ";
	return SQLGetChamp($sql);
}


/* Récupère la liste des ingrédients associés à une recette
 * Paramètres :
 * - $id_recette : int - L'identifiant de la recette
 * Retour : array - La liste des ingrédients avec leur quantité et unité. */
function Liste_ingredients($id_recette)
{
	$sql = "SELECT I.nom, L.quantite, L.unite_universelle
	FROM INGREDIENTS AS I JOIN LISTE_INGREDIENTS AS L ON I.id_ingredient=L.id_ingredient
	JOIN PAGE_RECETTES AS P ON P.id_liste_ingredents=L.id_liste_ingredients
	WHERE P.id_page_recette='$id_recette' ";
	return parcoursRS(SQLSelect($sql));
}



/* Récupère la liste des étapes d'une recette.
 * Paramètres :
 * - $id_recette : int - L'identifiant de la recette
 * Retour : string - La liste des étapes de la recette. */
function liste_etapes($id_recette)
{
	$sql = "SELECT liste_etape
	FROM PAGE_RECETTES
	WHERE id_page_recette='$id_recette' ";
	return SQLGetChamp($sql);
}



/* Récupère la liste des avis d'une recette.
 * Paramètres :
 * - $id_recette : int - L'identifiant de la recette
 * Retour : array - La liste des avis avec leur titre, texte et pseudo de l'auteur. */
function liste_avis($id_recette)
{
	$sql = "SELECT AVIS.titre, AVIS.texte_avis, PERSONNE.pseudo
	FROM PERSONNE 
	JOIN AVIS  ON PERSONNE.idUser=AVIS.idUser
	JOIN PAGE_RECETTES ON PAGE_RECETTES.id_page_recette=AVIS.id_page_recette 
	WHERE PAGE_RECETTES.id_page_recette='$id_recette' ";
	return parcoursRS(SQLSelect($sql));
}



/* Récupère la liste des avis d'une personne.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * Retour :  array - La liste des avis de la personne. */
function liste_avis_personne($idUser)
{
	$sql = "SELECT id_avis,nom,titre,texte_avis,AVIS.id_page_recette FROM `AVIS` JOIN `PAGE_RECETTES` ON PAGE_RECETTES.id_page_recette = AVIS.id_page_recette WHERE idUser = '$idUser'";
	return parcoursRS(SQLSelect($sql));
}



/* Récupère la liste des recettes favorites d'une personne.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * Retour : array - La liste des liens de présentation des recettes favorites. */
function liste_recette_fav($idUser)
{
	$sql = "SELECT ILLUSTRATIONS.id_page_recette,lien_presentation 
		FROM `ILLUSTRATIONS`
		JOIN  PAGE_RECETTES ON PAGE_RECETTES.id_page_recette = ILLUSTRATIONS.id_page_recette
		JOIN FAVORIS ON FAVORIS.id_page_recette = PAGE_RECETTES.id_page_recette
		WHERE FAVORIS.idUser = '$idUser';";
	return parcoursRS(SQLSelect($sql));
}



/*  Vérifie si une recette existe dans la base de données.
 * Paramètres :
 * - $id_recette : int - L'identifiant de la recette.
 * - $type : string - Le type de la recette.
 * Retour :  string - L'identifiant de la recette si elle existe */
function verifier_recette_bdd($id_recette, $type)
{
	$sql = "SELECT id_page_recette
	FROM PAGE_RECETTES
	WHERE id_page_recette='$id_recette' AND type='$type' ";
	return SQLGetChamp($sql);
}



/*  Poster un avis sur une recette.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * - $titre : string - Le titre de l'avis.
 * - $texte_avis : string - Le texte de l'avis.
 * Retour : Cette fonction ne retourne rien. */
function posterAvis($idUser, $id_recette, $titre, $texte_avis)
{
	$sql = "INSERT INTO AVIS (titre, texte_avis, idUser, id_page_recette)
	VALUES ('$titre','$texte_avis','$idUser','$id_recette')";
	SQLInsert($sql);
	$id_avis = SQLGetChamp("SELECT id_avis FROM AVIS WHERE idUser='$idUser' AND titre='$titre' AND texte_avis='$texte_avis'");
	return $id_avis;
}

/*  Modifier un avis sur une recette.
 * Paramètres :
 * - $sessionIdPers : int - L'identifiant de la personne.
 * - $id_page_recette : int - L'identifiant de la recette.
 * - $id_recette : int - L'identifiant de la recette.
 * - $titre : string - Le titre de l'avis.
 * - $contenu : string - Le texte de l'avis.
 * Retour : Cette fonction ne retourne rien. */
function modifierAvis($sessionIdPers, $id_page_recette, $id_recette, $titre, $contenu)
{
	$sql = "UPDATE AVIS SET titre='$titre', texte_avis='$contenu' WHERE id_page_recette='$id_page_recette' AND idUser='$sessionIdPers'";
	SQLUpdate($sql);
}

/*  Supprimer un avis sur une recette.
 * Paramètres :
 * - $id_avis : int - L'identifiant de l'avis.
 * Retour : Cette fonction ne retourne rien. */
function supprimerAvis($id_avis)
{
	SQLDelete("DELETE FROM AVIS WHERE id_avis='$id_avis'");
}


/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : Cette fonction ne retourne rien. */
function ajouter_fav($idUser, $id_recette)
{
	$sql = "INSERT INTO `FAVORIS` (`id_page_recette`, `idUser`) 
	VALUES ('$id_recette', '$idUser')";
	SQLInsert($sql);
}

/*  Vérifie si une recette est en favoris.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * Retour :  string - L'identifiant de la recette si elle existe */
function isFavoris($idUser, $id_recette)
{
	$sql = "SELECT * FROM `FAVORIS` WHERE `id_page_recette` = '$id_recette' AND `idUser` = '$idUser'";
	return SQLGetChamp($sql);
}

/*  Spprimer une recette en favoris.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : Cette fonction ne retourne rien. */
function supp_fav($idUser, $id_recette)
{
	$sql = "DELETE FROM `FAVORIS` WHERE `id_page_recette` = '$id_recette' AND `idUser` = '$idUser'";
	SQLDelete($sql);
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : Cette fonction ne retourne rien. */
function modifierPseudo($sessionIdPers, $pseudo)
{
	$sql = "UPDATE PERSONNE SET pseudo='$pseudo' WHERE idUser='$sessionIdPers'";
	if (SQLUpdate($sql))
		$_SESSION["pseudo"] = $pseudo;
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : Cette fonction ne retourne rien. */
function modifierMail($sessionIdPers, $mail)
{
	$sql = "UPDATE `PERSONNE` SET `adresse_mail` = '$mail' WHERE `PERSONNE`.`idUser` = '$sessionIdPers' ";
	if (SQLUpdate($sql))
		$_SESSION['mail'] = $mail;
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : Cette fonction ne retourne rien. */
function modifierPasse($sessionIdPers, $passe)
{
	$sql = "UPDATE `PERSONNE` SET `mot_de_passe` = '$passe' WHERE `PERSONNE`.`idUser` = '$sessionIdPers' ";
	SQLUpdate($sql);
}


/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : Cette fonction ne retourne rien. */
function ingredients()
{
	$sql = "SELECT nom, id_ingredient FROM INGREDIENTS";
	return parcoursRS(SQLSelect($sql));
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $idUser : int - L'identifiant de la personne.
 * - $id_recette : int - L'identifiant de la recette.
 * Retour : Cette fonction ne retourne rien. */
function nombreDeRecette()
{
	$sql = "SELECT COUNT(*) FROM PAGE_RECETTES";
	return SQLGetChamp($sql);
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $index_debut : int - L'index de début.
 * - $recettes_par_page : int - Le nombre de recettes par page.
 * - $duree_min : int - La durée minimale.
 * - $note_min : int - La note minimale.
 * Retour : Cette fonction ne retourne rien. */
function getRecettesFiltrees($index_debut, $recettes_par_page, $duree_min, $note_min)
{
	$duree_min_sec = strtotime("1970-01-01 $duree_min UTC");

	if ($duree_min == 0 && $note_min == 0) {
		$sql = "SELECT id_page_recette, nom FROM PAGE_RECETTES LIMIT $index_debut, $recettes_par_page";
	} else if ($duree_min == 0) {
		$sql = "SELECT id_page_recette, nom FROM PAGE_RECETTES WHERE note >= $note_min LIMIT $index_debut, $recettes_par_page";
	} else if ($note_min == 0) {
		$sql = "SELECT id_page_recette, nom FROM PAGE_RECETTES WHERE TIME_TO_SEC(temps_prep) + TIME_TO_SEC(temps_cuisson) <= $duree_min_sec LIMIT $index_debut, $recettes_par_page";
	} else {
		$sql = "SELECT id_page_recette, nom FROM PAGE_RECETTES WHERE TIME_TO_SEC(temps_prep) + TIME_TO_SEC(temps_cuisson) <= $duree_min_sec AND note >= $note_min LIMIT $index_debut, $recettes_par_page";
	}

	return parcoursRs(SQLSelect($sql));
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $index_debut : int - L'index de début.
 * - $recettes_par_page : int - Le nombre de recettes par page.
 * - $duree_min : int - La durée minimale.
 * - $note_min : int - La note minimale.
 * Retour : Cette fonction ne retourne rien. */
function recherche_aliment($aliment)
{
	$sql = "SELECT id_ingredient, nom FROM INGREDIENTS WHERE nom LIKE '%$aliment%'";
	return parcoursRs(SQLSelect($sql));
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $index_debut : int - L'index de début.
 * - $recettes_par_page : int - Le nombre de recettes par page.
 * - $duree_min : int - La durée minimale.
 * - $note_min : int - La note minimale.
 * Retour : Cette fonction ne retourne rien. */
function ajouter_ingredient_frigo($id_ingredient, $idUser)
{
	$sql = "INSERT INTO `FRIGO` (`id_ingredient`, `id_pers`) VALUES ('$id_ingredient', '$idUser')";
	SQLInsert($sql);
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $index_debut : int - L'index de début.
 * - $recettes_par_page : int - Le nombre de recettes par page.
 * - $duree_min : int - La durée minimale.
 * - $note_min : int - La note minimale.
 * Retour : Cette fonction ne retourne rien. */
function recup_recettes_generateur($idUser)
{
	$sql = "SELECT DISTINCT pr.id_page_recette
		FROM PAGE_RECETTES pr
		JOIN LISTE_RECETTES lr ON pr.id_page_recette = lr.id_page_recette
		JOIN PLANNING p ON lr.id_liste = p.id_liste
		JOIN LISTE_PLANNING lp ON p.id_planning = lp.id_planning
		JOIN PERSONNE pe ON lp.id_liste_planning = pe.id_liste_planning
		WHERE pr.id_page_recette NOT IN (
			SELECT lr.id_page_recette
			FROM LISTE_RECETTES lr
			JOIN PLANNING pl ON lr.id_liste = pl.id_liste
			WHERE pl.num_semaine = WEEK(NOW()) - 1
			) 
		AND pe.idUser = $idUser
		LIMIT 14;";

	return parcoursRs(SQLSelect($sql));
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $index_debut : int - L'index de début.
 * - $recettes_par_page : int - Le nombre de recettes par page.
 * - $duree_min : int - La durée minimale.
 * - $note_min : int - La note minimale.
 * Retour : Cette fonction ne retourne rien. */
function recup_recettes_aleatoires($nombre)
{
	$sql = "SELECT DISTINCT id_page_recette FROM PAGE_RECETTES WHERE type = '2' ORDER BY RAND() LIMIT $nombre";
	return parcoursRs(SQLSelect($sql));
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $index_debut : int - L'index de début.
 * - $recettes_par_page : int - Le nombre de recettes par page.
 * - $duree_min : int - La durée minimale.
 * - $note_min : int - La note minimale.
 * Retour : Cette fonction ne retourne rien. */
function dernier_id_liste()
{
	$sql = "SELECT MAX(id_liste) FROM LISTE_RECETTES";
	return SQLGetChamp($sql);
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $index_debut : int - L'index de début.
 * - $recettes_par_page : int - Le nombre de recettes par page.
 * - $duree_min : int - La durée minimale.
 * - $note_min : int - La note minimale.
 * Retour : Cette fonction ne retourne rien. */
function dernier_id_planning()
{
	$sql = "SELECT MAX(id_planning) FROM PLANNING";
	return SQLGetChamp($sql);
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $index_debut : int - L'index de début.
 * - $recettes_par_page : int - Le nombre de recettes par page.
 * - $duree_min : int - La durée minimale.
 * - $note_min : int - La note minimale.
 * Retour : Cette fonction ne retourne rien. */
function la_bonne_liste_planning($idUser)
{
	$sql = "SELECT id_liste_planning FROM PERSONNE WHERE idUser = $idUser";
	return SQLGetChamp($sql);
}

/*  Ajouter une recette en favoris.
 * Paramètres :
 * - $index_debut : int - L'index de début.
 * - $recettes_par_page : int - Le nombre de recettes par page.
 * - $duree_min : int - La durée minimale.
 * - $note_min : int - La note minimale.
 * Retour : Cette fonction ne retourne rien. */
function enregistrer_planning($recette0, $recette1, $recette2, $recette3, $recette4, $recette5, $recette6, $recette7, $recette8, $recette9, $recette10, $recette11, $recette12, $recette13, $idUser)
{
	$id_liste = dernier_id_liste() + 1;
	//ajouter chaque id_recette du tableau recette dans liste_recettes
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette0')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette1')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette2')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette3')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette4')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette5')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette6')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette7')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette8')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette9')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette10')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette11')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette12')";
	SQLInsert($sql);
	$sql = "INSERT INTO `LISTE_RECETTES` (`id_liste`, `id_page_recette`) VALUES ('$id_liste', '$recette13')";
	SQLInsert($sql);


	//ajouter id_liste dans planning
	$id_planning = dernier_id_planning() + 1;
	$sql = "INSERT INTO `PLANNING` (`id_planning`, `id_liste`,`num_semaine`) VALUES ('$id_planning', '$id_liste',WEEK(NOW()))";
	SQLInsert($sql);
	//ajouter id_planning dans liste_planning
	$id_liste_planning = la_bonne_liste_planning($idUser);
	$sql = "INSERT INTO `LISTE_PLANNING` (`id_liste_planning`, `id_planning`) VALUES ('$id_liste_planning', '$id_planning') ";
	SQLInsert($sql);

}

?>