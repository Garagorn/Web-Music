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
        $requete = "SELECT * FROM Musique";

        try {
            
            // Exécution de la requête
            $query = $connection->query($requete);
            $query->setFetchMode(PDO::FETCH_OBJ);
            $corps .= "<div class='grid-container'>";
            $corps .= "<div class='item'>idM</div>";
            $corps .= "<div class='item'>Titre</div>";
            $corps .= "<div class='item'>Action</div>";
            $corps .="</div>";

            while($req=$query->fetch()){
                $corps .="<div class='grid-container'>";
                $corps .='<span><u><b>' .htmlspecialchars($req->idM). '</b></u></span> <span><a href="objet.php?idM='.$req->idM.'">'.htmlspecialchars($req->titre).'</a></span>';

                $corps .='<span>';
                $corps .='<a href="index.php?action=modifier&idM=' . $req->idM . '"><span class="glyphicon glyphicon-pencil"></span></a>'; //Crayon modification
                $corps .='<a href="index.php?action=supprimer&idM=' . $req->idM . '"><span class="glyphicon glyphicon-trash"></span></a>'; // Poubelle suppression

                $corps .='</span>'; //Fermer le span 
                $corps .='</div>'; //Fermer le div
            }
            
            
        } catch (PDOException $e) {
            echo "Erreur lors de l'exécution de la requête : ", $e->getMessage();
        } finally {
            // Fermeture de la connection et libération de la ressource
            $query = null;
            $connection = null;
        }
        $zonePrincipale = $corps;
        break;
    
    case "delete":
        $corps="<h1> Suppression </h1>";
        $connection =connecter();
        $idM = key_exists('idM',$_POST)? $_POST['idM']: null;

        if($idM){
            $musique= new Musique($idM,"","","");
            if($musique->supprimer()){
                $corps="<h1>Suppression de la musique ".$idM."</h1>" ;
            }
            else{
                $corps= "<h1>Echec de la suppression</h1>";
            }
        }
        // Fermeture de la connection et libération de la ressource
        $connection = null;
        $zonePrincipale=$corps ;
        break;
    case "update":
        $connection =connecter();
        $type = key_exists('type',$_POST)? $_POST['type']: null;
            
        $corps="<h1>Mise à jour de la musique ".$idM."</h1>" ;
        $corps.= '<h2>'.$idM.' '.$titre.' '.$groupe.' '.$album.'</h2>';
        $connection = null;
        $zonePrincipale=$corps ;
        break;

    case "modifier": //un id particulier
        $corps="<h1> Modification </h1>";
        $idM=$_GET["idM"];

        include("formulaireMusique.html");
        $zonePrincipale=$corps;
        if($_SERVER["REQUEST_METHOD"]=="POST"){
			$titre = isset($_POST["titre"])? trim($_POST["titre"]):null;
            $groupe = isset($_POST["groupe"])? trim($_POST["groupe"]):null;
            $album = isset($_POST["album"])? trim($_POST["album"]):null;
            if($titre==""){
                $erreur["titre"]="Il manque un titre";
            }
            if($groupe==""){
                $erreur["groupe"]="Il manque un groupe";
            }
            if($album==""){
                $erreur["album"]="Il manque un album";
            }

            $compteur_erreur=count(array_filter($erreur));

                if($compteur_erreur==0){
                    if(isset($_POST['confirmation'])&& $_POST["confirmation"]=="Oui"){
                        $connection=connecter();
                        $idP=key_exists('idM',$_GET)?$_GET["idM"]:null;
                        $corps="<h1>Modifier la musique .$idM.</h1>";
                        $musique= new Musique($idM,$titre,$groupe,$album);
                        if($musique->modifier($titre,$groupe,$album)){
                            $corps="<h1>Modification réussie ! </h1>";
                            $zonePrincipale=$corps ;
                            $connection = null;
                            break;
                        }
                        else{
                            $corps="<h1>Echec de la modification ! </h1>";
                            $zonePrincipale=$corps ;
                            $connection = null;
                            break;
                        }
                    }
                    else{
                        $connection=null;
                        $zonePrincipale="";
                        break;
                    }
                }
                else{
                    $connection=null;
                    $zonePrincipale="";
                    break;
                }
        
        }
        break;
        
        
    case "saisir": // Saisie via le formulaire
        $corps="";
        $cible='saisir'; //insertion des données
        if (!isset($_POST["titre"]) && !isset($_POST["groupe"])&& !isset($_POST["album"])) {
            // Affichage du formulaire
            include("formulaireMusique.html");
            $zonePrincipale = $corps;
        } else {
            // Traitement des données du formulaire
            // Récupération des données
            $titre = isset($_POST['titre']) ? trim($_POST['titre']) : null;
            $groupe = isset($_POST['groupe']) ? trim($_POST['groupe']) : null;
            $album = isset($_POST['album']) ? trim($_POST['album']) : null;

            // Vérification des champs
            if (empty($titre)) {
                $erreur["titre"] = "il manque un titre";
            }
            if (empty($groupe)) {
                $erreur["groupe"] = "il manque un groupe";
            }

            if (empty($album)) {
                $erreur["album"] = "il manque un album";
            }

            // Comptage des erreurs
            $compteur_erreur=count(array_filter($erreur));

            // Traitement en fonction du nombre d'erreurs
            if ($compteur_erreur == 0) {
                
                $musique= new Musique(0,$titre,$groupe,$album);
                if($musique->enregistrer()){
                    $der_idM = $musique->getidM();
                    $corps = "Insertion de : ". $titre . " chanté(e) par " . $groupe . " et appartenant à l'album ". $album . " c'est la musique n° : <u>" .$der_idM . "</u><br/>";
                }
                else{
                    $corps="<h1> Echec de la saisie</h1>";
                }
                $zonePrincipale = $corps;

            } else {
                // Affichage du formulaire avec les erreurs
                include("formulaireMusique.html");
                $zonePrincipale = $corps;
            }
        }
        break;
    case "supprimer": //un id particulier
        $idM=$_GET["idM"];
        $connection =connecter();
        $tab = '<form action="index.php?action=delete" method="post">
                <input type="hidden" name="type" value="' . 'confirmdelete' . '"/>
                <input type="hidden" name="idM" value="' . $idM. '"/>
    
                 <p>Etes vous sûr de vouloir supprimer cette musique  ? </p>
                <p>
                    <input type="submit" value="Supprimer" class="btn btn-danger">
                    <a href="index.php" class="btn btn-secondary">Annuler</a>
                </p>
        </form>';
        
        $corps = $tab;
        $zonePrincipale=$corps ;
        $connection = null;
        break;
    default:
        $zonePrincipale = "";
        break;
}

// Inclusion du squelette HTML
include("squelette.php");
?>
