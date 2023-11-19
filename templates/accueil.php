<?php
include_once("libs/modele.php");
include_once("libs/maLibForms.php");

if (basename($_SERVER["PHP_SELF"]) != "index.php") {
	header("Location:../index.php?view=accueil");
	die("");
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Smiley</title>
	<link rel="stylesheet" type="text/css" href="css/accueil.css" />
</head>

<body>
	<form>
		<label for="table-size">Taille du tableau :</label>
		<input type="number" id="table-size" name="table-size" min="1" max="50" value="10">
		<button type="button" id="create-table">Créer le tableau</button>
	</form>

	<div class="container">
		<table id="pixel-table"></table>
	</div>

	<div class="container">
		<div id="color-palette"></div>
		<input type="file" id="upload-image">
	</div>

	<button type="button" id="export-small">Exporter en PNG (Petite)</button>
	<button type="button" id="export-medium">Exporter en PNG (Moyenne)</button>
	<button type="button" id="export-large">Exporter en PNG (Grande)</button>

	<div class="container">
		<h2>Smileys produits :</h2>
		<div id="produced-smileys"></div>
	</div>

	<script src="js/footer.js"></script>
	<script src="js/accueil.js"></script>
</body>

</html>