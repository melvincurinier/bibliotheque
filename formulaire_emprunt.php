<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>Formulaire pour les emprunts</title>
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

    echo '
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
                        <th>Date_rendu</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>';
    $query = $bdd->prepare('SELECT * FROM emprunt'); // requête SQL
    $query->execute(); // paramètres et exécution
    $countEmprunt = $query->rowCount();
    $result = $query->fetchAll();
    $query->closeCursor();

    foreach ($result as $emprunt) {
        echo '
                    <tr>
                        <th>' . $emprunt['id_emprunt'] . '</th>
                        <th>' . $emprunt['id_livre'] . '</th>
                        <th>' . $emprunt['id_abonne'] . '</th>
                        <th>' . $emprunt['date_sortie'] . '</th>
                        <th>' . $emprunt['date_rendu'] . '</th>
                        <th><a href="?action=modifier&id_emprunt=' . $emprunt['id_emprunt'] . '">&#x1F58A;</a></th>
                        <th><a href="?action=supprimer&id_emprunt=' . $emprunt['id_emprunt'] . '">&#10060;</a></th>
                    <tr>
                    ';
    };
    echo '
                </tbody>
            </table>';



    if (isset($_GET['action']) && $_GET['action'] == "modifier") {
        $requete = ("SELECT * FROM emprunt WHERE id_emprunt = $_GET[id_emprunt]");
        $query = $bdd->prepare($requete);
        $query->execute();
        $emprunt_choisi = $query->fetch();
        $query->closeCursor();
        echo '
                <form method="post" action="" class="formulaire">
                    <label for="id_abonne">Abonné</label><br>
                    <select id="id_abonne" name="id_abonne">';
        $query = $bdd->prepare('SELECT * FROM abonne'); // requête SQL
        $query->execute(); // paramètres et exécution
        $result = $query->fetchAll();
        $query->closeCursor();
        foreach ($result as $abonne) {
            if ($abonne['id_abonne'] == $emprunt_choisi['id_abonne']) {
                echo '
                                    <option value="' . $abonne['id_abonne'] . '" selected>' . $abonne['id_abonne'] . ' - ' . $abonne['prenom'] . '</option>
                                ';
            } else {
                echo '
                                    <option value="' . $abonne['id_abonne'] . '">' . $abonne['id_abonne'] . ' - ' . $abonne['prenom'] . '</option>
                                ';
            }
        }
        echo '
                    </select><br><br>
                    <label for="id_livre">Livre</label><br>
                    <select id="id_livre" name="id_livre">';
        $query = $bdd->prepare('SELECT * FROM livre'); // requête SQL
        $query->execute(); // paramètres et exécution
        $result = $query->fetchAll();
        $query->closeCursor();
        foreach ($result as $livre) {
            if ($livre['id_livre'] == $emprunt_choisi['id_livre']) {
                echo '
                                    <option value="' . $livre['id_livre'] . '" selected>' . $livre['id_livre'] . ' - ' . $livre['auteur'] . ' | ' . $livre['titre'] . '</option>
                                ';
            } else {
                echo '
                                    <option value="' . $livre['id_livre'] . '">' . $livre['id_livre'] . ' - ' . $livre['auteur'] . ' | ' . $livre['titre'] . '</option>
                                ';
            }
        }
        echo '
                    </select><br><br>
                    <label for="date_sortie">Date Sortie</label><br>
                    <input type="date" id="date_sortie" name="date_sortie" value="';
        if (isset($emprunt_choisi['date_sortie'])) {
            echo $emprunt_choisi['date_sortie'];
        }
        echo '"><br><br>
                    <label for="date_rendu">Date Rendu</label><br>
                    <input type="date" id="date_rendu" name="date_rendu" value="';
        if (isset($emprunt_choisi['date_rendu'])) {
            if ($emprunt_choisi['date_rendu'] != "") {
                echo $emprunt_choisi['date_rendu'];
            }
        }
        echo '"><br><br>
                    <input type="submit" value="modifier">
                </form>
                ';

        if ((isset($_GET['id_emprunt']))
            and isset($_POST['id_livre']) && ($_POST['id_livre'] != "")
            and isset($_POST['id_abonne']) && ($_POST['id_abonne'] != "")
            and isset($_POST['date_sortie']) && ($_POST['date_sortie'] != "")
            and isset($_POST['date_rendu'])
        ) {
            if ($_POST['date_rendu'] != "") {
                $requete = ("UPDATE emprunt set id_livre = $_POST[id_livre], id_abonne = $_POST[id_abonne], date_sortie = '$_POST[date_sortie]', date_rendu = '$_POST[date_rendu]' where id_emprunt = $_GET[id_emprunt]");
            } else {
                $requete = ("UPDATE emprunt set id_livre = $_POST[id_livre], id_abonne = $_POST[id_abonne], date_sortie = '$_POST[date_sortie]' where id_emprunt = $_GET[id_emprunt]");
            }
            $query = $bdd->prepare($requete);
            $query->execute();
            $query->closeCursor();
            echo '<p>L\'emprunt a bien été modifié !</p>';
        }
    } elseif (isset($_GET['action']) && $_GET['action'] == "supprimer") {
        echo '
                    <div>
                        <p>Voulez vous supprimer cet emprunt ?</p>
                        <a class="notxtdeco" href="?action=supprimer&id_emprunt=' . $emprunt['id_emprunt'] . '&rep=oui">Oui</a>
                        <a class="notxtdeco" href="?action=supprimer&id_emprunt=' . $emprunt['id_emprunt'] . '&rep=non">Non</a>
                    </div>
                    ';
        if (isset($_GET['rep']) && $_GET['rep'] == "oui") {
            $query = $bdd->prepare("DELETE FROM emprunt WHERE id_emprunt=$_GET[id_emprunt]");
            $query->execute();
            $query->closeCursor();
            echo '<p>L\'emprunt a bien été supprimé !</p>';
        }
    } else {
        if (isset($_POST['date_sortie']) && ($_POST['date_sortie'] != "")) {
            if ($_POST['date_rendu'] != "") {
                $requete = ("INSERT INTO emprunt (id_livre, id_abonne, date_sortie, date_rendu) VALUES ($_POST[id_livre], $_POST[id_abonne], '$_POST[date_sortie]', '$_POST[date_rendu]')");
            } else {
                $requete = ("INSERT INTO emprunt (id_livre, id_abonne, date_sortie) VALUES ($_POST[id_livre], $_POST[id_abonne], '$_POST[date_sortie]')");
            }
            $query = $bdd->prepare($requete);
            $query->execute();
            $query->closeCursor();
            echo '<p>L\'emprunt a bien été ajouté !</p>';
        }
        echo '
                <form method="post" action="" class="formulaire">
                    <label for="id_abonne">Abonné</label><br>
                    <select id="id_abonne" name="id_abonne">';
        $query = $bdd->prepare('SELECT * FROM abonne'); // requête SQL
        $query->execute(); // paramètres et exécution
        $result = $query->fetchAll();
        $query->closeCursor();
        foreach ($result as $abonne) {
            echo '
                            <option value="' . $abonne['id_abonne'] . '">' . $abonne['id_abonne'] . ' - ' . $abonne['prenom'] . '</option>
                            ';
        }
        echo '
                    </select><br><br>
                    <label for="id_livre">Livre</label><br>
                    <select id="id_livre" name="id_livre">';
        $query = $bdd->prepare('SELECT * FROM livre'); // requête SQL
        $query->execute(); // paramètres et exécution
        $result = $query->fetchAll();
        $query->closeCursor();
        foreach ($result as $livre) {
            echo '
                            <option value="' . $livre['id_livre'] . '">' . $livre['id_livre'] . ' - ' . $livre['auteur'] . ' | ' . $livre['titre'] . '</option>
                            ';
        }
        echo '
                    </select><br><br>
                    <label for="date_sortie">Date Sortie</label><br>
                    <input type="date" id="date_sortie" name="date_sortie"><br><br>
                    <label for="date_rendu">Date Rendu</label><br>
                    <input type="date" id="date_rendu" name="date_rendu"><br><br>
                    <input type="submit" value="Ajouter">
                </form>
                ';
    }
    echo '
            <div>
                <p>Il y a '.$countEmprunt.' emprunt(s) dans la base de données</p>
            </div>
            <div>
                ';
                $query = $bdd->prepare('SELECT e.id_livre, l.titre FROM emprunt e, livre l where e.id_livre = l.id_livre and e.date_rendu IS NULL');
                $query->execute();
                $result = $query->fetchAll();
                foreach($result as $livreNonRendu){
                    echo 'Le livre n°'.$livreNonRendu['id_livre'].' '.$livreNonRendu['titre'].' n\'a pas été rendu à la bibliothèque<br>';
                }
        echo'
            </div>
            <div>
            ';
            $query = $bdd->prepare('SELECT e.id_livre from abonne a, emprunt e where e.id_abonne = a.id_abonne and a.prenom = "Chloé"');
            $query->execute();
            $result = $query->fetchAll();
            foreach($result as $empruntDeChloe){
                echo 'Le livre n°'.$empruntDeChloe['id_livre'].' est emprunté par Chloé<br>';
            }
            echo'
            </div>
            <div>
            ';
            $query = $bdd->prepare('SELECT * from emprunt e, abonne a, livre l where e.id_livre = l.id_livre and e.id_abonne = a.id_abonne and l.auteur = "ALPHONSE DAUDET"');
            $query->execute();
            $result = $query->fetchAll();
            foreach($result as $empruntLivreAlphonseDaudet){
                echo 'L\'abonné n°'.$empruntLivreAlphonseDaudet['id_abonne'].' a déjà emprunté un livre d\'Alphonse DAUDET<br>';
            }
            echo'
            </div>
            <div>
            ';
            $query = $bdd->prepare('SELECT * from emprunt e, abonne a, livre l where e.id_livre = l.id_livre and e.id_abonne = a.id_abonne and a.prenom ="Chloé" and e.date_rendu IS NULL');
            $query->execute();
            $result = $query->fetchAll();
            foreach($result as $empruntNonRenduDeChloe){
                echo 'Cholé n\'a pas encore rendu le livre n°'.$empruntNonRenduDeChloe['id_livre'].' '.$empruntNonRenduDeChloe['titre'].'<br>';
            }
            echo'
            </div>
            <div>
            ';
            $query = $bdd->prepare('SELECT * from emprunt e, abonne a, livre l where e.id_livre = l.id_livre and e.id_abonne = a.id_abonne and a.prenom ="Chloé" and e.date_rendu IS NULL');
            $query->execute();
            $result = $query->fetchAll();
            foreach($result as $empruntNonRenduDeChloe){
                echo 'Cholé n\'a pas encore rendu le livre n°'.$empruntNonRenduDeChloe['id_livre'].' '.$empruntNonRenduDeChloe['titre'].'<br>';
            }
            echo'
            </div>
        </div>
        ';
    ?>
</body>

</html>