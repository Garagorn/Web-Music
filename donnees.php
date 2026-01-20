<?php
// Définition des types pour les variables

$groupe= null;
$album= null;
$titre = null;
$idM = null; 
$erreur = ["idM" => null,"titre"=> null,"groupe" => null, "album" => null,];

// Fonction de connection à la base de données
function connecter(): ?PDO{
    require_once('includes/config.php');


    // Options de connection
    $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    // connection à la base de données
    try {
        $dsn = DB_HOST . DB_NAME;
        $connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $connection;
    } catch (PDOException $e) {
        echo "connection à MySQL impossible : ", $e->getMessage();
        //exit(); // Arrêter l'exécution du script en cas d'échec de connection
        return null;
    }
}

class Musique{
	private int $idM;
	private string $titre;
	private string $groupe;
    private string $album;

    public function __construct(int $idM, string $titre,string $groupe, string $album) {
        $this->titre= $titre;
        $this->groupe = $groupe;
        $this->album= $album;
        $this->idM= $idM;
    }
		
	public function getIdM(){
		return $this->idM;
		}
	
	public function getTitre(){
		return $this->titre;
	}

    public function getGroupe(){
		return $this->groupe;
		}
	
	public function getAlbum(){
		return $this->album;
	}
		
    public function __toString(): string {
		return "Musique n° : {$this->idM} :	{$this->titre} chanté(e) par {$this->groupe} dans l'album : {$this->album} \n";	
    }

    public function enregistrer(): bool{
    	$connection= connecter();
    	if($connection==null){
    		return false;
    	}
    	else{
            $requete="INSERT INTO Musique (titre,groupe,album) VALUES ('$this->titre','$this->groupe','$this->album')";
            $connection->query($requete);
            if($connection->query($requete)){
                $this->idM= $connection->lastInsertId();
            }
            return true;
    	}
    }
    
    public function modifier(string $nouveau_titre,string $nouveau_groupe,string $nouvel_album): bool{
    	$connection= connecter();
    	if($connection==null){
    		return false;
    	}
	else{
        $requete = "UPDATE Musique  SET titre ='$nouveau_titre',groupe ='$nouveau_groupe',album ='$nouvel_album' WHERE idM =$this->idM";
        $connection->query($requete);
	}
	return true;
    }
    
    public function supprimer(): bool{
        $connection= connecter();
    	if($connection==null){
    		return false;
    	}
	else{
        $requete="DELETE FROM Musique WHERE idM='$this->idM'";
        $connection->query($requete);
	}
	return true;
    }

}



?>

