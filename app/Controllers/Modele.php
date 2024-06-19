<?php

namespace App\Controllers;
class Modele {
    public $num_modele;
    public $nom_modele;
    public $url_photo;
    public $annee_debut;
    public $annee_fin;
    public $nbr_produit;
    public $puissance_max;
    public $puissance_min;
    public $prix_neuf;
    public $prix_actuel;
    public $description;

    public function __construct($num_modele, $nom_modele, $url_photo, $annee_debut, $annee_fin, $nbr_produit, $puissance_max, $puissance_min, $prix_neuf, $prix_actuel, $description) {
        $this->num_modele = $num_modele;
        $this->nom_modele = $nom_modele;
        $this->url_photo = $url_photo;
        $this->annee_debut = $annee_debut;
        $this->annee_fin = $annee_fin;
        $this->nbr_produit = $nbr_produit;
        $this->puissance_max = $puissance_max;
        $this->puissance_min = $puissance_min;
        $this->prix_neuf = $prix_neuf;
        $this->prix_actuel = $prix_actuel;
        $this->description = $description;
    }

    public static function afficherModele() {
        $vehicules = Catalogue::getVoitures();
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
           
            if (empty($modele['url_photo'])) {
                echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . $modele['nom_modele'] . '">';
            }
            else {
                echo '<img src="' . $modele['url_photo'].'" alt="' . $modele['nom_modele'] . '">';
            }
            echo '<div class="caracteristique-modele">';
            echo'<h1>A PROPOS DE CE MODÈLE :</h1></br>';
            echo '<h1>' . $marque['nom_marque'] .' - '.$modele['nom_modele']. '</h1>';
            if ($modele['annee_debut'] == $modele['annee_fin']) {
                echo '<p>' . $modele['annee_debut'] . '</p>';
            } else {
                echo '<p>' . $modele['annee_debut'] .' - '.$modele['annee_fin']. '</p>';
            }
            echo '<h3>Puissance DIN :</h3>';
            if ($modele['puissance_max'] == $modele['puissance_min']) {
                echo '<p>' . $modele['puissance_max'] . 'ch</p>';
            } else {
                echo '<p>' . $modele['puissance_max'] .' ch - '.$modele['puissance_min']. ' ch</p>';
            }
            echo '<h3>Cote du modèle :</h3>';
            echo '<p>' . $modele['prix_actuel'] . ' €</p>';
            echo '<h3>Prix initial :</h3>';
            echo '<p>' . $modele['prix_neuf'] . ' €</p>';
            echo '<h3>Production :</h3>';
            echo '<p>' . $modele['nbr_produit'] . ' véhicules produits</p>';
            echo '<strong>' . $modele['description'] . '</strong>';
            echo '<div class="outils-modele"><p><a href="/modifier/'.$modele['num_modele'].'">Modifier</a>  - <a href="/marque/'.$marque['num_marque'].'">Voir la marque</a></p></div>';
            echo '</div>';
            echo '</div>';
        }else{
            header("Location: /");
            exit();
        }
    }

    public static function modifierModele(){
        $vehicules = Catalogue::getVoitures();
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
        }
        
        if (intval($_POST['nbr_produit']) < 0  || intval($_POST['prix_actuel']) < 0 || intval($_POST['puissance_min']) < 0 || intval($_POST['prix_neuf']) < 0) {
            $erreurs = ['negatif'=> 'Tous les chiffres doivent etre positif !'];
        }


        if (!empty($erreurs)) { 
            var_dump($erreurs);
        } else {
            file_put_contents('../requetes/data.json', json_encode($vehicules, JSON_PRETTY_PRINT));
            echo '<h2>Données mises à jour avec succès !</h2>';
            echo'<a class="retour" href="/modeles">Retour</a>';
            exit;
       } 
    }
    echo '<form action="//vintage-cars.com/modifier/'.$_GET['modele'].'" method="post" class="form-vehicules">';
    echo'<div class=formulaire>';
    echo '<input type="hidden" name="marqueIndex" value="' . $marqueIndex . '">';
    echo '<input type="hidden" name="modeleIndex" value="' . $modeleIndex . '">';
    echo '<label for="nom_marque">Marque : '.htmlspecialchars($marque['nom_marque']). '</label><br>';
    echo '<label for="nom_modele">Nom du modèle :</label>';
    echo '<input type="text" id="nom_modele" name="nom_modele" value="' . htmlspecialchars($modele['nom_modele']) . '"required><br>';
    echo '<label for="annee_debut">Année de début :</label>';
    echo '<input type="text" id="annee_debut" name="annee_debut" value="' . htmlspecialchars($modele['annee_debut']) . '"required><br>';
    echo '<label for="annee_fin">Année de fin :</label>';
    echo '<input type="text" id="annee_fin" name="annee_fin" value="' . htmlspecialchars($modele['annee_fin']) . '"required><br>';
    echo '<label for="nbr_produit">Nombre produit :</label>';
    echo '<input type="text" id="nbr_produit" name="nbr_produit" value="' . htmlspecialchars($modele['nbr_produit']) . '"required><br>';
    echo '<input id="submit" type="submit" name="submit" value="Modifier">';
    echo'</div><div class=formulaire>';
    echo '<div><label for="puissance_max">Puissance max :</label>';
    echo '<input type="text" id="puissance" name="puissance_max" value="' . htmlspecialchars($modele['puissance_max']) . '"><br>';
    echo '<label for="puissance_min">Puissance min :</label>';
    echo '<input type="text" id="puissance" name="puissance_min" value="' . htmlspecialchars($modele['puissance_min']) . '"><br></div>';
    echo '<div><label for="prix_neuf">Prix neuf :</label>';
    echo '<input type="text" id="prix_neuf" name="prix_neuf" value="' . htmlspecialchars($modele['prix_neuf']) . '"><br>';
    echo '<label for="prix_actuel">Prix actuel :</label>';
    echo '<input type="text" id="prix_actuel" name="prix_actuel" value="' . htmlspecialchars($modele['prix_actuel']) . '"><br></div>';
    echo '<label for="url_photo">URL de la photo :</label>';
    echo '<input type="text" id="url_photo" name="url_photo" value="' . htmlspecialchars($modele['url_photo']) . '"><br>';
    echo '<label for="description">Description :</label>';
    echo '<textarea id="description" name="description" required>' . htmlspecialchars($modele['description']) . '</textarea><br>';
    echo '</div></form>';
    }

    public static function ajouterModele(){
        $vehicules = Catalogue::getVoitures();
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
            $next_num_modele = 1;
             
                $marque = null;
                foreach ($vehicules['marques'] as $m) {
                    if ($m['num_marque'] == $num_marque) {
                        $marque = $m;
                        break;
                    }
                }
                foreach ($marque['modeles'] as $modele) {
                    while($modele['num_modele'] == $next_num_modele){
                        $next_num_modele = $next_num_modele + 1;
                    }
                }
        
       
            foreach ($vehicules['marques'] as &$marque) {
                if ($marque['num_marque'] == $num_marque) {
                   
                    $nouveau_modele = array(
                        "num_modele" => $next_num_modele,
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
            }
            if (empty($_POST['nom_modele']) || empty($_POST['annee_debut']) || empty($_POST['annee_fin']) || empty($_POST['nbr_produit']) || empty($_POST['description'])){
                $erreurs = ['requis'=> 'Certains champs sont vides !'];
            }
            if (intval($_POST['nbr_produit']) < 0  || intval($_POST['prix_actuel']) < 0 || intval($_POST['puissance_min']) < 0 || intval($_POST['prix_neuf']) < 0) {
                $erreurs = ['negatif'=> 'Tous les chiffres doivent etre positif !'];
            }
            if (!empty($erreurs)) { 
            } else {
                file_put_contents('../requetes/data.json', json_encode($vehicules, JSON_PRETTY_PRINT));
                echo "<h2>Le modèle a été ajouté avec succès!</h2>";
                echo'<a class="retour" href="/modeles">Retour</a>';
                exit;
            }
        }
       
    }
}