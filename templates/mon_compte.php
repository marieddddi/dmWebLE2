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
	<title>Smiley</title>
	<link rel="stylesheet" type="text/css" href="css/moncompte.css" />
</head>
<!-- **** F I N **** H E A D **** -->


<!-- **** B O D Y **** -->
<div id="letout">

	<h1 id="palete">Ma palette de couleurs</h1>
	<div id="liste">
		<?php
		function conversion_chaine($couleurs)
		{
			// Explode la chaîne de couleurs en un tableau en utilisant la virgule comme séparateur
			$couleursArray = explode(',', $couleurs);

			// Nettoie le tableau des éventuels espaces vides ou valeurs vides
			$couleursArray = array_filter(array_map('trim', $couleursArray));

			// Retourne le tableau des couleurs
			return $couleursArray;
		}
		// Affichage du tableau de couleurs récupéré
		valider('connecte', 'SESSION');
		$couleurs = palette_mon_compte($_SESSION['idUser']);
		$palette = conversion_chaine($couleurs);
		foreach ($palette as $p) {
			echo "<div class='color' style='background-color:" . $p . ";'></div>";
		}

		?>
	</div>

</div>
<!-- **** F I N **** B O D Y **** -->


</html>