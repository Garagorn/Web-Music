<?php

include('donnees.php');
// Détection de l'action à effectuer
$action = isset($_GET['action']) ? trim($_GET['action']) : null;

$idM = key_exists('idM',$_POST)? $_POST['idM']: null;
$titre = key_exists('titre',$_POST)? $_POST['titre']: null;
$groupe = key_exists('groupe',$_POST)? $_POST['groupe']: null;
$album = key_exists('album',$_POST)? $_POST['album']: null;

switch ($action) {
    case "tester":
        $connection =connecter();
        if($connection==null){ //Echec de la connection (ex etu_bd au lieu de _db)
            $corps="<h1>Echec de la connection !</h1> ";  
        }
        else{ //Réussite de la connection
            $corps="<h1>Connection établie !</h1> ";
        } //Mettre le message suivant le cas
        $zonePrincipale = $corps;
        break; //Sortir du case
        
    case "afficher":
		$corps = "<h1>Liste des musiques</h1>";
		$connection = connecter();
		
		if ($connection) {
		    $requete = "SELECT * FROM Musique ORDER BY idM";
		    
		    try {
		        $query = $connection->query($requete);
		        $query->setFetchMode(PDO::FETCH_OBJ);
		        
		        $corps .= "<div class='grid-container'>";
		        $corps .= "<div class='item'>idM</div>";
		        $corps .= "<div class='item'>Titre</div>";
		        $corps .= "<div class='item'>Action</div>";
		        $corps .= "</div>";
		        
		        while ($req = $query->fetch()) {
		            $corps .= "<div class='grid-container'>";
		            $corps .= '<span><u><b>' . htmlspecialchars($req->idM) . '</b></u></span>';
		            $corps .= '<span><a href="objet.php?idM=' . urlencode($req->idM) . '">' . htmlspecialchars($req->titre) . '</a></span>';
		            $corps .= '<span>';
		            $corps .= '<a href="index.php?action=modifier&idM=' . urlencode($req->idM) . '"><span class="glyphicon glyphicon-pencil"></span></a>';
		            $corps .= '<a href="index.php?action=supprimer&idM=' . urlencode($req->idM) . '"><span class="glyphicon glyphicon-trash"></span></a>';
		            $corps .= '</span>';
		            $corps .= '</div>';
		        }
		        
		    } catch (PDOException $e) {
		        $corps .= "<p>Erreur lors de l'exécution de la requête.</p>";
		        error_log("Erreur affichage : " . $e->getMessage());
		    } finally {
		        $query = null;
		        $connection = null;
		    }
		}
		$zonePrincipale = $corps;
		break;
    
    case "delete":
		$corps = "<h1>Suppression</h1>";
		$idM = key_exists('idM', $_POST) ? (int)$_POST['idM'] : null;
		
		if ($idM && $idM > 0) {
		    $musique = new Musique($idM, "", "", "");
		    if ($musique->supprimer()) {
		        $corps = "<h1>Suppression de la musique " . htmlspecialchars($idM) . " réussie</h1>";
		        $corps .= '<p><a href="index.php?action=afficher">Retour à la liste</a></p>';
		    } else {
		        $corps = "<h1>Échec de la suppression</h1>";
		    }
		} else {
		    $corps = "<h1>ID invalide</h1>";
		}
		$zonePrincipale = $corps;
    	break;
        
    case "update":
        $connection =connecter();
        $type = key_exists('type',$_POST)? $_POST['type']: null;
            
        $corps="<h1>Mise à jour de la musique ".$idM."</h1>" ;
        $corps.= '<h2>'.$idM.' '.$titre.' '.$groupe.' '.$album.'</h2>';
        $connection = null;
        $zonePrincipale=$corps ;
        break;

    case "modifier":
		$corps = "<h1>Modification</h1>";
		$idM = isset($_GET["idM"]) ? (int)$_GET["idM"] : 0;
		$erreur = ["titre" => null, "groupe" => null, "album" => null];
		
		if ($idM <= 0) {
		    $corps = "<h1>ID invalide</h1>";
		    $zonePrincipale = $corps;
		    break;
		}
		
		// Récupérer les données actuelles de la musique
		$connection = connecter();
		if ($connection) {
		    try {
		        $stmt = $connection->prepare("SELECT * FROM Musique WHERE idM = ?");
		        $stmt->execute([$idM]);
		        $musique_actuelle = $stmt->fetch(PDO::FETCH_OBJ);
		        
		        if (!$musique_actuelle) {
		            $corps = "<h1>Musique introuvable</h1>";
		            $zonePrincipale = $corps;
		            break;
		        }
		        
		        // Valeurs par défaut
		        $titre = $musique_actuelle->titre;
		        $groupe = $musique_actuelle->groupe;
		        $album = $musique_actuelle->album;
		        
		    } catch (PDOException $e) {
		        $corps = "<h1>Erreur de récupération des données</h1>";
		        $zonePrincipale = $corps;
		        break;
		    }
		}
		// Traitement du formulaire POST
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
		    $titre = isset($_POST["titre"]) ? trim($_POST["titre"]) : "";
		    $groupe = isset($_POST["groupe"]) ? trim($_POST["groupe"]) : "";
		    $album = isset($_POST["album"]) ? trim($_POST["album"]) : "";
		    
		    // Validation
		    if (empty($titre)) {
		        $erreur["titre"] = "Il manque un titre";
		    }
		    if (empty($groupe)) {
		        $erreur["groupe"] = "Il manque un groupe";
		    }
		    if (empty($album)) {
		        $erreur["album"] = "Il manque un album";
		    }
		    
		    $compteur_erreur = count(array_filter($erreur));
		    
		    if ($compteur_erreur == 0) {
		        if (isset($_POST['confirmation']) && $_POST["confirmation"] == "Oui") {
		            $musique = new Musique($idM, $titre, $groupe, $album);
		            if ($musique->modifier($titre, $groupe, $album)) {
		                $corps = "<h1>Modification réussie !</h1>";
		                $corps .= '<p><a href="index.php?action=afficher">Retour à la liste</a></p>';
		                $zonePrincipale = $corps;
		                break;
		            } else {
		                $corps = "<h1>Échec de la modification !</h1>";
		                $zonePrincipale = $corps;
		                break;
		            }
		        } else {
		            // Annulation
		            header("Location: index.php?action=afficher");
		            exit();
		        }
		    }
		}
		
		// Affichage du formulaire
		include("formulaireMusique.html");
		$zonePrincipale = $corps;
		break;
        
        
    case "saisir":
		$corps = "";
		$erreur = ["titre" => null, "groupe" => null, "album" => null];
		
		if (!isset($_POST["titre"]) && !isset($_POST["groupe"]) && !isset($_POST["album"])) {
		    // Initialiser les valeurs vides pour le formulaire
		    $titre = "";
		    $groupe = "";
		    $album = "";
		    $idM = 0;
		    include("formulaireMusique.html");
		    $zonePrincipale = $corps;
		} else {
		    // Traitement des données du formulaire
		    $titre = isset($_POST['titre']) ? trim($_POST['titre']) : "";
		    $groupe = isset($_POST['groupe']) ? trim($_POST['groupe']) : "";
		    $album = isset($_POST['album']) ? trim($_POST['album']) : "";
		    
		    // Validation
		    if (empty($titre)) {
		        $erreur["titre"] = "Il manque un titre";
		    }
		    if (empty($groupe)) {
		        $erreur["groupe"] = "Il manque un groupe";
		    }
		    if (empty($album)) {
		        $erreur["album"] = "Il manque un album";
		    }
		    
		    $compteur_erreur = count(array_filter($erreur));
		    
		    if ($compteur_erreur == 0) {
		        $musique = new Musique(0, $titre, $groupe, $album);
		        if ($musique->enregistrer()) {
		            $der_idM = $musique->getidM();
		            $corps = "<h1>Succès !</h1>";
		            $corps .= "<p>Insertion de : " . htmlspecialchars($titre) . " chanté(e) par " . htmlspecialchars($groupe);
		            $corps .= " et appartenant à l'album " . htmlspecialchars($album);
		            $corps .= " - Musique n° : <u>" . htmlspecialchars($der_idM) . "</u></p>";
		            $corps .= '<p><a href="index.php?action=afficher">Voir la liste</a></p>';
		        } else {
		            $corps = "<h1>Échec de la saisie</h1>";
		        }
		        $zonePrincipale = $corps;
		    } else {
		        // Affichage du formulaire avec erreurs
		        $idM = 0;
		        include("formulaireMusique.html");
		        $zonePrincipale = $corps;
		    }
		}
		break;
    
    case "supprimer":
		$idM = isset($_GET["idM"]) ? (int)$_GET["idM"] : 0;
		
		if ($idM <= 0) {
		    $corps = "<h1>ID invalide</h1>";
		    $zonePrincipale = $corps;
		    break;
		}
		
		$connection = connecter();
		
		// Vérifier que la musique existe
		if ($connection) {
		    try {
		        $stmt = $connection->prepare("SELECT titre FROM Musique WHERE idM = ?");
		        $stmt->execute([$idM]);
		        $musique = $stmt->fetch(PDO::FETCH_OBJ);
		        
		        if (!$musique) {
		            $corps = "<h1>Musique introuvable</h1>";
		            $zonePrincipale = $corps;
		            break;
		        }
		        
		        $corps = '<form action="index.php?action=delete" method="post">';
		        $corps .= '<input type="hidden" name="idM" value="' . htmlspecialchars($idM) . '"/>';
		        $corps .= '<p>Êtes-vous sûr de vouloir supprimer la musique "<strong>' . htmlspecialchars($musique->titre) . '</strong>" ?</p>';
		        $corps .= '<p>';
		        $corps .= '<input type="submit" value="Supprimer" class="btn btn-danger">';
		        $corps .= ' <a href="index.php?action=afficher" class="btn btn-secondary">Annuler</a>';
		        $corps .= '</p>';
		        $corps .= '</form>';
		        
		    } catch (PDOException $e) {
		        $corps = "<h1>Erreur lors de la vérification</h1>";
		        error_log("Erreur suppression : " . $e->getMessage());
		    }
		    $connection = null;
		}
		
		$zonePrincipale = $corps;
		break;
}

// Inclusion du squelette HTML
include("squelette.php");
?>
