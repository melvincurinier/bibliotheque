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
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>Id_emprunt</th>
                        <th>Id_livre</th>
                        <th>Id_abonne</th>
                        <th>Date_sortie</th>
                        <th>Date_sortie</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>';
                $query = $bdd->prepare('SELECT * FROM emprunt'); // requête SQL
                $query->execute(); // paramètres et exécution
                $result = $query->fetchAll();
                $query->closeCursor();

                foreach ($result as $emprunt){
                    echo '
                    <tr>
                        <th>'.$emprunt['id_emprunt'].'</th>
                        <th>'.$emprunt['id_livre'].'</th>
                        <th>'.$emprunt['id_abonne'].'</th>
                        <th>'.$emprunt['date_sortie'].'</th>
                        <th>'.$emprunt['date_rendu'].'</th>
                        <th><a href="?action=modifier">&#x1F58A;</a></th>
                        <th><a href="?action=supprimer">&#10060;</a></th>
                    <tr>
                    ';
                };
                echo '
                </tbody>
            </table>';
            if($_POST){
                $requete = "INSERT INTO emprunt (id_livre, id_abonne, date_sortie, date_rendu) VALUES ($_POST[id_livre], $_POST[id_abonne], '$_POST[date_sortie]', '$_POST[date_rendu]')";
                $query = $bdd->prepare($requete);
                $query->execute();
                $query->closeCursor();
                echo'<p>L\'emprunt a bien été ajouté !</p>';
            }
            echo'
            <form method="post" action="" class="formulaire">
                <label for="id_abonne">Abonné</label><br>
                <select id="id_abonne" name="id_abonne">';
                    $query = $bdd->prepare('SELECT * FROM abonne'); // requête SQL
                    $query->execute(); // paramètres et exécution
                    $result = $query->fetchAll();
                    $query->closeCursor();
                    foreach($result as $abonne){
                        echo'
                        <option value="'.$abonne['id_abonne'].'">'.$abonne['id_abonne'].' - '.$abonne['prenom'].'</option>
                        ';
                    }
                echo'
                </select><br><br>
                <label for="id_livre">Livre</label><br>
                <select id="id_livre" name="id_livre">';
                    $query = $bdd->prepare('SELECT * FROM livre'); // requête SQL
                    $query->execute(); // paramètres et exécution
                    $result = $query->fetchAll();
                    $query->closeCursor();
                    foreach($result as $livre){
                        echo'
                        <option value="'.$livre['id_livre'].'">'.$livre['id_livre'].' - '.$livre['auteur'].' | '.$livre['titre'].'</option>
                        ';
                    }
                echo'
                </select><br><br>
                <label for="date_sortie">Date Sortie</label><br>
                <input type="date" id="date_sortie" name="date_sortie"><br><br>
                <label for="date_rendu">Date Rendu</label><br>
                <input type="date" id="date_rendu" name="date_rendu"><br><br>
                <input type="submit" value="Ajouter">
            </form>
        </div>
        ';
    ?>
</body>
</html>