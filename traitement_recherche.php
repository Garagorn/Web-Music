<?php
include('donnees.php');
$connection =connecter();
$corps=null;  
//Version modifié du cm

//Vérifier si existe
$les_mots_cles=isset($_GET['mot_cle']) ? trim($_GET['mot_cle']) : null;


// Supposons que $les_mots_cles contient les mots-clés sous forme de chaîne de caractères
$tableau_mots_cles = explode(" ", $les_mots_cles);
$conditions = []; // Initialisation du tableau des conditions
$bind_params = []; // Initialisation du tableau pour les paramètres liés

if($connection!=null && $les_mots_cles!=null){
   // Boucle pour chaque mot-clé
   foreach ($tableau_mots_cles as $mot) {
      $mot = rtrim($mot, "s"); // Suppression du "s" à la fin du mot
      if (strlen($mot) > 3) { // Vérification si le mot-clé a une longueur suffisante
         $like = "%" . $mot . "%";
           $conditions[] = "titre LIKE ? OR groupe LIKE ? OR album LIKE ?";
           $bind_params[] = $like; //Cherche le titre
           $bind_params[] = $like; //Chercher le groupe
           $bind_params[] = $like; //Chercher l'album
      }
   }

   if(!empty($conditions)){
   //Requete du CM modifiée pour l'objet Musique
      // Construction de la requête SQL
      $requete = "SELECT * FROM Musique WHERE " . implode(" OR ", $conditions);
      // connection à la base de données et exécution de la requête préparée
      $statement = $connection->prepare($requete);
      $statement->execute($bind_params);
      // Récupération des résultats
      $resultats = $statement->fetchAll(PDO::FETCH_ASSOC);
      if(count($resultats)>0){
         $corps="<h1>Résultat(s) de la recherche : </h1> ";
         $corps .= "<div class='grid-container'>";
         $corps .= "<div class='item'>idM</div>";
         $corps .= "<div class='item'>Titre</div>";
         $corps .= "<div class='item'>Groupe</div>";
         $corps .= "<div class='item'>Album</div>";
         $corps .="</div>";
         foreach($resultats as $musique){
            $corps .="<div class='grid-container'>";
            $corps .='<span><u><b>' .htmlspecialchars($musique["idM"]). '</b></u></span>';
            $corps .='<span>' .htmlspecialchars($musique["titre"]). '</span>';
            $corps .='<span>' .htmlspecialchars($musique["groupe"]). '</span>';
            $corps .='<span>' .htmlspecialchars($musique["album"]). '</span>';
            $corps .='</div>'; //Fermer le div
         }
      }
      else{
         $corps="<h1>Aucun résultat pour : $les_mots_cles</h1> ";
      }
      
   }
}
else{
   $corps="<h1>Echec de la recherche</h1> ";
}
$zonePrincipale = $corps;
include("squelette.php");
?> 