<?php
include('donnees.php');
$corps=null;

$corps= "<h1> A propos et détails du site </h1>";
$corps.= "<p> Sité réaliser par TELLIER Basile, L2 Informatique, Groupe 1A,  </p>";
$corps.= "Réalisation de base : ";
$corps.= "<ul> <li> Arborescence de site :</li>";//Afficher le tree
$corps.= " 
Devoir contient dans includes/ le fichier config et à la racine se trouvent donnes.php, formulaireMusique.html,index.php, Musique.sql, objet.php,propos.php,(recherche.php et traitement_recherche.php utilisé dans le cm10), squelette.php et style.css";
$corps.="<li> Liste d'objets affichables indépendamment réalisé </li>";

$corps.="<li> Création d'objets réalisé </li>";
$corps.="<li> Modification d'objets réalisé </li>";
$corps.="<li> Utilisation de class(s) pour la manipulation d'objets réalisé </li>";
$corps.="<li> Suppression d'objets réalisé </li>";
$corps.="<li> Gestion de la BD avec PHP réalisé </li></ul>";

$corps.="<p> Compléments réalisé : Possibilité de filtrer la liste des objets via un champ de recherche </p>";

$zonePrincipale=$corps;
include("squelette.php");
?>
