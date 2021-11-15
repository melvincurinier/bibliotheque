<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>Formulaire pour les livres</title>
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
    $query = $bdd->prepare('SELECT * FROM livre'); // requête SQL
    $query->execute(); // paramètres et exécution

    $result = $query->fetchAll();
    $query->closeCursor();

    echo '
        <header>
            <div class="menuBar">
                <ul>
                    <a href="./formulaire_abonne.php"><li>Abonné</li></a>
                    <a href="./formulaire_livre.php"><li class="fwbl">Livre</li></a>
                    <a href="./formulaire_emprunt.php"><li>Emprunt</li></a>
                </ul>
            </div>
        </header>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>Id_livre</th>
                        <th>Auteur</th>
                        <th>Titre</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>';
    foreach ($result as $livre) {
        echo '
                    <tr>
                        <th>' . $livre['id_livre'] . '</th>
                        <th>' . $livre['auteur'] . '</th>
                        <th>' . $livre['titre'] . '</th>
                        <th><a href="?action=modifier&id_livre=' . $livre['id_livre'] . '">&#x1F58A;</a></th>
                        <th><a href="?action=supprimer&id_livre=' . $livre['id_livre'] . '">&#10060;</a></th>
                    <tr>
                    ';
    };
    echo '
                </tbody>
            </table>';



    if (isset($_GET['action']) && $_GET['action'] == "modifier") {
        $requete = ("SELECT * FROM livre WHERE id_livre = $_GET[id_livre]");
        $query = $bdd->prepare($requete);
        $query->execute();
        $livre_choisi = $query->fetch();
        $query->closeCursor();
        echo '
                <form method="post" action="" class="formulaire">
                    <label for="auteur">Auteur</label><br>
                    <input type="text" id="auteur" name="auteur" value="';
        if (isset($livre_choisi['auteur'])) {
            echo $livre_choisi['auteur'];
        }
        echo '"><br><br>
                    <label for="titre">Titre</label><br>
                    <input type="text" id="titre" name="titre" value="';
        if (isset($livre_choisi['titre'])) {
            echo $livre_choisi['titre'];
        }
        echo '"><br><br>
                    <input type="submit" value="Modifier">
                </form>
                ';
        if ((isset($_GET['id_livre']))
            and (isset($_POST['auteur'])) && ($_POST['auteur'] != "")
            and (isset($_POST['titre'])) && ($_POST['titre'] != "")
            and (isset($_GET['action'])) && ($_GET['action'] == "modifier")
        ) {
            $requete = ("UPDATE livre set auteur = '$_POST[auteur]', titre = '$_POST[titre]' WHERE id_livre = $_GET[id_livre]");
            $query = $bdd->prepare($requete);
            $query->execute();
            $query->closeCursor();
            echo '<p>Le livre a bien été modifié !</p>';
        }
    } elseif ((isset($_GET['action'])) && ($_GET['action'] == "supprimer")) {
        echo '
                <div>
                    <p>Voulez vous supprimer ce livre ?</p>
                    <a class="notxtdeco" href="?action=supprimer&id_livre=' . $livre['id_livre'] . '&rep=oui">Oui</a>
                    <a class="notxtdeco" href="?action=supprimer&id_livre=' . $livre['id_livre'] . '&rep=non">Non</a>
                </div>
                ';
        if ((isset($_GET['rep'])) && ($_GET['rep'] == "oui")
            and (isset($_GET['action'])) && ($_GET['action'] == "supprimer")
        ) {
            $query = $bdd->prepare("DELETE FROM livre WHERE id_livre=$_GET[id_livre]");
            $query->execute();
            $query->closeCursor();
            echo '<p>Le livre a bien été supprimé !</p>';
        }
    } else {
        if ((isset($_POST['auteur'])) && ($_POST['auteur'] != "")
            and (isset($_POST['titre'])) && ($_POST['titre'] != "")
        ) {
            $requete = "INSERT INTO livre (auteur, titre) VALUES ('$_POST[auteur]', '$_POST[titre]')";
            $query = $bdd->prepare($requete);
            $query->execute();
            $query->closeCursor();
            echo '<p>Le livre a bien été ajouté !</p>';
        }
        echo '
                <form method="post" action="" class="formulaire">
                <label for="auteur">Auteur</label><br>
                <input type="text" id="auteur" name="auteur"><br><br>
                <label for="titre">Titre</label><br>
                <input type="text" id="titre" name="titre"><br><br>
                <input type="submit" value="Ajouter">
                </form>
                ';
    }
    echo '
        </div>
        ';
    ?>
</body>

</html>