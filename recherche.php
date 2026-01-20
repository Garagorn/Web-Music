<?php
include('donnees.php');
$connection =connecter();
$corps='
    <form action="traitement_recherche.php" method="GET">
        <label for="mot_cle">Mot-clé :</label>
            <input type="text" id="mot_cle" name="mot_cle" required>
            <br><br>
        <button type="submit">Rechercher</button>
    </form>';
$zonePrincipale = $corps;
include("squelette.php");
?> 