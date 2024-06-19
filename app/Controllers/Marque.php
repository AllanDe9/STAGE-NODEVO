<?php

namespace App\Controllers;

class Marque {
    public $num_marque;
    public $nom_marque;
    public $url_logo_marque;
    public $descri_marque;
    public $modeles;

    public function __construct($num_marque, $nom_marque, $url_logo_marque, $descri_marque, $modeles = []) {
        $this->num_marque = $num_marque;
        $this->nom_marque = $nom_marque;
        $this->url_logo_marque = $url_logo_marque;
        $this->descri_marque = $descri_marque;
        $this->modeles = $modeles;
    }

    public static function afficherMarque() {
        $vehicules = Catalogue::getVoitures();
        $num_marque = isset($_GET['marque']) ? (int) $_GET['marque'] : 0;
            if ($num_marque) {
                $marque = null;
                foreach ($vehicules['marques'] as $m) {
                    if ($m['num_marque'] == $num_marque) {
                        $marque = $m;
                        break;
                    }
                }

                if ($marque) {
                    echo "<div class='info-marque'>";
                    echo '<div><img src="' . $marque['url_logo_marque'] . '">';
                    echo "<h1>" . htmlspecialchars($marque['nom_marque']) . "</h1></div>";
                    echo "<p>" . htmlspecialchars($marque['descri_marque']) . "</p>";

                   echo '</div><div class="liste-modeles">';
        $count = 0;
        echo '<div class="row">'; 
       
            foreach ($marque['modeles'] as $modele) {
                if ($count > 0 && $count % 3 == 0) {
                    echo '</div><div class="row">'; 
                    }
                    echo '<div class="modele">';
                  
                    if (empty($modele['url_photo'])) {
                        echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . $modele['nom_modele'] . '">';
                    }
                    else {
                        echo '<img src="' . $modele['url_photo'].'" alt="' . $modele['nom_modele'] . '">';
                    }
                    echo '<div class="info-modele"><p>' . $marque['nom_marque'] .' - '.$modele['nom_modele'] .' - '.$modele['annee_debut'] . '</p></div>';

                    echo '<div class="outils-modele"><p><a href="/modifier/'.$modele['num_modele'].'">Modifier</a>'.' - '.'<a href="/detail/'.$modele['num_modele'].'">Voir plus</a></p></div>';

                    echo '</div>';
                    $count++;
            }
        
        echo '</div>'; 

                    
                } else {
                    echo "<p>Marque non trouvée</p>";
                }
            } else {
                echo "<p>Aucun numéro de marque fourni</p>";
            }
    }
    public static function afficherAdminMarque($get){
        $vehicules = Catalogue::getVoitures();
        echo '<div class="admin-marques">';
        echo '    <div class="liste-marques">';
        echo '        <ul>';
        foreach ($vehicules['marques'] as $marque) {
            echo '            <a href="/administrateur/marques/' . $marque['num_marque'] . '"><li>' . $marque['nom_marque'] . '</li></a>';
        }
        echo '        </ul>';
        echo '    </div>';

        if (isset($get['marque'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $num_marque = $_POST['num_marque'];
                $nom_marque = $_POST['nom_marque'];
                $url_logo_marque = $_POST['url_logo_marque'];
                $descri_marque = $_POST['descri_marque'];

                $marque_modifiee = null;
                foreach ($vehicules['marques'] as &$marque) {
                    if ($marque['num_marque'] == $num_marque) {
                        $marque['nom_marque'] = $nom_marque;
                        $marque['url_logo_marque'] = $url_logo_marque;
                        $marque['descri_marque'] = $descri_marque;
                        $marque_modifiee = $marque;
                        break;
                    }
                }

                if (!$marque_modifiee) {
                    echo '<p>Erreur : Marque non trouvée.</p>';
                    exit;
                }

                $new_json = json_encode($vehicules, JSON_PRETTY_PRINT);
                file_put_contents('../requetes/data.json', $new_json);

                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            $num_marque = $get['marque'];

            $marque = null;
            foreach ($vehicules['marques'] as $m) {
                if ($m['num_marque'] == $num_marque) {
                    $marque = $m;
                    break;
                }
            }

            if (!$marque) {
                echo '<p>Marque non trouvée.</p>';
                exit;
            }

            echo '<div class="ajouter-marque">';
            echo '    <a href="/administrateur/marques">< Retour</a>';
            echo '    <div>';
            echo '        <img src="' . $marque['url_logo_marque'] . '">';
            echo '        <h1>' . $marque['nom_marque'] . '</h1>';
            echo '    </div>';
            echo '    <form method="post">';
            echo '        <input type="hidden" name="num_marque" value="' . $marque['num_marque'] . '">';
            echo '        <label for="nom_marque">Nom de la marque :</label><br>';
            echo '        <input type="text" id="nom_marque" name="nom_marque" value="' . $marque['nom_marque'] . '" required><br><br>';
            echo '        <label for="url_logo_marque">URL du logo :</label><br>';
            echo '        <input type="text" id="url_logo_marque" name="url_logo_marque" value="' . $marque['url_logo_marque'] . '"><br><br>';
            echo '        <label for="descri_marque">Description :</label><br>';
            echo '        <textarea id="descri_marque" name="descri_marque" rows="4" required>' . $marque['descri_marque'] . '</textarea><br><br>';
            echo '        <input type="submit" id="submit" value="Enregistrer les modifications">';
            echo '    </form>';
            echo '</div>';
        } else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nom_marque = $_POST['nom_marque'];
                $url_logo_marque = $_POST['url_logo_marque'];
                $descri_marque = $_POST['descri_marque'];

                $next_num_marque = 1;
                foreach ($vehicules['marques'] as $marque) {
                    while ($marque['num_marque'] == $next_num_marque) {
                        $next_num_marque = $next_num_marque + 1;
                    }
                }

                $nouvelle_marque = [
                    'num_marque' => $next_num_marque,
                    'nom_marque' => $nom_marque,
                    'url_logo_marque' => $url_logo_marque,
                    'descri_marque' => $descri_marque,
                    'modeles' => []
                ];

                $vehicules['marques'][] = $nouvelle_marque;

                $new_json_data = json_encode($vehicules, JSON_PRETTY_PRINT);
                file_put_contents('../requetes/data.json', $new_json_data);

                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            echo '<div class="ajouter-marque">';
            echo '    <h2>Ajouter une nouvelle marque</h2>';
            echo '    <form method="post">';
            echo '        <label for="nom_marque">Nom de la marque :</label><br>';
            echo '        <input type="text" id="nom_marque" name="nom_marque" required><br><br>';
            echo '        <label for="url_logo_marque">URL du logo :</label><br>';
            echo '        <input type="text" id="url_logo_marque" name="url_logo_marque" required><br><br>';
            echo '        <label for="descri_marque">Description :</label><br>';
            echo '        <textarea id="descri_marque" name="descri_marque" rows="4" required></textarea><br><br>';
            echo '        <input type="submit" value="Ajouter">';
            echo '    </form>';
            echo '</div>';
        }
        echo '</div>';
    }
}