<?php

namespace App\Controllers;


class Catalogue {
    public $marques;

    public function __construct($marques = []) {
        $this->marques = $marques;
    }
    public static function getVoitures() {
        $jsonData = file_get_contents('../requetes/data.json');
        return json_decode($jsonData, true);
    }
    public static function afficherMarques() {
        $vehicules = self::getVoitures();
        foreach ($vehicules['marques'] as $marque) {
            echo '<a href="/marque/'.$marque['num_marque'].'">'.$marque['nom_marque'].'</a>';
        }
    }
    public static function afficher3Modele() {
        $vehicules = self::getVoitures();
        $count = 1;
        foreach ($vehicules['marques'] as $marque) {
            foreach ($marque['modeles'] as $modele) {
                if ($count < 4 ) {
                            
                    echo '<div class="modele">';
                          
                    if (empty($modele['url_photo'])) {
                        echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . $modele['nom_modele'] . '">';
                    }
                                                                
                    else {
                        echo '<img src="' . $modele['url_photo'].'" alt="' . $modele['nom_modele'] . '">';
                    }
                    echo '<div class="info-modele"><p>' . $marque['nom_marque'] .' - '.$modele['nom_modele'] .' - '.$modele['annee_debut'] . '</p></div>';
        
                    echo '<div class="outils-modele"><p><a href="/modifier/'.$modele['num_modele'].'">Modifier</a>'.' - '.'<a href="/detail/'.$modele['num_modele'].'">Voir plus</a>'.' - '.'<a href="/marque/'.$marque['num_marque'].'">Voir la marque</a></p></div>';
  
                    echo '</div>'; 
                    $count++;
                } else {
                    break;
                }
            }
        }
        echo '</div>'; 
    }
    public static function afficherTousModeles($get) {
        $vehicules = self::getVoitures();
        echo '<div class="admin-modeles">';
        echo '    <div class="liste-modeles-admin">';
        $modelsParPage = 9;
        $totalModels = 0;
        foreach ($vehicules['marques'] as $marque) {
            $totalModels += count($marque['modeles']);
        }

        $totalPages = ceil($totalModels / $modelsParPage);
        $currentPage = isset($get['page']) ? $get['page'] : 1;
        $currentPage = max(1, min($currentPage, $totalPages)); 
        $start = ($currentPage - 1) * $modelsParPage;
        $end = $start + $modelsParPage;

        $modelsDisplayed = 0;
        $count = 0;
        echo '<div class="row">';
        foreach ($vehicules['marques'] as $marque) {
            foreach ($marque['modeles'] as $modele) {
                if ($modelsDisplayed >= $start && $modelsDisplayed < $end) {
                    if ($count > 0 && $count % 3 == 0) {
                        echo '</div><div class="row">'; 
                    }
                    echo '<div class="modele">';
                    
                    if (empty($modele['url_photo'])) {
                        echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . $modele['nom_modele'] . '">';
                    } else {
                        echo '<img src="' . $modele['url_photo'] . '" alt="' . $modele['nom_modele'] . '">';
                    }
                    echo '<div class="info-modele"><p>' . $marque['nom_marque'] .' - '.$modele['nom_modele'] .' - '.$modele['annee_debut'] . '</p></div>';

                    echo '<div class="outils-modele"><p><a href="/modifier/'.$modele['num_modele'].'">Modifier</a>'.' - '.'<a href="/detail/'.$modele['num_modele'].'">Voir plus</a>'.' - '.'<a href="?delete='.$modele['num_modele'].'">Supprimer</a></p></div>';

                    echo '</div>';
                    $count++;
                }
                $modelsDisplayed++;
            }
        }
        echo '</div>';
        echo '</div>'; 

        echo '<div class="pagination">';
        echo '<div class="bouton-pagination">';

        if ($currentPage > 1) {
            echo '<a href="/administrateur/modeles/' . ($currentPage - 1) . '"><div class="deplacement"><</div></a>';
        } else {
            echo '<div class="deplacement" id="gris"><</div>';
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                echo '<div class="num_page" id="page_select">' . $i . '</div>'; 
            } else {
                echo '<a href="/administrateur/modeles/' . $i . '"><div class="num_page">' . $i . '</div></a>';
            }
        }

