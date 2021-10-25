<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>Formulaire pour les abonnées</title>
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
        $query = $bdd->prepare('SELECT * FROM abonne'); // requête SQL
        $query->execute(); // paramètres et exécution

        $result = $query->fetchAll();
        $query->closeCursor();

        echo'
        <header>
            <div class="menuBar">
                <ul>
                    <a href="formulaire_abonne.php"><li class="fwbl">Abonné</li></a>
                    <a href="formulaire_livre.php"><li>Livre</li></a>
                    <a href="formulaire_emprunt.php"><li>Emprunt</li></a>
                </ul>
            </div>
        </header>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Prénom</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>';
                foreach ($result as $abonne){
                    echo '
                    <tr>
                        <th>'.$abonne['id_abonne'].'</th>
                        <th>'.$abonne['prenom'].'</th>
                        <th>&#x1F58A;</th>
                        <th>&#10060;</th>
                    <tr>
                    ';
                };
                echo '
                </tbody>
            </table>';
            if($_POST){
                $requete = "INSERT INTO abonne (prenom) VALUES ('$_POST[prenom]')";
                $query = $bdd->prepare($requete);
                $query->execute();
                $query->closeCursor();
                echo'Le prénom '.$_POST['prenom'].' a bien été ajouté !';
            }
            echo'
            <form method="post" action="">
                <label for="prenom">Prénom</label><br>
                <input type="text" id="prenom" name="prenom"><br><br>
                <input type="submit" value="Ajouter">
            </form>
        </div>
        ';
    ?>
</body>
</html>