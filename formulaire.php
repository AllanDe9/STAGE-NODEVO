<?php
$json_file = 'data.json';
$vehicules = json_decode(file_get_contents($json_file), true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Cars</title>
    <link rel="stylesheet"  href="style/style.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="/">
                <h1>Vintage Cars</h1>
                
            </a>
            <nav>
                <ul>
                    <li><a href="tous_les_modeles.php">Tous les modèles</a></li>
                    <li><a href="recherche.php">Rechercher</a></li>
                    <li>
                        <a id="bouton" class="bouton" tabindex="0">Marques</a>
                        <div class="menu" id="menu">
                          <?php 
                            foreach ($vehicules['marques'] as $marque) {
                                    echo '<a href="marque.php?marque='.$marque['num_marque'].'">'.$marque['nom_marque'].'</a>';
                            }
                          ?>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
    <?php
    if (isset($_GET['modele'])) {
        $num_modele = (int)$_GET['modele'];
        $modeleTrouve = false;
    foreach ($vehicules['marques'] as $marqueIndex => $marque) {
        foreach ($marque['modeles'] as $modeleIndex => $modele) {
            if ($modele['num_modele'] == $num_modele) {
                $modeleTrouve = true;
                break 2;
            }
        }
    }
    
    if (!$modeleTrouve) {
        echo 'Modèle non trouvé.';
        echo'<a href="tous_les_modeles.php">Retour</a>';
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $marqueIndex = (int)$_POST['marqueIndex'];
        $modeleIndex = (int)$_POST['modeleIndex'];
    
        $vehicules['marques'][$marqueIndex]['modeles'][$modeleIndex]['nom_modele'] = $_POST['nom_modele'];
        $vehicules['marques'][$marqueIndex]['modeles'][$modeleIndex]['url_photo'] = $_POST['url_photo'];
        $vehicules['marques'][$marqueIndex]['modeles'][$modeleIndex]['annee_debut'] = intval($_POST['annee_debut']);
        $vehicules['marques'][$marqueIndex]['modeles'][$modeleIndex]['annee_fin'] = intval($_POST['annee_fin']);
        $vehicules['marques'][$marqueIndex]['modeles'][$modeleIndex]['nbr_produit'] = intval($_POST['nbr_produit']);
        $vehicules['marques'][$marqueIndex]['modeles'][$modeleIndex]['puissance_max'] = intval($_POST['puissance_max']);
        $vehicules['marques'][$marqueIndex]['modeles'][$modeleIndex]['puissance_min'] = intval($_POST['puissance_min']);
        $vehicules['marques'][$marqueIndex]['modeles'][$modeleIndex]['prix_neuf'] = intval($_POST['prix_neuf']);
        $vehicules['marques'][$marqueIndex]['modeles'][$modeleIndex]['prix_actuel'] = intval($_POST['prix_actuel']);
        $vehicules['marques'][$marqueIndex]['modeles'][$modeleIndex]['description'] = $_POST['description'];
        
        if (isset($_POST['puissance_max']) && isset($_POST['puissance_min'])) {
            if (intval($_POST['puissance_max']) < intval($_POST['puissance_min'])){
                $erreurs = ['puissance'=>'La puissance max est plus petite que la puissance min !'];
            }
        }
        if (intval($_POST['annee_debut']) > intval($_POST['annee_fin'])){
            $erreurs = ['annee'=>"L'année de début est plus récente que l'année de fin !"];
        } else {
            if (intval($_POST['annee_debut']) > 1999){
            $erreurs = ['annee'=>"L'année de sortie de la voiture ne doit pas dépasser l'an 2000 !"];
            }
            if (intval($_POST['annee_debut']) < 1900){
                $erreurs = ['annee'=>"L'année de sortie de la voiture doit dépasser 1900 !"];
                }
            if (intval($_POST['annee_fin']) > 1999){
                $erreurs = ['annee'=>"L'année de la fin de production de la voiture ne doit pas dépasser 2000 !"];
            }
        }
        if (empty($_POST['nom_modele']) || empty($_POST['annee_debut']) || empty($_POST['annee_fin']) || empty($_POST['nbr_produit']) || empty($_POST['description'])){
            $erreurs = ['requis'=> 'Certains champs sont vides !'];
        }


        if (!empty($erreurs)) { 
            var_dump($erreurs);
        } else {
            file_put_contents($json_file, json_encode($vehicules, JSON_PRETTY_PRINT));
            echo 'Données mises à jour avec succès !';
            echo'<a href="tous_les_modeles.php">Retour</a>';
            exit;
       } 
    }
    
    echo '<form action="formulaire.php?modele='.$_GET['modele'].'" method="post">';
    echo '<input type="hidden" name="marqueIndex" value="' . $marqueIndex . '">';
    echo '<input type="hidden" name="modeleIndex" value="' . $modeleIndex . '">';
    echo '<label for="nom_marque">Marque : '.htmlspecialchars($marque['nom_marque']). '</label><br>';
    echo '<label for="nom_modele">Nom du modèle :</label>';
    echo '<input type="text" id="nom_modele" name="nom_modele" value="' . htmlspecialchars($modele['nom_modele']) . '"required><br>';
    echo '<label for="url_photo">URL de la photo :</label>';
    echo '<input type="text" id="url_photo" name="url_photo" value="' . htmlspecialchars($modele['url_photo']) . '"><br>';
    echo '<label for="annee_debut">Année de début :</label>';
    echo '<input type="number" id="annee_debut" name="annee_debut" value="' . htmlspecialchars($modele['annee_debut']) . '"required><br>';
    echo '<label for="annee_fin">Année de fin :</label>';
    echo '<input type="number" id="annee_fin" name="annee_fin" value="' . htmlspecialchars($modele['annee_fin']) . '"required><br>';
    echo '<label for="nbr_produit">Nombre produit :</label>';
    echo '<input type="number" id="nbr_produit" name="nbr_produit" value="' . htmlspecialchars($modele['nbr_produit']) . '"required><br>';
    echo '<label for="puissance_max">Puissance max :</label>';
    echo '<input type="number" id="puissance_max" name="puissance_max" value="' . htmlspecialchars($modele['puissance_max']) . '"><br>';
    echo '<label for="puissance_min">Puissance min :</label>';
    echo '<input type="number" id="puissance_min" name="puissance_min" value="' . htmlspecialchars($modele['puissance_min']) . '"><br>';
    echo '<label for="prix_neuf">Prix neuf :</label>';
    echo '<input type="number" id="prix_neuf" name="prix_neuf" value="' . htmlspecialchars($modele['prix_neuf']) . '"><br>';
    echo '<label for="prix_actuel">Prix actuel :</label>';
    echo '<input type="number" id="prix_actuel" name="prix_actuel" value="' . htmlspecialchars($modele['prix_actuel']) . '"><br>';
    echo '<label for="description">Description :</label>';
    echo '<textarea id="description" name="description"required>' . htmlspecialchars($modele['description']) . '</textarea><br>';
    echo '<input type="submit" name="submit" value="Modifier">';
    echo '</form>';

    } else {

        function compterTousLesModeles($marques) {
            $total = 0;
            foreach ($marques as $marque) {
                $total += count($marque['modeles']);
            }
            return $total;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
            $num_marque = intval($_POST['num_marque']);
            $nom_modele = $_POST['nom_modele'];
            $url_photo = $_POST['url_photo'];
            $annee_debut = intval($_POST['annee_debut']);
            $annee_fin = intval($_POST['annee_fin']);
            $nbr_produit = intval($_POST['nbr_produit']);
            $puissance_max = intval($_POST['puissance_max']);
            $puissance_min = intval($_POST['puissance_min']);
            $prix_neuf = intval($_POST['prix_neuf']);
            $prix_actuel = intval($_POST['prix_actuel']);
            $description = $_POST['description'];
        
       
            foreach ($vehicules['marques'] as &$marque) {
                if ($marque['num_marque'] == $num_marque) {
                   
                    $nouveau_modele = array(
                        "num_modele" => compterTousLesModeles($vehicules['marques']) + 1,
                        "nom_modele" => $nom_modele,
                        "url_photo" => $url_photo,
                        "annee_debut" => $annee_debut,
                        "annee_fin" => $annee_fin,
                        "nbr_produit" => $nbr_produit,
                        "puissance_max" => $puissance_max,
                        "puissance_min" => $puissance_min,
                        "prix_neuf" => $prix_neuf,
                        "prix_actuel" => $prix_actuel,
                        "description" => $description
                    );
                    $marque['modeles'][] = $nouveau_modele;
                    break;
                }
            }

            file_put_contents('data.json', json_encode($vehicules, JSON_PRETTY_PRINT));
            echo "Le modèle a été ajouté avec succès!";
            echo'<a href="tous_les_modeles.php">Retour</a>';
            exit;
        }
        ?>
        <form method="post">
        <label for="num_marque">Marque :</label>
        <select name="num_marque" id="num_marque" required>
            <?php
            foreach ($vehicules['marques'] as $marque) {
                echo "<option value='" . $marque['num_marque'] . "'>" . $marque['nom_marque'] . "</option>";
            }
            ?>
        </select><br>

        <label for="nom_modele">Nom du Modèle :</label>
        <input type="text" id="nom_modele" name="nom_modele" required><br>

        <label for="url_photo">URL Photo :</label>
        <input type="text" id="url_photo" name="url_photo"><br>

        <label for="annee_debut">Année de Début :</label>
        <input type="number" id="annee_debut" name="annee_debut" required><br>

        <label for="annee_fin">Année de Fin :</label>
        <input type="number" id="annee_fin" name="annee_fin" required><br>

        <label for="nbr_produit">Nombre Produit :</label>
        <input type="number" id="nbr_produit" name="nbr_produit" required><br>

        <label for="puissance_max">Puissance Max :</label>
        <input type="number" id="puissance_max" name="puissance_max"><br>

        <label for="puissance_min">Puissance Min :</label>
        <input type="number" id="puissance_min" name="puissance_min"><br>

        <label for="prix_neuf">Prix Neuf :</label>
        <input type="number" id="prix_neuf" name="prix_neuf"><br>

        <label for="prix_actuel">Prix Actuel :</label>
        <input type="number" id="prix_actuel" name="prix_actuel"><br>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea><br>

        <input type="submit" value="Ajouter le Modèle">
    </form>
    <?php
    }
    ?>
    </main>
</body>
</html>
