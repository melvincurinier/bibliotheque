<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>Formulaire pour les emprunts</title>
</head>
<body>
    <?php
        function connectDb(){
            $host = 'localhost'; // ou sql.hebergeur.com
            $user = 'root';      // ou login
            $pwd = '';      // ou xxxxxx
            $db = 'bibliotheque';
    
            try {
                $bdd = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8', $user, $pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                return $bdd;
            }
            catch (Exception $e)
            {
                exit('Erreur : '.$e->getMessage());
            }
        }
    
        $bdd = connectDb(); //connexion à la BDD
        $query = $bdd->prepare('SELECT * FROM emprunt'); // requête SQL
        $query->execute(); // paramètres et exécution

        $result = $query->fetchAll();
        $query->closeCursor();

        echo'
        <header>
            <div class="menuBar">
                <ul>
                    <a href="./formulaire_abonne.php"><li>Abonné</li></a>
                    <a href="./formulaire_livre.php"><li>Livre</li></a>
                    <a href="./formulaire_emprunt.php"><li class="fwbl">Emprunt</li></a>
                </ul>
            </div>
        </header>
        ';
    ?>
</body>
</html>