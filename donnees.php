<?php
// Définition des types pour les variables

$zonePrincipale = "";
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
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
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

    public function enregistrer(): bool {
		$connection = connecter();
		if($connection == null) return false;
		
		$requete = "INSERT INTO Musique (titre, groupe, album) VALUES (?, ?, ?)";
		$stmt = $connection->prepare($requete);
		$stmt->execute([$this->titre, $this->groupe, $this->album]);
		$this->idM = $connection->lastInsertId();

		return true;
	}
    
    public function modifier(string $nouveau_titre, string $nouveau_groupe, string $nouvel_album): bool {
        $connection = connecter();
        if ($connection == null) {
            return false;
        }
        
        try {
            $requete = "UPDATE Musique SET titre = ?, groupe = ?, album = ? WHERE idM = ?";
            $stmt = $connection->prepare($requete);
            $stmt->execute([$nouveau_titre, $nouveau_groupe, $nouvel_album, $this->idM]);

            $this->titre = $nouveau_titre;
            $this->groupe = $nouveau_groupe;
            $this->album = $nouvel_album;
            
            return true;
        } catch (PDOException $e) {
            error_log("Erreur modification musique : " . $e->getMessage());
            return false;
        }
    }
    
    public function supprimer(): bool {
        $connection = connecter();
        if ($connection == null) {
            return false;
        }
        
        try {
            $requete = "DELETE FROM Musique WHERE idM = ?";
            $stmt = $connection->prepare($requete);
            $stmt->execute([$this->idM]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur suppression musique : " . $e->getMessage());
            return false;
        }
    }
}
?>