        if ($currentPage < $totalPages) {
            echo '<a href="/administrateur/modeles/' . ($currentPage + 1) . '"><div class="deplacement">></div></a>';
        } else {
            echo '<div class="deplacement" id="gris">></div>';
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';

        if (isset($_GET['delete'])) {
            $numModele = intval($_GET['delete']);

            foreach ($vehicules['marques'] as &$marque) {
                foreach ($marque['modeles'] as $index => $modele) {
                    if ($modele['num_modele'] == $numModele) {
                        array_splice($marque['modeles'], $index, 1);
                        break;
                    }
                }
            }

            file_put_contents('../requetes/data.json', json_encode($vehicules, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $urlWithoutQuery = strtok($_SERVER["REQUEST_URI"], '?');
            echo '<script>window.location.href = "' . $urlWithoutQuery . '";</script>';
        }


    }
    public static function filter_modeles($modeles, $modele_recherche, $annee_recherche) {
        return array_filter($modeles, function($modele) use ($modele_recherche, $annee_recherche) {
            $matches_modele = $modele_recherche === '' || stripos($modele['nom_modele'], $modele_recherche) !== false;
            $matches_annee = $annee_recherche === '' || ($modele['annee_debut'] == $annee_recherche);
            return $matches_modele && $matches_annee;
        });
    }

    public static function build_query_string($params) {
        return http_build_query($params);
    }

    public static function afficherTousModelesRecherche() {
        $vehicules = self::getVoitures();
        $modelsParPage = 9;
        $totalModels = 0;
        $vide = false;
    
        $search_params = [
            'marque' => isset($_POST['marque']) ? $_POST['marque'] : (isset($_GET['marque']) ? $_GET['marque'] : 'Toutes'),
            'modele' => isset($_POST['modele']) ? $_POST['modele'] : (isset($_GET['modele']) ? $_GET['modele'] : ''),
            'annee' => isset($_POST['annee']) ? $_POST['annee'] : (isset($_GET['annee']) ? $_GET['annee'] : '')
        ];
               
            $marque_recherche = isset($_POST['marque']) ? $_POST['marque'] : 'Toutes';
            $modele_recherche = isset($_POST['modele']) ? $_POST['modele'] : '';
            $annee_recherche = isset($_POST['annee']) ? $_POST['annee'] : '';
        
        
            foreach ($vehicules['marques'] as $marque) {
                if ($marque_recherche === 'Toutes' || $marque['num_marque'] == $marque_recherche) {
                    $modeles_filtrés = self::filter_modeles($marque['modeles'], $modele_recherche, $annee_recherche);
                if (!empty($modeles_filtrés)) {
                    $resultats[] = [
                            'num_marque' => $marque['num_marque'],
                            'nom_marque' => $marque['nom_marque'],
                            'modeles' => $modeles_filtrés
                    ];
                    $totalModels += count($modeles_filtrés);
                }
                }
            }
            $totalPages = ceil($totalModels / $modelsParPage);
    
        
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $currentPage = max(1, min($currentPage, $totalPages)); 
            $start = ($currentPage - 1) * $modelsParPage;
            $end = $start + $modelsParPage;
            echo '<div class="liste-modeles">';
            echo '<div class="row">';
            $modelsDisplayed = 0;
            $count = 0;
            if (!empty($resultats)) {
            foreach ($resultats as $marque) {
                foreach ($marque['modeles'] as $modele) {
                    if ($modelsDisplayed >= $start && $modelsDisplayed < $end) {
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
    
                        echo '<div class="outils-modele"><p><a href="/modifier/'.$modele['num_modele'].'">Modifier</a>'.' - '.'<a href="/detail/'.$modele['num_modele'].'">Voir plus</a>'.' - '.'<a href="/marque/'.$marque['num_marque'].'">Voir la marque</a></p></div>';
    
                        echo '</div>';
                        $count++;
                    }
                    $modelsDisplayed++;
                }
            }
            echo '</div>';
            } else {
                echo "<p>Aucun modèle trouvé pour les critères de recherche donnés.</p>";
                $vide = true;
            }
       
        if ($vide == false) {
        echo '<div class="pagination">';
        echo '<div class="bouton-pagination">';
        $queryString = self::build_query_string($search_params);
       
        if ($currentPage > 1) {
            echo '<a href="/modeles/' . ($currentPage - 1) . '"><div class="deplacement"><</div></a>';
        } else {
            echo '<div class="deplacement" id="gris"><</div>';
        }
    
       
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                echo '<div class="num_page" id="page_select">' . $i . '</div>'; 
            } else {
                echo '<a href="/modeles/' . $i . '"><div class="num_page">' . $i . '</div></a>';
            }
        }
    
        
        if ($currentPage < $totalPages) {
            echo '<a href="/modeles/' . ($currentPage + 1) . '"><div class="deplacement">></div></a>';
        } else {
            echo '<div class="deplacement" id="gris">></div>';
        }
    
        echo '</div>';
        echo '</div>';
        }
        echo '</div>';
    }
}