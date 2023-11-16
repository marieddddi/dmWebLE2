<?php
session_start();

include_once "libs/maLibUtils.php";
include_once "libs/maLibSQL.pdo.php";
include_once "libs/maLibSecurisation.php";
include_once "libs/modele.php";

//faire avec post ?????
$qs = $_GET;

if ($action = valider("action")) {
	ob_start();
	echo "Action = '$action' <br />";
	// ATTENTION : le codage des caractères peut poser PB si on utilise des actions comportant des accents... 
	// A EVITER si on ne maitrise pas ce type de problématiques

	
	switch ($action) {
		// Connexion //////////////////////////////////////////////////
		case 'SE CONNECTER':
			$qs = array("view" => "accueil");
			// On verifie la presence des champs login et passe
			if ($login = valider("login"))
				if ($passe = valider("passe")) {
					// On verifie l'utilisateur, 
					// et on crée des variables de session si tout est OK
					// Cf. maLibSecurisation
					if (verifUser($login, $passe)) {
						// tout s'est bien passé, doit-on se souvenir de la personne ? 
						if (valider("remember")) {
							setcookie("login", $login, time() + 60 * 60 * 24 * 30);
							setcookie("passe", $password, time() + 60 * 60 * 24 * 30);
							setcookie("remember", true, time() + 60 * 60 * 24 * 30);
						} else {
							setcookie("login", "", time() - 3600);
							setcookie("passe", "", time() - 3600);
							setcookie("remember", false, time() - 3600);
						}
					}
				}
			// On affiche deconnexion?>

			<?php
			break;

		case 'Logout':
			// traitement métier
			session_destroy();
			$qs = array("msg" => "Déconnexion réussie", "view" => "accueil");
			break;

		case 'INSCRIVEZ-VOUS EN CLIQUANT ICI':
			//change la vue pour pouvoir créer son compte
			$qs = array("msg" => "", "view" => "creer_compte");
			break;

		case "S INSCRIRE":
			$pseudo = valider("pseudo");
			$passe = valider("passe");
			$passe2 = valider("passe2");
			$email = valider("mail");

			// Valider l'email
			if (!validateMail($email)) {
				$qs = array("msg" => " adresse email non valide", "view" => "creer_compte");
				break;
			}

			//verifier si les mots de passes correspondent 
			if ($passe != $passe2) {
				$qs = array("msg" => " Les mots de passe ne correspondent pas", "view" => "creer_compte");
				break;
			}
			//verifier si le format de l'email est valide
			if (veriflogin($pseudo)) {
				$qs = array("msg" => " Ce login est déjà utilisé", "view" => "creer_compte");
				break;
			}
			//verifier si l'utilisateur a bien entré un pseudo, mdp et mail
			if (isset($pseudo) || isset($passe) || isset($email)) {
				$qs = array("msg" => " Veuillez entrer un pseudo", "view" => "creer_compte");
				break;
			}
			//si tout est bon, on créait le compte 
			else {
				creer_compte($pseudo, $passe, $email);
				$qs = array("view" => "accueil");
				break;
			}






		case "Mon compte":
			$qs = array("view" => "mon_compte");
			break;

		case "planning":
			$qs = array("view" => "planning");
			break;

		case "ValiderModificationCompte":
			$qs = array("view" => "accueil");
			$qs["msg"] = "Compte modifié avec succès";
			$qs["msgType"] = "success";
			$qs["msgTitle"] = "Succès";
			$qs["msgIcon"] = "check";

			$login = valider("login");
			$passe = valider("passe");
			$passe2 = valider("passe2");
			$nom = valider("nom");
			break;


		case "Valider":

			$qs = array("view" => "Frigo");
			break;

		case "Passer":
			$qs = array("view" => "generateur");
			break;

		case "Valider !":
			$test = valider("added_aliments[]");
			$qs = array("view" => "generateur&added_aliments=$test");
			break;

		case "galerie":
			$qs = array("view" => "galerie");
			break;


		case "surprise":
			$qs = array("view" => "surprise");
			break;


		case "MENU DE FETE":
			$qs = array("view" => "menu_de_fete");
			break;

		case "propos":
			$qs = array("view" => "propos");
			break;

		case "contact":
			$qs = array("view" => "contact");
			break;

		case "equipe":
			$qs = array("view" => "equipe");
			break;

		case "sevices":
			$qs = array("view" => "sevices");
			break;

		case "Envoyer":
			$titre = valider("titre");
			$contenu = valider("contenu");
			$id_recette = valider("id_page_recette");
			$sessionIdPers = $_SESSION["idUser"];
			echo $sessionIdPers . "<br>" . $id_recette . "<br>" . $titre . "<br>" . $contenu;
			posterAvis($sessionIdPers, $id_recette, $titre, $contenu);
			$qs = array("msg" => " Avis posté avec succès", "view" => "mon_compte");
			break;


		case "Modifier":
			$sessionIdPers = $_SESSION["idUser"];
			$id_page_recette = valider("id_page_recette");
			$titre = valider("titre");
			$contenu = valider("contenu");
			modifierAvis($sessionIdPers, $id_page_recette, $id_recette, $titre, $contenu);
			$qs = array("msg" => " Avis posté avec succès", "view" => "mon_compte");
			break;

		case "Modifier le pseudo":
			$sessionIdPers = $_SESSION["idUser"];
			$pseudo = valider("pseudo");
			if (veriflogin($pseudo)) {
				$qs = array("msg" => " Ce login est déjà utilisé", "view" => "mon_compte");
				break;
			} else {
				modifierPseudo($sessionIdPers, $pseudo);
				$qs = array("msg" => " Pseudo modifié avec succès", "view" => "mon_compte");
				break;
			}

		case "Modifier le mail":
			$sessionIdPers = $_SESSION["idUser"];
			$mail = valider("mail");
			if (verifmail($mail) || (!validateMail($mail))) {
				$qs = array("msg" => " Ce mail est déjà utilisé", "view" => "mon_compte");
				break;
			} else {
				modifierMail($sessionIdPers, $mail);
				$qs = array("msg" => " Mail modifié avec succès", "view" => "mon_compte");
				break;
			}

		case "Modifier le mot de passe":
			$sessionIdPers = $_SESSION["idUser"];
			$passe = valider("passe");
			$passe2 = valider("passe2");
			if ($passe != $passe2) {
				$qs = array("msg" => " Les mots de passe ne correspondent pas", "view" => "modif-mdp");
				break;
			}
			modifierPasse($sessionIdPers, $passe);
			$qs = array("msg" => " Mot de passe modifié avec succès", "view" => "mon_compte");
			break;

		case "Ajouter aux favoris":
			$sessionIdPers = $_SESSION["idUser"];
			$id_recette = valider("idRecette");
			ajouter_fav($sessionIdPers, $id_recette);
			$qs = array("msg" => " Recette ajoutée aux favoris avec succès", "view" => "mon_compte");
			break;

		case "Supprimer des favoris":
			$sessionIdPers = $_SESSION["idUser"];
			$id_recette = valider("idRecette");
			supp_fav($sessionIdPers, $id_recette);
			$qs = array("msg" => " Recette supprimée des favoris avec succès", "view" => "mon_compte");
			break;

		case "Enregistrer":
			$sessionIdPers = $_SESSION["idUser"];
			//de 0 à 13
			$recette0 = valider("recette0");
			$recette1 = valider("recette1");
			$recette2 = valider("recette2");
			$recette3 = valider("recette3");
			$recette4 = valider("recette4");
			$recette5 = valider("recette5");
			$recette6 = valider("recette6");
			$recette7 = valider("recette7");
			$recette8 = valider("recette8");
			$recette9 = valider("recette9");
			$recette10 = valider("recette10");
			$recette11 = valider("recette11");
			$recette12 = valider("recette12");
			$recette13 = valider("recette13");
			enregistrer_planning($recette0, $recette1, $recette2, $recette3, $recette4, $recette5, $recette6, $recette7, $recette8, $recette9, $recette10, $recette11, $recette12, $recette13, $sessionIdPers);
			$qs = array("msg" => " Planning enregistré avec succès", "view" => "mon_compte");
			break;
	}

}

// On redirige toujours vers la page index, mais on ne connait pas le répertoire de base
// On l'extrait donc du chemin du script courant : $_SERVER["PHP_SELF"]
// Par exemple, si $_SERVER["PHP_SELF"] vaut /chat/data.php, dirname($_SERVER["PHP_SELF"]) contient /chat

$urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
// On redirige vers la page index avec les bons arguments

//header("Location:" . $urlBase . $qs);
rediriger($urlBase, $qs);

// On écrit seulement après cette entête
ob_end_flush();

?>