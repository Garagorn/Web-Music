<?php
include('donnees.php');

$connection = connecter();

$corps = '<h1>Recherche de musique</h1>';
$corps .= '<form action="traitement_recherche.php" method="GET">';
$corps .= '<label for="mot_cle">Mot-clé :</label>';
$corps .= '<input type="text" id="mot_cle" name="mot_cle" required placeholder="Titre, groupe ou album">';
$corps .= '<br><br>';
$corps .= '<button type="submit" class="btn btn-primary">Rechercher</button>';
$corps .= ' <a href="index.php?action=afficher" class="btn btn-secondary">Annuler</a>';
$corps .= '</form>';

$zonePrincipale = $corps;
include("squelette.php");
?>
