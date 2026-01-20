<?php
include('donnees.php');
$corps=null;

$idM=$_GET["idM"];
$connection =connecter();
$requete = "SELECT * FROM Musique WHERE idM=$idM";

try {
            
    // Exécution de la requête
    $query = $connection->query($requete);
    $query->setFetchMode(PDO::FETCH_OBJ);
    $corps .= "<div class='grid-container'>";
    $corps .= "<div class='item'>idM</div>";
    $corps .= "<div class='item'>Titre</div>";
    $corps .= "<div class='item'>Groupe</div>";
    $corps .= "<div class='item'>Album</div>";
    $corps .="</div>";

    while($req=$query->fetch()){
        $corps .="<div class='grid-container'>";
        //Encodage
        $corps .='<span><u><b>' . htmlspecialchars($req->idM) . '</b></u></span> <span><u><b>' . htmlspecialchars($req->titre) . '</b></u></span><span><u><b>' . htmlspecialchars($req->groupe) . '</b></u></span><span><u><b>' . htmlspecialchars($req->album) . '</b></u></span>';
        $corps .='</div>'; //Fermer le div
    }
    
    
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : ", $e->getMessage();
} finally {
    // Fermeture de la connexion et libération de la ressource
    $query = null;
    $connection = null;
    $zonePrincipale = $corps;
}

include("squelette.php");
?>
