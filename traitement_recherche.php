<?php
include('donnees.php');

$connection = connecter();
$corps = null;

$les_mots_cles = isset($_GET['mot_cle']) ? trim($_GET['mot_cle']) : null;

if (!$connection) {
    $corps = "<h1>Échec de la connexion à la base de données</h1>";
    $zonePrincipale = $corps;
    include("squelette.php");
    exit();
}

if (empty($les_mots_cles)) {
    $corps = "<h1>Veuillez saisir un mot-clé</h1>";
    $corps .= '<p><a href="recherche.php">Retour à la recherche</a></p>';
    $zonePrincipale = $corps;
    include("squelette.php");
    exit();
}

$tableau_mots_cles = explode(" ", $les_mots_cles);
$conditions = [];
$bind_params = [];


foreach ($tableau_mots_cles as $mot) {
    $mot = trim($mot);
    
    if (strlen($mot) > 3) {
        $mot = rtrim($mot, "s");
    }

    if (strlen($mot) > 2) {
        $like = "%" . $mot . "%";
        $conditions[] = "(titre LIKE ? OR groupe LIKE ? OR album LIKE ?)";
        $bind_params[] = $like; 
        $bind_params[] = $like; 
        $bind_params[] = $like; 
    }
}

if (empty($conditions)) {
    $corps = "<h1>Mots-clés trop courts</h1>";
    $corps .= "<p>Veuillez saisir des mots-clés d'au moins 3 caractères.</p>";
    $corps .= '<p><a href="recherche.php">Retour à la recherche</a></p>';
    $zonePrincipale = $corps;
    include("squelette.php");
    exit();
}

try {
    $requete = "SELECT * FROM Musique WHERE " . implode(" OR ", $conditions) . " ORDER BY titre";
    
    $statement = $connection->prepare($requete);
    $statement->execute($bind_params);
    
    $resultats = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($resultats) > 0) {
        $corps = "<h1>Résultat(s) de la recherche : " . htmlspecialchars($les_mots_cles) . "</h1>";
        $corps .= "<p>" . count($resultats) . " résultat(s) trouvé(s)</p>";
        
        $corps .= "<div class='grid-container'>";
        $corps .= "<div class='item'><strong>idM</strong></div>";
        $corps .= "<div class='item'><strong>Titre</strong></div>";
        $corps .= "<div class='item'><strong>Groupe</strong></div>";
        $corps .= "<div class='item'><strong>Album</strong></div>";
        $corps .= "</div>";
        
        foreach ($resultats as $musique) {
            $corps .= "<div class='grid-container'>";
            $corps .= '<span><u><b>' . htmlspecialchars($musique["idM"]) . '</b></u></span>';
            $corps .= '<span><a href="objet.php?idM=' . urlencode($musique["idM"]) . '">' . htmlspecialchars($musique["titre"]) . '</a></span>';
            $corps .= '<span>' . htmlspecialchars($musique["groupe"]) . '</span>';
            $corps .= '<span>' . htmlspecialchars($musique["album"]) . '</span>';
            $corps .= '</div>';
        }
        
        $corps .= '<p><a href="recherche.php">Nouvelle recherche</a></p>';
    } else {
        $corps = "<h1>Aucun résultat pour : " . htmlspecialchars($les_mots_cles) . "</h1>";
        $corps .= '<p><a href="recherche.php">Nouvelle recherche</a></p>';
    }
    
} catch (PDOException $e) {
    $corps = "<h1>Erreur lors de la recherche</h1>";
    error_log("Erreur recherche : " . $e->getMessage());
} finally {
    $statement = null;
    $connection = null;
}

$zonePrincipale = $corps;
include("squelette.php");
?>
