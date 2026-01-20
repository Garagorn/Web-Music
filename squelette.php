<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musique</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
    <h1>Musique</h1>
    <hr>
    <div class="Ycontainer">
        <div class="Ymain">
            <?php echo $zonePrincipale; ?>
        </div>
        <div class="Ysidebar">
            <p>
                <a href="index.php?action=tester">Vérifier connection </a>
                <a href="index.php?action=afficher">Affichage des musiques</a>
                <a href="index.php?action=saisir">Saisie d'une musique</a>
                <a href="recherche.php">Rechercher une musique</a>
                <a href="propos.php">A propos</a>
            </p>
        </div>
    </div>
    <hr>
</body>
</html>
