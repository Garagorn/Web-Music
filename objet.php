<?php
include('donnees.php');

$corps = null;
$idM = isset($_GET["idM"]) ? (int)$_GET["idM"] : 0;

if ($idM <= 0) {
    $corps = "<h1>ID invalide</h1>";
    $zonePrincipale = $corps;
    include("squelette.php");
    exit();
}

$connection = connecter();

if (!$connection) {
    $corps = "<h1>Erreur de connexion</h1>";
    $zonePrincipale = $corps;
    include("squelette.php");
    exit();
}

$requete = "SELECT * FROM Musique WHERE idM = ?";

try {
    $stmt = $connection->prepare($requete);
    $stmt->execute([$idM]);
    $stmt->setFetchMode(PDO::FETCH_OBJ);
    
    $musique = $stmt->fetch();
    
    if ($musique) {
        $corps = "<h1>Détails de la musique</h1>";
        $corps .= "<div class='grid-container'>";
        $corps .= "<div class='item'>idM</div>";
        $corps .= "<div class='item'>Titre</div>";
        $corps .= "<div class='item'>Groupe</div>";
        $corps .= "<div class='item'>Album</div>";
        $corps .= "</div>";
        
        $corps .= "<div class='grid-container'>";
        $corps .= '<span><u><b>' . htmlspecialchars($musique->idM) . '</b></u></span>';
        $corps .= '<span><u><b>' . htmlspecialchars($musique->titre) . '</b></u></span>';
        $corps .= '<span><u><b>' . htmlspecialchars($musique->groupe) . '</b></u></span>';
        $corps .= '<span><u><b>' . htmlspecialchars($musique->album) . '</b></u></span>';
        $corps .= '</div>';
        
        $corps .= '<p><a href="index.php?action=afficher">Retour à la liste</a></p>';
    } else {
        $corps = "<h1>Musique introuvable</h1>";
        $corps .= '<p><a href="index.php?action=afficher">Retour à la liste</a></p>';
    }
    
} catch (PDOException $e) {
    $corps = "<h1>Erreur lors de l'exécution de la requête</h1>";
    error_log("Erreur objet.php : " . $e->getMessage());
} finally {
    $stmt = null;
    $connection = null;
}

$zonePrincipale = $corps;
include("squelette.php");
?>
