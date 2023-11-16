<?php
include_once("libs/modele.php");
include_once("libs/maLibForms.php");
include_once("libs/maLibSecurisation.php");
include_once("libs/maLibUtils.php");
//C'est la propriété php_self qui nous l'indique : 
// Quand on vient de index : 
// [PHP_SELF] => /chatISIG/index.php 
// Quand on vient directement par le répertoire templates
// [PHP_SELF] => /chatISIG/templates/accueil.php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
// Pas de soucis de bufferisation, puisque c'est dans le cas où on appelle directement la page sans son contexte
if (basename($_SERVER["PHP_SELF"]) != "index.php") {
	header("Location:../index.php?view=accueil");
	die("");
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- **** H E A D **** -->

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Les recettes du chef</title>
	<link rel="stylesheet" type="text/css" href="css/moncompte.css" />
</head>
<!-- **** F I N **** H E A D **** -->


<!-- **** B O D Y **** -->
<div id="letout">

	<h1 id="anciens_plannings">MES ANCIENS PLANNINGS</h1>
	<div id="liste">
		<?php
		valider('connecte', 'SESSION');
		$planning = liste_planning($_SESSION["idUser"]);
		foreach ($planning as $p) {
			$clique = "<div id='planning'>Semaine " . $p['num_semaine'] . "</div>";
			$idPlanning = idPlanning($_SESSION["idUser"], $p['num_semaine']);
			mkLien($url = "index.php", $clique, $qs = "view=planning&id_planning=" . $idPlanning, $attrs = "");
		}
		?>
	</div>

	<h1 id="recettes_fav">MES RECETTES FAVORITES</h1>
	<div id="listefav">
		<?php
		$recettes = liste_recette_fav($_SESSION['idUser']);
		if ($recettes == NULL) {
			echo "<div id='planning'>Il n'y a pas de recette favorite</div>";
		} else {
			foreach ($recettes as $r) {
				$clique = $r['id_page_recette'];
				echo "<div id='recette'><a href='index.php?view=page_recette&id_page_recette=" . $clique . "'><img src='" . $r['lien_presentation'] . "'/></a></div>";
			}
		}
		?>
	</div>

	<h1 id="lesavis">MES AVIS</h1>
	<div id="liste">
		<?php
		$avis = liste_avis_personne($_SESSION['idUser']);
		foreach ($avis as $a) {
			echo "<div id='avis'><h3>" . $a['nom'] . "</h3><h5>" . $a['titre'] . "</h5><p>" . $a['texte_avis'] . "</p>";
			echo "<a href='index.php?view=avis-modif&id_page_recette=" . $a['id_page_recette'] . "'> Modifier </a>";
			echo "</div>";
		}
		?>
	</div>

	<h1 id="mescoordonnees">MES COORDONNÉES</h1>
	<div id="liste">
		<?php
		echo "<div id='coordonnees'>PSEUDO : " . $_SESSION['pseudo'] . "<div><a href='index.php?view=modif-pseudo&idUser'>Modifier</a></div></div>";
		echo "<div id='coordonnees'>ADRESSE MAIL : " . $_SESSION['mail'] . "<div><a href='index.php?view=modif-mail&idUser'>Modifier</a></div></div>";
		echo "<div id='coordonnees'>MOT DE PASSE : XXXXXXXXXXXXXXXXXX <div><a href='index.php?view=modif-mdp'>Modifier</a></div></div>";

		?>
	</div>

</div>
<!-- **** F I N **** B O D Y **** -->


</html>