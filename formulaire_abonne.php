<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>Formulaire pour les abonnées</title>
</head>

<body>
    <?php
    function connectDb()
    {
        $host = 'localhost'; // ou sql.hebergeur.com
        $user = 'root';      // ou login
        $pwd = '';      // ou xxxxxx
        $db = 'bibliotheque';

        try {
            $bdd = new PDO('mysql:host=' . $host . ';dbname=' . $db . ';charset=utf8', $user, $pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            return $bdd;
        } catch (Exception $e) {
            exit('Erreur : ' . $e->getMessage());
        }
    }

    $bdd = connectDb(); //connexion à la BDD
    $query = $bdd->prepare('SELECT * FROM abonne'); // requête SQL
    $query->execute(); // paramètres et exécution
    $countAbonne = $query->rowCount();
    $result = $query->fetchAll();
    $query->closeCursor();

    echo '
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
                        <th>Id_abonné</th>
                        <th>Prénom</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>';
    foreach ($result as $abonne) {
        echo '                        
                        <tr>
                            <th>' . $abonne['id_abonne'] . '</th>
                            <th>' . $abonne['prenom'] . '</th>
                            <th><a href="?action=modifier&id_abonne=' . $abonne['id_abonne'] . '">&#x1F58A;</a></th>
                            <th><a href="?action=supprimer&id_abonne=' . $abonne['id_abonne'] . '">&#10060;</a></th>
                        <tr>
                    ';
    };
    echo '
                </tbody>
            </table>';



    if ((isset($_GET['action'])) && ($_GET['action'] == "modifier")) {
        $requete = ("SELECT * FROM abonne WHERE id_abonne = $_GET[id_abonne]");
        $query = $bdd->prepare($requete);
        $query->execute();
        $abonne_choisi = $query->fetch();
        $query->closeCursor();
        echo '
                <form method="post" action="" class="formulaire">
                    <label for="prenom">Prénom</label><br>
                    <input type="text" id="prenom" name="prenom" value="';
        if (isset($abonne_choisi['prenom'])) {
            echo $abonne_choisi['prenom'];
        }
        echo '"><br><br>
                    <input type="submit" value="Modifier">
                </form>
                ';
        if ((isset($_GET['id_abonne']))
            and (isset($_POST['prenom'])) && ($_POST['prenom'] != "")
            and (isset($_GET['action'])) && ($_GET['action'] == "modifier")
        ) {
            $requete = ("UPDATE abonne set prenom = '$_POST[prenom]' WHERE id_abonne=$_GET[id_abonne]");
            $query = $bdd->prepare($requete);
            $query->execute();
            $query->closeCursor();
            echo '<p>L\'abonné a bien été modifié !</p>';
        }
    } elseif ((isset($_GET['action'])) && ($_GET['action'] == "supprimer")) {
        echo '
                <div>
                    <p>Voulez vous supprimer cet abonné ?</p>
                    <a class="notxtdeco" href="?action=supprimer&id_abonne=' . $abonne['id_abonne'] . '&rep=oui">Oui</a>
                    <a class="notxtdeco" href="?action=supprimer&id_abonne=' . $abonne['id_abonne'] . '&rep=non">Non</a>
                </div>
                ';
        if ((isset($_GET['rep'])) && ($_GET['rep'] == "oui")) {
            $query = $bdd->prepare("DELETE FROM abonne WHERE id_abonne=$_GET[id_abonne]");
            $query->execute();
            $query->closeCursor();
            echo '<p>L\'abonné  a bien été supprimé !</p>';
        }
    } else {
        if ((isset($_POST['prenom'])) && ($_POST['prenom'] != "")) {
            $requete = "INSERT INTO abonne (prenom) VALUES ('$_POST[prenom]')";
            $query = $bdd->prepare($requete);
            $query->execute();
            $query->closeCursor();
            echo '<p>L\'abonné  a bien été ajouté !</p>';
        }
        echo '
                <form method="post" action="" class="formulaire">
                    <label for="prenom">Prénom</label><br>
                    <input type="text" id="prenom" name="prenom"><br><br>
                    <input type="submit" value="Ajouter">
                </form>
                ';
    }
    echo '
            <p>Il y a '.$countAbonne.' emprunt(s) dans la base de données</p>
        </div>
        ';
    ?>
</body>

</html>